<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Informasi Desa
    |--------------------------------------------------------------------------
    |
    | Konfigurasi informasi desa yang akan digunakan dalam template surat
    |
    */

    'nama_desa' => env('DESA_NAMA', 'Nama Desa'),
    'nama_kecamatan' => env('DESA_KECAMATAN', 'Nama Kecamatan'),
    'nama_kabupaten' => env('DESA_KABUPATEN', 'Nama Kabupaten'),
    'alamat_lengkap' => env('DESA_ALAMAT', 'Alamat Lengkap Kantor Desa'),
    'nomor_telepon' => env('DESA_TELEPON', 'Nomor Telepon'),
    'email_desa' => env('DESA_EMAIL', 'email@desa.go.id'),
    
    /*
    |--------------------------------------------------------------------------
    | Informasi Kepala Desa
    |--------------------------------------------------------------------------
    */
    
    'nama_kepala_desa' => env('KEPALA_DESA_NAMA', 'Nama Kepala Desa'),
    'nip_kepala_desa' => env('KEPALA_DESA_NIP', 'NIP Kepala Desa'),
];