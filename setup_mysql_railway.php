<?php

// Setup MySQL Database on Railway
echo "=== SETUP MYSQL DATABASE ON RAILWAY ===\n\n";

// Check if we're on Railway
$isRailway = isset($_ENV['RAILWAY_ENVIRONMENT']) || 
             isset($_ENV['PORT']) || 
             (getenv('DB_CONNECTION') === 'mysql');

echo "Environment: " . ($isRailway ? "RAILWAY PRODUCTION" : "LOCAL DEVELOPMENT") . "\n\n";

if (!$isRailway) {
    echo "This script is designed to run on Railway production environment.\n";
    echo "Please deploy this script to Railway first.\n";
    exit(1);
}

// MySQL connection details from Railway environment
$host = getenv('DB_HOST') ?: 'mysql.railway.internal';
$port = getenv('DB_PORT') ?: '3306';
$database = getenv('DB_DATABASE') ?: 'railway';
$username = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD');

echo "MySQL Configuration:\n";
echo "Host: $host\n";
echo "Port: $port\n";
echo "Database: $database\n";
echo "Username: $username\n";
echo "Password: " . (empty($password) ? "NOT SET" : "SET") . "\n\n";

if (empty($password)) {
    echo "ERROR: MySQL password not found in environment variables!\n";
    echo "Please check Railway MySQL service configuration.\n";
    exit(1);
}

try {
    // Connect to MySQL
    $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]);
    
    echo "✓ Connected to MySQL database successfully!\n\n";
    
    // Check if tables exist
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Existing tables: " . (empty($tables) ? "NONE" : implode(', ', $tables)) . "\n\n";
    
    // Check if jenis_surats table exists and has data
    if (in_array('jenis_surats', $tables)) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM jenis_surats");
        $count = $stmt->fetch()['count'];
        
        echo "Jenis Surats records: $count\n";
        
        if ($count > 0) {
            echo "✓ Database already has data!\n\n";
            
            // Display existing data
            echo "=== EXISTING JENIS SURAT DATA ===\n";
            $stmt = $pdo->query("SELECT * FROM jenis_surats ORDER BY id");
            $records = $stmt->fetchAll();
            
            foreach ($records as $record) {
                echo "- {$record['kode']}: {$record['nama']}\n";
            }
            
            echo "\nMySQL database is ready!\n";
            exit(0);
        }
    }
    
    // If no data, try to import from exported SQL file
    $sqlFile = '../mysql_data_export.sql';
    
    if (file_exists($sqlFile)) {
        echo "Found exported data file, importing...\n";
        
        $sql = file_get_contents($sqlFile);
        
        // Execute SQL statements
        $statements = explode(';', $sql);
        $imported = 0;
        
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (empty($statement) || strpos($statement, '--') === 0) {
                continue;
            }
            
            try {
                $pdo->exec($statement);
                $imported++;
            } catch (Exception $e) {
                echo "Warning: " . $e->getMessage() . "\n";
            }
        }
        
        echo "✓ Imported $imported SQL statements\n\n";
        
        // Verify import
        if (in_array('jenis_surats', $tables) || $pdo->query("SHOW TABLES LIKE 'jenis_surats'")->rowCount() > 0) {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM jenis_surats");
            $count = $stmt->fetch()['count'];
            
            echo "=== IMPORT VERIFICATION ===\n";
            echo "Jenis Surats records: $count\n";
            
            if ($count > 0) {
                echo "✓ Data imported successfully!\n\n";
                
                $stmt = $pdo->query("SELECT * FROM jenis_surats ORDER BY id");
                $records = $stmt->fetchAll();
                
                echo "=== IMPORTED JENIS SURAT DATA ===\n";
                foreach ($records as $record) {
                    echo "- {$record['kode']}: {$record['nama']}\n";
                }
            }
        }
        
    } else {
        echo "No exported data file found at: $sqlFile\n";
        echo "Please run Laravel migrations and seeders manually.\n";
    }
    
    echo "\n=== MYSQL SETUP COMPLETED ===\n";
    echo "Database is ready for use!\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "\nTroubleshooting:\n";
    echo "1. Ensure MySQL service is running on Railway\n";
    echo "2. Check environment variables are set correctly\n";
    echo "3. Verify MySQL service is in the same project\n";
    exit(1);
}
?>