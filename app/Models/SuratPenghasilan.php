<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratPenghasilan extends Model
{
    use HasFactory;

    protected $fillable = [
        'permohonan_id',
        'jumlah_penghasilan',
        'keperluan'
    ];

    /**
     * Get the permohonan that owns the surat penghasilan.
     */
    public function permohonan()
    {
        return $this->belongsTo(Permohonan::class);
    }
}