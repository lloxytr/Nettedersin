<?php
$pageTitle='Öğretmen İletişim | Nettedersin';
$pageDescription='Toplu mesaj ve duyuru';
ob_start(); ?>
<section class="section container"><h1>İletişim Merkezi</h1><p class="subtitle">Canlı ders açma, ders içi chat, toplu mesaj gönderme ve duyuru paylaşımı.</p><div class="card"><ul><li>E-posta + site içi bildirim</li><li>Segment bazlı mesaj</li><li>Canlı ders daveti</li><li>Öncelikli soru kutusu</li></ul></div></section>
<?php $content=ob_get_clean(); require __DIR__.'/../../partials/layout.php';
