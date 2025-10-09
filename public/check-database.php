<?php

// Simple database checker for Railway production
echo "<h1>Railway Database Status</h1>";
echo "<style>body{font-family:Arial;margin:20px;} .success{color:green;} .error{color:red;} .info{color:blue;}</style>";

// Check if we're on Railway
$isRailway = isset($_ENV['RAILWAY_ENVIRONMENT']) || 
             isset($_ENV['PORT']) || 
             (getenv('DB_DATABASE') === '/tmp/database.sqlite');

echo "<p class='info'>Environment: " . ($isRailway ? "RAILWAY PRODUCTION" : "LOCAL DEVELOPMENT") . "</p>";

// Database path
$dbPath = $isRailway ? '/tmp/database.sqlite' : '../database.sqlite';
echo "<p>Database Path: <code>$dbPath</code></p>";

// Check if database exists
if (file_exists($dbPath)) {
    echo "<p class='success'>✓ Database file exists</p>";
    echo "<p>Size: " . number_format(filesize($dbPath)) . " bytes</p>";
    
    try {
        // Connect to database
        $pdo = new PDO('sqlite:' . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "<p class='success'>✓ Database connection successful</p>";
        
        // Check jenis_surats table
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM jenis_surats");
        $count = $stmt->fetch()['count'];
        
        echo "<h2>Jenis Surat Data</h2>";
        echo "<p>Total records: <strong>$count</strong></p>";
        
        if ($count > 0) {
            echo "<h3>Available Jenis Surat:</h3>";
            echo "<ul>";
            
            $stmt = $pdo->query("SELECT * FROM jenis_surats ORDER BY id");
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($records as $record) {
                echo "<li><strong>{$record['kode']}</strong>: {$record['nama']}</li>";
            }
            echo "</ul>";
            
            echo "<p class='success'>✅ Jenis Surat data is available!</p>";
        } else {
            echo "<p class='error'>❌ No Jenis Surat data found</p>";
            
            // If no data and we have source database, copy it
            if ($isRailway) {
                $sourceDb = '../railway_database.sqlite';
                if (file_exists($sourceDb)) {
                    echo "<p class='info'>Attempting to copy database...</p>";
                    
                    if (copy($sourceDb, $dbPath)) {
                        echo "<p class='success'>✓ Database copied successfully</p>";
                        echo "<script>setTimeout(function(){location.reload();}, 2000);</script>";
                        echo "<p>Refreshing page in 2 seconds...</p>";
                    } else {
                        echo "<p class='error'>❌ Failed to copy database</p>";
                    }
                }
            }
        }
        
        // Check other tables
        echo "<h3>Other Tables:</h3>";
        $tables = ['users', 'roles', 'permohonans'];
        echo "<ul>";
        foreach ($tables as $table) {
            try {
                $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
                $count = $stmt->fetch()['count'];
                echo "<li>$table: $count records</li>";
            } catch (Exception $e) {
                echo "<li>$table: <span class='error'>Error or doesn't exist</span></li>";
            }
        }
        echo "</ul>";
        
    } catch (Exception $e) {
        echo "<p class='error'>❌ Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
} else {
    echo "<p class='error'>❌ Database file does not exist</p>";
    
    // If on Railway and database missing, try to copy from source
    if ($isRailway) {
        $sourceDb = '../railway_database.sqlite';
        if (file_exists($sourceDb)) {
            echo "<p class='info'>Source database found, attempting to create production database...</p>";
            
            // Create directory if needed
            $dir = dirname($dbPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            
            if (copy($sourceDb, $dbPath)) {
                echo "<p class='success'>✓ Database created successfully</p>";
                echo "<script>setTimeout(function(){location.reload();}, 2000);</script>";
                echo "<p>Refreshing page in 2 seconds...</p>";
            } else {
                echo "<p class='error'>❌ Failed to create database</p>";
            }
        } else {
            echo "<p class='error'>❌ Source database not found</p>";
        }
    }
}

echo "<hr>";
echo "<p><small>Last checked: " . date('Y-m-d H:i:s') . "</small></p>";
echo "<p><a href='javascript:location.reload()'>🔄 Refresh</a></p>";
?>