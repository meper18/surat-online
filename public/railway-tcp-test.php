<?php
echo "<h1>🔧 Railway TCP Proxy MySQL Test</h1>";
echo "<p><strong>Timestamp:</strong> " . date('Y-m-d H:i:s') . "</p>";

// Test using Railway TCP Proxy (from railway variables output)
$host = 'metro.proxy.rlwy.net';
$port = 19820; // RAILWAY_TCP_PROXY_PORT
$database = 'railway';
$username = 'root';
$password = 'XQKSMTWvXSMKoKFoXAznbkZgIdEGZiIv';

echo "<h2>🔍 Testing TCP Proxy Connection</h2>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Parameter</th><th>Value</th></tr>";
echo "<tr><td>host</td><td>$host</td></tr>";
echo "<tr><td>port</td><td>$port</td></tr>";
echo "<tr><td>database</td><td>$database</td></tr>";
echo "<tr><td>username</td><td>$username</td></tr>";
echo "<tr><td>password</td><td>***SET***</td></tr>";
echo "</table>";

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_TIMEOUT => 15
    ]);
    
    echo "<div style='background: #d4edda; padding: 10px; margin: 10px 0; border: 1px solid #c3e6cb;'>";
    echo "✅ <strong>Connection Successful!</strong>";
    echo "</div>";
    
    // Test query
    $stmt = $pdo->query("SELECT VERSION() as version, DATABASE() as current_db");
    $result = $stmt->fetch();
    
    echo "<h3>📊 Database Info:</h3>";
    echo "<ul>";
    echo "<li><strong>MySQL Version:</strong> " . $result['version'] . "</li>";
    echo "<li><strong>Current Database:</strong> " . $result['current_db'] . "</li>";
    echo "</ul>";
    
    // Check tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h3>📋 Tables in Database:</h3>";
    if (empty($tables)) {
        echo "<p style='color: orange;'>⚠️ No tables found. Database needs to be set up.</p>";
        echo "<div style='background: #fff3cd; padding: 10px; margin: 10px 0; border: 1px solid #ffeaa7;'>";
        echo "💡 <strong>Next Step:</strong> Run database migrations and seeders to set up the tables.";
        echo "</div>";
    } else {
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";
    }
    
} catch (PDOException $e) {
    echo "<div style='background: #f8d7da; padding: 10px; margin: 10px 0; border: 1px solid #f5c6cb;'>";
    echo "❌ <strong>Connection Failed:</strong> " . $e->getMessage();
    echo "</div>";
}

echo "<hr>";
echo "<h2>🔧 Environment Debug</h2>";
echo "<ul>";
echo "<li><strong>PHP Version:</strong> " . PHP_VERSION . "</li>";
echo "<li><strong>PDO MySQL Available:</strong> " . (extension_loaded('pdo_mysql') ? 'Yes' : 'No') . "</li>";
echo "<li><strong>Server Name:</strong> " . ($_SERVER['SERVER_NAME'] ?? 'Not set') . "</li>";
echo "</ul>";

// Test alternative connection methods
echo "<hr>";
echo "<h2>🔄 Alternative Connection Tests</h2>";

// Test 1: localhost
echo "<h3>Test 1: localhost</h3>";
try {
    $dsn = "mysql:host=localhost;port=3306;dbname=railway;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [PDO::ATTR_TIMEOUT => 5]);
    echo "✅ localhost connection works<br>";
} catch (PDOException $e) {
    echo "❌ localhost failed: " . $e->getMessage() . "<br>";
}

// Test 2: 127.0.0.1
echo "<h3>Test 2: 127.0.0.1</h3>";
try {
    $dsn = "mysql:host=127.0.0.1;port=3306;dbname=railway;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [PDO::ATTR_TIMEOUT => 5]);
    echo "✅ 127.0.0.1 connection works<br>";
} catch (PDOException $e) {
    echo "❌ 127.0.0.1 failed: " . $e->getMessage() . "<br>";
}
?>