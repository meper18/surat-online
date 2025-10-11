<?php
echo "<h1>🚂 Railway Environment Diagnosis</h1>";

echo "<h2>🔍 Environment Detection:</h2>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Check</th><th>Result</th></tr>";

// Check various Railway indicators
$railwayChecks = [
    'RAILWAY_ENVIRONMENT' => isset($_ENV['RAILWAY_ENVIRONMENT']) || isset($_SERVER['RAILWAY_ENVIRONMENT']),
    'RAILWAY_PROJECT_ID' => isset($_ENV['RAILWAY_PROJECT_ID']) || isset($_SERVER['RAILWAY_PROJECT_ID']),
    'RAILWAY_SERVICE_ID' => isset($_ENV['RAILWAY_SERVICE_ID']) || isset($_SERVER['RAILWAY_SERVICE_ID']),
    'RAILWAY_DEPLOYMENT_ID' => isset($_ENV['RAILWAY_DEPLOYMENT_ID']) || isset($_SERVER['RAILWAY_DEPLOYMENT_ID']),
    'PORT env var' => isset($_ENV['PORT']) || isset($_SERVER['PORT']),
    'Running on port 80/443' => ($_SERVER['SERVER_PORT'] ?? '') == '80' || ($_SERVER['SERVER_PORT'] ?? '') == '443',
];

foreach ($railwayChecks as $check => $result) {
    $status = $result ? '✅ Yes' : '❌ No';
    echo "<tr><td><strong>$check</strong></td><td>$status</td></tr>";
}
echo "</table>";

echo "<h2>📋 All Environment Variables:</h2>";
echo "<div style='max-height: 300px; overflow-y: scroll; border: 1px solid #ccc; padding: 10px;'>";
echo "<pre>";
ksort($_ENV);
foreach ($_ENV as $key => $value) {
    // Hide sensitive values
    if (strpos(strtolower($key), 'password') !== false || 
        strpos(strtolower($key), 'secret') !== false ||
        strpos(strtolower($key), 'key') !== false) {
        $value = str_repeat('*', min(8, strlen($value)));
    }
    echo "$key = $value\n";
}
echo "</pre>";
echo "</div>";

echo "<h2>🗄️ Database Variables Check:</h2>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Variable</th><th>\$_ENV</th><th>getenv()</th><th>\$_SERVER</th></tr>";

$dbVars = [
    'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD',
    'MYSQLHOST', 'MYSQLPORT', 'MYSQLDATABASE', 'MYSQLUSER', 'MYSQLPASSWORD', 
    'DATABASE_URL', 'MYSQL_URL'
];

foreach ($dbVars as $var) {
    $envVal = $_ENV[$var] ?? 'NOT SET';
    $getenvVal = getenv($var) ?: 'NOT SET';
    $serverVal = $_SERVER[$var] ?? 'NOT SET';
    
    // Hide passwords
    if (strpos(strtolower($var), 'password') !== false) {
        if ($envVal !== 'NOT SET') $envVal = str_repeat('*', 8);
        if ($getenvVal !== 'NOT SET') $getenvVal = str_repeat('*', 8);
        if ($serverVal !== 'NOT SET') $serverVal = str_repeat('*', 8);
    }
    
    echo "<tr>";
    echo "<td><strong>$var</strong></td>";
    echo "<td>$envVal</td>";
    echo "<td>$getenvVal</td>";
    echo "<td>$serverVal</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h2>🔧 Server Information:</h2>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
$serverInfo = [
    'SERVER_NAME' => $_SERVER['SERVER_NAME'] ?? 'NOT SET',
    'SERVER_PORT' => $_SERVER['SERVER_PORT'] ?? 'NOT SET',
    'HTTP_HOST' => $_SERVER['HTTP_HOST'] ?? 'NOT SET',
    'REQUEST_URI' => $_SERVER['REQUEST_URI'] ?? 'NOT SET',
    'DOCUMENT_ROOT' => $_SERVER['DOCUMENT_ROOT'] ?? 'NOT SET',
    'PHP_VERSION' => phpversion(),
    'OS' => php_uname(),
];

foreach ($serverInfo as $key => $value) {
    echo "<tr><td><strong>$key</strong></td><td>$value</td></tr>";
}
echo "</table>";

echo "<h2>📁 File System Check:</h2>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
$fileChecks = [
    '.env file' => file_exists('../.env') ? '✅ Exists' : '❌ Missing',
    '.env.mysql file' => file_exists('../.env.mysql') ? '✅ Exists' : '❌ Missing',
    'config/database.php' => file_exists('../config/database.php') ? '✅ Exists' : '❌ Missing',
    'Current directory' => getcwd(),
    'App directory' => dirname(__DIR__),
];

foreach ($fileChecks as $check => $result) {
    echo "<tr><td><strong>$check</strong></td><td>$result</td></tr>";
}
echo "</table>";

// Try to read .env file if it exists
if (file_exists('../.env')) {
    echo "<h2>📄 .env File Contents:</h2>";
    echo "<pre>";
    $envContent = file_get_contents('../.env');
    // Hide sensitive values
    $envContent = preg_replace('/^(.*PASSWORD.*=)(.+)$/m', '$1********', $envContent);
    $envContent = preg_replace('/^(.*SECRET.*=)(.+)$/m', '$1********', $envContent);
    $envContent = preg_replace('/^(.*KEY.*=)(.+)$/m', '$1********', $envContent);
    echo htmlspecialchars($envContent);
    echo "</pre>";
}

echo "<hr>";
echo "<p><small>Generated at: " . date('Y-m-d H:i:s') . " UTC</small></p>";
?>