<?php

namespace App\Http\Controllers;

use App\Models\Permohonan;
use App\Models\DokumenWajib;
use App\Models\JenisSurat;
use App\Models\User;
use App\Http\Requests\StorePermohonanRequest;
use App\Http\Requests\StorePermohonanWithDocumentsRequest;
use App\Http\Requests\UpdateStatusRequest;
use App\Models\AuditTrail;
use App\Mail\StatusChangeNotification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PermohonanController extends Controller
{
    /**
     * Display a listing of applications
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Start building the query
        $query = Permohonan::with(['user', 'jenisSurat']);
        
        // Apply user role filter
        if (!$user->hasRole('admin') && !$user->hasRole('operator')) {
            $query->where('user_id', $user->id);
        }
        
        // Apply status filter if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Apply search filter if provided
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('jenisSurat', function($subQuery) use ($searchTerm) {
                    $subQuery->where('nama', 'like', '%' . $searchTerm . '%');
                })
                ->orWhere('keperluan', 'like', '%' . $searchTerm . '%');
            });
        }
        
        $permohonan = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Preserve query parameters in pagination links
        $permohonan->appends($request->query());

        return view('permohonan.index', compact('permohonan'));
    }

    /**
     * Show the form for creating a new application
     */
    public function create()
    {
        $jenisSurat = JenisSurat::all();
        return view('permohonan.create', compact('jenisSurat'));
    }

    /**
     * Store a newly created application with multiple documents
     */
    public function store(StorePermohonanWithDocumentsRequest $request)
    {
        DB::beginTransaction();
        
        try {
            // Create the permohonan
            $permohonan = new Permohonan();
            $permohonan->kode_permohonan = 'PRM-' . date('Ymd') . '-' . str_pad(Permohonan::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            $permohonan->user_id = Auth::id();
            $permohonan->jenis_surat_id = $request->jenis_surat_id;
            $permohonan->keperluan = $request->keperluan;
            $permohonan->status = 'diajukan';
            $permohonan->tanggal_permohonan = now();
            $permohonan->tanggal_surat_pernyataan = $request->tanggal_surat_pernyataan;
            $permohonan->catatan = $request->catatan;
            $permohonan->save();

            // Handle required document uploads
            $requiredDocuments = [
                'ktp_pemohon' => 'ktp_pemohon',
                'kk_pemohon' => 'kk_pemohon', 
                'surat_pernyataan_kaling' => 'surat_pernyataan_kaling',
                'ktp_saksi1' => 'ktp_saksi1',
                'ktp_saksi2' => 'ktp_saksi2'
            ];

            foreach ($requiredDocuments as $inputName => $jenisDoc) {
                if ($request->hasFile($inputName)) {
                    $file = $request->file($inputName);
                    $filename = time() . '_' . $inputName . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('dokumen_wajib', $filename, 'public');
                    
                    DokumenWajib::create([
                        'permohonan_id' => $permohonan->id,
                        'jenis_dokumen' => $jenisDoc,
                        'nama_file' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'is_required' => true
                    ]);
                }
            }

            // Log audit trail for application creation
            AuditTrail::log(
                'create',
                Permohonan::class,
                $permohonan->id,
                null,
                $permohonan->toArray(),
                "Permohonan surat {$permohonan->jenisSurat->nama} berhasil diajukan dengan kode {$permohonan->kode_permohonan} dan 5 dokumen wajib"
            );

            DB::commit();

            // Redirect to appropriate detail form based on jenis surat
            $jenisSurat = $permohonan->jenisSurat;
            $redirectRoute = $this->getDetailFormRoute($jenisSurat->nama, $permohonan->id);
            
            if ($redirectRoute) {
                return redirect()->route($redirectRoute, $permohonan->id)
                    ->with('success', 'Permohonan awal berhasil dibuat dengan semua dokumen wajib! Silakan lengkapi data detail untuk ' . $jenisSurat->nama . '.');
            }

            // Fallback to dashboard if no specific detail form exists
            return redirect()->route('warga.dashboard')
                ->with('success', 'Permohonan surat berhasil diajukan dengan semua dokumen wajib!');
                
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating permohonan with documents: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan permohonan. Silakan coba lagi.');
        }
    }

    /**
     * Show the form for editing the specified application
     */
    public function edit(Permohonan $permohonan)
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Only admin and operator can edit
        if (!$user->hasRole('admin') && !$user->hasRole('operator')) {
            abort(403, 'Unauthorized action.');
        }

        // Load relationships
        $permohonan->load(['user', 'jenisSurat']);
        
        return view('permohonan.edit', compact('permohonan'));
    }

    /**
     * Update the specified application
     */
    public function update(Request $request, Permohonan $permohonan)
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Only admin and operator can update
        if (!$user->hasRole('admin') && !$user->hasRole('operator')) {
            abort(403, 'Unauthorized action.');
        }

        Log::info('Update permohonan started', [
            'permohonan_id' => $permohonan->id,
            'user_id' => $user->id,
            'request_data' => $request->all()
        ]);

        $request->validate([
            'keperluan' => 'required|string|max:1000',
            'dokumen_pendukung' => 'nullable|string|max:500',
            'tanggal_surat_pernyataan' => 'required|date',
            'catatan' => 'nullable|string|max:500',
            'keterangan_status' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        
        try {
            $oldData = $permohonan->toArray();
            
            $updateData = [
                'keperluan' => $request->keperluan,
                'tanggal_surat_pernyataan' => $request->tanggal_surat_pernyataan,
                'catatan' => $request->catatan,
            ];

            // Add optional fields if provided
            if ($request->filled('dokumen_pendukung')) {
                $updateData['dokumen_pendukung'] = $request->dokumen_pendukung;
            }

            // Only admin/operator can update keterangan_status
            if (($user->hasRole('admin') || $user->hasRole('operator')) && $request->filled('keterangan_status')) {
                $updateData['keterangan_status'] = $request->keterangan_status;
            }
            
            $permohonan->update($updateData);

            Log::info('Permohonan updated successfully', [
                'permohonan_id' => $permohonan->id,
                'old_data' => $oldData,
                'new_data' => $permohonan->fresh()->toArray()
            ]);

            // Log audit trail
            AuditTrail::log(
                'update',
                Permohonan::class,
                $permohonan->id,
                null,
                $permohonan->toArray(),
                "Permohonan {$permohonan->kode_permohonan} diperbarui oleh {$user->name}"
            );
            
            DB::commit();

            return redirect()->route($user->hasRole('admin') ? 'admin.permohonan.index' : 'operator.permohonan.index')
                ->with('success', 'Permohonan berhasil diperbarui!');
                
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating permohonan: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui permohonan.')
                ->withInput();
        }
    }

    /**
     * Display the specified application
     */
    public function show(Permohonan $permohonan)
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Check if user can view this application
        if (!$user->hasRole('admin') && !$user->hasRole('operator') && $permohonan->user_id !== $user->id) {

            abort(403, 'Unauthorized action.');
        }

        // Load all documents (both required and additional)
        $permohonan->load([
            'user', 
            'jenisSurat', 
            'dokumenWajib' => function($query) {
                $query->orderBy('is_required', 'desc')->orderBy('jenis_dokumen');
            }
        ]);
        
        // Separate required and additional documents
        $requiredDocuments = $permohonan->dokumenWajib->where('is_required', true);
        $additionalDocuments = $permohonan->dokumenWajib->where('is_required', false);
        
        return view('permohonan.show', compact('permohonan', 'requiredDocuments', 'additionalDocuments'));
    }

    /**
     * Update application status (approve/reject)
     */
    public function updateStatus(UpdateStatusRequest $request, Permohonan $permohonan)
    {
        try {
            Log::info('Status update started', [
                'permohonan_id' => $permohonan->id,
                'old_status' => $permohonan->status,
                'new_status' => $request->status
            ]);

            /** @var User $user */
            $user = Auth::user();
            
            // Store old status for email notification
            $oldStatus = $permohonan->status;
            $newStatus = $request->status;

            $permohonan->status = $newStatus;
            $permohonan->keterangan_status = $request->keterangan_status;
            $permohonan->diproses_oleh = $user->id;
            $permohonan->tanggal_diproses = now();

            if ($request->status === 'selesai') {
                $permohonan->tanggal_selesai = now();
            }

            Log::info('About to save permohonan');
            $permohonan->save();
            Log::info('Permohonan saved successfully');

            // Temporarily disable audit trail logging to isolate the issue
            // Log audit trail for status change
            // if ($oldStatus !== $newStatus) {
            //     \Log::info('Logging audit trail');
            //     AuditTrail::log(
            //         'update_status',
            //         Permohonan::class,
            //         $permohonan->id,
            //         ['status' => $oldStatus],
            //         ['status' => $newStatus, 'keterangan_status' => $request->keterangan_status],
            //         "Status permohonan {$permohonan->kode_permohonan} diubah dari '{$oldStatus}' menjadi '{$newStatus}'"
            //     );
            //     \Log::info('Audit trail logged successfully');
            // }

            Log::info('Status update completed successfully');

            // Status update completed - notification will be visible on user's dashboard
            // No email notification needed as users can check status updates in real-time on their dashboard

            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status permohonan berhasil diperbarui! Warga dapat melihat update status di dashboard mereka.',
                    'data' => [
                        'status' => $newStatus,
                        'keterangan_status' => $request->keterangan_status,
                        'tanggal_diproses' => $permohonan->tanggal_diproses->format('d F Y, H:i')
                    ]
                ]);
            }

            return redirect()->back()
                ->with('success', 'Status permohonan berhasil diperbarui! Warga dapat melihat update status di dashboard mereka.');
        } catch (\Exception $e) {
            Log::error('Status update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'permohonan_id' => $permohonan->id ?? 'unknown'
            ]);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengupdate status: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengupdate status: ' . $e->getMessage());
        }
    }

    /**
     * Download generated letter
     */
    public function download(Permohonan $permohonan)
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Check if user can download this letter
        if (!$user->hasRole('admin') && !$user->hasRole('operator') && $permohonan->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        if (!$permohonan->file_surat || !Storage::disk('public')->exists($permohonan->file_surat)) {
            abort(404, 'File surat tidak ditemukan.');
        }

        // Log audit trail for document download
        AuditTrail::log(
            'download',
            Permohonan::class,
            $permohonan->id,
            null,
            null,
            "File surat {$permohonan->kode_permohonan} diunduh oleh {$user->name}"
        );

        $filename = basename($permohonan->file_surat);
        return response()->download(Storage::disk('public')->path($permohonan->file_surat), $filename);
    }

    /**
     * Download specific document from DokumenWajib
     */
    public function downloadDocument(Permohonan $permohonan, $documentId)
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Check if user can download this document
        if (!$user->hasRole('admin') && !$user->hasRole('operator') && $permohonan->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $dokumen = DokumenWajib::where('permohonan_id', $permohonan->id)
            ->where('id', $documentId)
            ->firstOrFail();

        if (!Storage::disk('public')->exists($dokumen->file_path)) {
            abort(404, 'File not found.');
        }

        return response()->download(Storage::disk('public')->path($dokumen->file_path), $dokumen->nama_file);
    }

    /**
     * Delete application (only by owner or admin)
     */
    public function destroy(Permohonan $permohonan)
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Only owner or admin can delete
        if (!$user->hasRole('admin') && $permohonan->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();
        
        try {
            // Delete associated document files
            foreach ($permohonan->dokumenWajib as $dokumen) {
                if (Storage::disk('public')->exists($dokumen->file_path)) {
                    Storage::disk('public')->delete($dokumen->file_path);
                }
            }

            // Delete old dokumen_pendukung if exists
            if ($permohonan->dokumen_pendukung && Storage::disk('public')->exists($permohonan->dokumen_pendukung)) {
                Storage::disk('public')->delete($permohonan->dokumen_pendukung);
            }

            $permohonan->delete();
            
            DB::commit();

            return redirect()->back()
                ->with('success', 'Permohonan berhasil dihapus!');
                
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting permohonan: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus permohonan.');
        }
    }

    /**
     * Add signature to permohonan
     */
    public function addSignature(Request $request, Permohonan $permohonan)
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Only admin and operator can add signatures
        if (!$user->hasRole('admin') && !$user->hasRole('operator')) {
            abort(403, 'Unauthorized action.');
        }

        // Debug logging
        \Illuminate\Support\Facades\Log::info('AddSignature Request Data:', [
            'signature_type' => $request->signature_type,
            'digital_signature_length' => $request->digital_signature ? strlen($request->digital_signature) : 0,
            'digital_signature_preview' => $request->digital_signature ? substr($request->digital_signature, 0, 100) . '...' : null,
            'permohonan_id' => $permohonan->id
        ]);

        // Validate request
        try {
            $request->validate([
                'signature_type' => 'required|in:digital,qr_code',
                'digital_signature' => 'required_if:signature_type,digital|nullable|string'
            ]);
            \Illuminate\Support\Facades\Log::info('Validation passed, signature_type: ' . $request->signature_type);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('Validation failed:', ['errors' => $e->errors(), 'request_data' => $request->all()]);
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $e->errors()], 422);
        }

        DB::beginTransaction();
        
        try {
            $oldData = $permohonan->toArray();
            
            // Update signature data
            $updateData = [
                'signature_type' => $request->signature_type,
                'signed_at' => now(),
                'signed_by' => $user->id
            ];

            if ($request->signature_type === 'digital') {
                // Process digital signature - remove data:image/png;base64, prefix if present
                $digitalSignature = $request->digital_signature;
                \Illuminate\Support\Facades\Log::info('Processing digital signature:', [
                    'original_length' => strlen($digitalSignature),
                    'has_prefix' => strpos($digitalSignature, 'data:image/png;base64,') === 0
                ]);
                
                if (strpos($digitalSignature, 'data:image/png;base64,') === 0) {
                    $digitalSignature = substr($digitalSignature, 22);
                    \Illuminate\Support\Facades\Log::info('Removed prefix, new length:', ['length' => strlen($digitalSignature)]);
                }
                $updateData['digital_signature'] = $digitalSignature;
            } else if ($request->signature_type === 'qr_code') {
                // Generate QR code
                \Illuminate\Support\Facades\Log::info('About to generate QR code for permohonan ID: ' . $permohonan->id);
                $qrResult = $this->generateQRCode($permohonan);
                \Illuminate\Support\Facades\Log::info('QR code generation result:', $qrResult);
                $updateData['qr_code_data'] = $qrResult['data'];
                $updateData['qr_code_image'] = $qrResult['data_url'];
            }

            \Illuminate\Support\Facades\Log::info('Updating permohonan with data:', $updateData);
            $permohonan->update($updateData);
            
            // Verify the update
            $permohonan->refresh();
            \Illuminate\Support\Facades\Log::info('Permohonan after update:', [
                'signature_type' => $permohonan->signature_type,
                'signed_at' => $permohonan->signed_at,
                'digital_signature_length' => $permohonan->digital_signature ? strlen($permohonan->digital_signature) : 0
            ]);

            // Log audit trail
            AuditTrail::log(
                'add_signature',
                Permohonan::class,
                $permohonan->id,
                $oldData,
                $permohonan->toArray(),
                "Tanda tangan {$request->signature_type} ditambahkan pada permohonan {$permohonan->kode_permohonan} oleh {$user->name}"
            );
            
            DB::commit();

            return redirect()->back()
                ->with('success', 'Tanda tangan berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error adding signature: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan tanda tangan.')
                ->withInput();
        }
    }

    /**
     * Verify QR Code data
     */
    public function verifyQRCode($kode_permohonan)
    {
        try {
            $permohonan = Permohonan::where('kode_permohonan', $kode_permohonan)->first();
            
            if (!$permohonan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permohonan tidak ditemukan'
                ], 404);
            }

            if (!$permohonan->qr_code_data) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code tidak tersedia untuk permohonan ini'
                ], 404);
            }

            $qrData = json_decode($permohonan->qr_code_data, true);
            
            return response()->json([
                'success' => true,
                'message' => 'QR Code valid',
                'data' => [
                    'kode_permohonan' => $qrData['kode_permohonan'],
                    'jenis_surat' => $qrData['jenis_surat'],
                    'pemohon' => $qrData['pemohon'],
                    'tanggal_diproses' => $qrData['tanggal_diproses'],
                    'status' => $qrData['status'],
                    'signed_at' => $qrData['signed_at'],
                    'verified_at' => now()->format('d/m/Y H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('QR Code verification failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memverifikasi QR Code'
            ], 500);
        }
    }
    /**
     * Convert SVG to PNG using GD library
     */
    private function convertSvgToPng($svgString, $width, $height)
    {
        // Create a new image resource
        $image = imagecreatetruecolor($width, $height);
        
        // Set white background
        $white = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $white);
        
        // For simplicity, we'll use a different approach
        // Generate QR code directly as PNG using a simple method
        // This is a fallback that creates a basic QR pattern
        
        // Actually, let's use a simpler approach - generate as SVG and save as PNG
        // We'll create a temporary file and use imagick if available, or GD conversion
        
        $tempSvgFile = tempnam(sys_get_temp_dir(), 'qr_svg_');
        file_put_contents($tempSvgFile, $svgString);
        
        try {
            // Try to use Imagick if available (but we'll catch the error)
            if (extension_loaded('imagick') && class_exists('Imagick')) {
                $imagick = new \Imagick();
                $imagick->readImage($tempSvgFile);
                $imagick->setImageFormat('png');
                $imagick->setImageBackgroundColor('white');
                $imagick->setImageAlphaChannel(\Imagick::ALPHACHANNEL_REMOVE);
                $pngData = $imagick->getImageBlob();
                $imagick->clear();
                unlink($tempSvgFile);
                return $pngData;
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::info('Imagick not available, using alternative method');
        }
        
        // Fallback: Create a simple PNG with text
        $black = imagecolorallocate($image, 0, 0, 0);
        
        // Add some basic pattern (this is a simplified QR-like pattern)
        for ($x = 0; $x < $width; $x += 10) {
            for ($y = 0; $y < $height; $y += 10) {
                if (($x + $y) % 20 == 0) {
                    imagefilledrectangle($image, $x, $y, $x + 8, $y + 8, $black);
                }
            }
        }
        
        // Capture the PNG data
        ob_start();
        imagepng($image);
        $pngData = ob_get_contents();
        ob_end_clean();
        
        imagedestroy($image);
        unlink($tempSvgFile);
        
        return $pngData;
    }

    private function generateQRCode($permohonan)
    {
        try {
            \Illuminate\Support\Facades\Log::info('Starting QR code generation for permohonan: ' . $permohonan->kode_permohonan);
            
            // Create QR code data with permohonan information
            $qrData = [
                'kode_permohonan' => $permohonan->kode_permohonan,
                'jenis_surat' => $permohonan->jenisSurat->nama,
                'pemohon' => $permohonan->user->name,
                'tanggal_diproses' => $permohonan->tanggal_diproses ? $permohonan->tanggal_diproses->format('d/m/Y') : null,
                'status' => $permohonan->status,
                'signed_at' => now()->format('d/m/Y H:i:s'),
                'verification_url' => route('permohonan.verify', $permohonan->kode_permohonan)
            ];

            $qrString = json_encode($qrData);
            \Illuminate\Support\Facades\Log::info('QR data created:', ['data' => $qrString]);
            
            // Generate QR code as base64 data URL (compatible with DomPDF)
            $qrResult = $this->generateQRCodeBase64($qrString, 300);
            
            if (!$qrResult['success']) {
                throw new \Exception('Failed to generate QR code: ' . $qrResult['error']);
            }

            \Illuminate\Support\Facades\Log::info('QR code base64 generated successfully, size: ' . $qrResult['base64_size'] . ' characters');

            return [
                'data' => $qrString,
                'data_url' => $qrResult['data_url'],
                'png_size' => $qrResult['png_size']
            ];

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('QR Code generation failed: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error('Stack trace: ' . $e->getTraceAsString());
            throw new \Exception('Failed to generate QR code: ' . $e->getMessage());
        }
    }

    /**
     * Generate QR Code as base64 data URL that works with DomPDF
     */
    private function generateQRCodeBase64($data, $size = 300) {
        try {
            // Use only GD-compatible approach
            if (extension_loaded('gd')) {
                // Generate SVG first using BaconQrCode
                $svgRenderer = new \BaconQrCode\Renderer\ImageRenderer(
                    new \BaconQrCode\Renderer\RendererStyle\RendererStyle($size, 2),
                    new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
                );
                $writer = new \BaconQrCode\Writer($svgRenderer);
                $svgString = $writer->writeString($data);
                
                // Parse SVG and convert to PNG using GD
                $dom = new \DOMDocument();
                $dom->loadXML($svgString);
                
                // Get SVG dimensions
                $svgElement = $dom->getElementsByTagName('svg')->item(0);
                $width = (int)$svgElement->getAttribute('width') ?: $size;
                $height = (int)$svgElement->getAttribute('height') ?: $size;
                
                // Create GD image
                $image = imagecreatetruecolor($width, $height);
                $white = imagecolorallocate($image, 255, 255, 255);
                $black = imagecolorallocate($image, 0, 0, 0);
                
                // Fill with white background
                imagefill($image, 0, 0, $white);
                
                // Parse path elements (BaconQrCode uses paths, not rectangles)
                $paths = $dom->getElementsByTagName('path');
                $pathCount = 0;
                
                foreach ($paths as $path) {
                    $fill = $path->getAttribute('fill');
                    $d = $path->getAttribute('d');
                    
                    // Only process black paths
                    if (($fill === '#000000' || $fill === 'black' || $fill === '#000' || empty($fill)) && !empty($d)) {
                        // Parse the path data - BaconQrCode generates simple rectangular paths
                        // Format is typically: M x y h width v height h -width z (for rectangles)
                        if (preg_match_all('/M\s*([0-9.]+)\s*([0-9.]+)\s*h\s*([0-9.]+)\s*v\s*([0-9.]+)\s*h\s*-?([0-9.]+)\s*z?/i', $d, $matches, PREG_SET_ORDER)) {
                            foreach ($matches as $match) {
                                $x = (float)$match[1];
                                $y = (float)$match[2];
                                $w = (float)$match[3];
                                $h = (float)$match[4];
                                
                                // Draw rectangle
                                imagefilledrectangle($image, 
                                    (int)$x, (int)$y, 
                                    (int)($x + $w - 1), (int)($y + $h - 1), 
                                    $black
                                );
                                $pathCount++;
                            }
                        }
                        // Also handle simpler path formats
                        elseif (preg_match_all('/M\s*([0-9.]+)\s*([0-9.]+)\s*L\s*([0-9.]+)\s*([0-9.]+)\s*L\s*([0-9.]+)\s*([0-9.]+)\s*L\s*([0-9.]+)\s*([0-9.]+)\s*z?/i', $d, $matches, PREG_SET_ORDER)) {
                            foreach ($matches as $match) {
                                // This is a more complex path, draw as polygon
                                $points = array(
                                    (int)$match[1], (int)$match[2], // M point
                                    (int)$match[3], (int)$match[4], // L point 1
                                    (int)$match[5], (int)$match[6], // L point 2
                                    (int)$match[7], (int)$match[8]  // L point 3
                                );
                                imagefilledpolygon($image, $points, 4, $black);
                                $pathCount++;
                            }
                        }
                    }
                }
                
                // If no paths were processed, the QR might be in a different format
                // Let's try a fallback approach
                if ($pathCount === 0) {
                    // Check for rect elements as fallback
                    $rectangles = $dom->getElementsByTagName('rect');
                    foreach ($rectangles as $rect) {
                        $x = (int)$rect->getAttribute('x');
                        $y = (int)$rect->getAttribute('y');
                        $w = (int)$rect->getAttribute('width');
                        $h = (int)$rect->getAttribute('height');
                        $fill = $rect->getAttribute('fill');
                        
                        if ($fill === '#000000' || $fill === 'black' || $fill === '#000') {
                            imagefilledrectangle($image, $x, $y, $x + $w - 1, $y + $h - 1, $black);
                            $pathCount++;
                        }
                    }
                }
                
                // Convert to PNG
                ob_start();
                imagepng($image);
                $pngData = ob_get_contents();
                ob_end_clean();
                imagedestroy($image);
                
                \Illuminate\Support\Facades\Log::info('QR Code generation details:', [
                    'method' => 'gd_svg_parsing',
                    'svg_length' => strlen($svgString),
                    'dimensions' => $width . 'x' . $height,
                    'paths_processed' => $pathCount,
                    'png_size' => strlen($pngData),
                    'base64_size' => strlen(base64_encode($pngData))
                ]);
                
            } else {
                throw new \Exception('GD extension is not available');
            }
            
            // Create base64 data URL
            $base64 = base64_encode($pngData);
            $dataUrl = 'data:image/png;base64,' . $base64;
            
            return [
                'success' => true,
                'data_url' => $dataUrl,
                'png_size' => strlen($pngData),
                'base64_size' => strlen($dataUrl)
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
      * Get the appropriate detail form route based on jenis surat
      */
     private function getDetailFormRoute($jenisSurat, $permohonanId)
     {
         $routeMap = [
             'Surat Keterangan Penghasilan' => 'warga.surat-penghasilan.create',
             'Surat Keterangan Domisili Tinggal' => 'warga.surat-domisili-tinggal.create',
             'Surat Keterangan Domisili Usaha' => 'warga.surat-domisili-usaha.create',
             'Surat Keterangan Pindah/Mandah' => 'warga.surat-mandah.create',
             'Surat Keterangan Kematian' => 'warga.surat-kematian.create',
             'Surat Keterangan Nikah' => 'warga.surat-nikah.create',
             'Surat Keterangan Belum Menikah' => 'warga.surat-nikah.create',
         ];

         return $routeMap[$jenisSurat] ?? null;
     }
}