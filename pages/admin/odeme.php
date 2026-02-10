<?php
$pageTitle='Admin Ödeme | Nettedersin';
$pageDescription='Ödeme ve paket operasyonu';
ob_start(); ?>
<section class="section container"><h1>Ödeme & Paket</h1><p class="subtitle">İyzico/PayTR entegrasyonu, paketler, kuponlar, iade ve iptal yönetimi.</p><div class="card"><ul><li>Satış akışı</li><li>Kupon kurguları</li><li>İade otomasyonu</li><li>Fatura yönetimi</li></ul></div></section>
<?php $content=ob_get_clean(); require __DIR__.'/../../partials/layout.php';
