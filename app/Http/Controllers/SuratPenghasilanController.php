<?php

namespace App\Http\Controllers;

use App\Models\Permohonan;
use App\Models\SuratPenghasilan;
use App\Models\User;
use App\Services\ImprovedPDFService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratPenghasilanController extends Controller
{
    /**
     * Show the form for creating surat penghasilan details
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

        return view('surat.penghasilan.create', compact('permohonan'));
    }

    /**
     * Store surat penghasilan details
     */
    public function store(Request $request, $permohonanId)
    {
        $request->validate([
            'jumlah_penghasilan' => 'required|numeric|min:0',
            'keperluan' => 'required|string|max:255',
        ]);

        $permohonan = Permohonan::findOrFail($permohonanId);
        
        // Check if user owns this permohonan or has admin/operator role
        /** @var User $user */
        $user = Auth::user();
        if ($permohonan->user_id !== Auth::id() && !$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        // Create or update surat penghasilan
        $suratPenghasilan = SuratPenghasilan::updateOrCreate(
            ['permohonan_id' => $permohonanId],
            [
                'jumlah_penghasilan' => $request->jumlah_penghasilan,
                'keperluan' => $request->keperluan,
            ]
        );

        // Update permohonan status if needed
        if ($permohonan->status === 'pending') {
            $permohonan->update(['status' => 'diproses']);
        }

        return redirect()->route('warga.permohonan.show', $permohonanId)
            ->with('success', 'Data surat penghasilan berhasil disimpan!');
    }

    /**
     * Generate PDF for surat penghasilan
     */
    public function generatePDF($permohonanId)
    {
        $permohonan = Permohonan::with(['user', 'jenisSurat', 'suratPenghasilan'])->findOrFail($permohonanId);
        
        // Check authorization
        /** @var User $user */
        $user = Auth::user();
        if (!$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        if (!$permohonan->suratPenghasilan) {
            return redirect()->back()->with('error', 'Data surat penghasilan belum lengkap!');
        }

        // Generate nomor surat if not exists
        if (!$permohonan->nomor_surat) {
            $permohonan->nomor_surat = $permohonan->generateNomorSurat();
            $permohonan->save();
        }

        $data = [
            'permohonan' => $permohonan,
            'user' => $permohonan->user,
            'suratPenghasilan' => $permohonan->suratPenghasilan,
            'tanggal_surat' => now()->format('d F Y'),
            'desa' => config('desa'),
        ];

        // Use ImprovedPDFService for better QR code quality
        $improvedPDFService = new ImprovedPDFService();
        
        // Get the template HTML
        $html = view('surat.penghasilan.template', $data)->render();
        
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
        
        $filename = 'surat_penghasilan_' . $permohonan->kode_permohonan . '.pdf';
        
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
     * Show surat penghasilan details
     */
    public function show($permohonanId)
    {
        $permohonan = Permohonan::with(['user', 'jenisSurat', 'suratPenghasilan'])->findOrFail($permohonanId);
        
        // Check authorization
        /** @var User $user */
        $user = Auth::user();
        if ($permohonan->user_id !== Auth::id() && !$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        return view('surat.penghasilan.show', compact('permohonan'));
    }

    /**
     * Show the form for editing surat penghasilan
     */
    public function edit($permohonanId)
    {
        $permohonan = Permohonan::with('suratPenghasilan')->findOrFail($permohonanId);
        
        // Check if user owns this permohonan or has admin/operator role
        /** @var User $user */
        $user = Auth::user();
        if ($permohonan->user_id !== Auth::id() && !$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        if (!$permohonan->suratPenghasilan) {
            return redirect()->route('warga.surat-penghasilan.create', $permohonanId)
                ->with('error', 'Data surat penghasilan belum ada. Silakan isi data terlebih dahulu.');
        }

        return view('surat.penghasilan.edit', compact('permohonan'));
    }

    /**
     * Update surat penghasilan
     */
    public function update(Request $request, Permohonan $permohonan)
    {
        $request->validate([
            'jumlah_penghasilan' => 'required|numeric|min:0',
            'keperluan' => 'required|string|max:255',
        ]);
        
        // Check authorization
        /** @var User $user */
        $user = Auth::user();
        if ($permohonan->user_id !== Auth::id() && !$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        $permohonan->suratPenghasilan->update([
            'jumlah_penghasilan' => $request->jumlah_penghasilan,
            'keperluan' => $request->keperluan,
        ]);

        // Determine redirect route based on user role
        $routePrefix = 'warga';
        if ($user->hasRole('admin')) {
            $routePrefix = 'admin';
        } elseif ($user->hasRole('operator')) {
            $routePrefix = 'operator';
        }

        // Debug logging
        Log::info('Redirect Debug', [
            'user_id' => $user->id,
            'user_role' => $user->role->name ?? 'no role',
            'route_prefix' => $routePrefix,
            'permohonan_id' => $permohonan->id,
            'target_route' => $routePrefix . '.permohonan.index',
            'generated_url' => route($routePrefix . '.permohonan.index')
        ]);

        return redirect()->route($routePrefix . '.permohonan.index')
            ->with('success', 'Data surat penghasilan berhasil diperbarui!');
    }
}