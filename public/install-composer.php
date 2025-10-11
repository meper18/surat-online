<?php
echo "<h1>Enhanced Composer Installation for Railway</h1>";

// Set environment variables that Composer needs
putenv('HOME=/tmp');
putenv('COMPOSER_HOME=/tmp/.composer');

echo "<h2>Environment Setup:</h2>";
echo "Setting HOME=/tmp<br>";
echo "Setting COMPOSER_HOME=/tmp/.composer<br><br>";

// Check if composer.json exists
if (!file_exists('/app/composer.json')) {
    echo "<p style='color: red;'>❌ composer.json not found in /app/</p>";
    exit;
}

echo "<p style='color: green;'>✅ composer.json found</p>";

// Create necessary directories
$dirs = ['/tmp', '/tmp/.composer', '/app/vendor'];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "Created directory: $dir<br>";
    }
}

echo "<h2>Downloading Composer:</h2>";

// Download Composer installer
$composerSetup = '/tmp/composer-setup.php';
$composerPhar = '/tmp/composer.phar';

// Download Composer installer
echo "Downloading Composer installer...<br>";
$installer = file_get_contents('https://getcomposer.org/installer');
if ($installer === false) {
    echo "<p style='color: red;'>❌ Failed to download Composer installer</p>";
    exit;
}

file_put_contents($composerSetup, $installer);
echo "<p style='color: green;'>✅ Composer installer downloaded</p>";

// Run Composer installer
echo "<h2>Installing Composer:</h2>";
chdir('/tmp');

$output = [];
$return_var = 0;

// Install Composer
exec("php composer-setup.php 2>&1", $output, $return_var);

echo "<pre>";
foreach ($output as $line) {
    echo htmlspecialchars($line) . "\n";
}
echo "</pre>";

if ($return_var !== 0) {
    echo "<p style='color: red;'>❌ Composer installation failed</p>";
    exit;
}

if (!file_exists($composerPhar)) {
    echo "<p style='color: red;'>❌ composer.phar not created</p>";
    exit;
}

echo "<p style='color: green;'>✅ Composer installed successfully</p>";

// Change to app directory
chdir('/app');

echo "<h2>Installing Dependencies:</h2>";

// Run composer install with various options
$commands = [
    "php /tmp/composer.phar install --no-dev --optimize-autoloader --no-interaction",
    "php /tmp/composer.phar install --optimize-autoloader --no-interaction",
    "php /tmp/composer.phar install --no-interaction"
];

$success = false;
foreach ($commands as $cmd) {
    echo "<strong>Trying:</strong> $cmd<br>";
    
    $output = [];
    $return_var = 0;
    
    exec($cmd . " 2>&1", $output, $return_var);
    
    echo "<pre>";
    foreach ($output as $line) {
        echo htmlspecialchars($line) . "\n";
    }
    echo "</pre>";
    
    if ($return_var === 0) {
        echo "<p style='color: green;'>✅ Dependencies installed successfully!</p>";
        $success = true;
        break;
    } else {
        echo "<p style='color: orange;'>⚠️ Command failed with code: $return_var</p><br>";
    }
}

if (!$success) {
    echo "<p style='color: red;'>❌ All composer install attempts failed</p>";
    
    // Try to create vendor directory manually and download core files
    echo "<h2>Attempting Manual Dependency Setup:</h2>";
    
    if (!is_dir('/app/vendor')) {
        mkdir('/app/vendor', 0755, true);
    }
    
    // Try to create a basic autoload.php
    $autoloadContent = '<?php
// Basic autoload for Laravel
spl_autoload_register(function ($class) {
    $file = __DIR__ . "/" . str_replace("\\\\", "/", $class) . ".php";
    if (file_exists($file)) {
        require $file;
    }
});

// Include Laravel framework if available
$frameworkAutoload = __DIR__ . "/laravel/framework/src/Illuminate/Foundation/helpers.php";
if (file_exists($frameworkAutoload)) {
    require $frameworkAutoload;
}
';
    
    file_put_contents('/app/vendor/autoload.php', $autoloadContent);
    echo "Created basic autoload.php<br>";
}

echo "<h2>Verification:</h2>";

// Check vendor directory
if (is_dir('/app/vendor')) {
    echo "<p style='color: green;'>✅ vendor/ directory exists</p>";
    
    $vendorFiles = scandir('/app/vendor');
    echo "Vendor contents: " . implode(', ', array_slice($vendorFiles, 2)) . "<br>";
} else {
    echo "<p style='color: red;'>❌ vendor/ directory missing</p>";
}

// Check autoload.php
if (file_exists('/app/vendor/autoload.php')) {
    echo "<p style='color: green;'>✅ autoload.php exists</p>";
} else {
    echo "<p style='color: red;'>❌ autoload.php missing</p>";
}

// Test Laravel class loading
echo "<h2>Testing Laravel Classes:</h2>";

if (file_exists('/app/vendor/autoload.php')) {
    require_once '/app/vendor/autoload.php';
    
    $classes = [
        'Illuminate\\Foundation\\Application',
        'Illuminate\\Support\\Facades\\DB',
        'Illuminate\\Http\\Request'
    ];
    
    foreach ($classes as $class) {
        if (class_exists($class)) {
            echo "<p style='color: green;'>✅ $class available</p>";
        } else {
            echo "<p style='color: red;'>❌ $class not found</p>";
        }
    }
}

echo "<h2>Next Steps:</h2>";
echo "<p>1. If dependencies were installed successfully, test the main Laravel application</p>";
echo "<p>2. If installation failed, Railway may need manual dependency management</p>";
echo "<p>3. Check the main application at: <a href='/'>Main Application</a></p>";

echo "<br><p><a href='/'>← Back to Main Application</a></p>";
?>