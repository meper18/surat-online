<?php

echo "Copying database to Railway production path:\n";
echo "==========================================\n\n";

try {
    $sourceDb = __DIR__ . '/railway_database.sqlite';
    $targetDb = '/tmp/database.sqlite';
    
    // For Railway deployment, ensure the target directory exists
    $targetDir = dirname($targetDb);
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
        echo "✓ Created target directory: $targetDir\n";
    }
    
    // Copy the database file
    if (file_exists($sourceDb)) {
        if (copy($sourceDb, $targetDb)) {
            echo "✓ Database copied successfully from $sourceDb to $targetDb\n";
            
            // Verify the copy
            if (file_exists($targetDb)) {
                $sourceSize = filesize($sourceDb);
                $targetSize = filesize($targetDb);
                echo "✓ Source size: $sourceSize bytes\n";
                echo "✓ Target size: $targetSize bytes\n";
                
                if ($sourceSize === $targetSize) {
                    echo "✅ Database copy verification successful!\n";
                } else {
                    echo "❌ Database copy verification failed - size mismatch\n";
                    exit(1);
                }
            } else {
                echo "❌ Target database file not found after copy\n";
                exit(1);
            }
        } else {
            echo "❌ Failed to copy database file\n";
            exit(1);
        }
    } else {
        echo "❌ Source database file not found: $sourceDb\n";
        exit(1);
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}