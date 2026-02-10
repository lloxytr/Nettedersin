<?php
$pageTitle='Öğretmen İçerik | Nettedersin';
$pageDescription='Video/PDF içerik yönetimi';
ob_start(); ?>
<section class="section container"><h1>İçerik Yönetimi</h1><p class="subtitle">Video yükleme, bölümlendirme, PDF/test ekleme, yayın tarih planlama.</p><div class="card"><ul><li>Bulk upload</li><li>Konu-kazanım etiketleme</li><li>Taslak/yayında durumları</li><li>İçerik arşivi</li></ul></div></section>
<?php $content=ob_get_clean(); require __DIR__.'/../../partials/layout.php';
