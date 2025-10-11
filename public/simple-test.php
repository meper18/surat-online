<?php
// Simple Laravel Dependencies Test
echo "<h2>Laravel Dependencies Test</h2>";

// Check if vendor directory exists
echo "<h3>1. Vendor Directory Check:</h3>";
$vendorPath = __DIR__ . '/../vendor';
if (is_dir($vendorPath)) {
    echo "<p style='color: green;'>✅ Vendor directory exists</p>";
} else {
    echo "<p style='color: red;'>❌ Vendor directory NOT found</p>";
    echo "<p><strong>Solution:</strong> Run 'composer install' on Railway</p>";
    exit;
}

// Check if autoload file exists
echo "<h3>2. Composer Autoload Check:</h3>";
$autoloadPath = $vendorPath . '/autoload.php';
if (file_exists($autoloadPath)) {
    echo "<p style='color: green;'>✅ Composer autoload file exists</p>";
    
    // Try to load autoload
    try {
        require_once $autoloadPath;
        echo "<p style='color: green;'>✅ Composer autoload loaded successfully</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Failed to load autoload: " . $e->getMessage() . "</p>";
        exit;
    }
} else {
    echo "<p style='color: red;'>❌ Composer autoload file NOT found</p>";
    exit;
}

// Check if Laravel classes are available
echo "<h3>3. Laravel Classes Check:</h3>";
$laravelClasses = [
    'Illuminate\Foundation\Application',
    'Illuminate\Support\Facades\DB',
    'Illuminate\Http\Request',
    'Illuminate\Routing\Router'
];

foreach ($laravelClasses as $class) {
    if (class_exists($class)) {
        echo "<p style='color: green;'>✅ $class available</p>";
    } else {
        echo "<p style='color: red;'>❌ $class NOT available</p>";
    }
}

// Check Laravel version
echo "<h3>4. Laravel Version:</h3>";
if (class_exists('Illuminate\Foundation\Application')) {
    $app = new Illuminate\Foundation\Application(realpath(__DIR__ . '/../'));
    echo "<p><strong>Laravel Version:</strong> " . $app->version() . "</p>";
} else {
    echo "<p style='color: red;'>❌ Cannot determine Laravel version</p>";
}

// Check if .env.railway is being read
echo "<h3>5. Environment File Test:</h3>";
$envFile = __DIR__ . '/../.env.railway';
if (file_exists($envFile)) {
    echo "<p style='color: green;'>✅ .env.railway exists</p>";
    
    // Load environment manually
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $envVars = [];
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && !str_starts_with($line, '#')) {
            list($key, $value) = explode('=', $line, 2);
            $envVars[trim($key)] = trim($value, '"');
        }
    }
    
    echo "<p><strong>APP_KEY:</strong> " . (isset($envVars['APP_KEY']) ? 'SET' : 'NOT SET') . "</p>";
    echo "<p><strong>DB_CONNECTION:</strong> " . ($envVars['DB_CONNECTION'] ?? 'NOT SET') . "</p>";
    echo "<p><strong>APP_ENV:</strong> " . ($envVars['APP_ENV'] ?? 'NOT SET') . "</p>";
} else {
    echo "<p style='color: red;'>❌ .env.railway NOT found</p>";
}

// Test MySQL connection with Laravel
echo "<h3>6. Laravel MySQL Test:</h3>";
if (class_exists('Illuminate\Database\Capsule\Manager')) {
    try {
        $capsule = new Illuminate\Database\Capsule\Manager;
        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => 'metro.proxy.rlwy.net',
            'port' => 19820,
            'database' => 'railway',
            'username' => 'root',
            'password' => 'XQKSMTWvXSMKoKFoXAznbkZgIdEGZiIv',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]);
        
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        
        $result = $capsule->select('SELECT 1 as test');
        echo "<p style='color: green;'>✅ Laravel MySQL connection successful</p>";
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Laravel MySQL connection failed: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color: orange;'>⚠️ Illuminate\Database\Capsule\Manager not available</p>";
}

echo "<hr>";
echo "<p><a href='/'>← Try Main Application</a></p>";
echo "<p><a href='/fix-mysql.php'>← MySQL Fix</a></p>";
?>