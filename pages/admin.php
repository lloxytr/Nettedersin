<?php
require __DIR__ . '/../partials/data.php';
require __DIR__ . '/../partials/components.php';

$pageTitle = 'Admin Paneli | Nettedersin';
$pageDescription = 'Kullanıcı, ödeme, içerik ve güvenlik yönetimi merkezi.';

ob_start();
?>
<section class="hero small">
    <h1>Admin Paneli</h1>
    <p>Rol yönetimi, ödeme süreçleri, raporlama ve güvenlik kontrolleri.</p>
</section>
<section class="grid three">
    <?php foreach ($adminFeatures as $group) {
        renderFeatureCard($group);
    } ?>
</section>
<?php
$content = ob_get_clean();
require __DIR__ . '/../partials/layout.php';
