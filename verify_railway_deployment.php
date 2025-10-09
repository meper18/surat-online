<?php

echo "Railway Deployment Verification\n";
echo "===============================\n\n";

// Check if we're running on Railway (production)
$isRailway = isset($_ENV['RAILWAY_ENVIRONMENT']) || 
             isset($_ENV['PORT']) || 
             (isset($_ENV['DB_DATABASE']) && $_ENV['DB_DATABASE'] === '/tmp/database.sqlite');

echo "Environment: " . ($isRailway ? "RAILWAY PRODUCTION" : "LOCAL DEVELOPMENT") . "\n";

if ($isRailway) {
    echo "✓ Running on Railway production\n";
    
    // Check if database file exists
    $dbPath = '/tmp/database.sqlite';
    echo "Database path: $dbPath\n";
    echo "Database exists: " . (file_exists($dbPath) ? 'YES' : 'NO') . "\n";
    
    if (!file_exists($dbPath)) {
        echo "\n❌ Database file missing! Running setup...\n";
        
        // Copy from our prepared database
        $sourceDb = __DIR__ . '/railway_database.sqlite';
        if (file_exists($sourceDb)) {
            echo "Copying database from $sourceDb to $dbPath\n";
            
            // Ensure directory exists
            $dir = dirname($dbPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
                echo "Created directory: $dir\n";
            }
            
            // Copy file
            if (copy($sourceDb, $dbPath)) {
                echo "✓ Database copied successfully\n";
                echo "New database size: " . number_format(filesize($dbPath)) . " bytes\n";
            } else {
                echo "❌ Failed to copy database\n";
                exit(1);
            }
        } else {
            echo "❌ Source database not found: $sourceDb\n";
            exit(1);
        }
    }
    
    // Verify database content
    try {
        $pdo = new PDO('sqlite:' . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM jenis_surats");
        $count = $stmt->fetch()['count'];
        echo "Jenis Surats in production: $count records\n";
        
        if ($count > 0) {
            echo "✅ Database is properly set up!\n";
            
            $stmt = $pdo->query("SELECT * FROM jenis_surats ORDER BY id");
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "\nAvailable Jenis Surat:\n";
            foreach ($records as $record) {
                echo "  - {$record['kode']}: {$record['nama']}\n";
            }
        } else {
            echo "❌ No jenis surat data found\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Database error: " . $e->getMessage() . "\n";
    }
    
} else {
    echo "ℹ️ Running locally - checking local files\n";
    
    // Check local files
    $files = ['database.sqlite', 'railway_database.sqlite'];
    foreach ($files as $file) {
        if (file_exists($file)) {
            echo "✓ $file exists (" . number_format(filesize($file)) . " bytes)\n";
        } else {
            echo "❌ $file missing\n";
        }
    }
}

echo "\n✅ Verification completed!\n";