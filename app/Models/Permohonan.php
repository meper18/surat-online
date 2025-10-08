<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permohonan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_permohonan',
        'user_id',
        'jenis_surat_id',
        'tanggal_permohonan',
        'tanggal_surat_pernyataan',
        'keperluan',
        'dokumen_pendukung',
        'status',
        'keterangan_status',
        'catatan',
        'diproses_oleh',
        'tanggal_diproses',
        'tanggal_selesai',
        'nomor_surat',
        'file_surat',
        'signature_type',
        'digital_signature',
        'qr_code_data',
        'qr_code_image',
        'signed_at',
        'signed_by'
    ];

    protected $casts = [
        'tanggal_permohonan' => 'date',
        'tanggal_surat_pernyataan' => 'date',
        'tanggal_diproses' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'signed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the permohonan.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the jenis surat that owns the permohonan.
     */
    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }

    /**
     * Get the dokumen persyaratans for the permohonan.
     */
    public function dokumenPersyaratans()
    {
        return $this->hasMany(DokumenPersyaratan::class);
    }

    /**
     * Get the surat penghasilan associated with the permohonan.
     */
    public function suratPenghasilan()
    {
        return $this->hasOne(SuratPenghasilan::class);
    }

    /**
     * Get the surat domisili tinggal associated with the permohonan.
     */
    public function suratDomisiliTinggal()
    {
        return $this->hasOne(SuratDomisiliTinggal::class);
    }

    /**
     * Get the surat domisili usaha associated with the permohonan.
     */
    public function suratDomisiliUsaha()
    {
        return $this->hasOne(SuratDomisiliUsaha::class);
    }

    /**
     * Get the surat mandah associated with the permohonan.
     */
    public function suratMandah()
    {
        return $this->hasOne(SuratMandah::class);
    }

    /**
     * Get the surat kematian associated with the permohonan.
     */
    public function suratKematian()
    {
        return $this->hasOne(SuratKematian::class);
    }

    /**
     * Get the dokumen wajib for the permohonan.
     */
    public function dokumenWajib()
    {
        return $this->hasMany(DokumenWajib::class);
    }

    /**
     * Get the surat nikah associated with the permohonan.
     */
    public function suratNikah()
    {
        return $this->hasOne(SuratNikah::class);
    }

    /**
     * Check if permohonan has complete detail data based on jenis surat
     */
    public function hasCompleteDetailData()
    {
        $jenisSurat = $this->jenisSurat->nama;
        
        switch ($jenisSurat) {
            case 'Surat Keterangan Penghasilan':
                return $this->suratPenghasilan !== null;
            case 'Surat Keterangan Domisili Tinggal':
                return $this->suratDomisiliTinggal !== null;
            case 'Surat Keterangan Domisili Usaha':
                return $this->suratDomisiliUsaha !== null;
            case 'Surat Keterangan Pindah/Mandah':
                return $this->suratMandah !== null;
            case 'Surat Keterangan Kematian':
                return $this->suratKematian !== null;
            case 'Surat Keterangan Nikah':
            case 'Surat Keterangan Belum Menikah':
                return $this->suratNikah !== null;
            default:
                return true; // For surat types without detail forms
        }
    }

    /**
     * Get the detail form route for this permohonan
     */
    public function getDetailFormRoute()
    {
        $jenisSurat = $this->jenisSurat->nama;
        
        $routeMap = [
            'Surat Keterangan Penghasilan' => 'warga.surat-penghasilan.create',
            'Surat Keterangan Domisili Tinggal' => 'warga.surat-domisili-tinggal.create',
            'Surat Keterangan Domisili Usaha' => 'warga.surat-domisili-usaha.create',
            'Surat Keterangan Pindah/Mandah' => 'warga.surat-mandah.create',
            'Surat Keterangan Kematian' => 'warga.surat-kematian.create',
            'Surat Keterangan Nikah' => 'warga.surat-nikah.create',
            'Surat Keterangan Belum Menikah' => 'warga.surat-nikah.create',
        ];

        return $routeMap[$jenisSurat] ?? null;
    }

    /**
     * Generate kode permohonan.
     */
    public static function generateKodePermohonan()
    {
        $prefix = 'PERM';
        $date = now()->format('Ymd');
        $lastPermohonan = self::whereDate('created_at', now())->latest()->first();
        
        if ($lastPermohonan) {
            $lastNumber = (int) substr($lastPermohonan->kode_permohonan, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate nomor surat.
     */
    public function generateNomorSurat()
    {
        $jenisSurat = $this->jenisSurat;
        $bulan = now()->format('m');
        $tahun = now()->format('Y');
        $kode = $jenisSurat->kode;
        
        $lastPermohonan = self::where('jenis_surat_id', $this->jenis_surat_id)
            ->whereNotNull('nomor_surat')
            ->whereYear('created_at', $tahun)
            ->latest()
            ->first();
        
        if ($lastPermohonan && $lastPermohonan->nomor_surat) {
            $parts = explode('/', $lastPermohonan->nomor_surat);
            $lastNumber = (int) $parts[0];
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return str_pad($newNumber, 3, '0', STR_PAD_LEFT) . '/' . $kode . '/' . $bulan . '/' . $tahun;
    }
}