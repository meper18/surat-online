<?php
// Quick MySQL connection fix for Railway deployment
// This file will update the .env.railway file with proper MySQL credentials

echo "<h2>MySQL Connection Fix for Railway</h2>";

$envFile = __DIR__ . '/../.env.railway';

if (!file_exists($envFile)) {
    echo "<p style='color: red;'>Error: .env.railway file not found!</p>";
    exit;
}

// Read current content
$content = file_get_contents($envFile);

// Fix the MySQL configuration
$newContent = preg_replace(
    '/DATABASE_URL=\$\{\{ MySQL\.MYSQL_URL \}\}/',
    '#DATABASE_URL=${{ MySQL.MYSQL_URL }}',
    $content
);

$newContent = preg_replace(
    '/#DB_HOST=metro\.proxy\.rlwy\.net/',
    'DB_HOST=metro.proxy.rlwy.net',
    $newContent
);

$newContent = preg_replace(
    '/#DB_PORT=19820/',
    'DB_PORT=19820',
    $newContent
);

$newContent = preg_replace(
    '/#DB_DATABASE=railway/',
    'DB_DATABASE=railway',
    $newContent
);

$newContent = preg_replace(
    '/#DB_USERNAME=root/',
    'DB_USERNAME=root',
    $newContent
);

$newContent = preg_replace(
    '/#DB_PASSWORD=XQKSMTWvXSMKoKFoXAznbkZgIdEGZiIv/',
    'DB_PASSWORD=XQKSMTWvXSMKoKFoXAznbkZgIdEGZiIv',
    $newContent
);

// Write the fixed content
if (file_put_contents($envFile, $newContent)) {
    echo "<p style='color: green;'>✅ Successfully updated .env.railway file!</p>";
    echo "<p>MySQL connection should now work properly.</p>";
    
    // Test the connection
    echo "<h3>Testing MySQL Connection:</h3>";
    
    try {
        $pdo = new PDO(
            "mysql:host=metro.proxy.rlwy.net;port=19820;dbname=railway",
            "root",
            "XQKSMTWvXSMKoKFoXAznbkZgIdEGZiIv",
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        echo "<p style='color: green;'>✅ MySQL connection successful!</p>";
        
        // Check if tables exist
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "<p><strong>Database tables found:</strong> " . count($tables) . "</p>";
        if (count($tables) > 0) {
            echo "<ul>";
            foreach ($tables as $table) {
                echo "<li>$table</li>";
            }
            echo "</ul>";
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ MySQL connection failed: " . $e->getMessage() . "</p>";
    }
    
} else {
    echo "<p style='color: red;'>❌ Failed to update .env.railway file!</p>";
}

echo "<hr>";
echo "<p><a href='/'>← Back to Main Application</a></p>";
echo "<p><a href='/mysql-setup.php'>← Back to MySQL Setup</a></p>";
?>