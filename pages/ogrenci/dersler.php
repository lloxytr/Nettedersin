<?php
$pageTitle='Öğrenci Dersler | Nettedersin';
$pageDescription='Ders oynatma ve içerik ekranı';
ob_start(); ?>
<section class="section container"><h1>Derslerim</h1><p class="subtitle">Video player, hız kontrolü, PDF notları, yorum-soru ve tamamlandı işaretleme bu modülde yer alır.</p><div class="card"><ul><li>HLS video altyapısı</li><li>Alt yazı + kalite seçimi</li><li>PDF not görüntüleme</li><li>Yorum/soru alanı</li></ul></div></section>
<?php $content=ob_get_clean(); require __DIR__.'/../../partials/layout.php';
