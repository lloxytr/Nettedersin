<?php
require __DIR__ . '/../partials/data.php';
require __DIR__ . '/../partials/components.php';

$pageTitle = 'Öğrenci Paneli | Nettedersin';
$pageDescription = 'Ders, test, motivasyon, etkileşim ve satın alma ekranları.';

ob_start();
?>
<section class="hero small">
    <h1>Öğrenci Paneli</h1>
    <p>Ders izleme, test çözme, hedef takibi ve satın alma süreçlerinin toplandığı panel.</p>
</section>
<section class="grid three">
    <?php foreach ($studentFeatures as $group) {
        renderFeatureCard($group);
    } ?>
</section>
<?php
$content = ob_get_clean();
require __DIR__ . '/../partials/layout.php';
