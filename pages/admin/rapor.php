<?php
$pageTitle='Admin Raporlar | Nettedersin';
$pageDescription='İş zekası ve raporlama';
ob_start(); ?>
<section class="section container"><h1>Raporlama</h1><p class="subtitle">Kullanıcı büyümesi, satış raporları, en çok izlenen dersler, aktif/pasif kullanıcılar.</p><div class="card"><ul><li>WAU/MAU</li><li>LTV/CAC</li><li>En çok satan paket</li><li>En iyi öğretmen raporu</li></ul></div></section>
<?php $content=ob_get_clean(); require __DIR__.'/../../partials/layout.php';
