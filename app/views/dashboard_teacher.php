<h1 class="text-3xl font-bold">Öğretmen Paneli</h1>
<div class="grid md:grid-cols-2 gap-4 mt-4">
  <div class="bg-slate-900 border border-slate-800 rounded p-4">
    <h2 class="font-semibold mb-2">Kurs Oluştur</h2>
    <form method="post" action="/ogretmen/kurs" class="space-y-2">
      <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
      <input name="title" placeholder="Kurs başlığı" class="w-full p-2 rounded bg-slate-800 border border-slate-700" required>
      <textarea name="description" placeholder="Açıklama" class="w-full p-2 rounded bg-slate-800 border border-slate-700" required></textarea>
      <button class="w-full p-2 bg-blue-600 rounded">Kurs Oluştur</button>
    </form>
  </div>
  <div class="bg-slate-900 border border-slate-800 rounded p-4">
    <h2 class="font-semibold mb-2">Soru Bankası - Yeni Soru</h2>
    <form method="post" action="/ogretmen/question" class="space-y-2 text-sm">
      <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
      <textarea name="question_text" placeholder="Soru metni" class="w-full p-2 rounded bg-slate-800 border border-slate-700" required></textarea>
      <input name="option_a" placeholder="A" class="w-full p-2 rounded bg-slate-800 border border-slate-700" required>
      <input name="option_b" placeholder="B" class="w-full p-2 rounded bg-slate-800 border border-slate-700" required>
      <input name="option_c" placeholder="C" class="w-full p-2 rounded bg-slate-800 border border-slate-700" required>
      <input name="option_d" placeholder="D" class="w-full p-2 rounded bg-slate-800 border border-slate-700" required>
      <div class="flex gap-2">
        <input name="topic" placeholder="Konu" class="flex-1 p-2 rounded bg-slate-800 border border-slate-700">
        <select name="correct_option" class="p-2 rounded bg-slate-800 border border-slate-700"><option>A</option><option>B</option><option>C</option><option>D</option></select>
      </div>
      <button class="w-full p-2 bg-emerald-600 rounded">Soru Ekle</button>
    </form>
  </div>
</div>

<div class="mt-6 bg-slate-900 border border-slate-800 rounded p-4">
  <h2 class="font-semibold mb-2">Test Oluştur</h2>
  <form method="post" action="/ogretmen/test" class="space-y-2">
    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
    <select name="course_id" class="w-full p-2 rounded bg-slate-800 border border-slate-700" required>
      <option value="">Kurs seç</option>
      <?php foreach ($courses as $c): ?><option value="<?= (int)$c['id'] ?>"><?= e($c['title']) ?></option><?php endforeach; ?>
    </select>
    <input name="title" placeholder="Test başlığı" class="w-full p-2 rounded bg-slate-800 border border-slate-700" required>
    <input name="duration_seconds" type="number" value="1800" class="w-full p-2 rounded bg-slate-800 border border-slate-700" required>
    <p class="text-sm text-slate-400">Son eklenen 10 soru otomatik bağlanır (hızlı üretim modu).</p>
    <button class="w-full p-2 bg-blue-600 rounded">Test Oluştur</button>
  </form>
</div>

<div class="mt-6 grid md:grid-cols-2 gap-4">
<?php foreach ($courses as $c): ?>
  <div class="bg-slate-900 border border-slate-800 rounded p-4">
    <h3 class="font-bold"><?= e($c['title']) ?></h3><p class="text-slate-400 text-sm">Durum: <?= e($c['status']) ?></p>
    <form method="post" action="/ogretmen/section" class="mt-3 flex gap-2">
      <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="course_id" value="<?= (int)$c['id'] ?>">
      <input name="title" placeholder="Yeni bölüm" class="flex-1 p-2 rounded bg-slate-800 border border-slate-700" required>
      <button class="px-3 bg-slate-700 rounded">Ekle</button>
    </form>
  </div>
<?php endforeach; ?>
</div>

<div class="mt-6 bg-slate-900 border border-slate-800 rounded p-4">
  <h2 class="font-semibold mb-2">Son Sorular</h2>
  <ul class="text-sm text-slate-300 space-y-1">
    <?php foreach ($questions as $q): ?>
      <li>#<?= (int)$q['id'] ?> - <?= e($q['question_text']) ?> (<?= e($q['topic'] ?? '-') ?>)</li>
    <?php endforeach; ?>
  </ul>
</div>
