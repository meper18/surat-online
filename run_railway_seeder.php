<?php

require 'vendor/autoload.php';

// Set server variables to prevent IP-related errors
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['REQUEST_METHOD'] = 'GET';

// Load Railway environment
if (file_exists('.env.railway')) {
    $lines = file('.env.railway', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}

// Override database path for Railway testing
$_ENV['DB_DATABASE'] = '/tmp/database.sqlite';
putenv('DB_DATABASE=/tmp/database.sqlite');

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Running JenisSuratSeeder on Railway production database:\n";
echo "====================================================\n\n";

try {
    // Check database connection
    $connection = DB::connection()->getPdo();
    echo "✓ Database connection successful\n\n";
    
    // Check current jenis surat data
    $existingJenisSurat = App\Models\JenisSurat::all();
    echo "Current jenis surat count: " . $existingJenisSurat->count() . "\n\n";
    
    if ($existingJenisSurat->count() > 0) {
        echo "Jenis surat already exists. Clearing existing data first...\n";
        App\Models\JenisSurat::truncate();
        echo "✓ Existing data cleared\n\n";
    }
    
    // Run the seeder manually
    echo "Running JenisSuratSeeder...\n";
    
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
        App\Models\JenisSurat::create($surat);
        echo "✓ Created: {$surat['nama']} ({$surat['kode']})\n";
    }
    
    echo "\n✅ JenisSuratSeeder completed successfully!\n";
    
    // Verify the data
    $newJenisSurat = App\Models\JenisSurat::all();
    echo "\nVerification - Total jenis surat: " . $newJenisSurat->count() . "\n";
    foreach ($newJenisSurat as $surat) {
        echo "- ID: {$surat->id}, Kode: {$surat->kode}, Nama: {$surat->nama}\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}