<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratDomisiliUsaha extends Model
{
    use HasFactory;

    protected $fillable = [
        'permohonan_id',
        'nama_usaha',
        'alamat_usaha',
        'keperluan'
    ];

    /**
     * Get the permohonan that owns the surat domisili usaha.
     */
    public function permohonan()
    {
        return $this->belongsTo(Permohonan::class);
    }
}