<?php

// Export SQLite data to MySQL-compatible SQL format
echo "=== EXPORT SQLITE DATA TO MYSQL ===\n\n";

$sqliteDb = 'database.sqlite';

if (!file_exists($sqliteDb)) {
    echo "Error: database.sqlite not found!\n";
    exit(1);
}

try {
    $pdo = new PDO('sqlite:' . $sqliteDb);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to SQLite database successfully.\n\n";
    
    // Tables to export
    $tables = ['roles', 'jenis_surats', 'users'];
    
    $sqlOutput = "-- MySQL Data Export from SQLite\n";
    $sqlOutput .= "-- Generated on: " . date('Y-m-d H:i:s') . "\n\n";
    
    foreach ($tables as $table) {
        echo "Exporting table: $table\n";
        
        // Get table data
        $stmt = $pdo->query("SELECT * FROM $table");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($rows)) {
            echo "  - No data found in $table\n";
            continue;
        }
        
        echo "  - Found " . count($rows) . " records\n";
        
        $sqlOutput .= "-- Data for table: $table\n";
        
        foreach ($rows as $row) {
            $columns = array_keys($row);
            $values = array_values($row);
            
            // Escape values for MySQL
            $escapedValues = array_map(function($value) {
                if ($value === null) {
                    return 'NULL';
                }
                return "'" . addslashes($value) . "'";
            }, $values);
            
            $sqlOutput .= "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $escapedValues) . ");\n";
        }
        
        $sqlOutput .= "\n";
    }
    
    // Save to file
    $outputFile = 'mysql_data_export.sql';
    file_put_contents($outputFile, $sqlOutput);
    
    echo "\n=== EXPORT COMPLETED ===\n";
    echo "Data exported to: $outputFile\n";
    echo "File size: " . number_format(filesize($outputFile)) . " bytes\n\n";
    
    // Display summary
    echo "=== DATA SUMMARY ===\n";
    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
        $count = $stmt->fetch()['count'];
        echo "$table: $count records\n";
    }
    
    echo "\n=== JENIS SURAT DATA ===\n";
    $stmt = $pdo->query("SELECT * FROM jenis_surats ORDER BY id");
    $jenisSurats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($jenisSurats as $js) {
        echo "- {$js['kode']}: {$js['nama']}\n";
    }
    
    echo "\nReady for MySQL migration!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>