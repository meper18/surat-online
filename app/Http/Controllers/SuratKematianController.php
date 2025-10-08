<?php

namespace App\Http\Controllers;

use App\Models\Permohonan;
use App\Models\SuratKematian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratKematianController extends Controller
{
    /**
     * Show the form for creating surat kematian details
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

        return view('surat.kematian.create', compact('permohonan'));
    }

    /**
     * Store surat kematian details
     */
    public function store(Request $request, $permohonanId)
    {
        $request->validate([
            'hubungan_keluarga' => 'required|string|max:255',
            'nama_meninggal' => 'required|string|max:255',
            'tempat_lahir_meninggal' => 'required|string|max:255',
            'tanggal_lahir_meninggal' => 'required|date',
            'nik_meninggal' => 'required|string|max:16',
            'nomor_kk_meninggal' => 'required|string|max:16',
            'agama_meninggal' => 'required|string|max:255',
            'alamat_meninggal' => 'required|string',
            'hari_meninggal' => 'required|string|max:255',
            'tanggal_meninggal' => 'required|date',
            'waktu_meninggal' => 'required',
            'tempat_meninggal' => 'required|string|max:255',
            'penentu_kematian' => 'required|string|max:255',
            'surat_rs' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'foto_makam' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $permohonan = Permohonan::findOrFail($permohonanId);
        
        // Check if user owns this permohonan or has admin/operator role
        /** @var User $user */
        $user = Auth::user();
        if ($permohonan->user_id !== Auth::id() && !$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        // Create or update surat kematian
        $suratKematian = SuratKematian::updateOrCreate(
            ['permohonan_id' => $permohonanId],
            [
                'hubungan_keluarga' => $request->hubungan_keluarga,
                'nama_meninggal' => $request->nama_meninggal,
                'tempat_lahir_meninggal' => $request->tempat_lahir_meninggal,
                'tanggal_lahir_meninggal' => $request->tanggal_lahir_meninggal,
                'nik_meninggal' => $request->nik_meninggal,
                'nomor_kk_meninggal' => $request->nomor_kk_meninggal,
                'agama_meninggal' => $request->agama_meninggal,
                'alamat_meninggal' => $request->alamat_meninggal,
                'hari_meninggal' => $request->hari_meninggal,
                'tanggal_meninggal' => $request->tanggal_meninggal,
                'waktu_meninggal' => $request->waktu_meninggal,
                'tempat_meninggal' => $request->tempat_meninggal,
                'penentu_kematian' => $request->penentu_kematian,
            ]
        );

        // Handle additional document uploads (surat_rs and foto_makam)
        $additionalDocuments = ['surat_rs', 'foto_makam'];
        
        foreach ($additionalDocuments as $docType) {
            if ($request->hasFile($docType)) {
                $file = $request->file($docType);
                $filename = time() . '_' . $docType . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('dokumen_wajib', $filename, 'public');
                
                // Check if document already exists and update or create
                \App\Models\DokumenWajib::updateOrCreate(
                    [
                        'permohonan_id' => $permohonan->id,
                        'jenis_dokumen' => $docType
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
        }

        // Update permohonan status if needed
        if ($permohonan->status === 'pending') {
            $permohonan->update(['status' => 'diproses']);
        }

        return redirect()->route('warga.permohonan.show', $permohonanId)
            ->with('success', 'Data surat kematian berhasil disimpan!');
    }

    /**
     * Generate PDF for surat kematian
     */
    public function generatePDF($permohonanId)
    {
        $permohonan = Permohonan::with(['user', 'jenisSurat', 'suratKematian'])->findOrFail($permohonanId);
        
        // Check authorization
        /** @var User $user */
        $user = Auth::user();
        if ($permohonan->user_id !== Auth::id() && !$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        if (!$permohonan->suratKematian) {
            return redirect()->back()->with('error', 'Data surat kematian belum lengkap!');
        }

        // Generate nomor surat if not exists
        if (!$permohonan->nomor_surat) {
            $permohonan->nomor_surat = $permohonan->generateNomorSurat();
            $permohonan->save();
        }

        $data = [
            'permohonan' => $permohonan,
            'user' => $permohonan->user,
            'suratKematian' => $permohonan->suratKematian,
            'tanggal_surat' => now()->format('d F Y'),
        ];

        $pdf = PDF::loadView('surat.kematian.template', $data);
        $filename = 'surat_kematian_' . $permohonan->kode_permohonan . '.pdf';
        
        // Save PDF file
        $pdfPath = 'surat_generated/' . $filename;
        Storage::disk('public')->put($pdfPath, $pdf->output());
        
        // Update permohonan with file path and status
        $permohonan->update([
            'file_surat' => $pdfPath,
            'status' => 'selesai'
        ]);

        return $pdf->download($filename);
    }

    /**
     * Show surat kematian details
     */
    public function show($permohonanId)
    {
        $permohonan = Permohonan::with(['user', 'jenisSurat', 'suratKematian'])->findOrFail($permohonanId);
        
        // Check authorization
        /** @var User $user */
        $user = Auth::user();
        if (!$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        return view('surat.kematian.show', compact('permohonan'));
    }

    /**
     * Show the form for editing surat kematian details
     */
    public function edit($permohonanId)
    {
        $permohonan = Permohonan::with('suratKematian')->findOrFail($permohonanId);
        
        // Check if user owns this permohonan or has admin/operator role
        /** @var User $user */
        $user = Auth::user();
        if ($permohonan->user_id !== Auth::id() && !$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        if (!$permohonan->suratKematian) {
            return redirect()->route('warga.surat-kematian.create', $permohonanId)
                ->with('error', 'Data surat kematian belum ada. Silakan isi data terlebih dahulu.');
        }

        return view('surat.kematian.edit', compact('permohonan'));
    }

    /**
     * Update surat kematian
     */
    public function update(Request $request, Permohonan $permohonan)
    {
        $request->validate([
            'hubungan_keluarga' => 'required|string|max:255',
            'nama_meninggal' => 'required|string|max:255',
            'tempat_lahir_meninggal' => 'required|string|max:255',
            'tanggal_lahir_meninggal' => 'required|date',
            'nik_meninggal' => 'required|string|max:16',
            'nomor_kk_meninggal' => 'required|string|max:16',
            'agama_meninggal' => 'required|string|max:255',
            'alamat_meninggal' => 'required|string',
            'hari_meninggal' => 'required|string|max:255',
            'tanggal_meninggal' => 'required|date',
            'waktu_meninggal' => 'required',
            'tempat_meninggal' => 'required|string|max:255',
            'penentu_kematian' => 'required|string|max:255',
        ]);
        
        // Check authorization
        /** @var User $user */
        $user = Auth::user();
        if ($permohonan->user_id !== Auth::id() && !$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        $permohonan->suratKematian->update([
            'hubungan_keluarga' => $request->hubungan_keluarga,
            'nama_meninggal' => $request->nama_meninggal,
            'tempat_lahir_meninggal' => $request->tempat_lahir_meninggal,
            'tanggal_lahir_meninggal' => $request->tanggal_lahir_meninggal,
            'nik_meninggal' => $request->nik_meninggal,
            'nomor_kk_meninggal' => $request->nomor_kk_meninggal,
            'agama_meninggal' => $request->agama_meninggal,
            'alamat_meninggal' => $request->alamat_meninggal,
            'hari_meninggal' => $request->hari_meninggal,
            'tanggal_meninggal' => $request->tanggal_meninggal,
            'waktu_meninggal' => $request->waktu_meninggal,
            'tempat_meninggal' => $request->tempat_meninggal,
            'penentu_kematian' => $request->penentu_kematian,
        ]);

        // Determine redirect route based on user role
        $routePrefix = 'warga';
        if ($user->hasRole('admin')) {
            $routePrefix = 'admin';
        } elseif ($user->hasRole('operator')) {
            $routePrefix = 'operator';
        }

        return redirect()->route($routePrefix . '.permohonan.index')
            ->with('success', 'Data surat kematian berhasil diperbarui!');
    }
}