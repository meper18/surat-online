<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratDomisiliTinggal extends Model
{
    use HasFactory;

    protected $fillable = [
        'permohonan_id',
        'alamat_sekarang',
        'keperluan'
    ];

    /**
     * Get the permohonan that owns the surat domisili tinggal.
     */
    public function permohonan()
    {
        return $this->belongsTo(Permohonan::class);
    }
}