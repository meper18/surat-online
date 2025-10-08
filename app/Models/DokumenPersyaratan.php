<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenPersyaratan extends Model
{
    use HasFactory;

    protected $fillable = ['permohonan_id', 'nama_dokumen', 'file_path', 'wajib'];

    /**
     * Get the permohonan that owns the dokumen persyaratan.
     */
    public function permohonan()
    {
        return $this->belongsTo(Permohonan::class);
    }
}