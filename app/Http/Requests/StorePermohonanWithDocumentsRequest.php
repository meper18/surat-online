<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePermohonanWithDocumentsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'keperluan' => 'required|string|max:1000',
            'tanggal_surat_pernyataan' => 'required|date',
            'catatan' => 'nullable|string|max:500',
            
            // Required documents
            'ktp_pemohon' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'kk_pemohon' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'surat_pernyataan_kaling' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ktp_saksi1' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ktp_saksi2' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'jenis_surat_id.required' => 'Jenis surat harus dipilih.',
            'jenis_surat_id.exists' => 'Jenis surat yang dipilih tidak valid.',
            'keperluan.required' => 'Keperluan harus diisi.',
            'keperluan.max' => 'Keperluan maksimal 1000 karakter.',
            'tanggal_surat_pernyataan.required' => 'Tanggal surat pernyataan harus diisi.',
            'tanggal_surat_pernyataan.date' => 'Format tanggal tidak valid.',
            'catatan.max' => 'Catatan maksimal 500 karakter.',
            
            // Document validation messages
            'ktp_pemohon.required' => 'KTP Pemohon wajib diupload.',
            'ktp_pemohon.file' => 'KTP Pemohon harus berupa file.',
            'ktp_pemohon.mimes' => 'KTP Pemohon harus berformat PDF, JPG, JPEG, atau PNG.',
            'ktp_pemohon.max' => 'Ukuran file KTP Pemohon maksimal 2MB.',
            
            'kk_pemohon.required' => 'Kartu Keluarga Pemohon wajib diupload.',
            'kk_pemohon.file' => 'Kartu Keluarga Pemohon harus berupa file.',
            'kk_pemohon.mimes' => 'Kartu Keluarga Pemohon harus berformat PDF, JPG, JPEG, atau PNG.',
            'kk_pemohon.max' => 'Ukuran file Kartu Keluarga Pemohon maksimal 2MB.',
            
            'surat_pernyataan_kaling.required' => 'Surat Pernyataan + TTD Kepala Lingkungan wajib diupload.',
            'surat_pernyataan_kaling.file' => 'Surat Pernyataan + TTD Kepala Lingkungan harus berupa file.',
            'surat_pernyataan_kaling.mimes' => 'Surat Pernyataan + TTD Kepala Lingkungan harus berformat PDF, JPG, JPEG, atau PNG.',
            'surat_pernyataan_kaling.max' => 'Ukuran file Surat Pernyataan + TTD Kepala Lingkungan maksimal 2MB.',
            
            'ktp_saksi1.required' => 'KTP Saksi 1 wajib diupload.',
            'ktp_saksi1.file' => 'KTP Saksi 1 harus berupa file.',
            'ktp_saksi1.mimes' => 'KTP Saksi 1 harus berformat PDF, JPG, JPEG, atau PNG.',
            'ktp_saksi1.max' => 'Ukuran file KTP Saksi 1 maksimal 2MB.',
            
            'ktp_saksi2.required' => 'KTP Saksi 2 wajib diupload.',
            'ktp_saksi2.file' => 'KTP Saksi 2 harus berupa file.',
            'ktp_saksi2.mimes' => 'KTP Saksi 2 harus berformat PDF, JPG, JPEG, atau PNG.',
            'ktp_saksi2.max' => 'Ukuran file KTP Saksi 2 maksimal 2MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'jenis_surat_id' => 'jenis surat',
            'keperluan' => 'keperluan',
            'tanggal_surat_pernyataan' => 'tanggal surat pernyataan',
            'catatan' => 'catatan',
            'ktp_pemohon' => 'KTP Pemohon',
            'kk_pemohon' => 'Kartu Keluarga Pemohon',
            'surat_pernyataan_kaling' => 'Surat Pernyataan + TTD Kepala Lingkungan',
            'ktp_saksi1' => 'KTP Saksi 1',
            'ktp_saksi2' => 'KTP Saksi 2',
        ];
    }
}