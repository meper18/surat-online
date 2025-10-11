<?php
/**
 * Database Data Check Script
 * Check if user and surat data exists in the database
 */

// Load environment variables
if (file_exists(__DIR__ . '/../.env.railway')) {
    $lines = file(__DIR__ . '/../.env.railway', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value, '"');
        }
    }
}

// Database connection details
$host = $_ENV['DB_HOST'] ?? 'localhost';
$port = $_ENV['DB_PORT'] ?? 3306;
$database = $_ENV['DB_DATABASE'] ?? 'railway';
$username = $_ENV['DB_USERNAME'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? '';

echo "<h1>🔍 Database Data Check</h1>";
echo "<p><strong>Checking database:</strong> {$host}:{$port}/{$database}</p>";

try {
    // Connect to database
    $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    echo "<p>✅ <strong>Database connection successful!</strong></p>";
    
    // Check if tables exist
    $tables = [
        'users' => 'User accounts',
        'roles' => 'User roles',
        'jenis_surats' => 'Jenis surat',
        'permohonans' => 'Permohonan surat',
        'surat_penghasilans' => 'Surat penghasilan',
        'surat_domisili_tinggals' => 'Surat domisili tinggal',
        'surat_domisili_usahas' => 'Surat domisili usaha',
        'surat_mandahs' => 'Surat mandah',
        'surat_kematians' => 'Surat kematian',
        'surat_nikahs' => 'Surat nikah',
        'migrations' => 'Migration history'
    ];
    
    echo "<h2>📊 Table Status & Data Count</h2>";
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Table</th><th>Description</th><th>Exists</th><th>Row Count</th><th>Status</th></tr>";
    
    $totalData = 0;
    $missingTables = [];
    
    foreach ($tables as $table => $description) {
        try {
            // Check if table exists
            $stmt = $pdo->query("SHOW TABLES LIKE '{$table}'");
            $tableExists = $stmt->rowCount() > 0;
            
            if ($tableExists) {
                // Count rows
                $stmt = $pdo->query("SELECT COUNT(*) as count FROM `{$table}`");
                $count = $stmt->fetch()['count'];
                $totalData += $count;
                
                $status = $count > 0 ? "✅ Has data" : "⚠️ Empty";
                $statusColor = $count > 0 ? "green" : "orange";
                
                echo "<tr>";
                echo "<td><strong>{$table}</strong></td>";
                echo "<td>{$description}</td>";
                echo "<td style='color: green;'>✅ Yes</td>";
                echo "<td style='text-align: center;'>{$count}</td>";
                echo "<td style='color: {$statusColor};'>{$status}</td>";
                echo "</tr>";
            } else {
                $missingTables[] = $table;
                echo "<tr>";
                echo "<td><strong>{$table}</strong></td>";
                echo "<td>{$description}</td>";
                echo "<td style='color: red;'>❌ No</td>";
                echo "<td style='text-align: center;'>-</td>";
                echo "<td style='color: red;'>❌ Missing</td>";
                echo "</tr>";
            }
        } catch (Exception $e) {
            echo "<tr>";
            echo "<td><strong>{$table}</strong></td>";
            echo "<td>{$description}</td>";
            echo "<td style='color: red;'>❌ Error</td>";
            echo "<td style='text-align: center;'>-</td>";
            echo "<td style='color: red;'>Error: " . $e->getMessage() . "</td>";
            echo "</tr>";
        }
    }
    
    echo "</table>";
    
    // Summary
    echo "<h2>📋 Summary</h2>";
    echo "<ul>";
    echo "<li><strong>Total data rows:</strong> {$totalData}</li>";
    echo "<li><strong>Missing tables:</strong> " . (count($missingTables) > 0 ? implode(', ', $missingTables) : 'None') . "</li>";
    echo "</ul>";
    
    // Check migrations
    echo "<h2>🔄 Migration Status</h2>";
    try {
        $stmt = $pdo->query("SELECT migration, batch FROM migrations ORDER BY batch DESC, migration DESC LIMIT 10");
        $migrations = $stmt->fetchAll();
        
        if (count($migrations) > 0) {
            echo "<p>✅ <strong>Latest migrations applied:</strong></p>";
            echo "<ul>";
            foreach ($migrations as $migration) {
                echo "<li>Batch {$migration['batch']}: {$migration['migration']}</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>⚠️ <strong>No migrations found!</strong> Database might not be properly initialized.</p>";
        }
    } catch (Exception $e) {
        echo "<p>❌ <strong>Cannot check migrations:</strong> " . $e->getMessage() . "</p>";
    }
    
    // Check specific user data
    echo "<h2>👥 User Data Details</h2>";
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        $userCount = $stmt->fetch()['count'];
        
        if ($userCount > 0) {
            $stmt = $pdo->query("SELECT u.name, u.email, r.name as role_name FROM users u LEFT JOIN roles r ON u.role_id = r.id LIMIT 5");
            $users = $stmt->fetchAll();
            
            echo "<p>✅ <strong>Found {$userCount} users. Sample data:</strong></p>";
            echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
            echo "<tr><th>Name</th><th>Email</th><th>Role</th></tr>";
            foreach ($users as $user) {
                echo "<tr>";
                echo "<td>{$user['name']}</td>";
                echo "<td>{$user['email']}</td>";
                echo "<td>" . ($user['role_name'] ?? 'No role') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>❌ <strong>No users found in database!</strong></p>";
        }
    } catch (Exception $e) {
        echo "<p>❌ <strong>Cannot check user data:</strong> " . $e->getMessage() . "</p>";
    }
    
    // Check permohonan data
    echo "<h2>📄 Permohonan Data Details</h2>";
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM permohonans");
        $permohonanCount = $stmt->fetch()['count'];
        
        if ($permohonanCount > 0) {
            $stmt = $pdo->query("SELECT p.kode_permohonan, p.status, js.nama as jenis_surat, u.name as user_name FROM permohonans p LEFT JOIN jenis_surats js ON p.jenis_surat_id = js.id LEFT JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC LIMIT 5");
            $permohonans = $stmt->fetchAll();
            
            echo "<p>✅ <strong>Found {$permohonanCount} permohonan. Latest data:</strong></p>";
            echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
            echo "<tr><th>Kode</th><th>Jenis Surat</th><th>User</th><th>Status</th></tr>";
            foreach ($permohonans as $permohonan) {
                echo "<tr>";
                echo "<td>{$permohonan['kode_permohonan']}</td>";
                echo "<td>" . ($permohonan['jenis_surat'] ?? 'Unknown') . "</td>";
                echo "<td>" . ($permohonan['user_name'] ?? 'Unknown') . "</td>";
                echo "<td>{$permohonan['status']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>❌ <strong>No permohonan found in database!</strong></p>";
        }
    } catch (Exception $e) {
        echo "<p>❌ <strong>Cannot check permohonan data:</strong> " . $e->getMessage() . "</p>";
    }
    
    // Recommendations
    echo "<h2>💡 Recommendations</h2>";
    if ($totalData == 0) {
        echo "<div style='background: #ffebee; padding: 15px; border-left: 4px solid #f44336;'>";
        echo "<h3>🚨 Critical: No data found!</h3>";
        echo "<p><strong>Possible causes:</strong></p>";
        echo "<ul>";
        echo "<li>Database was reset or recreated</li>";
        echo "<li>Migrations were run without seeding data</li>";
        echo "<li>Connected to wrong database</li>";
        echo "<li>Data was accidentally deleted</li>";
        echo "</ul>";
        echo "<p><strong>Next steps:</strong></p>";
        echo "<ol>";
        echo "<li>Run migrations: <code>php artisan migrate</code></li>";
        echo "<li>Run seeders: <code>php artisan db:seed</code></li>";
        echo "<li>Or use the seeder script: <a href='/run-seeder.php'>run-seeder.php</a></li>";
        echo "</ol>";
        echo "</div>";
    } elseif (count($missingTables) > 0) {
        echo "<div style='background: #fff3e0; padding: 15px; border-left: 4px solid #ff9800;'>";
        echo "<h3>⚠️ Warning: Some tables are missing</h3>";
        echo "<p>Run migrations to create missing tables: <code>php artisan migrate</code></p>";
        echo "</div>";
    } else {
        echo "<div style='background: #e8f5e8; padding: 15px; border-left: 4px solid #4caf50;'>";
        echo "<h3>✅ Database looks healthy!</h3>";
        echo "<p>All tables exist and contain data.</p>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ <strong>Database connection failed:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Please check:</strong></p>";
    echo "<ul>";
    echo "<li>Database credentials in .env.railway</li>";
    echo "<li>Database server is running</li>";
    echo "<li>Network connectivity</li>";
    echo "</ul>";
}

echo "<hr>";
echo "<p><small>Generated at: " . date('Y-m-d H:i:s') . "</small></p>";
?>