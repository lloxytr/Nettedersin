<?php
$pageTitle='Öğrenci Motivasyon | Nettedersin';
$pageDescription='Takip ve rozet modülü';
ob_start(); ?>
<section class="section container"><h1>Takip & Motivasyon</h1><p class="subtitle">Günlük süre, haftalık gelişim tablosu, rozet sistemi ve hedef yönetimi.</p><div class="card"><ul><li>Hedef belirleme</li><li>Seri günü takibi</li><li>Haftalık grafikler</li><li>Başarı yüzdesi</li></ul></div></section>
<?php $content=ob_get_clean(); require __DIR__.'/../../partials/layout.php';
