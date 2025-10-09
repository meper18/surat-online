<?php

// Export ALL SQLite data to MySQL-compatible SQL format
echo "=== EXPORT ALL SQLITE DATA TO MYSQL ===\n\n";

$sqliteDb = 'database.sqlite';

if (!file_exists($sqliteDb)) {
    echo "Error: database.sqlite not found!\n";
    exit(1);
}

try {
    $pdo = new PDO('sqlite:' . $sqliteDb);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to SQLite database successfully.\n\n";
    
    // Get all tables
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
    $allTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Found tables: " . implode(', ', $allTables) . "\n\n";
    
    $sqlOutput = "-- Complete MySQL Data Export from SQLite\n";
    $sqlOutput .= "-- Generated on: " . date('Y-m-d H:i:s') . "\n";
    $sqlOutput .= "-- Total tables: " . count($allTables) . "\n\n";
    
    $sqlOutput .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";
    
    $totalRecords = 0;
    $tablesWithData = [];
    
    foreach ($allTables as $table) {
        echo "Processing table: $table\n";
        
        // Get table data
        $stmt = $pdo->query("SELECT * FROM `$table`");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($rows)) {
            echo "  - No data found in $table\n";
            continue;
        }
        
        $recordCount = count($rows);
        echo "  - Found $recordCount records\n";
        $totalRecords += $recordCount;
        $tablesWithData[] = $table;
        
        $sqlOutput .= "-- =============================================\n";
        $sqlOutput .= "-- Data for table: $table ($recordCount records)\n";
        $sqlOutput .= "-- =============================================\n";
        
        // Clear existing data
        $sqlOutput .= "DELETE FROM `$table`;\n";
        
        foreach ($rows as $row) {
            $columns = array_keys($row);
            $values = array_values($row);
            
            // Escape values for MySQL
            $escapedValues = array_map(function($value) {
                if ($value === null) {
                    return 'NULL';
                }
                if (is_numeric($value)) {
                    return $value;
                }
                return "'" . addslashes($value) . "'";
            }, $values);
            
            $sqlOutput .= "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $escapedValues) . ");\n";
        }
        
        $sqlOutput .= "\n";
    }
    
    $sqlOutput .= "SET FOREIGN_KEY_CHECKS = 1;\n\n";
    $sqlOutput .= "-- Export completed: $totalRecords total records from " . count($tablesWithData) . " tables\n";
    
    // Save to file
    $outputFile = 'complete_mysql_data_export.sql';
    file_put_contents($outputFile, $sqlOutput);
    
    echo "\n=== EXPORT COMPLETED ===\n";
    echo "Data exported to: $outputFile\n";
    echo "File size: " . number_format(filesize($outputFile)) . " bytes\n";
    echo "Total records: $totalRecords\n";
    echo "Tables with data: " . count($tablesWithData) . "\n\n";
    
    // Display detailed summary
    echo "=== DETAILED DATA SUMMARY ===\n";
    foreach ($allTables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM `$table`");
        $count = $stmt->fetch()['count'];
        $status = $count > 0 ? "✓" : "○";
        echo "$status $table: $count records\n";
    }
    
    // Show critical data
    echo "\n=== CRITICAL DATA VERIFICATION ===\n";
    
    // Jenis Surats
    if (in_array('jenis_surats', $allTables)) {
        echo "JENIS SURATS:\n";
        $stmt = $pdo->query("SELECT * FROM jenis_surats ORDER BY id");
        $jenisSurats = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($jenisSurats as $js) {
            echo "  - {$js['kode']}: {$js['nama']}\n";
        }
    }
    
    // Users
    if (in_array('users', $allTables)) {
        echo "\nUSERS:\n";
        $stmt = $pdo->query("SELECT id, name, email, role_id FROM users ORDER BY id");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($users as $user) {
            echo "  - {$user['name']} ({$user['email']}) - Role: {$user['role_id']}\n";
        }
    }
    
    // Roles
    if (in_array('roles', $allTables)) {
        echo "\nROLES:\n";
        $stmt = $pdo->query("SELECT * FROM roles ORDER BY id");
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($roles as $role) {
            echo "  - ID {$role['id']}: {$role['name']}\n";
        }
    }
    
    // Permohonans
    if (in_array('permohonans', $allTables)) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM permohonans");
        $permohonanCount = $stmt->fetch()['count'];
        echo "\nPERMOHONANS: $permohonanCount records\n";
    }
    
    echo "\n=== MIGRATION READY ===\n";
    echo "All data exported and ready for MySQL migration!\n";
    echo "Use this file to import data after running Laravel migrations on MySQL.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>