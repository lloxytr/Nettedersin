<?php
require __DIR__ . '/partials/data.php';
require __DIR__ . '/partials/components.php';

$pageTitle = 'Nettedersin | Premium Online Eğitim Platformu';
$pageDescription = 'Markahost Eco Linux ile uyumlu PHP tabanlı online eğitim platformu başlangıç sürümü.';

ob_start();
?>
<section class="hero">
    <p class="badge">Markahost Eco Linux uyumlu PHP sürümü</p>
    <h1>Türkiye'nin en iyi online eğitim platformu için hızlı ve şık başlangıç</h1>
    <p>
        Bu sürüm PHP ile çalışır. Öğrenci, öğretmen ve admin panellerinin temel ekranlarını,
        tasarım dilini ve modül planını içerir.
    </p>
</section>

<section class="grid three">
    <?php foreach ($studentFeatures as $group) {
        renderFeatureCard($group);
    } ?>
</section>

<section class="split">
    <article>
        <h2>Öğretmen Üretim Merkezi</h2>
        <div class="grid two">
            <?php foreach ($teacherFeatures as $group) {
                renderFeatureCard($group);
            } ?>
        </div>
    </article>
    <article>
        <h2>Admin Operasyon Merkezi</h2>
        <div class="grid two">
            <?php foreach ($adminFeatures as $group) {
                renderFeatureCard($group);
            } ?>
        </div>
    </article>
</section>
<?php
$content = ob_get_clean();
require __DIR__ . '/partials/layout.php';
