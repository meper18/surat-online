<?php
echo "<h1>Database Seeder untuk Railway</h1>";

// Set memory limit untuk seeder yang besar
ini_set('memory_limit', '512M');
set_time_limit(300); // 5 menit timeout

// Load Laravel
try {
    require_once '/app/vendor/autoload.php';
    
    // Bootstrap Laravel application
    $app = require_once '/app/bootstrap/app.php';
    
    // Load environment
    if (file_exists('/app/.env.railway')) {
        $dotenv = Dotenv\Dotenv::createImmutable('/app', '.env.railway');
        $dotenv->load();
    }
    
    // Boot the application
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "<p style='color: green;'>✅ Laravel berhasil di-load</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error loading Laravel: " . $e->getMessage() . "</p>";
    exit;
}

echo "<h2>Status Database:</h2>";

// Test database connection
try {
    $pdo = new PDO(
        "mysql:host=" . $_ENV['DB_HOST'] . ";port=" . $_ENV['DB_PORT'] . ";dbname=" . $_ENV['DB_DATABASE'],
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD']
    );
    echo "<p style='color: green;'>✅ Koneksi database berhasil</p>";
    
    // Check existing tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "<p>Tabel yang ada: " . count($tables) . " tabel</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error koneksi database: " . $e->getMessage() . "</p>";
    exit;
}

echo "<h2>Menjalankan Database Seeder:</h2>";

// Run seeders
$seeders = [
    'RoleSeeder',
    'UserSeeder', 
    'JenisSuratSeeder'
];

foreach ($seeders as $seeder) {
    echo "<h3>Menjalankan $seeder:</h3>";
    
    try {
        // Use Artisan to run seeder
        $exitCode = Illuminate\Support\Facades\Artisan::call('db:seed', [
            '--class' => $seeder,
            '--force' => true
        ]);
        
        $output = Illuminate\Support\Facades\Artisan::output();
        
        if ($exitCode === 0) {
            echo "<p style='color: green;'>✅ $seeder berhasil dijalankan</p>";
            if (!empty($output)) {
                echo "<pre style='background: #e8f5e8; padding: 10px;'>" . htmlspecialchars($output) . "</pre>";
            }
        } else {
            echo "<p style='color: red;'>❌ $seeder gagal dengan exit code: $exitCode</p>";
            echo "<pre style='background: #ffe6e6; padding: 10px;'>" . htmlspecialchars($output) . "</pre>";
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error menjalankan $seeder: " . $e->getMessage() . "</p>";
        echo "<pre style='background: #ffe6e6; padding: 10px;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
    
    echo "<hr>";
}

echo "<h2>Menjalankan Semua Seeder Sekaligus:</h2>";

try {
    echo "<p>Menjalankan php artisan db:seed --force...</p>";
    
    $exitCode = Illuminate\Support\Facades\Artisan::call('db:seed', [
        '--force' => true
    ]);
    
    $output = Illuminate\Support\Facades\Artisan::output();
    
    if ($exitCode === 0) {
        echo "<p style='color: green;'>✅ Semua seeder berhasil dijalankan</p>";
    } else {
        echo "<p style='color: red;'>❌ Seeder gagal dengan exit code: $exitCode</p>";
    }
    
    if (!empty($output)) {
        echo "<pre style='background: #f0f0f0; padding: 10px;'>" . htmlspecialchars($output) . "</pre>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error menjalankan seeder: " . $e->getMessage() . "</p>";
    echo "<pre style='background: #ffe6e6; padding: 10px;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "<h2>Verifikasi Data:</h2>";

// Check seeded data
try {
    // Check roles
    $stmt = $pdo->query("SELECT COUNT(*) FROM roles");
    $roleCount = $stmt->fetchColumn();
    echo "<p>Roles: $roleCount record</p>";
    
    // Check users
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $userCount = $stmt->fetchColumn();
    echo "<p>Users: $userCount record</p>";
    
    // Check jenis_surats
    $stmt = $pdo->query("SELECT COUNT(*) FROM jenis_surats");
    $jenisCount = $stmt->fetchColumn();
    echo "<p>Jenis Surat: $jenisCount record</p>";
    
    if ($roleCount > 0 && $userCount > 0 && $jenisCount > 0) {
        echo "<p style='color: green; font-weight: bold;'>✅ Database seeder berhasil! Data sudah tersedia.</p>";
    } else {
        echo "<p style='color: orange; font-weight: bold;'>⚠️ Beberapa data mungkin belum ter-seed dengan benar.</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error verifikasi data: " . $e->getMessage() . "</p>";
}

echo "<h2>Langkah Selanjutnya:</h2>";
echo "<p>1. Jika seeder berhasil, coba akses aplikasi utama</p>";
echo "<p>2. Login dengan user yang sudah di-seed</p>";
echo "<p>3. Periksa apakah semua fitur berfungsi dengan baik</p>";

echo "<br><p><a href='/'>← Kembali ke Aplikasi Utama</a></p>";
?>