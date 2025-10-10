<?php
echo "<h1>🔍 Debug Environment Variables</h1>";
echo "<h2>All Environment Variables:</h2>";
echo "<pre>";
print_r($_ENV);
echo "</pre>";

echo "<h2>Specific Database Variables:</h2>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Variable</th><th>$_ENV</th><th>getenv()</th><th>$_SERVER</th></tr>";

$vars = ['DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD', 
         'MYSQLHOST', 'MYSQLPORT', 'MYSQLDATABASE', 'MYSQLUSER', 'MYSQLPASSWORD', 'DATABASE_URL'];

foreach ($vars as $var) {
    echo "<tr>";
    echo "<td><strong>$var</strong></td>";
    echo "<td>" . (isset($_ENV[$var]) ? $_ENV[$var] : 'NOT SET') . "</td>";
    echo "<td>" . (getenv($var) ?: 'NOT SET') . "</td>";
    echo "<td>" . (isset($_SERVER[$var]) ? $_SERVER[$var] : 'NOT SET') . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h2>Laravel Config:</h2>";
echo "<pre>";
if (file_exists('../config/database.php')) {
    echo "Database config exists\n";
    echo "Default connection: " . (getenv('DB_CONNECTION') ?: 'sqlite') . "\n";
} else {
    echo "Database config not found\n";
}
echo "</pre>";

echo "<hr>";
echo "<p><small>Generated at: " . date('Y-m-d H:i:s') . " UTC</small></p>";
?>