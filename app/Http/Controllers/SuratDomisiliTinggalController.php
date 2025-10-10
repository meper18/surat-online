<?php

namespace App\Http\Controllers;

use App\Models\Permohonan;
use App\Models\SuratDomisiliTinggal;
use App\Models\User;
use App\Services\ImprovedPDFService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratDomisiliTinggalController extends Controller
{
    /**
     * Show the form for creating surat domisili tinggal details
     */
    public function create($permohonanId)
    {
        $permohonan = Permohonan::findOrFail($permohonanId);
        
        // Check if user owns this permohonan or has admin/operator role
        /** @var User $user */
        $user = Auth::user();
        if ($permohonan->user_id !== Auth::id() && !$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        return view('surat.domisili-tinggal.create', compact('permohonan'));
    }

    /**
     * Store surat domisili tinggal details
     */
    public function store(Request $request, $permohonanId)
    {
        $request->validate([
            'alamat_sekarang' => 'required|string',
            'keperluan' => 'required|string|max:255',
        ]);

        $permohonan = Permohonan::findOrFail($permohonanId);
        
        // Check if user owns this permohonan or has admin/operator role
        /** @var User $user */
        $user = Auth::user();
        if ($permohonan->user_id !== Auth::id() && !$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        // Create or update surat domisili tinggal
        $suratDomisiliTinggal = SuratDomisiliTinggal::updateOrCreate(
            ['permohonan_id' => $permohonanId],
            [
                'alamat_sekarang' => $request->alamat_sekarang,
                'keperluan' => $request->keperluan,
            ]
        );

        // Update permohonan status if needed
        if ($permohonan->status === 'pending') {
            $permohonan->update(['status' => 'diproses']);
        }

        // Determine redirect route based on user role
        $routePrefix = 'warga';
        if ($user->hasRole('admin')) {
            $routePrefix = 'admin';
        } elseif ($user->hasRole('operator')) {
            $routePrefix = 'operator';
        }

        return redirect()->route($routePrefix . '.permohonan.index')
            ->with('success', 'Data surat domisili tinggal berhasil disimpan!');
    }

    /**
     * Update surat domisili tinggal
     */
    public function update(Request $request, Permohonan $permohonan)
    {
        $request->validate([
            'alamat_sekarang' => 'required|string',
            'keperluan' => 'required|string|max:255',
        ]);
        
        // Check if user owns this permohonan or has admin/operator role
        /** @var User $user */
        $user = Auth::user();
        if ($permohonan->user_id !== Auth::id() && !$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        // Create or update surat domisili tinggal
        $suratDomisiliTinggal = SuratDomisiliTinggal::updateOrCreate(
            ['permohonan_id' => $permohonan->id],
            [
                'alamat_sekarang' => $request->alamat_sekarang,
                'keperluan' => $request->keperluan,
            ]
        );

        // Update permohonan status if needed
        if ($permohonan->status === 'pending') {
            $permohonan->update(['status' => 'diproses']);
        }

        // Determine redirect route based on user role
        $routePrefix = 'warga';
        if ($user->hasRole('admin')) {
            $routePrefix = 'admin';
        } elseif ($user->hasRole('operator')) {
            $routePrefix = 'operator';
        }

        return redirect()->route($routePrefix . '.permohonan.index')
            ->with('success', 'Data surat domisili tinggal berhasil diperbarui!');
    }

    /**
     * Generate PDF for surat domisili tinggal
     */
    public function generatePDF($permohonanId)
    {
        $permohonan = Permohonan::with(['user', 'jenisSurat', 'suratDomisiliTinggal'])->findOrFail($permohonanId);
        
        // Check authorization
        /** @var User $user */
        $user = Auth::user();
        if (!$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        if (!$permohonan->suratDomisiliTinggal) {
            return redirect()->back()->with('error', 'Data surat domisili tinggal belum lengkap!');
        }

        // Generate nomor surat if not exists
        if (!$permohonan->nomor_surat) {
            $permohonan->nomor_surat = $permohonan->generateNomorSurat();
            $permohonan->save();
        }

        $data = [
            'permohonan' => $permohonan,
            'user' => $permohonan->user,
            'suratDomisiliTinggal' => $permohonan->suratDomisiliTinggal,
            'tanggal_surat' => now()->format('d F Y'),
        ];

        // Use ImprovedPDFService for better QR code quality
        $improvedPDFService = new ImprovedPDFService();
        
        // Get the template HTML
        $html = view('surat.domisili-tinggal.template', $data)->render();
        
        // Generate QR code data if signature exists
        $qrData = null;
        if ($permohonan->signature_type === 'qr_code' && $permohonan->qr_code_data) {
            $qrData = $permohonan->qr_code_data;
        }
        
        // Replace existing QR code with high-quality version
        if ($qrData) {
            $html = $improvedPDFService->replaceQRCodeInTemplate($html, $qrData);
        }
        
        // Generate PDF with improved service
        $pdfContent = $improvedPDFService->generatePDF($html, $qrData);
        
        $filename = 'surat_domisili_tinggal_' . $permohonan->kode_permohonan . '.pdf';
        
        // Save PDF file
        $pdfPath = 'surat_generated/' . $filename;
        Storage::disk('public')->put($pdfPath, $pdfContent);
        
        // Update permohonan with file path and status
        $permohonan->update([
            'file_surat' => $pdfPath,
            'status' => 'selesai'
        ]);

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Show surat domisili tinggal details
     */
    public function show($permohonanId)
    {
        $permohonan = Permohonan::with(['user', 'jenisSurat', 'suratDomisiliTinggal'])->findOrFail($permohonanId);
        
        // Check authorization
        /** @var User $user */
        $user = Auth::user();
        if ($permohonan->user_id !== Auth::id() && !$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        return view('surat.domisili-tinggal.show', compact('permohonan'));
    }

    /**
     * Show the form for editing surat domisili tinggal
     */
    public function edit($permohonanId)
    {
        $permohonan = Permohonan::with('suratDomisiliTinggal')->findOrFail($permohonanId);
        
        // Check if user owns this permohonan or has admin/operator role
        /** @var User $user */
        $user = Auth::user();
        if ($permohonan->user_id !== Auth::id() && !$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        if (!$permohonan->suratDomisiliTinggal) {
            return redirect()->route('warga.surat-domisili-tinggal.create', $permohonanId)
                ->with('error', 'Data surat domisili tinggal belum ada. Silakan isi data terlebih dahulu.');
        }

        return view('surat.domisili-tinggal.edit', compact('permohonan'));
    }
}