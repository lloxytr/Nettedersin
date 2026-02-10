<?php

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string
{
    if (!isset($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function verify_csrf(): void
{
    $token = $_POST['_csrf'] ?? '';
    if (!$token || !hash_equals($_SESSION['_csrf'] ?? '', $token)) {
        http_response_code(419);
        exit('CSRF doğrulaması başarısız.');
    }
}

function render(string $view, array $params = []): void
{
    extract($params);
    $viewPath = __DIR__ . '/views/' . $view . '.php';
    include __DIR__ . '/views/layout.php';
}

function old(string $key, string $default = ''): string
{
    return e($_POST[$key] ?? $default);
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function require_auth(): void
{
    if (!current_user()) {
        redirect('/giris');
    }
}

function require_role(array $roles): void
{
    require_auth();
    $role = current_user()['role'] ?? '';
    if (!in_array($role, $roles, true)) {
        http_response_code(403);
        exit('Bu sayfaya erişim yetkiniz yok.');
    }
}

function to_lower(string $text): string
{
    return function_exists('mb_strtolower') ? mb_strtolower($text, 'UTF-8') : strtolower($text);
}

function slugify(string $text): string
{
    $text = to_lower($text);
    $replace = ['ş' => 's', 'ı' => 'i', 'ğ' => 'g', 'ç' => 'c', 'ö' => 'o', 'ü' => 'u'];
    $text = strtr($text, $replace);
    $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
    return trim((string)$text, '-');
}

function seo_meta(string $title, string $desc): array
{
    return ['metaTitle' => $title, 'metaDesc' => $desc];
}
