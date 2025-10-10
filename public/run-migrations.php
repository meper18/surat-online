<?php
echo "<h1>🚀 Database Migration & Seeder Runner</h1>";
echo "<p><strong>Timestamp:</strong> " . date('Y-m-d H:i:s') . "</p>";

// Set up Laravel environment
require_once __DIR__ . '/../vendor/autoload.php';

try {
    // Bootstrap Laravel application
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    
    echo "<div style='background: #d4edda; padding: 10px; margin: 10px 0; border: 1px solid #c3e6cb;'>";
    echo "✅ <strong>Laravel Application Loaded Successfully!</strong>";
    echo "</div>";
    
    echo "<h2>📊 Current Database Status</h2>";
    
    // Test database connection
    try {
        $pdo = new PDO(
            "mysql:host=metro.proxy.rlwy.net;port=19820;dbname=railway;charset=utf8mb4",
            "root",
            "XQKSMTWvXSMKoKFoXAznbkZgIdEGZiIv",
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        echo "<p>✅ Database connection successful</p>";
        
        // Check existing tables
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "<h3>📋 Current Tables:</h3>";
        if (empty($tables)) {
            echo "<p style='color: orange;'>⚠️ No tables found. Need to run migrations.</p>";
        } else {
            echo "<ul>";
            foreach ($tables as $table) {
                echo "<li>$table</li>";
            }
            echo "</ul>";
        }
        
    } catch (PDOException $e) {
        echo "<div style='background: #f8d7da; padding: 10px; margin: 10px 0; border: 1px solid #f5c6cb;'>";
        echo "❌ <strong>Database Connection Failed:</strong> " . $e->getMessage();
        echo "</div>";
        exit;
    }
    
    echo "<hr>";
    echo "<h2>🔧 Running Migrations</h2>";
    
    // Run migrations
    ob_start();
    $exitCode = $kernel->call('migrate', ['--force' => true]);
    $migrationOutput = ob_get_clean();
    
    if ($exitCode === 0) {
        echo "<div style='background: #d4edda; padding: 10px; margin: 10px 0; border: 1px solid #c3e6cb;'>";
        echo "✅ <strong>Migrations completed successfully!</strong>";
        echo "</div>";
        echo "<pre style='background: #f8f9fa; padding: 10px; border: 1px solid #dee2e6;'>";
        echo htmlspecialchars($migrationOutput);
        echo "</pre>";
    } else {
        echo "<div style='background: #f8d7da; padding: 10px; margin: 10px 0; border: 1px solid #f5c6cb;'>";
        echo "❌ <strong>Migration failed with exit code:</strong> $exitCode";
        echo "</div>";
        echo "<pre style='background: #f8f9fa; padding: 10px; border: 1px solid #dee2e6;'>";
        echo htmlspecialchars($migrationOutput);
        echo "</pre>";
    }
    
    echo "<hr>";
    echo "<h2>🌱 Running Seeders</h2>";
    
    // Run seeders
    ob_start();
    $exitCode = $kernel->call('db:seed', ['--force' => true]);
    $seederOutput = ob_get_clean();
    
    if ($exitCode === 0) {
        echo "<div style='background: #d4edda; padding: 10px; margin: 10px 0; border: 1px solid #c3e6cb;'>";
        echo "✅ <strong>Seeders completed successfully!</strong>";
        echo "</div>";
        echo "<pre style='background: #f8f9fa; padding: 10px; border: 1px solid #dee2e6;'>";
        echo htmlspecialchars($seederOutput);
        echo "</pre>";
    } else {
        echo "<div style='background: #f8d7da; padding: 10px; margin: 10px 0; border: 1px solid #f5c6cb;'>";
        echo "❌ <strong>Seeder failed with exit code:</strong> $exitCode";
        echo "</div>";
        echo "<pre style='background: #f8f9fa; padding: 10px; border: 1px solid #dee2e6;'>";
        echo htmlspecialchars($seederOutput);
        echo "</pre>";
    }
    
    echo "<hr>";
    echo "<h2>📊 Final Database Status</h2>";
    
    // Check tables after migration
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h3>📋 Tables After Migration:</h3>";
    echo "<ul>";
    foreach ($tables as $table) {
        // Get row count for each table
        try {
            $countStmt = $pdo->query("SELECT COUNT(*) as count FROM `$table`");
            $count = $countStmt->fetch()['count'];
            echo "<li><strong>$table</strong> ($count rows)</li>";
        } catch (Exception $e) {
            echo "<li><strong>$table</strong> (error counting rows)</li>";
        }
    }
    echo "</ul>";
    
    // Check jenis_surats specifically
    try {
        $stmt = $pdo->query("SELECT * FROM jenis_surats ORDER BY id");
        $jenisSurats = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h3>📝 Jenis Surat Data:</h3>";
        if (empty($jenisSurats)) {
            echo "<p style='color: orange;'>⚠️ No jenis_surats data found.</p>";
        } else {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Nama</th><th>Kode</th><th>Deskripsi</th></tr>";
            foreach ($jenisSurats as $jenis) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($jenis['id']) . "</td>";
                echo "<td>" . htmlspecialchars($jenis['nama']) . "</td>";
                echo "<td>" . htmlspecialchars($jenis['kode']) . "</td>";
                echo "<td>" . htmlspecialchars($jenis['deskripsi'] ?? '') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error checking jenis_surats: " . $e->getMessage() . "</p>";
    }
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 10px; margin: 10px 0; border: 1px solid #f5c6cb;'>";
    echo "❌ <strong>Error:</strong> " . $e->getMessage();
    echo "</div>";
    echo "<pre style='background: #f8f9fa; padding: 10px; border: 1px solid #dee2e6;'>";
    echo $e->getTraceAsString();
    echo "</pre>";
}
?>