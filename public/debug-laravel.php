<?php
// Laravel Debug Script for Railway
echo "<h2>Laravel Debug - Railway Environment</h2>";

// Check if Laravel bootstrap exists
$bootstrapPath = __DIR__ . '/../bootstrap/app.php';
echo "<h3>1. Laravel Bootstrap Check:</h3>";
if (file_exists($bootstrapPath)) {
    echo "<p style='color: green;'>✅ Laravel bootstrap found</p>";
} else {
    echo "<p style='color: red;'>❌ Laravel bootstrap NOT found at: $bootstrapPath</p>";
    exit;
}

// Check .env.railway file
echo "<h3>2. Environment File Check:</h3>";
$envFile = __DIR__ . '/../.env.railway';
if (file_exists($envFile)) {
    echo "<p style='color: green;'>✅ .env.railway exists</p>";
    $envContent = file_get_contents($envFile);
    echo "<pre style='background: #f5f5f5; padding: 10px; font-size: 12px;'>";
    echo htmlspecialchars($envContent);
    echo "</pre>";
} else {
    echo "<p style='color: red;'>❌ .env.railway NOT found</p>";
}

// Test MySQL connection directly
echo "<h3>3. Direct MySQL Connection Test:</h3>";
try {
    $pdo = new PDO(
        "mysql:host=metro.proxy.rlwy.net;port=19820;dbname=railway",
        "root",
        "XQKSMTWvXSMKoKFoXAznbkZgIdEGZiIv",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "<p style='color: green;'>✅ Direct MySQL connection successful</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Direct MySQL connection failed: " . $e->getMessage() . "</p>";
}

// Check PHP extensions
echo "<h3>4. PHP Extensions Check:</h3>";
$requiredExtensions = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json'];
foreach ($requiredExtensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<p style='color: green;'>✅ $ext loaded</p>";
    } else {
        echo "<p style='color: red;'>❌ $ext NOT loaded</p>";
    }
}

// Check file permissions
echo "<h3>5. File Permissions Check:</h3>";
$paths = [
    '../storage/logs' => 'Storage logs directory',
    '../storage/framework/cache' => 'Cache directory',
    '../storage/framework/sessions' => 'Sessions directory',
    '../storage/framework/views' => 'Views directory',
    '../bootstrap/cache' => 'Bootstrap cache directory'
];

foreach ($paths as $path => $description) {
    $fullPath = __DIR__ . '/' . $path;
    if (is_dir($fullPath)) {
        if (is_writable($fullPath)) {
            echo "<p style='color: green;'>✅ $description is writable</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ $description exists but not writable</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ $description does not exist: $fullPath</p>";
    }
}

// Try to initialize Laravel
echo "<h3>6. Laravel Initialization Test:</h3>";
try {
    // Set environment
    putenv('APP_ENV=production');
    
    // Try to require Laravel bootstrap
    require_once $bootstrapPath;
    
    echo "<p style='color: green;'>✅ Laravel bootstrap loaded successfully</p>";
    
    // Try to create Laravel app instance
    $app = require_once $bootstrapPath;
    echo "<p style='color: green;'>✅ Laravel app instance created</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Laravel initialization failed: " . $e->getMessage() . "</p>";
    echo "<p><strong>Stack trace:</strong></p>";
    echo "<pre style='background: #ffe6e6; padding: 10px; font-size: 11px;'>";
    echo htmlspecialchars($e->getTraceAsString());
    echo "</pre>";
}

// Check Laravel logs
echo "<h3>7. Laravel Logs Check:</h3>";
$logPath = __DIR__ . '/../storage/logs/laravel.log';
if (file_exists($logPath)) {
    echo "<p style='color: green;'>✅ Laravel log file exists</p>";
    $logContent = file_get_contents($logPath);
    $lines = explode("\n", $logContent);
    $recentLines = array_slice($lines, -20); // Last 20 lines
    
    echo "<p><strong>Recent log entries:</strong></p>";
    echo "<pre style='background: #f0f0f0; padding: 10px; font-size: 11px; max-height: 300px; overflow-y: auto;'>";
    echo htmlspecialchars(implode("\n", $recentLines));
    echo "</pre>";
} else {
    echo "<p style='color: orange;'>⚠️ No Laravel log file found</p>";
}

// Environment variables check
echo "<h3>8. Environment Variables:</h3>";
$envVars = ['APP_ENV', 'APP_KEY', 'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME'];
foreach ($envVars as $var) {
    $value = getenv($var) ?: $_ENV[$var] ?? 'NOT SET';
    if ($var === 'DB_PASSWORD' || $var === 'APP_KEY') {
        $value = $value !== 'NOT SET' ? '[HIDDEN]' : 'NOT SET';
    }
    echo "<p><strong>$var:</strong> $value</p>";
}

echo "<hr>";
echo "<p><a href='/'>← Try Main Application</a></p>";
echo "<p><a href='/fix-mysql.php'>← Back to MySQL Fix</a></p>";
?>