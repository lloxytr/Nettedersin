<?php
if (!isset($pageTitle)) {
    $pageTitle = 'Nettedersin';
}
if (!isset($pageDescription)) {
    $pageDescription = 'Nettedersin online eğitim platformu';
}
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    <link rel="stylesheet" href="/public/style.css">
</head>
<body>
<header class="header">
    <div class="container nav">
        <strong class="brand">Nettedersin</strong>
        <nav>
            <a href="/index.php">Ana Sayfa</a>
            <a href="/pages/ogrenci.php">Öğrenci Paneli</a>
            <a href="/pages/ogretmen.php">Öğretmen Paneli</a>
            <a href="/pages/admin.php">Admin Paneli</a>
        </nav>
    </div>
</header>
<main class="container">
<?= $content ?>
</main>
</body>
</html>
