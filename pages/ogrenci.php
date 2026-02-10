<?php
require __DIR__ . '/../partials/page-shell.php';

$pageTitle = 'Öğrenci Paneli | Nettedersin';
$pageDescription = 'Ders, test, hedef ve motivasyon paneli';

ob_start();
renderPanelPage(
    'Öğrenci Dashboard',
    'Kaldığın yerden devam et, testlerini çöz, yanlış defterini kapat.',
    Repository::getDashboardMetrics('ogrenci'),
    $studentFeatures,
    [
        ['href' => '/pages/ogrenci/dersler.php', 'label' => 'Derslerim'],
        ['href' => '/pages/ogrenci/testler.php', 'label' => 'Testlerim'],
        ['href' => '/pages/ogrenci/motivasyon.php', 'label' => 'Motivasyon'],
        ['href' => '/pages/ogrenci/satin-al.php', 'label' => 'Paket Satın Al'],
    ]
);
$content = ob_get_clean();
require __DIR__ . '/../partials/layout.php';
