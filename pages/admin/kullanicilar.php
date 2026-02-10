<?php
$pageTitle='Admin Kullanıcılar | Nettedersin';
$pageDescription='Rol ve yetki yönetimi';
ob_start(); ?>
<section class="section container"><h1>Kullanıcı Yönetimi</h1><p class="subtitle">Öğrenci/öğretmen/admin rolleri, hesap dondurma, yetki matrisi.</p><div class="card"><ul><li>Rol bazlı erişim</li><li>Hesap kilitleme</li><li>KYC/KVKK kayıtları</li><li>Toplu kullanıcı işlemleri</li></ul></div></section>
<?php $content=ob_get_clean(); require __DIR__.'/../../partials/layout.php';
