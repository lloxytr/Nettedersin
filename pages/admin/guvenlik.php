<?php
$pageTitle='Admin Güvenlik | Nettedersin';
$pageDescription='Sistem güvenliği';
ob_start(); ?>
<section class="section container"><h1>Güvenlik Merkezi</h1><p class="subtitle">Video link koruması, tek cihaz oturum, IP kontrolü, log kayıtları ve alarm yönetimi.</p><div class="card"><ul><li>2FA</li><li>Rate limit</li><li>Audit log</li><li>Riskli giriş tespiti</li></ul></div></section>
<?php $content=ob_get_clean(); require __DIR__.'/../../partials/layout.php';
