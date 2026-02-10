<?php
$pageTitle='Öğrenci Testler | Nettedersin';
$pageDescription='Test ve deneme modülü';
ob_start(); ?>
<section class="section container"><h1>Test & Deneme Merkezi</h1><p class="subtitle">Konu sonu test, zamanlı deneme, otomatik puanlama, doğru/yanlış analiz ve video çözüm.</p><div class="card"><ul><li>Soru bankası</li><li>Yanlış defteri</li><li>Zamanlayıcı</li><li>Çözüm videosu</li></ul></div></section>
<?php $content=ob_get_clean(); require __DIR__.'/../../partials/layout.php';
