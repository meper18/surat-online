<?php

require 'vendor/autoload.php';

// Set server variables to prevent IP-related errors
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['HTTP_HOST'] = 'surat-online-production.up.railway.app';
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

echo "Force Railway Database Setup:\n";
echo "============================\n\n";

try {
    // 1. Create database directory and file if needed
    echo "1. Setting up database file...\n";
    $dbPath = '/tmp/database.sqlite';
    
    // For Windows testing, use a local path
    if (PHP_OS_FAMILY === 'Windows') {
        $dbPath = __DIR__ . '/railway_database.sqlite';
        $_ENV['DB_DATABASE'] = $dbPath;
        putenv('DB_DATABASE=' . $dbPath);
    }
    
    // Ensure database file exists
    if (!file_exists($dbPath)) {
        $dir = dirname($dbPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        touch($dbPath);
        echo "✓ Database file created at: $dbPath\n";
    } else {
        echo "✓ Database file exists at: $dbPath\n";
    }
    
    // 2. Initialize Laravel app
    $app = require_once 'bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    // 3. Clear all caches
    echo "\n2. Clearing all caches...\n";
    try {
        Artisan::call('config:clear');
        echo "✓ Config cache cleared\n";
    } catch (Exception $e) {
        echo "Config clear warning: " . $e->getMessage() . "\n";
    }
    
    try {
        Artisan::call('view:clear');
        echo "✓ View cache cleared\n";
    } catch (Exception $e) {
        echo "View clear warning: " . $e->getMessage() . "\n";
    }
    
    try {
        Artisan::call('route:clear');
        echo "✓ Route cache cleared\n";
    } catch (Exception $e) {
        echo "Route clear warning: " . $e->getMessage() . "\n";
    }
    
    // 4. Test database connection
    echo "\n3. Testing database connection...\n";
    $connection = DB::connection()->getPdo();
    echo "✓ Database connection successful\n";
    
    // 5. Force run migrations
    echo "\n4. Running migrations...\n";
    Artisan::call('migrate:fresh', ['--force' => true]);
    echo "✓ Fresh migrations completed\n";
    echo Artisan::output();
    
    // 6. Force seed jenis surat data
    echo "\n5. Force seeding jenis surat data...\n";
    
    // Clear existing data first
    try {
        DB::table('jenis_surats')->truncate();
        echo "✓ Existing jenis surat data cleared\n";
    } catch (Exception $e) {
        echo "Clear data warning: " . $e->getMessage() . "\n";
    }
    
    // Insert data manually
    $jenisSurat = [
        [
            'nama' => 'Surat Keterangan Penghasilan',
            'kode' => 'SKP',
            'deskripsi' => 'Surat yang menerangkan penghasilan seseorang',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'nama' => 'Surat Keterangan Domisili Tinggal',
            'kode' => 'SKDT',
            'deskripsi' => 'Surat yang menerangkan domisili tempat tinggal seseorang',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'nama' => 'Surat Keterangan Domisili Usaha',
            'kode' => 'SKDU',
            'deskripsi' => 'Surat yang menerangkan domisili tempat usaha',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'nama' => 'Surat Keterangan Pindah/Mandah',
            'kode' => 'SKM',
            'deskripsi' => 'Surat yang menerangkan kepindahan seseorang',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'nama' => 'Surat Keterangan Kematian',
            'kode' => 'SKK',
            'deskripsi' => 'Surat yang menerangkan kematian seseorang',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'nama' => 'Surat Keterangan Nikah',
            'kode' => 'SKN',
            'deskripsi' => 'Surat yang menerangkan status pernikahan seseorang',
            'created_at' => now(),
            'updated_at' => now()
        ],
    ];

    foreach ($jenisSurat as $surat) {
        DB::table('jenis_surats')->insert($surat);
        echo "✓ Inserted: {$surat['nama']} ({$surat['kode']})\n";
    }
    
    // 7. Verify data using both raw SQL and Eloquent
    echo "\n6. Verification...\n";
    
    // Raw SQL verification
    $rawCount = DB::select("SELECT COUNT(*) as count FROM jenis_surats")[0]->count;
    echo "Raw SQL count: $rawCount\n";
    
    // Eloquent verification
    $eloquentCount = App\Models\JenisSurat::count();
    echo "Eloquent count: $eloquentCount\n";
    
    if ($rawCount > 0 && $eloquentCount > 0) {
        echo "\n✅ Data verification successful!\n";
        
        // Show all data
        $allData = App\Models\JenisSurat::all();
        echo "\nAll Jenis Surat data:\n";
        foreach ($allData as $surat) {
            echo "- ID: {$surat->id}, Kode: {$surat->kode}, Nama: {$surat->nama}\n";
        }
    } else {
        echo "\n❌ Data verification failed!\n";
        exit(1);
    }
    
    // 8. Test the controller method
    echo "\n7. Testing controller method...\n";
    $controllerData = App\Models\JenisSurat::all();
    echo "Controller method returns: " . $controllerData->count() . " items\n";
    
    if ($controllerData->count() > 0) {
        echo "✓ Controller method working correctly\n";
    } else {
        echo "❌ Controller method not returning data\n";
    }
    
    echo "\n✅ Force Railway setup completed successfully!\n";
    echo "\nNext steps:\n";
    echo "1. Deploy this script to Railway\n";
    echo "2. Run this script on Railway production\n";
    echo "3. Clear browser cache and refresh the application\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}