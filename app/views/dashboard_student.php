<h1 class="text-3xl font-bold">Öğrenci Paneli</h1>
<div class="grid md:grid-cols-3 gap-4 mt-4">
  <div class="bg-slate-900 border border-slate-800 rounded p-4"><p class="text-slate-400">Genel İlerleme</p><p class="text-2xl font-bold"><?= e((string)$stats['progressPercent']) ?>%</p></div>
  <div class="bg-slate-900 border border-slate-800 rounded p-4"><p class="text-slate-400">Son Ders</p><p class="font-bold"><?= e($stats['lastLesson']['title'] ?? 'Yok') ?></p></div>
  <div class="bg-slate-900 border border-slate-800 rounded p-4"><p class="text-slate-400">Son Test Skoru</p><p class="text-2xl font-bold"><?= e((string)($stats['lastTest']['score'] ?? 0)) ?></p></div>
</div>
<div class="mt-6 flex gap-3"><a href="/kurslar" class="px-3 py-2 bg-blue-600 rounded">Kurslar</a><a href="/ogrenci/satin-al" class="px-3 py-2 bg-slate-700 rounded">Paket Satın Al</a></div>
