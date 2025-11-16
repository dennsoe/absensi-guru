<?php
/**
 * Installer - Drop All Tables & Re-import
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

session_start();

// Get config from POST or session
if (isset($_POST['db_host']) && isset($_POST['db_name'])) {
    $config = [
        'host' => $_POST['db_host'],
        'port' => $_POST['db_port'] ?? '3306',
        'name' => $_POST['db_name'],
        'user' => $_POST['db_user'],
        'pass' => $_POST['db_pass'] ?? ''
    ];
    $_SESSION['db_config'] = $config;
} elseif (isset($_SESSION['db_config'])) {
    $config = $_SESSION['db_config'];
} else {
    echo json_encode(['success' => false, 'message' => 'Konfigurasi database tidak ditemukan. Silakan ulangi dari step 2.']);
    exit;
}

try {
    $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['name']};charset=utf8mb4";
    $pdo = new PDO($dsn, $config['user'], $config['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // Disable foreign key checks
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
    
    // Get all tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Drop all tables
    foreach ($tables as $table) {
        $pdo->exec("DROP TABLE IF EXISTS `{$table}`");
    }
    
    // Re-enable foreign key checks
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    
    // Now import the SQL files
    $sqlPart1 = file_get_contents(__DIR__ . '/../database/absensi_guru_part1.sql');
    $sqlPart2 = file_get_contents(__DIR__ . '/../database/absensi_guru_part2.sql');
    
    if (!$sqlPart1 || !$sqlPart2) {
        throw new Exception('File SQL tidak ditemukan');
    }
    
    // Execute SQL (no transaction needed for DDL statements)
    // DDL statements (CREATE TABLE) cause implicit commits in MySQL
    $pdo->exec($sqlPart1);
    $pdo->exec($sqlPart2);
    
    // Save config to file
    $configContent = "<?php
/**
 * Konfigurasi Database - Fleksibel untuk berbagai environment
 */

// Database Configuration dengan environment detection
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'development');
}

if (ENVIRONMENT === 'development') {
    // Development (Local)
    define('DB_HOST', '{$config['host']}');
    define('DB_NAME', '{$config['name']}');
    define('DB_USER', '{$config['user']}');
    define('DB_PASS', '{$config['pass']}');
    define('DB_CHARSET', 'utf8mb4');
} else {
    // Production - baca dari environment variables atau .env file
    define('DB_HOST', getenv('DB_HOST') ?: '{$config['host']}');
    define('DB_NAME', getenv('DB_NAME') ?: '{$config['name']}');
    define('DB_USER', getenv('DB_USER') ?: '{$config['user']}');
    define('DB_PASS', getenv('DB_PASS') ?: '{$config['pass']}');
    define('DB_CHARSET', 'utf8mb4');
}

// PDO Options
define('DB_OPTIONS', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => \"SET NAMES \" . DB_CHARSET
]);

/**
 * Get Database Connection
 */
function getDB() {
    static \$pdo = null;
    
    if (\$pdo === null) {
        try {
            \$dsn = \"mysql:host=\" . DB_HOST . \";dbname=\" . DB_NAME . \";charset=\" . DB_CHARSET;
            \$pdo = new PDO(\$dsn, DB_USER, DB_PASS, DB_OPTIONS);
        } catch (PDOException \$e) {
            if (DEBUG_MODE) {
                die(\"Koneksi database gagal: \" . \$e->getMessage());
            } else {
                error_log(\"Database Error: \" . \$e->getMessage());
                die(\"Terjadi kesalahan sistem. Silakan hubungi administrator.\");
            }
        }
    }
    
    return \$pdo;
}
";
    
    file_put_contents(__DIR__ . '/../config/database.php', $configContent);
    
    echo json_encode([
        'success' => true,
        'message' => 'Database berhasil di-reset dan diimport! 17 tabel dibuat.',
        'dropped' => count($tables)
    ]);
    
} catch (Exception $e) {
    // Try to re-enable foreign key checks if possible
    try {
        if (isset($pdo) && $pdo instanceof PDO) {
            $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
        }
    } catch (Exception $fkException) {
        // Ignore FK errors
    }
    
    echo json_encode([
        'success' => false,
        'message' => 'Reset & import gagal: ' . $e->getMessage()
    ]);
}