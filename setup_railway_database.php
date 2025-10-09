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

echo "Setting up Railway Database:\n";
echo "===========================\n\n";

try {
    // 1. Create database directory and file
    echo "1. Creating database file...\n";
    $dbPath = '/tmp/database.sqlite';
    
    // For Windows testing, use a local path
    if (PHP_OS_FAMILY === 'Windows') {
        $dbPath = __DIR__ . '/railway_database.sqlite';
        $_ENV['DB_DATABASE'] = $dbPath;
        putenv('DB_DATABASE=' . $dbPath);
    }
    
    // Create the database file if it doesn't exist
    if (!file_exists($dbPath)) {
        $dir = dirname($dbPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        touch($dbPath);
        echo "✓ Database file created at: $dbPath\n";
    } else {
        echo "✓ Database file already exists at: $dbPath\n";
    }
    
    // 2. Initialize Laravel app
    $app = require_once 'bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    // 3. Test database connection
    echo "\n2. Testing database connection...\n";
    $connection = DB::connection()->getPdo();
    echo "✓ Database connection successful\n";
    
    // 4. Run migrations
    echo "\n3. Running migrations...\n";
    Artisan::call('migrate', ['--force' => true]);
    echo "✓ Migrations completed\n";
    echo Artisan::output();
    
    // 5. Check if jenis_surat table exists
    echo "\n4. Checking jenis_surat table...\n";
    $tableExists = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name='jenis_surats'");
    if (count($tableExists) > 0) {
        echo "✓ jenis_surats table exists\n";
    } else {
        echo "❌ jenis_surats table does not exist\n";
        exit(1);
    }
    
    // 6. Check existing data
    echo "\n5. Checking existing jenis surat data...\n";
    $existingData = App\Models\JenisSurat::all();
    echo "Current records: " . $existingData->count() . "\n";
    
    // 7. Run seeder if no data exists
    if ($existingData->count() == 0) {
        echo "\n6. Running JenisSuratSeeder...\n";
        Artisan::call('db:seed', ['--class' => 'JenisSuratSeeder', '--force' => true]);
        echo "✓ Seeder completed\n";
        echo Artisan::output();
    } else {
        echo "\n6. Data already exists, skipping seeder\n";
    }
    
    // 8. Verify final data
    echo "\n7. Final verification...\n";
    $finalData = App\Models\JenisSurat::all();
    echo "Total jenis surat records: " . $finalData->count() . "\n";
    
    if ($finalData->count() > 0) {
        echo "Jenis Surat data:\n";
        foreach ($finalData as $surat) {
            echo "- ID: {$surat->id}, Kode: {$surat->kode}, Nama: {$surat->nama}\n";
        }
    }
    
    echo "\n✅ Railway database setup completed successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}