<?php
$pageTitle='Öğrenci Satın Alma | Nettedersin';
$pageDescription='Paket satın alma ve fatura';
ob_start(); ?>
<section class="section container"><h1>Satın Alma Merkezi</h1><p class="subtitle">Paket bazlı erişim, abonelik, deneme süresi, fatura görüntüleme ve kullanım takibi.</p><div class="card"><ul><li>İyzico/PayTR ödeme</li><li>Kupon sistemi</li><li>Fatura ekranı</li><li>Abonelik durumu</li></ul></div></section>
<?php $content=ob_get_clean(); require __DIR__.'/../../partials/layout.php';
