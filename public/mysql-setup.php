<?php

/**
 * AGGRESSIVE MySQL Setup for Railway
 * This script will force MySQL connection and setup
 */

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔧 Force MySQL Setup - Railway</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
            min-height: 100vh;
        }
        .container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }
        .status {
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            font-weight: bold;
        }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .section {
            margin: 25px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .data-table th, .data-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .data-table th {
            background: #667eea;
            color: white;
        }
        .data-table tr:hover {
            background: #f5f5f5;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 5px;
            transition: background 0.3s;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background: #5a6fd8;
        }
        .btn-danger {
            background: #dc3545;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .code {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            overflow-x: auto;
            border-left: 4px solid #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 Force MySQL Setup - Railway</h1>
            <p>Memaksa koneksi MySQL bekerja</p>
            <p><strong>Timestamp:</strong> <?= date('Y-m-d H:i:s') ?></p>
        </div>

<?php

// Check if action is requested
$action = $_GET['action'] ?? '';

// Environment detection
$isRailway = isset($_ENV['RAILWAY_ENVIRONMENT']) || isset($_SERVER['RAILWAY_ENVIRONMENT']);

echo "<div class='section'>";
echo "<h2>🔍 Environment Detection</h2>";
echo "<div class='info'>Environment: " . ($isRailway ? "Railway Production" : "Local Development") . "</div>";

// Try multiple ways to get MySQL variables
$mysqlVars = [];

// Method 1: Direct environment variables
$envVars = ['MYSQLHOST', 'MYSQLPORT', 'MYSQLDATABASE', 'MYSQLUSER', 'MYSQLPASSWORD', 'MYSQL_URL'];
foreach ($envVars as $var) {
    $mysqlVars[$var] = $_ENV[$var] ?? $_SERVER[$var] ?? getenv($var) ?: 'NOT SET';
}

// Method 2: Try to parse MYSQL_URL if available
$mysqlUrl = $mysqlVars['MYSQL_URL'];
$parsedUrl = null;
if ($mysqlUrl !== 'NOT SET') {
    $parsedUrl = parse_url($mysqlUrl);
}

echo "<h3>📊 Environment Variables Check</h3>";
echo "<table class='data-table'>";
echo "<tr><th>Variable</th><th>Value</th><th>Status</th></tr>";
foreach ($mysqlVars as $var => $value) {
    $status = ($value !== 'NOT SET') ? "✅ SET" : "❌ NOT SET";
    $displayValue = ($var === 'MYSQLPASSWORD' && $value !== 'NOT SET') ? '***' : $value;
    echo "<tr><td>$var</td><td>$displayValue</td><td>$status</td></tr>";
}
echo "</table>";

// Show parsed URL if available
if ($parsedUrl) {
    echo "<h3>🔗 Parsed MYSQL_URL</h3>";
    echo "<table class='data-table'>";
    echo "<tr><th>Component</th><th>Value</th></tr>";
    echo "<tr><td>Host</td><td>" . ($parsedUrl['host'] ?? 'N/A') . "</td></tr>";
    echo "<tr><td>Port</td><td>" . ($parsedUrl['port'] ?? 'N/A') . "</td></tr>";
    echo "<tr><td>Database</td><td>" . (ltrim($parsedUrl['path'] ?? '', '/') ?: 'N/A') . "</td></tr>";
    echo "<tr><td>Username</td><td>" . ($parsedUrl['user'] ?? 'N/A') . "</td></tr>";
    echo "<tr><td>Password</td><td>" . (isset($parsedUrl['pass']) ? '***' : 'N/A') . "</td></tr>";
    echo "</table>";
}

echo "</div>";

// Manual MySQL Configuration Form
echo "<div class='section'>";
echo "<h2>🛠️ Manual MySQL Configuration</h2>";
echo "<p>Jika environment variables tidak tersedia, masukkan konfigurasi MySQL secara manual:</p>";

echo "<form method='POST'>";
echo "<table class='data-table'>";
echo "<tr><th>Parameter</th><th>Value</th></tr>";
echo "<tr><td>Host</td><td><input type='text' name='mysql_host' value='" . ($_POST['mysql_host'] ?? 'autorack.proxy.rlwy.net') . "' style='width:100%;padding:8px;'></td></tr>";
echo "<tr><td>Port</td><td><input type='text' name='mysql_port' value='" . ($_POST['mysql_port'] ?? '3306') . "' style='width:100%;padding:8px;'></td></tr>";
echo "<tr><td>Database</td><td><input type='text' name='mysql_database' value='" . ($_POST['mysql_database'] ?? 'railway') . "' style='width:100%;padding:8px;'></td></tr>";
echo "<tr><td>Username</td><td><input type='text' name='mysql_username' value='" . ($_POST['mysql_username'] ?? 'root') . "' style='width:100%;padding:8px;'></td></tr>";
echo "<tr><td>Password</td><td><input type='password' name='mysql_password' value='" . ($_POST['mysql_password'] ?? '') . "' style='width:100%;padding:8px;'></td></tr>";
echo "</table>";
echo "<button type='submit' name='test_connection' class='btn'>🔍 Test Connection</button>";
echo "<button type='submit' name='setup_database' class='btn'>🚀 Setup Database</button>";
echo "<button type='submit' name='run_migrations' class='btn'>📋 Run Migrations</button>";
echo "</form>";
echo "</div>";

// Handle form submissions
if ($_POST) {
    $config = [
        'host' => $_POST['mysql_host'] ?? '',
        'port' => $_POST['mysql_port'] ?? '3306',
        'database' => $_POST['mysql_database'] ?? '',
        'username' => $_POST['mysql_username'] ?? '',
        'password' => $_POST['mysql_password'] ?? '',
    ];
    
    if (isset($_POST['test_connection'])) {
        echo "<div class='section'>";
        echo "<h2>🔍 Connection Test</h2>";
        
        try {
            $dsn = "mysql:host={$config['host']};port={$config['port']};charset=utf8mb4";
            $pdo = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            
            echo "<div class='success'>✅ MySQL Connection Successful!</div>";
            
            // Test database existence
            $stmt = $pdo->query("SHOW DATABASES LIKE '{$config['database']}'");
            if ($stmt->rowCount() > 0) {
                echo "<div class='success'>✅ Database '{$config['database']}' exists</div>";
            } else {
                echo "<div class='warning'>⚠️ Database '{$config['database']}' does not exist</div>";
                echo "<div class='info'>Will create database during setup</div>";
            }
            
        } catch (Exception $e) {
            echo "<div class='error'>❌ Connection Failed: " . $e->getMessage() . "</div>";
        }
        echo "</div>";
    }
    
    if (isset($_POST['setup_database'])) {
        echo "<div class='section'>";
        echo "<h2>🚀 Database Setup</h2>";
        
        try {
            // Connect without database first
            $dsn = "mysql:host={$config['host']};port={$config['port']};charset=utf8mb4";
            $pdo = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            
            // Create database if not exists
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$config['database']}`");
            echo "<div class='success'>✅ Database '{$config['database']}' created/verified</div>";
            
            // Connect to the specific database
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset=utf8mb4";
            $pdo = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            
            echo "<div class='success'>✅ Connected to database '{$config['database']}'</div>";
            
            // Update Laravel .env.railway file
            $envContent = "APP_NAME=\"Surat Online\"\n";
            $envContent .= "APP_ENV=production\n";
            $envContent .= "APP_KEY=base64:YourAppKeyHere\n";
            $envContent .= "APP_DEBUG=false\n";
            $envContent .= "APP_URL=https://your-app.railway.app\n\n";
            $envContent .= "DB_CONNECTION=mysql\n";
            $envContent .= "DB_HOST={$config['host']}\n";
            $envContent .= "DB_PORT={$config['port']}\n";
            $envContent .= "DB_DATABASE={$config['database']}\n";
            $envContent .= "DB_USERNAME={$config['username']}\n";
            $envContent .= "DB_PASSWORD={$config['password']}\n";
            
            if (file_put_contents(__DIR__ . '/../.env.railway', $envContent)) {
                echo "<div class='success'>✅ Updated .env.railway file</div>";
            } else {
                echo "<div class='error'>❌ Failed to update .env.railway file</div>";
            }
            
        } catch (Exception $e) {
            echo "<div class='error'>❌ Setup Failed: " . $e->getMessage() . "</div>";
        }
        echo "</div>";
    }
    
    if (isset($_POST['run_migrations'])) {
        echo "<div class='section'>";
        echo "<h2>📋 Running Migrations</h2>";
        
        try {
            // Change to Laravel directory
            chdir(__DIR__ . '/..');
            
            // Set environment variables
            putenv("DB_CONNECTION=mysql");
            putenv("DB_HOST={$config['host']}");
            putenv("DB_PORT={$config['port']}");
            putenv("DB_DATABASE={$config['database']}");
            putenv("DB_USERNAME={$config['username']}");
            putenv("DB_PASSWORD={$config['password']}");
            
            // Run migrations
            $output = [];
            $returnCode = 0;
            exec('php artisan migrate --force 2>&1', $output, $returnCode);
            
            if ($returnCode === 0) {
                echo "<div class='success'>✅ Migrations completed successfully!</div>";
            } else {
                echo "<div class='error'>❌ Migration failed with return code: $returnCode</div>";
            }
            
            echo "<div class='code'>";
            echo "<strong>Migration Output:</strong><br>";
            echo implode("<br>", $output);
            echo "</div>";
            
        } catch (Exception $e) {
            echo "<div class='error'>❌ Migration Failed: " . $e->getMessage() . "</div>";
        }
        echo "</div>";
    }
}

// Quick Actions
echo "<div class='section'>";
echo "<h2>⚡ Quick Actions</h2>";
echo "<a href='?action=refresh' class='btn'>🔄 Refresh Page</a>";
echo "<a href='/debug-env.php' class='btn'>🔍 Debug Environment</a>";
echo "<a href='/check-mysql-url.php' class='btn'>🔗 Check MySQL URL</a>";
echo "</div>";

?>

    </div>
</body>
</html>