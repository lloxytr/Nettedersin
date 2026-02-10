<?php
require __DIR__ . '/../partials/page-shell.php';

$pageTitle = 'Admin Paneli | Nettedersin';
$pageDescription = 'Operasyon, ödeme, güvenlik ve raporlama merkezi';

ob_start();
renderPanelPage(
    'Admin Control Center',
    'Kullanıcı, içerik, ödeme ve güvenlik operasyonlarını tek merkezden yönetin.',
    Repository::getDashboardMetrics('admin'),
    $adminFeatures,
    [
        ['href' => '/pages/admin/kullanicilar.php', 'label' => 'Kullanıcılar'],
        ['href' => '/pages/admin/odeme.php', 'label' => 'Ödeme'],
        ['href' => '/pages/admin/rapor.php', 'label' => 'Raporlar'],
        ['href' => '/pages/admin/guvenlik.php', 'label' => 'Güvenlik'],
    ]
);
$content = ob_get_clean();
require __DIR__ . '/../partials/layout.php';
