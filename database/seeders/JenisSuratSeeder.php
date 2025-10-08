<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisSurat = [
            [
                'nama' => 'Surat Keterangan Penghasilan',
                'kode' => 'SKP',
                'deskripsi' => 'Surat yang menerangkan penghasilan seseorang'
            ],
            [
                'nama' => 'Surat Keterangan Domisili Tinggal',
                'kode' => 'SKDT',
                'deskripsi' => 'Surat yang menerangkan domisili tempat tinggal seseorang'
            ],
            [
                'nama' => 'Surat Keterangan Domisili Usaha',
                'kode' => 'SKDU',
                'deskripsi' => 'Surat yang menerangkan domisili tempat usaha'
            ],
            [
                'nama' => 'Surat Keterangan Pindah/Mandah',
                'kode' => 'SKM',
                'deskripsi' => 'Surat yang menerangkan kepindahan seseorang'
            ],
            [
                'nama' => 'Surat Keterangan Kematian',
                'kode' => 'SKK',
                'deskripsi' => 'Surat yang menerangkan kematian seseorang'
            ],
            [
                'nama' => 'Surat Keterangan Nikah',
                'kode' => 'SKN',
                'deskripsi' => 'Surat yang menerangkan status pernikahan seseorang'
            ],
        ];

        foreach ($jenisSurat as $surat) {
            JenisSurat::create($surat);
        }
    }
}