<?php
$defaults = [
    'host' => 'localhost',
    'port' => '3306',
    'database' => 'nettepfg_db',
    'username' => 'nettepfg_user',
    'password' => 'Sifre1234.',
];

$input = $defaults;
$status = null;
$messages = [];

function splitSqlStatements(string $sql): array
{
    $clean = preg_replace('/^\s*--.*$/m', '', $sql);
    $chunks = array_filter(array_map('trim', explode(';', (string)$clean)));
    return array_values($chunks);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (array_keys($defaults) as $key) {
        $input[$key] = trim((string)($_POST[$key] ?? ''));
    }

    try {
        $dsn = sprintf('mysql:host=%s;port=%s;charset=utf8mb4', $input['host'], $input['port']);
        $pdo = new PDO($dsn, $input['username'], $input['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        $pdo->exec('CREATE DATABASE IF NOT EXISTS `' . str_replace('`', '', $input['database']) . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        $pdo->exec('USE `' . str_replace('`', '', $input['database']) . '`');

        $schemaPath = __DIR__ . '/schema.sql';
        $seedPath = __DIR__ . '/seed.sql';

        foreach (splitSqlStatements((string)file_get_contents($schemaPath)) as $statement) {
            $pdo->exec($statement);
        }

        foreach (splitSqlStatements((string)file_get_contents($seedPath)) as $statement) {
            $pdo->exec($statement);
        }

        $configContent = "<?php\nreturn [\n"
            . "    'host' => getenv('DB_HOST') ?: '" . addslashes($input['host']) . "',\n"
            . "    'port' => getenv('DB_PORT') ?: '" . addslashes($input['port']) . "',\n"
            . "    'database' => getenv('DB_NAME') ?: '" . addslashes($input['database']) . "',\n"
            . "    'username' => getenv('DB_USER') ?: '" . addslashes($input['username']) . "',\n"
            . "    'password' => getenv('DB_PASS') ?: '" . addslashes($input['password']) . "',\n"
            . "    'charset' => 'utf8mb4'\n"
            . "];\n";

        file_put_contents(__DIR__ . '/../config/database.php', $configContent);

        $status = 'success';
        $messages[] = 'Kurulum tamamlandı. Veritabanı tabloları oluşturuldu ve config dosyası güncellendi.';
        $messages[] = 'Güvenlik için kurulum sonrası setup/install.php dosyasını silin veya erişimi kapatın.';
    } catch (Throwable $e) {
        $status = 'error';
        $messages[] = 'Kurulum başarısız: ' . $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nettedersin Kurulum Sihirbazı</title>
    <style>
        body { font-family: Inter, Arial, sans-serif; background: #0b1220; color: #e2e8f0; margin: 0; }
        .wrap { max-width: 760px; margin: 2rem auto; padding: 1.2rem; }
        .card { background: #111c32; border: 1px solid #223557; border-radius: 14px; padding: 1.2rem; }
        h1 { margin-top: 0; }
        label { display: block; margin-top: .75rem; font-weight: 600; }
        input { width: 100%; padding: .65rem .7rem; border-radius: 8px; border: 1px solid #375179; background: #0f172a; color: #e2e8f0; }
        button { margin-top: 1rem; background: #3b82f6; color: #fff; border: 0; padding: .7rem 1rem; border-radius: 8px; font-weight: 700; }
        .msg { margin: .8rem 0; padding: .7rem .8rem; border-radius: 8px; }
        .success { background: #12331f; border: 1px solid #2f7d47; }
        .error { background: #3a1717; border: 1px solid #7a2b2b; }
        code { background: #0b172c; padding: .1rem .35rem; border-radius: 4px; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>Nettedersin Kurulum (Terminal Yoksa)</h1>
        <p>Bu ekran, terminal olmadan MySQL tablolarını kurar ve <code>config/database.php</code> dosyasını günceller.</p>

        <?php if ($status): ?>
            <div class="msg <?= $status === 'success' ? 'success' : 'error' ?>">
                <?php foreach ($messages as $message): ?>
                    <p><?= htmlspecialchars($message) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <label for="host">DB Host</label>
            <input id="host" name="host" value="<?= htmlspecialchars($input['host']) ?>" required>

            <label for="port">DB Port</label>
            <input id="port" name="port" value="<?= htmlspecialchars($input['port']) ?>" required>

            <label for="database">DB Name</label>
            <input id="database" name="database" value="<?= htmlspecialchars($input['database']) ?>" required>

            <label for="username">DB User</label>
            <input id="username" name="username" value="<?= htmlspecialchars($input['username']) ?>" required>

            <label for="password">DB Password</label>
            <input id="password" type="password" name="password" value="<?= htmlspecialchars($input['password']) ?>" required>

            <button type="submit">Kurulumu Başlat</button>
        </form>
    </div>
</div>
</body>
</html>
