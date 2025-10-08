<?php

namespace App\Http\Controllers;

use App\Models\Permohonan;
use App\Models\SuratNikah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratNikahController extends Controller
{
    /**
     * Show the form for creating surat nikah details
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

        return view('surat.nikah.create', compact('permohonan'));
    }

    /**
     * Store surat nikah details
     */
    public function store(Request $request, $permohonanId)
    {
        $request->validate([
            'nama_ayah' => 'required|string|max:255',
            'tempat_lahir_ayah' => 'required|string|max:255',
            'tanggal_lahir_ayah' => 'required|date',
            'nik_ayah' => 'required|string|max:16',
            'agama_ayah' => 'required|string|max:255',
            'pekerjaan_ayah' => 'required|string|max:255',
            'alamat_ayah' => 'required|string',
            'nama_ibu' => 'required|string|max:255',
            'tempat_lahir_ibu' => 'required|string|max:255',
            'tanggal_lahir_ibu' => 'required|date',
            'nik_ibu' => 'required|string|max:16',
            'agama_ibu' => 'required|string|max:255',
            'pekerjaan_ibu' => 'required|string|max:255',
            'alamat_ibu' => 'required|string',
            'ktp_ayah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ktp_ibu' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $permohonan = Permohonan::findOrFail($permohonanId);
        
        // Check if user owns this permohonan or has admin/operator role
        /** @var User $user */
        $user = Auth::user();
        if ($permohonan->user_id !== Auth::id() && !$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        // Create or update surat nikah
        $suratNikah = SuratNikah::updateOrCreate(
            ['permohonan_id' => $permohonanId],
            [
                'nama_ayah' => $request->nama_ayah,
                'tempat_lahir_ayah' => $request->tempat_lahir_ayah,
                'tanggal_lahir_ayah' => $request->tanggal_lahir_ayah,
                'nik_ayah' => $request->nik_ayah,
                'agama_ayah' => $request->agama_ayah,
                'pekerjaan_ayah' => $request->pekerjaan_ayah,
                'alamat_ayah' => $request->alamat_ayah,
                'nama_ibu' => $request->nama_ibu,
                'tempat_lahir_ibu' => $request->tempat_lahir_ibu,
                'tanggal_lahir_ibu' => $request->tanggal_lahir_ibu,
                'nik_ibu' => $request->nik_ibu,
                'agama_ibu' => $request->agama_ibu,
                'pekerjaan_ibu' => $request->pekerjaan_ibu,
                'alamat_ibu' => $request->alamat_ibu,
            ]
        );

        // Handle additional document uploads (ktp_ayah and ktp_ibu)
        $additionalDocuments = ['ktp_ayah', 'ktp_ibu'];
        
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
            ->with('success', 'Data surat nikah berhasil disimpan!');
    }

    /**
     * Generate PDF for surat nikah
     */
    public function generatePDF($permohonanId)
    {
        $permohonan = Permohonan::with(['user', 'jenisSurat', 'suratNikah'])->findOrFail($permohonanId);
        
        // Check authorization
        /** @var User $user */
        $user = Auth::user();
        if (!$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        if (!$permohonan->suratNikah) {
            return redirect()->back()->with('error', 'Data surat nikah belum lengkap!');
        }

        // Generate nomor surat if not exists
        if (!$permohonan->nomor_surat) {
            $permohonan->nomor_surat = $permohonan->generateNomorSurat();
            $permohonan->save();
        }

        $data = [
            'permohonan' => $permohonan,
            'user' => $permohonan->user,
            'suratNikah' => $permohonan->suratNikah,
            'tanggal_surat' => now()->format('d F Y'),
        ];

        $pdf = PDF::loadView('surat.nikah.template', $data);
        $filename = 'surat_nikah_' . $permohonan->kode_permohonan . '.pdf';
        
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
     * Show surat nikah details
     */
    public function show($permohonanId)
    {
        $permohonan = Permohonan::with(['user', 'jenisSurat', 'suratNikah'])->findOrFail($permohonanId);
        
        // Check authorization
        /** @var User $user */
        $user = Auth::user();
        if ($permohonan->user_id !== Auth::id() && !$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        return view('surat.nikah.show', compact('permohonan'));
    }

    /**
     * Show the form for editing surat nikah
     */
    public function edit($permohonanId)
    {
        $permohonan = Permohonan::with('suratNikah')->findOrFail($permohonanId);
        
        // Check if user owns this permohonan or has admin/operator role
        /** @var User $user */
        $user = Auth::user();
        if ($permohonan->user_id !== Auth::id() && !$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        if (!$permohonan->suratNikah) {
            return redirect()->route('warga.surat-nikah.create', $permohonanId)
                ->with('error', 'Data surat nikah belum ada. Silakan isi data terlebih dahulu.');
        }

        return view('surat.nikah.edit', compact('permohonan'));
    }

    /**
     * Update surat nikah
     */
    public function update(Request $request, Permohonan $permohonan)
    {
        $request->validate([
            'nama_ayah' => 'required|string|max:255',
            'tempat_lahir_ayah' => 'required|string|max:255',
            'tanggal_lahir_ayah' => 'required|date',
            'nik_ayah' => 'required|string|max:16',
            'agama_ayah' => 'required|string|max:255',
            'pekerjaan_ayah' => 'required|string|max:255',
            'alamat_ayah' => 'required|string',
            'nama_ibu' => 'required|string|max:255',
            'tempat_lahir_ibu' => 'required|string|max:255',
            'tanggal_lahir_ibu' => 'required|date',
            'nik_ibu' => 'required|string|max:16',
            'agama_ibu' => 'required|string|max:255',
            'pekerjaan_ibu' => 'required|string|max:255',
            'alamat_ibu' => 'required|string',
        ]);
        
        // Check if user owns this permohonan or has admin/operator role
        /** @var User $user */
        $user = Auth::user();
        if ($permohonan->user_id !== Auth::id() && !$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        $permohonan->suratNikah->update([
            'nama_ayah' => $request->nama_ayah,
            'tempat_lahir_ayah' => $request->tempat_lahir_ayah,
            'tanggal_lahir_ayah' => $request->tanggal_lahir_ayah,
            'nik_ayah' => $request->nik_ayah,
            'agama_ayah' => $request->agama_ayah,
            'pekerjaan_ayah' => $request->pekerjaan_ayah,
            'alamat_ayah' => $request->alamat_ayah,
            'nama_ibu' => $request->nama_ibu,
            'tempat_lahir_ibu' => $request->tempat_lahir_ibu,
            'tanggal_lahir_ibu' => $request->tanggal_lahir_ibu,
            'nik_ibu' => $request->nik_ibu,
            'agama_ibu' => $request->agama_ibu,
            'pekerjaan_ibu' => $request->pekerjaan_ibu,
            'alamat_ibu' => $request->alamat_ibu,
        ]);

        // Determine redirect route based on user role
        $routePrefix = 'warga';
        if ($user->hasRole('admin')) {
            $routePrefix = 'admin';
        } elseif ($user->hasRole('operator')) {
            $routePrefix = 'operator';
        }

        return redirect()->route($routePrefix . '.permohonan.index')
            ->with('success', 'Data surat nikah berhasil diperbarui!');
    }
}