<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratNikah extends Model
{
    use HasFactory;

    protected $fillable = [
        'permohonan_id',
        'nama_ayah',
        'tempat_lahir_ayah',
        'tanggal_lahir_ayah',
        'nik_ayah',
        'agama_ayah',
        'pekerjaan_ayah',
        'alamat_ayah',
        'nama_ibu',
        'tempat_lahir_ibu',
        'tanggal_lahir_ibu',
        'nik_ibu',
        'agama_ibu',
        'pekerjaan_ibu',
        'alamat_ibu'
    ];

    protected $casts = [
        'tanggal_lahir_ayah' => 'date',
        'tanggal_lahir_ibu' => 'date',
    ];

    /**
     * Get the permohonan that owns the surat nikah.
     */
    public function permohonan()
    {
        return $this->belongsTo(Permohonan::class);
    }
}