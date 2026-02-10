<?php
$defaults = [
    'host' => 'localhost',
    'port' => '3306',
    'database' => 'nettepfg_db',
    'username' => 'nettepfg_user',
    'password' => 'Sifre1234.',
    'base_url' => ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost'),
    'admin_name' => 'Site Admin',
    'admin_email' => 'admin@nettedersin.com',
    'admin_password' => 'Admin12345!'
];
$input = $defaults;
$status = null;
$message = '';
$uploadsOk = is_dir(__DIR__ . '/../uploads') && is_writable(__DIR__ . '/../uploads');

function splitSql(string $sql): array {
    $clean = preg_replace('/^\s*--.*$/m', '', $sql);
    return array_values(array_filter(array_map('trim', explode(';', (string)$clean))));
}

function hasColumn(PDO $pdo, string $dbName, string $table, string $column): bool {
    try {
        $q = $pdo->prepare('SELECT COUNT(*) c FROM information_schema.columns WHERE table_schema=:db AND table_name=:t AND column_name=:c');
        $q->execute(['db'=>$dbName,'t'=>$table,'c'=>$column]);
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

function ensureCompatColumns(PDO $pdo, string $dbName): void {
    $patches = [
        ['users', "ADD COLUMN role ENUM('student','teacher','admin') NOT NULL DEFAULT 'student'"],
        ['users', "ADD COLUMN status ENUM('active','frozen') NOT NULL DEFAULT 'active'"],
        ['users', "ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP"],
        ['users', "ADD COLUMN updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP"],
        ['courses', "ADD COLUMN status ENUM('draft','review','published') DEFAULT 'draft'"],
    ];

    foreach ($patches as [$table, $ddl]) {
        $col = trim(explode(' ', str_replace('ADD COLUMN ', '', $ddl), 2)[0], '`');
        if (!hasColumn($pdo, $dbName, $table, $col)) {
            try {
                $pdo->exec("ALTER TABLE `{$table}` {$ddl}");
            } catch (Throwable $e) {
                // shared hosting varyasyonlarında sessiz geç
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($defaults as $k => $v) { $input[$k] = trim((string)($_POST[$k] ?? '')); }

    try {
        $dsn = sprintf('mysql:host=%s;port=%s;charset=utf8mb4', $input['host'], $input['port']);
        $pdo = new PDO($dsn, $input['username'], $input['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $dbName = str_replace('`', '', $input['database']);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `{$dbName}`");

        foreach (splitSql((string)file_get_contents(__DIR__ . '/schema.sql')) as $stmt) { $pdo->exec($stmt); }
        ensureCompatColumns($pdo, $dbName);
        foreach (splitSql((string)file_get_contents(__DIR__ . '/seed.sql')) as $stmt) { $pdo->exec($stmt); }

        $hash = password_hash($input['admin_password'], PASSWORD_BCRYPT);
        $stmt = $pdo->prepare('INSERT INTO users (full_name, email, password_hash, role, status, created_at) VALUES (:n,:e,:p,"admin","active",NOW()) ON DUPLICATE KEY UPDATE full_name=VALUES(full_name), password_hash=VALUES(password_hash), role="admin"');
        $stmt->execute(['n' => $input['admin_name'], 'e' => strtolower($input['admin_email']), 'p' => $hash]);

        $cfg = "<?php\nreturn [\n"
            . "  'app_name' => 'Nettedersin',\n"
            . "  'base_url' => '" . addslashes($input['base_url']) . "',\n"
            . "  'db' => [\n"
            . "    'host' => '" . addslashes($input['host']) . "',\n"
            . "    'port' => '" . addslashes($input['port']) . "',\n"
            . "    'database' => '" . addslashes($input['database']) . "',\n"
            . "    'username' => '" . addslashes($input['username']) . "',\n"
            . "    'password' => '" . addslashes($input['password']) . "',\n"
            . "    'charset' => 'utf8mb4'\n"
            . "  ],\n"
            . "  'security' => [\n"
            . "    'session_name' => 'nettedersin_session',\n"
            . "    'csrf_key' => '" . bin2hex(random_bytes(16)) . "'\n"
            . "  ],\n"
            . "  'payment' => [\n"
            . "    'mode' => 'manual',\n"
            . "    'provider' => 'iyzico'\n"
            . "  ]\n"
            . "];\n";

        file_put_contents(__DIR__ . '/../config/config.php', $cfg);

        if (!is_dir(__DIR__ . '/../uploads')) {
            @mkdir(__DIR__ . '/../uploads', 0775, true);
        }

        $status = 'success';
        $message = 'Kurulum tamamlandı. Giriş: /giris (admin e-postanız ve şifrenizle). Güvenlik için setup/install.php dosyasını kaldırın.';
    } catch (Throwable $e) {
        $status = 'error';
        $message = $e->getMessage();
    }
}
?>
<!doctype html><html lang="tr"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Nettedersin Installer</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-slate-950 text-slate-100"><div class="max-w-3xl mx-auto p-6">
<h1 class="text-3xl font-bold">Nettedersin Kurulum Sihirbazı</h1>
<p class="text-slate-400 mt-2">Terminal olmadan kurulum: DB oluştur, tabloları yaz, admin hesabını üret.</p>
<?php if (!$uploadsOk): ?><p class="mt-3 p-3 rounded bg-amber-900 border border-amber-700">Uyarı: <code>/uploads</code> yazılabilir değil. Dosya yükleme için klasör izni verin.</p><?php endif; ?>
<?php if ($status): ?><p class="mt-3 p-3 rounded <?= $status === 'success' ? 'bg-emerald-900 border border-emerald-700' : 'bg-red-900 border border-red-700' ?>\"><?= htmlspecialchars($message) ?></p><?php endif; ?>
<form method="post" class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-3 bg-slate-900 border border-slate-800 p-4 rounded-xl">
<?php foreach ($input as $k => $v): ?>
  <div class="<?= in_array($k,['base_url','admin_name','admin_email','admin_password'],true) ? 'md:col-span-2' : '' ?>">
    <label class="text-sm text-slate-300"><?= htmlspecialchars($k) ?></label>
    <input name="<?= htmlspecialchars($k) ?>" value="<?= htmlspecialchars($v) ?>" class="mt-1 w-full p-2 rounded bg-slate-800 border border-slate-700" <?= $k === 'admin_password' ? 'type="password"' : '' ?> required>
  </div>
<?php endforeach; ?>
<button class="md:col-span-2 p-3 bg-blue-600 rounded font-semibold">Kurulumu Başlat</button>
</form></div></body></html>
