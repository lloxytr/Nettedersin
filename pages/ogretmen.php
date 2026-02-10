<?php
require __DIR__ . '/../partials/page-shell.php';

$pageTitle = 'Öğretmen Paneli | Nettedersin';
$pageDescription = 'İçerik üretimi ve öğrenci analizi';

ob_start();
renderPanelPage(
    'Öğretmen Stüdyosu',
    'Yeni ders yayınla, test hazırla, öğrenci performansını canlı takip et.',
    Repository::getDashboardMetrics('ogretmen'),
    $teacherFeatures,
    [
        ['href' => '/pages/ogretmen/icerik.php', 'label' => 'İçerik Yönetimi'],
        ['href' => '/pages/ogretmen/sinav.php', 'label' => 'Sınavlar'],
        ['href' => '/pages/ogretmen/analitik.php', 'label' => 'Analitik'],
        ['href' => '/pages/ogretmen/iletisim.php', 'label' => 'İletişim'],
    ]
);
$content = ob_get_clean();
require __DIR__ . '/../partials/layout.php';
