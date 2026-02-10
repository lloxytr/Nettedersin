<h1 class="text-3xl font-bold"><?= e($course['title']) ?></h1>
<p class="text-slate-300 mt-2"><?= e($course['description']) ?></p>
<?php if (!$hasAccess): ?>
<div class="bg-amber-950 border border-amber-700 rounded p-4 mt-4">Bu kurs için aktif paketiniz yok. <a href="/ogrenci/satin-al" class="text-blue-400">Paket satın al</a>.</div>
<?php endif; ?>

<div class="mt-6 space-y-4">
  <?php foreach ($sections as $section): ?>
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-4">
      <h2 class="font-semibold"><?= e($section['title']) ?></h2>
      <ul class="mt-3 space-y-2 text-sm">
      <?php foreach ($section['lessons'] as $lesson): ?>
        <li><a class="text-blue-400" href="/ders/<?= (int)$lesson['id'] ?>"><?= e($lesson['title']) ?></a></li>
      <?php endforeach; ?>
      </ul>
    </div>
  <?php endforeach; ?>
</div>

<script type="application/ld+json">
<?= json_encode($courseSchema, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) ?>
</script>
