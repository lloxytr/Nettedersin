<?php $metaTitle = $metaTitle ?? 'Nettedersin LMS'; $metaDesc = $metaDesc ?? 'Online eğitim platformu'; ?>
<!doctype html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($metaTitle) ?></title>
  <meta name="description" content="<?= e($metaDesc) ?>">
  <link rel="canonical" href="<?= e(($config['base_url'] ?? '') . ($_SERVER['REQUEST_URI'] ?? '/')) ?>">
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen">
<header class="border-b border-slate-800 bg-slate-900/80 backdrop-blur sticky top-0 z-40">
  <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
    <a href="/" class="font-extrabold text-blue-400">Nettedersin LMS</a>
    <nav class="text-sm flex gap-4 items-center">
      <a href="/kurslar" class="hover:text-white text-slate-300">Kurslar</a>
      <a href="/blog/ornek" class="hover:text-white text-slate-300">Blog</a>
      <?php if (!empty($_SESSION['user'])): ?>
        <a href="/dashboard" class="hover:text-white text-slate-300">Panel</a>
        <form method="post" action="/cikis" class="inline">
          <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
          <button class="px-3 py-1 rounded bg-slate-800 hover:bg-slate-700">Çıkış</button>
        </form>
      <?php else: ?>
        <a href="/giris" class="hover:text-white text-slate-300">Giriş</a>
        <a href="/kayit" class="px-3 py-1 rounded bg-blue-600 hover:bg-blue-500">Kayıt Ol</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<main class="max-w-6xl mx-auto px-4 py-8">
<?php include $viewPath; ?>
</main>

<footer class="border-t border-slate-800 py-6 text-xs text-slate-400">
  <div class="max-w-6xl mx-auto px-4 flex flex-wrap gap-4 justify-between">
    <p>© <?= date('Y') ?> Nettedersin</p>
    <div class="flex gap-3">
      <a href="/sitemap.xml" class="hover:text-slate-200">Sitemap</a>
      <a href="/robots.txt" class="hover:text-slate-200">Robots</a>
    </div>
  </div>
</footer>
</body>
</html>
