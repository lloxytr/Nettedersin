<?php
require __DIR__ . '/partials/data.php';
require __DIR__ . '/partials/components.php';
require __DIR__ . '/lib/Repository.php';

$pageTitle = 'Nettedersin | Premium Online Eğitim Platformu';
$pageDescription = 'xbowtie seviyesinde premium UX hedefiyle, Markahost uyumlu PHP online eğitim platformu başlangıcı.';

$roadmap = Repository::getRoadmap();
ob_start();
?>
<section class="hero">
    <div class="container hero-grid">
        <div>
            <p class="badge">Hafif dark premium tema • Markahost Eco Linux uyumlu</p>
            <h1>Türkiye'nin en iddialı online eğitim platformunu üretime hazır kurulumla teslim ediyoruz.</h1>
            <p class="subtitle">
                Öğrenci, öğretmen ve admin panelleri; ödeme, içerik, test, raporlama ve güvenlik modülleriyle birlikte.
            </p>
            <div class="hero-actions">
                <a class="btn primary" href="#pricing">Paketleri Gör</a>
                <a class="btn ghost" href="/pages/admin.php">Admin Panelini Aç</a>
            </div>
        </div>
        <div class="hero-panel">
            <h3>Canlı Sistem Durumu (Mockup)</h3>
            <ul>
                <li><span>Bugünkü Aktif Öğrenci</span><strong>18.420</strong></li>
                <li><span>Canlı Ders Sayısı</span><strong>124</strong></li>
                <li><span>Toplam Çözülen Soru</span><strong>2.3M</strong></li>
                <li><span>Başarı Artışı (30 gün)</span><strong>+%21</strong></li>
            </ul>
        </div>
    </div>
</section>

<section class="section container">
    <h2>Rakamlarla Nettedersin</h2>
    <div class="metrics-grid">
        <?php foreach ($platformMetrics as $metric) {
            renderMetric($metric);
        } ?>
    </div>
</section>

<section class="section container">
    <h2>Öğrenci Deneyimi</h2>
    <div class="grid three">
        <?php foreach ($studentFeatures as $group) {
            renderFeatureCard($group);
        } ?>
    </div>
</section>

<section class="section dark">
    <div class="container">
        <h2>Öğretmen + Admin Operasyon Gücü</h2>
        <div class="grid two">
            <div>
                <h3 class="section-sub">Öğretmen Paneli</h3>
                <div class="grid two compact">
                    <?php foreach ($teacherFeatures as $group) {
                        renderFeatureCard($group);
                    } ?>
                </div>
            </div>
            <div>
                <h3 class="section-sub">Admin Paneli</h3>
                <div class="grid two compact">
                    <?php foreach ($adminFeatures as $group) {
                        renderFeatureCard($group);
                    } ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section container">
    <h2>Üretim Yol Haritası (Tam Kapsam)</h2>
    <div class="grid two compact">
        <?php foreach ($roadmap as $item) { ?>
            <article class="value-card">
                <h3><?= htmlspecialchars($item['phase']) ?> • <?= htmlspecialchars($item['title']) ?></h3>
                <p>Durum: <?= htmlspecialchars($item['status']) ?></p>
            </article>
        <?php } ?>
    </div>
</section>

<section class="section container">
    <h2>Neden Premium Görünür?</h2>
    <div class="values-grid">
        <?php foreach ($premiumSections as $item) {
            renderValueCard($item);
        } ?>
    </div>
</section>

<section class="section container" id="pricing">
    <h2>Paketler</h2>
    <div class="plans-grid">
        <?php foreach ($plans as $plan) {
            renderPlanCard($plan);
        } ?>
    </div>
</section>

<section class="section container faq-wrap">
    <h2>Sık Sorulan Sorular</h2>
    <?php foreach ($faqItems as $faq) {
        renderFaq($faq);
    } ?>
</section>

<section class="section container">
    <h2>Veritabanı Kurulum Bilgisi</h2>
    <div class="card">
        <ul>
            <li>DB: <strong>nettepfg_db</strong></li>
            <li>Kullanıcı: <strong>nettepfg_user</strong></li>
            <li>Şifre: <strong>Sifre1234.</strong></li>
            <li>Kurulum: <code>setup/schema.sql</code> ardından <code>setup/seed.sql</code> import edin.</li>
        </ul>
    </div>
</section>

<section class="section cta">
    <div class="container cta-inner">
        <div>
            <h2>Hedef: Türkiye'nin en iyi eğitim deneyimi</h2>
            <p>İstersen bir sonraki adımda giriş sistemi ve gerçek CRUD ekranlarını da bağlıyorum.</p>
        </div>
        <a class="btn primary" href="/pages/admin/guvenlik.php">Güvenlik Merkezine Git</a>
    </div>
</section>
<?php
$content = ob_get_clean();
require __DIR__ . '/partials/layout.php';
