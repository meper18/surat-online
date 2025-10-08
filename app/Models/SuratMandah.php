<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMandah extends Model
{
    use HasFactory;

    protected $fillable = [
        'permohonan_id',
        'alamat_mandah',
        'kelurahan_mandah',
        'kecamatan_mandah',
        'kabupaten_mandah',
        'provinsi_mandah',
        'jumlah_keluarga_ikut',
        'nama_pengikut1',
        'jenis_kelamin_pengikut1',
        'hubungan_keluarga_pengikut1',
        'nama_pengikut2',
        'jenis_kelamin_pengikut2',
        'hubungan_keluarga_pengikut2',
        'nama_pengikut3',
        'jenis_kelamin_pengikut3',
        'hubungan_keluarga_pengikut3'
    ];

    /**
     * Get the permohonan that owns the surat mandah.
     */
    public function permohonan()
    {
        return $this->belongsTo(Permohonan::class);
    }
}