<?php
echo "<h1>🔍 MYSQL_URL Variable Check</h1>";

echo "<h2>Environment Variables:</h2>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Variable</th><th>Value</th><th>Status</th></tr>";

$vars = ['MYSQL_URL', 'DATABASE_URL', 'MYSQLHOST', 'MYSQLPORT', 'MYSQLDATABASE', 'MYSQLUSER', 'MYSQLPASSWORD'];

foreach ($vars as $var) {
    $value = getenv($var);
    $status = $value ? '✅ SET' : '❌ NOT SET';
    $displayValue = $value ? (strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value) : 'NOT SET';
    echo "<tr><td><strong>$var</strong></td><td><code>$displayValue</code></td><td>$status</td></tr>";
}

echo "</table>";

echo "<h2>Laravel Database Config:</h2>";
echo "<pre>";
try {
    $config = config('database.connections.mysql');
    echo "MySQL Config:\n";
    print_r($config);
} catch (Exception $e) {
    echo "Error reading config: " . $e->getMessage();
}
echo "</pre>";

echo "<h2>Test Database Connection:</h2>";
try {
    $pdo = new PDO(getenv('DATABASE_URL') ?: getenv('MYSQL_URL'));
    echo "<p style='color: green;'>✅ Connection successful with URL!</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Connection failed: " . $e->getMessage() . "</p>";
}

echo "<hr><p><small>Generated at: " . date('Y-m-d H:i:s T') . "</small></p>";
?>