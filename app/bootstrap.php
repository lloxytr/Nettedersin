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

$db = Database::connection($config['db']);


function __schema_has_column(PDO $pdo, string $dbName, string $table, string $column): bool
{
    $q = $pdo->prepare('SELECT COUNT(*) c FROM information_schema.columns WHERE table_schema=:db AND table_name=:t AND column_name=:c');
    $q->execute(['db' => $dbName, 't' => $table, 'c' => $column]);
    return ((int)$q->fetchColumn()) > 0;
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
            $pdo->exec("ALTER TABLE `{$table}` {$ddl}");
        }
    }
}

__schema_bootstrap_patch($db, $config['db']);
$repo = new Repository($db);
$auth = new AuthService($repo, $config);
$paymentService = new PaymentService($repo, $config);

$auth->ensureSessionRow();
