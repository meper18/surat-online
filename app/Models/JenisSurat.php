<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'kode', 'deskripsi'];

    /**
     * Get the permohonans for the jenis surat.
     */
    public function permohonans()
    {
        return $this->hasMany(Permohonan::class);
    }
}