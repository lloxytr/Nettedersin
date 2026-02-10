<?php
$pageTitle='Öğretmen Analitik | Nettedersin';
$pageDescription='Öğrenci başarı analizi';
ob_start(); ?>
<section class="section container"><h1>Öğrenci Analitiği</h1><p class="subtitle">Kim ne izlemiş, nerede bırakmış, hangi testte zorlanmış gibi raporlar.</p><div class="card"><ul><li>Drop-off analizi</li><li>Başarı trend grafiği</li><li>Canlı ders katılımı</li><li>Riskli öğrenci listesi</li></ul></div></section>
<?php $content=ob_get_clean(); require __DIR__.'/../../partials/layout.php';
