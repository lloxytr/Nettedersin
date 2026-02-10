<?php
$pageTitle='Öğretmen Sınav | Nettedersin';
$pageDescription='Soru bankası ve test';
ob_start(); ?>
<section class="section container"><h1>Sınav & Test</h1><p class="subtitle">Soru bankası oluşturma, test üretimi, otomatik puanlama ve video çözüm eşleme.</p><div class="card"><ul><li>Zorluk seviyesi</li><li>Konu bazlı filtre</li><li>Optik şablon</li><li>Çözüm video bağlantısı</li></ul></div></section>
<?php $content=ob_get_clean(); require __DIR__.'/../../partials/layout.php';
