<?php

namespace App\Http\Controllers;

use App\Models\Permohonan;
use App\Models\SuratDomisiliUsaha;
use App\Models\User;
use App\Services\ImprovedPDFService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratDomisiliUsahaController extends Controller
{
    /**
     * Show the form for creating surat domisili usaha details
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

        return view('surat.domisili-usaha.create', compact('permohonan'));
    }

    /**
     * Store surat domisili usaha details
     */
    public function store(Request $request, $permohonanId)
    {
        $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'alamat_usaha' => 'required|string',
            'keperluan' => 'required|string|max:255',
            'foto_tempat_usaha' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $permohonan = Permohonan::findOrFail($permohonanId);
        
        // Check if user owns this permohonan or has admin/operator role
        /** @var User $user */
        $user = Auth::user();
        if ($permohonan->user_id !== Auth::id() && !$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        // Create or update surat domisili usaha
        $suratDomisiliUsaha = SuratDomisiliUsaha::updateOrCreate(
            ['permohonan_id' => $permohonanId],
            [
                'nama_usaha' => $request->nama_usaha,
                'alamat_usaha' => $request->alamat_usaha,
                'keperluan' => $request->keperluan,
            ]
        );

        // Handle additional document upload (foto_tempat_usaha)
        if ($request->hasFile('foto_tempat_usaha')) {
            $file = $request->file('foto_tempat_usaha');
            $filename = time() . '_foto_tempat_usaha_' . $file->getClientOriginalName();
            $path = $file->storeAs('dokumen_wajib', $filename, 'public');
            
            // Check if document already exists and update or create
            \App\Models\DokumenWajib::updateOrCreate(
                [
                    'permohonan_id' => $permohonan->id,
                    'jenis_dokumen' => 'foto_tempat_usaha'
                ],
                [
                    'nama_file' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'is_required' => false
                ]
            );
        }

        // Update permohonan status if needed
        if ($permohonan->status === 'pending') {
            $permohonan->update(['status' => 'diproses']);
        }

        return redirect()->route('warga.permohonan.show', $permohonanId)
            ->with('success', 'Data surat domisili usaha berhasil disimpan!');
    }

    /**
     * Generate PDF for surat domisili usaha
     */
    public function generatePDF($permohonanId)
    {
        $permohonan = Permohonan::with(['user', 'jenisSurat', 'suratDomisiliUsaha'])->findOrFail($permohonanId);
        
        // Check authorization
        /** @var User $user */
        $user = Auth::user();
        if (!$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        if (!$permohonan->suratDomisiliUsaha) {
            return redirect()->back()->with('error', 'Data surat domisili usaha belum lengkap!');
        }

        // Generate nomor surat if not exists
        if (!$permohonan->nomor_surat) {
            $permohonan->nomor_surat = $permohonan->generateNomorSurat();
            $permohonan->save();
        }

        $data = [
            'permohonan' => $permohonan,
            'user' => $permohonan->user,
            'suratDomisiliUsaha' => $permohonan->suratDomisiliUsaha,
            'tanggal_surat' => now()->format('d F Y'),
        ];

        // Use ImprovedPDFService for better QR code quality
        $improvedPDFService = new ImprovedPDFService();
        
        // Get the template HTML
        $html = view('surat.domisili-usaha.template', $data)->render();
        
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
        
        $filename = 'surat_domisili_usaha_' . $permohonan->kode_permohonan . '.pdf';
        
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
     * Show surat domisili usaha details
     */
    public function show($permohonanId)
    {
        $permohonan = Permohonan::with(['user', 'jenisSurat', 'suratDomisiliUsaha'])->findOrFail($permohonanId);
        
        // Check authorization
        /** @var User $user */
        $user = Auth::user();
        if ($permohonan->user_id !== Auth::id() && !$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        return view('surat.domisili-usaha.show', compact('permohonan'));
    }

    /**
     * Show the form for editing surat domisili usaha
     */
    public function edit($permohonanId)
    {
        $permohonan = Permohonan::with('suratDomisiliUsaha')->findOrFail($permohonanId);
        
        // Check if user owns this permohonan or has admin/operator role
        /** @var User $user */
        $user = Auth::user();
        if ($permohonan->user_id !== Auth::id() && !$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        if (!$permohonan->suratDomisiliUsaha) {
            return redirect()->route('warga.surat-domisili-usaha.create', $permohonanId)
                ->with('error', 'Data surat domisili usaha belum ada. Silakan isi data terlebih dahulu.');
        }

        return view('surat.domisili-usaha.edit', compact('permohonan'));
    }

    /**
     * Update surat domisili usaha
     */
    public function update(Request $request, Permohonan $permohonan)
    {
        $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'alamat_usaha' => 'required|string',
            'keperluan' => 'required|string|max:255',
        ]);
        
        // Check if user owns this permohonan or has admin/operator role
        /** @var User $user */
        $user = Auth::user();
        if ($permohonan->user_id !== Auth::id() && !$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        $permohonan->suratDomisiliUsaha->update([
            'nama_usaha' => $request->nama_usaha,
            'alamat_usaha' => $request->alamat_usaha,
            'keperluan' => $request->keperluan,
        ]);

        // Determine redirect route based on user role
        $routePrefix = 'warga';
        if ($user->hasRole('admin')) {
            $routePrefix = 'admin';
        } elseif ($user->hasRole('operator')) {
            $routePrefix = 'operator';
        }

        return redirect()->route($routePrefix . '.permohonan.index')
            ->with('success', 'Data surat domisili usaha berhasil diperbarui!');
    }
}