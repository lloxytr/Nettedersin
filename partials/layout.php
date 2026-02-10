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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/style.css">
</head>
<body class="theme-dark-soft">
<header class="header">
    <div class="container nav">
        <a class="brand" href="/index.php">Nettedersin</a>
        <nav>
            <a href="/index.php">Ana Sayfa</a>
            <a href="/pages/ogrenci.php">Öğrenci</a>
            <a href="/pages/ogretmen.php">Öğretmen</a>
            <a href="/pages/admin.php">Admin</a>
            <a class="cta-link" href="/index.php#pricing">Paketler</a>
        </nav>
    </div>
    <div class="subnav container">
        <a href="/pages/ogrenci/dersler.php">Dersler</a>
        <a href="/pages/ogrenci/testler.php">Testler</a>
        <a href="/pages/ogretmen/icerik.php">İçerik Stüdyosu</a>
        <a href="/pages/admin/odeme.php">Ödeme</a>
        <a href="/pages/admin/guvenlik.php">Güvenlik</a>
    </div>
</header>
<main>
<?= $content ?>
</main>
<footer class="footer">
    <div class="container footer-inner">
        <p>© <?= date('Y') ?> Nettedersin - Türkiye'nin premium online eğitim platformu vizyonu.</p>
        <p>WhatsApp Destek • KVKK • Mesafeli Satış Sözleşmesi • ISO27001 hedefi</p>
    </div>
</footer>
</body>
</html>
