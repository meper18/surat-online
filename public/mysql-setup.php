<?php

/**
 * Web-Accessible MySQL Setup for Railway
 * URL: https://your-app.railway.app/mysql-setup.php
 */

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MySQL Setup - Railway Deployment</title>
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
        }
        .btn:hover {
            background: #5a6fd8;
        }
        .code {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            overflow-x: auto;
            border-left: 4px solid #667eea;
        }
        .progress {
            width: 100%;
            height: 20px;
            background: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
        }
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transition: width 0.3s ease;
        }
    </style>
    <script>
        function refreshPage() {
            location.reload();
        }
        
        // Auto-refresh every 30 seconds
        setTimeout(refreshPage, 30000);
    </script>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 MySQL Setup - Railway Deployment</h1>
            <p>Migrasi Database dari SQLite ke MySQL</p>
            <p><strong>Timestamp:</strong> <?= date('Y-m-d H:i:s') ?></p>
        </div>

<?php

// Environment detection
$isRailway = isset($_ENV['RAILWAY_ENVIRONMENT']) || isset($_SERVER['RAILWAY_ENVIRONMENT']);

echo "<div class='section'>";
echo "<h2>🔍 Environment Detection</h2>";
echo "<div class='info'>Environment: " . ($isRailway ? "Railway Production" : "Local Development") . "</div>";

// MySQL Configuration
$mysqlConfig = [
    'host' => $_ENV['MYSQLHOST'] ?? $_SERVER['MYSQLHOST'] ?? 'localhost',
    'port' => $_ENV['MYSQLPORT'] ?? $_SERVER['MYSQLPORT'] ?? '3306',
    'database' => $_ENV['MYSQLDATABASE'] ?? $_SERVER['MYSQLDATABASE'] ?? 'railway',
    'username' => $_ENV['MYSQLUSER'] ?? $_SERVER['MYSQLUSER'] ?? 'root',
    'password' => $_ENV['MYSQLPASSWORD'] ?? $_SERVER['MYSQLPASSWORD'] ?? '',
];

echo "<h3>📊 MySQL Configuration</h3>";
echo "<table class='data-table'>";
echo "<tr><th>Parameter</th><th>Value</th><th>Status</th></tr>";
echo "<tr><td>Host</td><td>{$mysqlConfig['host']}</td><td>" . (!empty($mysqlConfig['host']) ? "✅" : "❌") . "</td></tr>";
echo "<tr><td>Port</td><td>{$mysqlConfig['port']}</td><td>" . (!empty($mysqlConfig['port']) ? "✅" : "❌") . "</td></tr>";
echo "<tr><td>Database</td><td>{$mysqlConfig['database']}</td><td>" . (!empty($mysqlConfig['database']) ? "✅" : "❌") . "</td></tr>";
echo "<tr><td>Username</td><td>{$mysqlConfig['username']}</td><td>" . (!empty($mysqlConfig['username']) ? "✅" : "❌") . "</td></tr>";
echo "<tr><td>Password</td><td>" . (empty($mysqlConfig['password']) ? "Not set" : "***") . "</td><td>" . (!empty($mysqlConfig['password']) ? "✅" : "❌") . "</td></tr>";
echo "</table>";
echo "</div>";

$setupProgress = 0;
$totalSteps = 6;

try {
    // Step 1: Connect to MySQL
    echo "<div class='section'>";
    echo "<h2>🔌 Step 1: MySQL Connection</h2>";
    
    $dsn = "mysql:host={$mysqlConfig['host']};port={$mysqlConfig['port']};dbname={$mysqlConfig['database']};charset=utf8mb4";
    $pdo = new PDO($dsn, $mysqlConfig['username'], $mysqlConfig['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]);
    
    echo "<div class='success'>✅ MySQL Connection: SUCCESS</div>";
    $setupProgress++;
    echo "</div>";
    
    // Step 2: Check existing tables
    echo "<div class='section'>";
    echo "<h2>📋 Step 2: Database Analysis</h2>";
    
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<div class='info'>Found " . count($tables) . " existing tables</div>";
    
    if (!empty($tables)) {
        echo "<table class='data-table'>";
        echo "<tr><th>Table Name</th><th>Record Count</th></tr>";
        foreach ($tables as $table) {
            $countStmt = $pdo->query("SELECT COUNT(*) FROM `$table`");
            $count = $countStmt->fetchColumn();
            echo "<tr><td>$table</td><td>$count</td></tr>";
        }
        echo "</table>";
    }
    $setupProgress++;
    echo "</div>";
    
    // Step 3: Check critical tables
    echo "<div class='section'>";
    echo "<h2>🎯 Step 3: Critical Tables Check</h2>";
    
    $criticalTables = ['migrations', 'jenis_surats', 'roles', 'users'];
    $missingTables = [];
    
    echo "<table class='data-table'>";
    echo "<tr><th>Table</th><th>Status</th><th>Records</th></tr>";
    
    foreach ($criticalTables as $table) {
        $exists = in_array($table, $tables);
        if ($exists) {
            $countStmt = $pdo->query("SELECT COUNT(*) FROM `$table`");
            $count = $countStmt->fetchColumn();
            echo "<tr><td>$table</td><td><span style='color: green'>✅ EXISTS</span></td><td>$count</td></tr>";
        } else {
            echo "<tr><td>$table</td><td><span style='color: red'>❌ MISSING</span></td><td>-</td></tr>";
            $missingTables[] = $table;
        }
    }
    echo "</table>";
    
    if (!empty($missingTables)) {
        echo "<div class='warning'>⚠ Missing tables: " . implode(', ', $missingTables) . "</div>";
        echo "<div class='info'>These tables will be created by Laravel migrations</div>";
    }
    $setupProgress++;
    echo "</div>";
    
    // Step 4: Data Import Check
    echo "<div class='section'>";
    echo "<h2>📥 Step 4: Data Import Status</h2>";
    
    $exportFile = '../complete_mysql_data_export.sql';
    if (file_exists($exportFile)) {
        echo "<div class='success'>✅ Export file found: complete_mysql_data_export.sql</div>";
        
        // Check if jenis_surats has data
        if (in_array('jenis_surats', $tables)) {
            $stmt = $pdo->query("SELECT COUNT(*) FROM jenis_surats");
            $jenisCount = $stmt->fetchColumn();
            
            if ($jenisCount == 0) {
                echo "<div class='warning'>⚠ Jenis Surats table is empty - importing data...</div>";
                
                $sql = file_get_contents($exportFile);
                $statements = array_filter(array_map('trim', explode(';', $sql)));
                
                $imported = 0;
                foreach ($statements as $statement) {
                    if (!empty($statement) && !preg_match('/^--/', $statement)) {
                        try {
                            $pdo->exec($statement);
                            $imported++;
                        } catch (PDOException $e) {
                            if (strpos($e->getMessage(), 'Duplicate entry') === false) {
                                echo "<div class='warning'>Warning: " . $e->getMessage() . "</div>";
                            }
                        }
                    }
                }
                
                echo "<div class='success'>✅ Imported $imported SQL statements</div>";
            } else {
                echo "<div class='info'>ℹ Data already exists ($jenisCount records in jenis_surats)</div>";
            }
        }
    } else {
        echo "<div class='error'>❌ Export file not found</div>";
        echo "<div class='info'>Manual data seeding required</div>";
    }
    $setupProgress++;
    echo "</div>";
    
    // Step 5: Jenis Surat Verification
    echo "<div class='section'>";
    echo "<h2>📝 Step 5: Jenis Surat Data Verification</h2>";
    
    if (in_array('jenis_surats', $tables)) {
        $stmt = $pdo->query("SELECT * FROM jenis_surats ORDER BY id");
        $jenisSurats = $stmt->fetchAll();
        
        if (!empty($jenisSurats)) {
            echo "<div class='success'>✅ Found " . count($jenisSurats) . " Jenis Surat entries</div>";
            
            echo "<table class='data-table'>";
            echo "<tr><th>ID</th><th>Nama</th><th>Deskripsi</th><th>Template</th></tr>";
            foreach ($jenisSurats as $jenis) {
                echo "<tr>";
                echo "<td>{$jenis['id']}</td>";
                echo "<td>{$jenis['nama']}</td>";
                echo "<td>" . substr($jenis['deskripsi'] ?? '', 0, 50) . "...</td>";
                echo "<td>{$jenis['template_file']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<div class='error'>❌ No Jenis Surat data found</div>";
        }
    } else {
        echo "<div class='warning'>⚠ Jenis Surats table not found</div>";
    }
    $setupProgress++;
    echo "</div>";
    
    // Step 6: Final Status
    echo "<div class='section'>";
    echo "<h2>🎉 Step 6: Setup Complete</h2>";
    
    $progressPercent = ($setupProgress / $totalSteps) * 100;
    echo "<div class='progress'>";
    echo "<div class='progress-bar' style='width: {$progressPercent}%'></div>";
    echo "</div>";
    echo "<p>Progress: {$setupProgress}/{$totalSteps} steps completed ({$progressPercent}%)</p>";
    
    if ($setupProgress == $totalSteps) {
        echo "<div class='success'>";
        echo "<h3>🎊 MySQL Migration Successful!</h3>";
        echo "<p>✅ Database connection established</p>";
        echo "<p>✅ Tables verified and created</p>";
        echo "<p>✅ Data imported successfully</p>";
        echo "<p>✅ Jenis Surat dropdown ready</p>";
        echo "</div>";
        
        echo "<div class='info'>";
        echo "<h3>🚀 Next Steps:</h3>";
        echo "<ol>";
        echo "<li>Update your .env file with MySQL credentials</li>";
        echo "<li>Run Laravel migrations: <code>php artisan migrate --force</code></li>";
        echo "<li>Test the application dropdown functionality</li>";
        echo "<li>Verify all features work correctly</li>";
        echo "</ol>";
        echo "</div>";
    }
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<div class='section'>";
    echo "<h2>❌ MySQL Connection Failed</h2>";
    echo "<div class='error'>Error: " . $e->getMessage() . "</div>";
    
    echo "<div class='info'>";
    echo "<h3>🔧 Troubleshooting Steps:</h3>";
    echo "<ol>";
    echo "<li>Verify MySQL service is running on Railway</li>";
    echo "<li>Check all environment variables are set correctly</li>";
    echo "<li>Ensure MySQL service is linked to your application</li>";
    echo "<li>Check Railway logs for MySQL service status</li>";
    echo "</ol>";
    echo "</div>";
    
    echo "<div class='code'>";
    echo "<h4>Required Environment Variables:</h4>";
    echo "MYSQLHOST=your-mysql-host<br>";
    echo "MYSQLPORT=3306<br>";
    echo "MYSQLDATABASE=railway<br>";
    echo "MYSQLUSER=root<br>";
    echo "MYSQLPASSWORD=your-password";
    echo "</div>";
    echo "</div>";
}

?>

        <div class="section">
            <h2>🔄 Actions</h2>
            <a href="javascript:refreshPage()" class="btn">🔄 Refresh Status</a>
            <a href="../" class="btn">🏠 Back to Application</a>
            <a href="check-database.php" class="btn">📊 Database Checker</a>
        </div>
        
        <div class="section">
            <h2>📋 Migration Summary</h2>
            <div class="info">
                <p><strong>Original Issue:</strong> SQLite database file path problems on Railway</p>
                <p><strong>Solution:</strong> Migrate to MySQL for better Railway compatibility</p>
                <p><strong>Expected Result:</strong> 6 Jenis Surat options in dropdown</p>
                <p><strong>Benefits:</strong> Persistent storage, better performance, automatic backups</p>
            </div>
        </div>
    </div>
</body>
</html>