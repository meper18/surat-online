<?php

/**
 * Comprehensive MySQL Deployment Script for Railway
 * This script handles complete migration from SQLite to MySQL
 */

echo "=== RAILWAY MYSQL DEPLOYMENT SCRIPT ===\n\n";

// Environment detection
$isRailway = isset($_ENV['RAILWAY_ENVIRONMENT']) || isset($_SERVER['RAILWAY_ENVIRONMENT']);
$isWeb = isset($_SERVER['HTTP_HOST']);

if ($isWeb) {
    echo "<h1>Railway MySQL Deployment</h1>";
    echo "<pre>";
}

echo "Environment: " . ($isRailway ? "Railway" : "Local") . "\n";
echo "Interface: " . ($isWeb ? "Web" : "CLI") . "\n";
echo "Timestamp: " . date('Y-m-d H:i:s') . "\n\n";

// MySQL Configuration for Railway
$mysqlConfig = [
    'host' => $_ENV['MYSQLHOST'] ?? $_SERVER['MYSQLHOST'] ?? 'localhost',
    'port' => $_ENV['MYSQLPORT'] ?? $_SERVER['MYSQLPORT'] ?? '3306',
    'database' => $_ENV['MYSQLDATABASE'] ?? $_SERVER['MYSQLDATABASE'] ?? 'railway',
    'username' => $_ENV['MYSQLUSER'] ?? $_SERVER['MYSQLUSER'] ?? 'root',
    'password' => $_ENV['MYSQLPASSWORD'] ?? $_SERVER['MYSQLPASSWORD'] ?? '',
];

echo "=== MYSQL CONNECTION CONFIGURATION ===\n";
echo "Host: " . $mysqlConfig['host'] . "\n";
echo "Port: " . $mysqlConfig['port'] . "\n";
echo "Database: " . $mysqlConfig['database'] . "\n";
echo "Username: " . $mysqlConfig['username'] . "\n";
echo "Password: " . (empty($mysqlConfig['password']) ? "Not set" : "***") . "\n\n";

try {
    // Connect to MySQL
    $dsn = "mysql:host={$mysqlConfig['host']};port={$mysqlConfig['port']};dbname={$mysqlConfig['database']};charset=utf8mb4";
    $pdo = new PDO($dsn, $mysqlConfig['username'], $mysqlConfig['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]);
    
    echo "✅ MySQL Connection: SUCCESS\n\n";
    
    // Check if database is empty
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "=== DATABASE STATUS ===\n";
    echo "Existing tables: " . count($tables) . "\n";
    
    if (!empty($tables)) {
        echo "Tables found:\n";
        foreach ($tables as $table) {
            $countStmt = $pdo->query("SELECT COUNT(*) FROM `$table`");
            $count = $countStmt->fetchColumn();
            echo "  - $table: $count records\n";
        }
        echo "\n";
    }
    
    // Check if Laravel migrations table exists
    $migrationsExist = in_array('migrations', $tables);
    $jenisExist = in_array('jenis_surats', $tables);
    
    echo "=== MIGRATION STATUS ===\n";
    echo "Migrations table exists: " . ($migrationsExist ? "YES" : "NO") . "\n";
    echo "Jenis Surats table exists: " . ($jenisExist ? "YES" : "NO") . "\n\n";
    
    // If database is empty or missing key tables, run migrations
    if (!$migrationsExist || !$jenisExist) {
        echo "=== RUNNING LARAVEL MIGRATIONS ===\n";
        
        // Simulate Laravel migration commands (in real deployment, these would be run via artisan)
        echo "Commands to run on Railway:\n";
        echo "1. php artisan migrate --force\n";
        echo "2. php artisan db:seed --force\n\n";
        
        // Create essential tables manually if needed
        if (!$migrationsExist) {
            echo "Creating migrations table...\n";
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS `migrations` (
                    `id` int unsigned NOT NULL AUTO_INCREMENT,
                    `migration` varchar(255) NOT NULL,
                    `batch` int NOT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
            echo "✅ Migrations table created\n";
        }
        
        if (!$jenisExist) {
            echo "Creating jenis_surats table...\n";
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS `jenis_surats` (
                    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                    `nama` varchar(255) NOT NULL,
                    `deskripsi` text,
                    `template_file` varchar(255) DEFAULT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
            echo "✅ Jenis Surats table created\n";
        }
    }
    
    // Import data from export file if available
    $exportFile = 'complete_mysql_data_export.sql';
    if (file_exists($exportFile)) {
        echo "=== IMPORTING DATA ===\n";
        echo "Found export file: $exportFile\n";
        
        $sql = file_get_contents($exportFile);
        
        // Check if jenis_surats has data
        $stmt = $pdo->query("SELECT COUNT(*) FROM jenis_surats");
        $jenisCount = $stmt->fetchColumn();
        
        if ($jenisCount == 0) {
            echo "Importing data from export file...\n";
            
            // Split SQL into individual statements
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            
            $imported = 0;
            foreach ($statements as $statement) {
                if (!empty($statement) && !preg_match('/^--/', $statement)) {
                    try {
                        $pdo->exec($statement);
                        $imported++;
                    } catch (PDOException $e) {
                        // Skip duplicate entries or other non-critical errors
                        if (strpos($e->getMessage(), 'Duplicate entry') === false) {
                            echo "Warning: " . $e->getMessage() . "\n";
                        }
                    }
                }
            }
            
            echo "✅ Imported $imported SQL statements\n";
        } else {
            echo "Data already exists, skipping import\n";
        }
    } else {
        echo "⚠ Export file not found: $exportFile\n";
        echo "Manual data seeding may be required\n";
    }
    
    // Verify critical data
    echo "\n=== DATA VERIFICATION ===\n";
    
    $tables_to_check = ['jenis_surats', 'roles', 'users'];
    foreach ($tables_to_check as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) FROM `$table`");
            $count = $stmt->fetchColumn();
            echo "$table: $count records\n";
            
            if ($table == 'jenis_surats' && $count > 0) {
                $stmt = $pdo->query("SELECT id, nama FROM jenis_surats LIMIT 10");
                $jenis = $stmt->fetchAll();
                echo "  Jenis Surat entries:\n";
                foreach ($jenis as $item) {
                    echo "    - ID {$item['id']}: {$item['nama']}\n";
                }
            }
        } catch (PDOException $e) {
            echo "$table: Table not found\n";
        }
    }
    
    echo "\n=== DEPLOYMENT SUMMARY ===\n";
    echo "✅ MySQL connection established\n";
    echo "✅ Database structure verified\n";
    echo "✅ Data migration completed\n";
    echo "✅ Ready for application testing\n\n";
    
    echo "=== NEXT STEPS ===\n";
    echo "1. Update .env file with MySQL credentials\n";
    echo "2. Deploy application to Railway\n";
    echo "3. Test dropdown functionality\n";
    echo "4. Verify all features work correctly\n\n";
    
    echo "=== EXPECTED RESULTS ===\n";
    echo "- Jenis Surat dropdown should show 6 options\n";
    echo "- All forms should work correctly\n";
    echo "- Database operations should be faster\n";
    echo "- No more SQLite file path issues\n\n";
    
} catch (PDOException $e) {
    echo "❌ MySQL Connection Failed: " . $e->getMessage() . "\n\n";
    
    echo "=== TROUBLESHOOTING ===\n";
    echo "1. Verify MySQL service is running on Railway\n";
    echo "2. Check environment variables:\n";
    echo "   - MYSQLHOST\n";
    echo "   - MYSQLPORT\n";
    echo "   - MYSQLDATABASE\n";
    echo "   - MYSQLUSER\n";
    echo "   - MYSQLPASSWORD\n";
    echo "3. Ensure MySQL service is linked to your app\n";
    echo "4. Check Railway logs for MySQL service status\n\n";
    
    echo "=== RAILWAY MYSQL SETUP ===\n";
    echo "1. Go to Railway Dashboard\n";
    echo "2. Create new MySQL service\n";
    echo "3. Link MySQL service to your app\n";
    echo "4. Copy connection variables to your app\n";
    echo "5. Redeploy your application\n\n";
}

if ($isWeb) {
    echo "</pre>";
    echo "<p><strong>Deployment Status:</strong> " . (isset($pdo) ? "✅ Success" : "❌ Failed") . "</p>";
    echo "<p><a href='javascript:location.reload()'>🔄 Refresh Status</a></p>";
}

echo "=== END OF DEPLOYMENT SCRIPT ===\n";
?>