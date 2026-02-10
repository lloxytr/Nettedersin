<?php
$configPath = __DIR__ . '/../config/config.php';

if (!is_file($configPath)) {
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    if (strpos($uri, '/setup/install.php') !== 0) {
        header('Location: /setup/install.php');
        exit;
    }
}

$config = is_file($configPath) ? require $configPath : require __DIR__ . '/../config/config.example.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name($config['security']['session_name'] ?? 'nettedersin_session');
    session_start();
}

require_once __DIR__ . '/../lib/Database.php';
require_once __DIR__ . '/../lib/Repository.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/services/AuthService.php';
require_once __DIR__ . '/services/PaymentService.php';

try {
    $db = Database::connection($config['db']);
} catch (Throwable $e) {
    http_response_code(500);
    exit('Veritabanı bağlantısı kurulamadı. Lütfen setup/install.php ile kurulum bilgilerini kontrol edin.');
}

function __schema_has_column(PDO $pdo, string $dbName, string $table, string $column): bool
{
    try {
        $q = $pdo->prepare('SELECT COUNT(*) c FROM information_schema.columns WHERE table_schema=:db AND table_name=:t AND column_name=:c');
        $q->execute(['db' => $dbName, 't' => $table, 'c' => $column]);
        return ((int)$q->fetchColumn()) > 0;
    } catch (Throwable $e) {
        try {
            $q = $pdo->prepare("SHOW COLUMNS FROM `{$table}` LIKE :col");
            $q->execute(['col' => $column]);
            return (bool)$q->fetch();
        } catch (Throwable $e2) {
            return true;
        }
    }
}

function __schema_bootstrap_patch(PDO $pdo, array $dbCfg): void
{
    $dbName = $dbCfg['database'];
    $patches = [
        ['users', "ADD COLUMN role ENUM('student','teacher','admin') NOT NULL DEFAULT 'student'"],
        ['users', "ADD COLUMN status ENUM('active','frozen') NOT NULL DEFAULT 'active'"],
        ['courses', "ADD COLUMN status ENUM('draft','review','published') DEFAULT 'draft'"],
    ];

    foreach ($patches as [$table, $ddl]) {
        $col = trim(explode(' ', str_replace('ADD COLUMN ', '', $ddl), 2)[0], '`');
        if (!__schema_has_column($pdo, $dbName, $table, $col)) {
            try {
                $pdo->exec("ALTER TABLE `{$table}` {$ddl}");
            } catch (Throwable $e) {
                // Shared hosting varyasyonlarında sessiz geç
            }
        }
    }
}

try {
    __schema_bootstrap_patch($db, $config['db']);
} catch (Throwable $e) {
    // Çökme engeli
}

$repo = new Repository($db);
$auth = new AuthService($repo, $config);
$paymentService = new PaymentService($repo, $config);
$auth->ensureSessionRow();
