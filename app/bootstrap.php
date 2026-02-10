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
$repo = new Repository($db);
$auth = new AuthService($repo, $config);
$paymentService = new PaymentService($repo, $config);

$auth->ensureSessionRow();
