<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenWajib extends Model
{
    use HasFactory;

    protected $table = 'dokumen_wajib';

    protected $fillable = [
        'permohonan_id',
        'jenis_dokumen',
        'nama_file',
        'file_path',
        'file_size',
        'mime_type',
        'is_required'
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    /**
     * Get the permohonan that owns the dokumen wajib.
     */
    public function permohonan()
    {
        return $this->belongsTo(Permohonan::class);
    }

    /**
     * Get human readable document type name
     */
    public function getJenisDokumenNameAttribute()
    {
        $names = [
            'ktp_pemohon' => 'KTP Pemohon',
            'kk_pemohon' => 'Kartu Keluarga Pemohon',
            'surat_pernyataan_kaling' => 'Surat Pernyataan + TTD Kepala Lingkungan',
            'ktp_saksi1' => 'KTP Saksi 1',
            'ktp_saksi2' => 'KTP Saksi 2',
            'foto_tempat_usaha' => 'Foto Tempat Usaha',
            'surat_rs' => 'Surat Keterangan Rumah Sakit',
            'foto_makam' => 'Foto Makam',
            'ktp_ayah' => 'KTP Ayah',
            'ktp_ibu' => 'KTP Ibu'
        ];

        return $names[$this->jenis_dokumen] ?? $this->jenis_dokumen;
    }

    /**
     * Get required documents for initial application
     */
    public static function getRequiredDocuments()
    {
        return [
            'ktp_pemohon' => 'KTP Pemohon',
            'kk_pemohon' => 'Kartu Keluarga Pemohon',
            'surat_pernyataan_kaling' => 'Surat Pernyataan + TTD Kepala Lingkungan',
            'ktp_saksi1' => 'KTP Saksi 1',
            'ktp_saksi2' => 'KTP Saksi 2'
        ];
    }

    /**
     * Get additional documents by surat type
     */
    public static function getAdditionalDocumentsBySuratType($suratType)
    {
        $additionalDocs = [
            'domisili-usaha' => [
                'foto_tempat_usaha' => 'Foto Tempat Usaha'
            ],
            'kematian' => [
                'surat_rs' => 'Surat Keterangan Rumah Sakit (Opsional)',
                'foto_makam' => 'Foto Makam'
            ],
            'nikah' => [
                'ktp_ayah' => 'KTP Ayah',
                'ktp_ibu' => 'KTP Ibu'
            ]
        ];

        return $additionalDocs[$suratType] ?? [];
    }

    /**
     * Format file size for display
     */
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes === 0) return '0 Bytes';
        
        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));
        
        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }
}