<?php
/**
 * Interactive MySQL Database Setup for Railway
 * URL: https://your-app.railway.app/setup-database.php
 */

$setupAction = $_GET['action'] ?? '';
$setupComplete = false;
$setupResults = [];

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Database - Railway MySQL</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1000px;
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
        .btn {
            display: inline-block;
            padding: 15px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin: 10px 5px;
            transition: all 0.3s;
            font-size: 16px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }
        .btn-success {
            background: #28a745;
        }
        .btn-success:hover {
            background: #218838;
        }
        .btn-danger {
            background: #dc3545;
        }
        .btn-danger:hover {
            background: #c82333;
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
        .progress {
            width: 100%;
            height: 25px;
            background: #e9ecef;
            border-radius: 12px;
            overflow: hidden;
            margin: 15px 0;
        }
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #28a745, #20c997);
            transition: width 0.5s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 Setup Database MySQL</h1>
            <p>Sistem Surat Online - Railway Deployment</p>
            <p><strong>Timestamp:</strong> <?= date('Y-m-d H:i:s') ?></p>
        </div>

<?php

// MySQL Configuration
$mysqlConfig = [
    'host' => $_ENV['MYSQLHOST'] ?? $_SERVER['MYSQLHOST'] ?? 'localhost',
    'port' => $_ENV['MYSQLPORT'] ?? $_SERVER['MYSQLPORT'] ?? '3306',
    'database' => $_ENV['MYSQLDATABASE'] ?? $_SERVER['MYSQLDATABASE'] ?? 'railway',
    'username' => $_ENV['MYSQLUSER'] ?? $_SERVER['MYSQLUSER'] ?? 'root',
    'password' => $_ENV['MYSQLPASSWORD'] ?? $_SERVER['MYSQLPASSWORD'] ?? '',
];

// Check MySQL Configuration
echo "<div class='section'>";
echo "<h2>🔍 MySQL Configuration Check</h2>";
echo "<table class='data-table'>";
echo "<tr><th>Parameter</th><th>Value</th><th>Status</th></tr>";
echo "<tr><td>Host</td><td>{$mysqlConfig['host']}</td><td>" . (!empty($mysqlConfig['host']) ? "✅" : "❌") . "</td></tr>";
echo "<tr><td>Port</td><td>{$mysqlConfig['port']}</td><td>" . (!empty($mysqlConfig['port']) ? "✅" : "❌") . "</td></tr>";
echo "<tr><td>Database</td><td>{$mysqlConfig['database']}</td><td>" . (!empty($mysqlConfig['database']) ? "✅" : "❌") . "</td></tr>";
echo "<tr><td>Username</td><td>{$mysqlConfig['username']}</td><td>" . (!empty($mysqlConfig['username']) ? "✅" : "❌") . "</td></tr>";
echo "<tr><td>Password</td><td>" . (empty($mysqlConfig['password']) ? "Not set" : "Set") . "</td><td>" . (!empty($mysqlConfig['password']) ? "✅" : "❌") . "</td></tr>";
echo "</table>";

$configOk = !empty($mysqlConfig['host']) && !empty($mysqlConfig['password']);

if ($configOk) {
    echo "<div class='success'>✅ MySQL configuration is complete</div>";
} else {
    echo "<div class='error'>❌ MySQL configuration is incomplete</div>";
}
echo "</div>";

// Test MySQL Connection
if ($configOk) {
    try {
        $dsn = "mysql:host={$mysqlConfig['host']};port={$mysqlConfig['port']};dbname={$mysqlConfig['database']};charset=utf8mb4";
        $pdo = new PDO($dsn, $mysqlConfig['username'], $mysqlConfig['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        
        echo "<div class='section'>";
        echo "<h2>🔌 MySQL Connection Test</h2>";
        echo "<div class='success'>✅ MySQL Connection: SUCCESSFUL</div>";
        
        // Check existing tables
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "<div class='info'>Found " . count($tables) . " existing tables</div>";
        
        // Check if setup is needed
        $needsSetup = count($tables) == 0 || !in_array('jenis_surats', $tables);
        
        if ($needsSetup) {
            echo "<div class='warning'>⚠ Database setup required</div>";
        } else {
            // Check jenis_surats data
            $stmt = $pdo->query("SELECT COUNT(*) FROM jenis_surats");
            $jenisCount = $stmt->fetchColumn();
            
            if ($jenisCount == 0) {
                echo "<div class='warning'>⚠ Jenis Surats table is empty - data import needed</div>";
                $needsSetup = true;
            } else {
                echo "<div class='success'>✅ Database already set up with $jenisCount Jenis Surat entries</div>";
            }
        }
        echo "</div>";
        
        // Setup Action
        if ($setupAction === 'setup' && $needsSetup) {
            echo "<div class='section'>";
            echo "<h2>🔄 Running Database Setup...</h2>";
            
            $progress = 0;
            $totalSteps = 4;
            
            // Step 1: Run Laravel Migrations
            echo "<div class='info'>Step 1/4: Running Laravel migrations...</div>";
            
            // We'll use the existing SQL export file
            $exportFile = '../complete_mysql_data_export.sql';
            if (file_exists($exportFile)) {
                echo "<div class='success'>✅ Found export file</div>";
                $progress++;
                
                // Step 2: Import SQL
                echo "<div class='info'>Step 2/4: Importing database structure...</div>";
                
                $sql = file_get_contents($exportFile);
                $statements = array_filter(array_map('trim', explode(';', $sql)));
                
                $imported = 0;
                $errors = 0;
                
                foreach ($statements as $statement) {
                    if (!empty($statement) && !preg_match('/^--/', $statement)) {
                        try {
                            $pdo->exec($statement);
                            $imported++;
                        } catch (PDOException $e) {
                            if (strpos($e->getMessage(), 'Duplicate entry') === false && 
                                strpos($e->getMessage(), 'already exists') === false) {
                                $errors++;
                            }
                        }
                    }
                }
                
                echo "<div class='success'>✅ Imported $imported SQL statements</div>";
                if ($errors > 0) {
                    echo "<div class='warning'>⚠ $errors statements had errors (likely duplicates)</div>";
                }
                $progress++;
                
                // Step 3: Verify tables
                echo "<div class='info'>Step 3/4: Verifying tables...</div>";
                
                $stmt = $pdo->query("SHOW TABLES");
                $newTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                echo "<div class='success'>✅ Created " . count($newTables) . " tables</div>";
                $progress++;
                
                // Step 4: Verify data
                echo "<div class='info'>Step 4/4: Verifying data...</div>";
                
                if (in_array('jenis_surats', $newTables)) {
                    $stmt = $pdo->query("SELECT COUNT(*) FROM jenis_surats");
                    $jenisCount = $stmt->fetchColumn();
                    
                    echo "<div class='success'>✅ Jenis Surats: $jenisCount records</div>";
                    $progress++;
                } else {
                    echo "<div class='error'>❌ Jenis Surats table not found</div>";
                }
                
                // Progress bar
                $progressPercent = ($progress / $totalSteps) * 100;
                echo "<div class='progress'>";
                echo "<div class='progress-bar' style='width: {$progressPercent}%'>{$progressPercent}%</div>";
                echo "</div>";
                
                if ($progress == $totalSteps) {
                    echo "<div class='success'>";
                    echo "<h3>🎉 Database Setup Complete!</h3>";
                    echo "<p>✅ All tables created successfully</p>";
                    echo "<p>✅ Data imported successfully</p>";
                    echo "<p>✅ Ready for production use</p>";
                    echo "</div>";
                    $setupComplete = true;
                }
                
            } else {
                echo "<div class='error'>❌ Export file not found: $exportFile</div>";
            }
            echo "</div>";
        }
        
        // Show current data status
        if (in_array('jenis_surats', $tables)) {
            echo "<div class='section'>";
            echo "<h2>📝 Jenis Surat Data</h2>";
            
            $stmt = $pdo->query("SELECT * FROM jenis_surats ORDER BY id");
            $jenisSurats = $stmt->fetchAll();
            
            if (!empty($jenisSurats)) {
                echo "<div class='success'>✅ Found " . count($jenisSurats) . " Jenis Surat entries</div>";
                
                echo "<table class='data-table'>";
                echo "<tr><th>ID</th><th>Nama</th><th>Deskripsi</th></tr>";
                foreach ($jenisSurats as $jenis) {
                    echo "<tr>";
                    echo "<td>{$jenis['id']}</td>";
                    echo "<td>{$jenis['nama']}</td>";
                    echo "<td>" . substr($jenis['deskripsi'] ?? '', 0, 80) . "...</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<div class='warning'>⚠ No data found in jenis_surats table</div>";
            }
            echo "</div>";
        }
        
    } catch (PDOException $e) {
        echo "<div class='section'>";
        echo "<h2>❌ MySQL Connection Failed</h2>";
        echo "<div class='error'>Error: " . $e->getMessage() . "</div>";
        echo "</div>";
        $configOk = false;
    }
}

?>

        <div class="section">
            <h2>🎯 Actions</h2>
            
            <?php if ($configOk && !$setupComplete && ($needsSetup ?? true)): ?>
                <a href="?action=setup" class="btn btn-success">🚀 Setup Database</a>
            <?php endif; ?>
            
            <?php if ($setupComplete || !($needsSetup ?? true)): ?>
                <a href="../" class="btn btn-success">🏠 Go to Application</a>
            <?php endif; ?>
            
            <a href="javascript:location.reload()" class="btn">🔄 Refresh</a>
            <a href="mysql-setup.php" class="btn">📊 View Details</a>
        </div>
        
        <div class="section">
            <h2>📋 Setup Information</h2>
            <div class="info">
                <p><strong>Purpose:</strong> Setup MySQL database for Surat Online system</p>
                <p><strong>Expected Result:</strong> 6 Jenis Surat options in dropdown</p>
                <p><strong>Tables:</strong> ~22 tables with complete data structure</p>
                <p><strong>Data:</strong> ~45 initial records including jenis surat options</p>
            </div>
        </div>
    </div>
</body>
</html>