<?php
// Composer Install Script for Railway
echo "<h2>Composer Dependencies Installation</h2>";

// Check current directory
echo "<h3>Current Directory:</h3>";
echo "<p>" . getcwd() . "</p>";

// Check if we can change to project root
$projectRoot = dirname(__DIR__);
echo "<h3>Project Root:</h3>";
echo "<p>$projectRoot</p>";

// Check if composer.json exists
if (file_exists($projectRoot . '/composer.json')) {
    echo "<p style='color: green;'>✅ composer.json found</p>";
} else {
    echo "<p style='color: red;'>❌ composer.json NOT found</p>";
    exit;
}

// Check if composer is available
echo "<h3>Composer Check:</h3>";
$composerCheck = shell_exec('which composer 2>/dev/null || echo "not found"');
if (trim($composerCheck) !== 'not found') {
    echo "<p style='color: green;'>✅ Composer is available: " . trim($composerCheck) . "</p>";
} else {
    echo "<p style='color: orange;'>⚠️ Composer command not found, trying alternative methods</p>";
}

// Try to run composer install
echo "<h3>Installing Dependencies:</h3>";
echo "<p>Running composer install...</p>";

// Change to project directory
chdir($projectRoot);

// Execute composer install
$output = [];
$returnCode = 0;

// Try different composer commands
$commands = [
    'composer install --no-dev --optimize-autoloader 2>&1',
    'php composer.phar install --no-dev --optimize-autoloader 2>&1',
    '/usr/local/bin/composer install --no-dev --optimize-autoloader 2>&1'
];

foreach ($commands as $cmd) {
    echo "<p>Trying: <code>$cmd</code></p>";
    exec($cmd, $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "<p style='color: green;'>✅ Composer install successful!</p>";
        break;
    } else {
        echo "<p style='color: red;'>❌ Command failed with code: $returnCode</p>";
        echo "<pre style='background: #ffe6e6; padding: 10px;'>";
        echo htmlspecialchars(implode("\n", $output));
        echo "</pre>";
        $output = []; // Reset for next command
    }
}

// Check if vendor directory was created
if (is_dir($projectRoot . '/vendor')) {
    echo "<p style='color: green;'>✅ Vendor directory created</p>";
    
    // Check if autoload file exists
    if (file_exists($projectRoot . '/vendor/autoload.php')) {
        echo "<p style='color: green;'>✅ Autoload file created</p>";
        
        // Test autoload
        try {
            require_once $projectRoot . '/vendor/autoload.php';
            echo "<p style='color: green;'>✅ Autoload works</p>";
            
            // Test Laravel classes
            if (class_exists('Illuminate\Foundation\Application')) {
                echo "<p style='color: green;'>✅ Laravel classes available</p>";
            } else {
                echo "<p style='color: red;'>❌ Laravel classes still not available</p>";
            }
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Autoload error: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Autoload file not created</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Vendor directory not created</p>";
}

// Show final status
echo "<h3>Final Status:</h3>";
if (is_dir($projectRoot . '/vendor') && file_exists($projectRoot . '/vendor/autoload.php')) {
    echo "<p style='color: green; font-weight: bold;'>✅ Dependencies installed successfully!</p>";
    echo "<p><a href='/'>Try Main Application Now</a></p>";
} else {
    echo "<p style='color: red; font-weight: bold;'>❌ Dependencies installation failed</p>";
    echo "<p>Manual intervention may be required on Railway deployment.</p>";
}

echo "<hr>";
echo "<p><a href='/simple-test.php'>← Test Dependencies</a></p>";
echo "<p><a href='/fix-mysql.php'>← MySQL Fix</a></p>";
?>