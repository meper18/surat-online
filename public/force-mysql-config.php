<?php
echo "<h1>🔧 Force MySQL Configuration</h1>";

// Check if we're on Railway
$isRailway = isset($_ENV['RAILWAY_ENVIRONMENT']) || isset($_SERVER['RAILWAY_ENVIRONMENT']);

if ($isRailway) {
    echo "<p>✅ Running on Railway environment</p>";
    
    // Try to get Railway MySQL variables
    $railwayVars = [
        'MYSQLHOST' => $_ENV['MYSQLHOST'] ?? $_SERVER['MYSQLHOST'] ?? null,
        'MYSQLPORT' => $_ENV['MYSQLPORT'] ?? $_SERVER['MYSQLPORT'] ?? null,
        'MYSQLDATABASE' => $_ENV['MYSQLDATABASE'] ?? $_SERVER['MYSQLDATABASE'] ?? null,
        'MYSQLUSER' => $_ENV['MYSQLUSER'] ?? $_SERVER['MYSQLUSER'] ?? null,
        'MYSQLPASSWORD' => $_ENV['MYSQLPASSWORD'] ?? $_SERVER['MYSQLPASSWORD'] ?? null,
        'DATABASE_URL' => $_ENV['DATABASE_URL'] ?? $_SERVER['DATABASE_URL'] ?? null,
    ];
    
    echo "<h2>Railway MySQL Variables:</h2>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Variable</th><th>Value</th><th>Status</th></tr>";
    
    foreach ($railwayVars as $key => $value) {
        $status = $value ? '✅ Found' : '❌ Missing';
        $displayValue = $value ? (strlen($value) > 20 ? substr($value, 0, 20) . '...' : $value) : 'NOT SET';
        echo "<tr><td><strong>$key</strong></td><td><code>$displayValue</code></td><td>$status</td></tr>";
    }
    echo "</table>";
    
    // If we have DATABASE_URL, parse it
    if ($railwayVars['DATABASE_URL']) {
        echo "<h2>🔗 Parsing DATABASE_URL:</h2>";
        $url = parse_url($railwayVars['DATABASE_URL']);
        echo "<pre>";
        print_r($url);
        echo "</pre>";
        
        // Set environment variables from DATABASE_URL
        if ($url) {
            putenv("DB_CONNECTION=mysql");
            putenv("DB_HOST=" . ($url['host'] ?? ''));
            putenv("DB_PORT=" . ($url['port'] ?? '3306'));
            putenv("DB_DATABASE=" . ltrim($url['path'] ?? '', '/'));
            putenv("DB_USERNAME=" . ($url['user'] ?? ''));
            putenv("DB_PASSWORD=" . ($url['pass'] ?? ''));
            
            echo "<p>✅ Environment variables set from DATABASE_URL</p>";
        }
    } else {
        // Set from individual variables
        if ($railwayVars['MYSQLHOST']) {
            putenv("DB_CONNECTION=mysql");
            putenv("DB_HOST=" . $railwayVars['MYSQLHOST']);
            putenv("DB_PORT=" . ($railwayVars['MYSQLPORT'] ?? '3306'));
            putenv("DB_DATABASE=" . ($railwayVars['MYSQLDATABASE'] ?? 'railway'));
            putenv("DB_USERNAME=" . ($railwayVars['MYSQLUSER'] ?? 'root'));
            putenv("DB_PASSWORD=" . ($railwayVars['MYSQLPASSWORD'] ?? ''));
            
            echo "<p>✅ Environment variables set from individual MySQL variables</p>";
        }
    }
    
    // Test MySQL connection
    echo "<h2>🔌 Testing MySQL Connection:</h2>";
    try {
        $host = getenv('DB_HOST');
        $port = getenv('DB_PORT');
        $database = getenv('DB_DATABASE');
        $username = getenv('DB_USERNAME');
        $password = getenv('DB_PASSWORD');
        
        if ($host && $database && $username) {
            $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            
            echo "<p>✅ MySQL connection successful!</p>";
            
            // Test query
            $stmt = $pdo->query("SELECT DATABASE() as current_db, NOW() as current_time");
            $result = $stmt->fetch();
            echo "<p>Current database: <strong>" . $result['current_db'] . "</strong></p>";
            echo "<p>Current time: <strong>" . $result['current_time'] . "</strong></p>";
            
        } else {
            echo "<p>❌ Missing required MySQL configuration</p>";
        }
    } catch (Exception $e) {
        echo "<p>❌ MySQL connection failed: " . $e->getMessage() . "</p>";
    }
    
} else {
    echo "<p>❌ Not running on Railway environment</p>";
}

echo "<hr>";
echo "<p><small>Generated at: " . date('Y-m-d H:i:s') . " UTC</small></p>";
?>