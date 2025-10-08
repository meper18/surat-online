<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKematian extends Model
{
    use HasFactory;

    protected $fillable = [
        'permohonan_id',
        'hubungan_keluarga',
        'nama_meninggal',
        'tempat_lahir_meninggal',
        'tanggal_lahir_meninggal',
        'nik_meninggal',
        'nomor_kk_meninggal',
        'agama_meninggal',
        'alamat_meninggal',
        'hari_meninggal',
        'tanggal_meninggal',
        'waktu_meninggal',
        'tempat_meninggal',
        'penentu_kematian'
    ];

    protected $casts = [
        'tanggal_lahir_meninggal' => 'date',
        'tanggal_meninggal' => 'date',
        'waktu_meninggal' => 'datetime',
    ];

    /**
     * Get the permohonan that owns the surat kematian.
     */
    public function permohonan()
    {
        return $this->belongsTo(Permohonan::class);
    }
}