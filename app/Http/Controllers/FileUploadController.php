<?php

namespace App\Http\Controllers;

use App\Models\DokumenPersyaratan;
use App\Models\Permohonan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    /**
     * Upload additional documents for a permohonan
     */
    public function uploadDocument(Request $request, $permohonanId)
    {
        $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
            'wajib' => 'boolean'
        ]);

        $permohonan = Permohonan::findOrFail($permohonanId);
        
        // Check authorization
        /** @var User $user */
        $user = Auth::user();
        if ($permohonan->user_id !== Auth::id() && !$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        // Handle file upload
        $file = $request->file('file');
        $filename = time() . '_' . Str::slug($request->nama_dokumen) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('dokumen_persyaratan', $filename, 'public');

        // Save document record
        DokumenPersyaratan::create([
            'permohonan_id' => $permohonanId,
            'nama_dokumen' => $request->nama_dokumen,
            'file_path' => $path,
            'wajib' => $request->boolean('wajib', false)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil diunggah!'
        ]);
    }

    /**
     * Delete a document
     */
    public function deleteDocument($documentId)
    {
        $document = DokumenPersyaratan::findOrFail($documentId);
        $permohonan = $document->permohonan;
        
        // Check authorization
        /** @var User $user */
        $user = Auth::user();
        if ($permohonan->user_id !== Auth::id() && !$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        // Delete file from storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        // Delete record
        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil dihapus!'
        ]);
    }

    /**
     * Download a document
     */
    public function downloadDocument($documentId)
    {
        $document = DokumenPersyaratan::findOrFail($documentId);
        $permohonan = $document->permohonan;
        
        // Check authorization
        /** @var User $user */
        $user = Auth::user();
        if ($permohonan->user_id !== Auth::id() && !$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::download('public/' . $document->file_path, $document->nama_dokumen);
    }

    /**
     * Get documents for a permohonan
     */
    public function getDocuments($permohonanId)
    {
        $permohonan = Permohonan::findOrFail($permohonanId);
        
        // Check authorization
        /** @var User $user */
        $user = Auth::user();
        if ($permohonan->user_id !== Auth::id() && !$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        $documents = $permohonan->dokumenPersyaratans()->get();

        return response()->json([
            'success' => true,
            'documents' => $documents->map(function ($doc) {
                return [
                    'id' => $doc->id,
                    'nama_dokumen' => $doc->nama_dokumen,
                    'wajib' => $doc->wajib,
                    'created_at' => $doc->created_at->format('d/m/Y H:i'),
                    'download_url' => route('documents.download', $doc->id),
                    'delete_url' => route('documents.delete', $doc->id)
                ];
            })
        ]);
    }

    /**
     * Upload generated letter file
     */
    public function uploadGeneratedLetter(Request $request, $permohonanId)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240', // 10MB max for PDF
        ]);

        $permohonan = Permohonan::findOrFail($permohonanId);
        
        // Check authorization - only admin/operator can upload generated letters
        /** @var User $user */
        $user = Auth::user();
        if (!$user->hasRole(['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }

        // Handle file upload
        $file = $request->file('file');
        $filename = 'surat_' . $permohonan->kode_permohonan . '_' . time() . '.pdf';
        $path = $file->storeAs('surat_generated', $filename, 'public');

        // Update permohonan with generated letter path
        $permohonan->update([
            'file_surat' => $path,
            'status' => 'selesai'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Surat berhasil diunggah!',
            'file_path' => $path
        ]);
    }
}