<?php
require __DIR__ . '/../partials/data.php';
require __DIR__ . '/../partials/components.php';

$pageTitle = 'Öğretmen Paneli | Nettedersin';
$pageDescription = 'İçerik üretimi, test yönetimi ve öğrenci performans takibi.';

ob_start();
?>
<section class="hero small">
    <h1>Öğretmen Paneli</h1>
    <p>Video yükleme, soru bankası, yayın planlama ve öğrenci analitiği paneli.</p>
</section>
<section class="grid three">
    <?php foreach ($teacherFeatures as $group) {
        renderFeatureCard($group);
    } ?>
</section>
<?php
$content = ob_get_clean();
require __DIR__ . '/../partials/layout.php';
