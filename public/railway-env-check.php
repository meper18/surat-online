<?php
// Railway Environment Variables Checker
echo "<h1>🔍 Railway Environment Variables Check</h1>";

echo "<h2>📋 Current Environment Variables</h2>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Variable</th><th>Value</th><th>Status</th></tr>";

$required_vars = [
    'MYSQLHOST' => 'MySQL Host',
    'MYSQLPORT' => 'MySQL Port', 
    'MYSQLDATABASE' => 'MySQL Database',
    'MYSQLUSER' => 'MySQL User',
    'MYSQLPASSWORD' => 'MySQL Password',
    'DATABASE_URL' => 'Database URL (Alternative)'
];

foreach ($required_vars as $var => $description) {
    $value = $_ENV[$var] ?? getenv($var) ?? 'NOT SET';
    $status = ($value !== 'NOT SET') ? '✅' : '❌';
    
    // Hide password for security
    if ($var === 'MYSQLPASSWORD' && $value !== 'NOT SET') {
        $display_value = str_repeat('*', strlen($value));
    } elseif ($var === 'DATABASE_URL' && $value !== 'NOT SET') {
        $display_value = preg_replace('/:[^:@]*@/', ':****@', $value);
    } else {
        $display_value = $value;
    }
    
    echo "<tr>";
    echo "<td><strong>$var</strong><br><small>$description</small></td>";
    echo "<td><code>$display_value</code></td>";
    echo "<td>$status</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h2>🔧 Troubleshooting Steps</h2>";

$mysql_vars_set = !empty($_ENV['MYSQLHOST'] ?? getenv('MYSQLHOST')) && 
                  !empty($_ENV['MYSQLPASSWORD'] ?? getenv('MYSQLPASSWORD'));
$database_url_set = !empty($_ENV['DATABASE_URL'] ?? getenv('DATABASE_URL'));

if (!$mysql_vars_set && !$database_url_set) {
    echo "<div style='background: #ffebee; padding: 15px; border-left: 4px solid #f44336;'>";
    echo "<h3>❌ No MySQL Configuration Found</h3>";
    echo "<p><strong>Action Required:</strong></p>";
    echo "<ol>";
    echo "<li>Go to Railway Dashboard → Your Laravel App → Variables</li>";
    echo "<li>Add MySQL environment variables:</li>";
    echo "<ul>";
    echo "<li><code>MYSQLHOST</code> = Your MySQL host</li>";
    echo "<li><code>MYSQLPORT</code> = 3306</li>";
    echo "<li><code>MYSQLDATABASE</code> = railway</li>";
    echo "<li><code>MYSQLUSER</code> = root</li>";
    echo "<li><code>MYSQLPASSWORD</code> = Your MySQL password</li>";
    echo "</ul>";
    echo "<li>OR link your MySQL service to auto-generate DATABASE_URL</li>";
    echo "</ol>";
    echo "</div>";
} elseif ($database_url_set && !$mysql_vars_set) {
    echo "<div style='background: #e8f5e8; padding: 15px; border-left: 4px solid #4caf50;'>";
    echo "<h3>✅ DATABASE_URL Found</h3>";
    echo "<p>Your app should work with DATABASE_URL. If still having issues, try adding individual MySQL variables.</p>";
    echo "</div>";
} elseif ($mysql_vars_set) {
    echo "<div style='background: #e8f5e8; padding: 15px; border-left: 4px solid #4caf50;'>";
    echo "<h3>✅ MySQL Variables Found</h3>";
    echo "<p>Configuration looks good. If still having connection issues:</p>";
    echo "<ol>";
    echo "<li>Verify MySQL service is running</li>";
    echo "<li>Check if MySQL service is linked to your app</li>";
    echo "<li>Try redeploying your application</li>";
    echo "</ol>";
    echo "</div>";
}

echo "<h2>🔗 Quick Links</h2>";
echo "<ul>";
echo "<li><a href='/mysql-setup.php'>🔄 Retry MySQL Setup</a></li>";
echo "<li><a href='/'>🏠 Go to Application</a></li>";
echo "</ul>";

echo "<hr>";
echo "<p><small>Generated at: " . date('Y-m-d H:i:s T') . "</small></p>";
?>