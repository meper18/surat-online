<?php
// Force MySQL Test - Direct Connection Test
echo "<h1>🔧 Force MySQL Connection Test</h1>";
echo "<p><strong>Timestamp:</strong> " . date('Y-m-d H:i:s') . "</p>";

// Try different connection methods
$connectionMethods = [
    'Railway Internal' => [
        'host' => 'mysql.railway.internal',
        'port' => '3306',
        'database' => 'railway',
        'username' => 'root',
        'password' => 'password'
    ],
    'Environment Variables' => [
        'host' => getenv('MYSQLHOST') ?: 'localhost',
        'port' => getenv('MYSQLPORT') ?: '3306',
        'database' => getenv('MYSQLDATABASE') ?: 'railway',
        'username' => getenv('MYSQLUSER') ?: 'root',
        'password' => getenv('MYSQLPASSWORD') ?: ''
    ]
];

// Try DATABASE_URL parsing
$databaseUrl = getenv('DATABASE_URL');
if ($databaseUrl) {
    $parsed = parse_url($databaseUrl);
    if ($parsed) {
        $connectionMethods['DATABASE_URL'] = [
            'host' => $parsed['host'] ?? 'localhost',
            'port' => $parsed['port'] ?? '3306',
            'database' => ltrim($parsed['path'] ?? '', '/') ?: 'railway',
            'username' => $parsed['user'] ?? 'root',
            'password' => $parsed['pass'] ?? ''
        ];
    }
}

foreach ($connectionMethods as $method => $config) {
    echo "<h2>🔍 Testing: $method</h2>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Parameter</th><th>Value</th></tr>";
    foreach ($config as $key => $value) {
        $displayValue = ($key === 'password' && !empty($value)) ? '***SET***' : ($value ?: 'NOT SET');
        echo "<tr><td>$key</td><td>$displayValue</td></tr>";
    }
    echo "</table>";
    
    // Test connection
    try {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
        $pdo = new PDO($dsn, $config['username'], $config['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Test query
        $stmt = $pdo->query("SELECT 1 as test");
        $result = $stmt->fetch();
        
        echo "<div style='background: #d4edda; padding: 10px; margin: 10px 0; border: 1px solid #c3e6cb;'>";
        echo "✅ <strong>Connection Successful!</strong> Test query returned: " . $result['test'];
        echo "</div>";
        
        // Check if tables exist
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "<p><strong>Tables found:</strong> " . count($tables) . "</p>";
        if (count($tables) > 0) {
            echo "<ul>";
            foreach (array_slice($tables, 0, 10) as $table) {
                echo "<li>$table</li>";
            }
            if (count($tables) > 10) {
                echo "<li>... and " . (count($tables) - 10) . " more tables</li>";
            }
            echo "</ul>";
        }
        
    } catch (Exception $e) {
        echo "<div style='background: #f8d7da; padding: 10px; margin: 10px 0; border: 1px solid #f5c6cb;'>";
        echo "❌ <strong>Connection Failed:</strong> " . $e->getMessage();
        echo "</div>";
    }
    
    echo "<hr>";
}

echo "<h2>📋 Environment Debug</h2>";
echo "<p><strong>DATABASE_URL:</strong> " . (getenv('DATABASE_URL') ? 'SET' : 'NOT SET') . "</p>";
echo "<p><strong>All MySQL env vars:</strong></p>";
echo "<ul>";
$mysqlVars = ['MYSQLHOST', 'MYSQLPORT', 'MYSQLDATABASE', 'MYSQLUSER', 'MYSQLPASSWORD'];
foreach ($mysqlVars as $var) {
    $value = getenv($var);
    echo "<li><strong>$var:</strong> " . ($value ? 'SET' : 'NOT SET') . "</li>";
}
echo "</ul>";
?>