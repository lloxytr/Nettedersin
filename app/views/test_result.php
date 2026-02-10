<h1 class="text-3xl font-bold">Test Sonucu</h1>
<div class="grid md:grid-cols-3 gap-4 mt-4">
  <div class="bg-slate-900 p-4 rounded border border-slate-800"><p>Skor</p><p class="text-2xl font-black"><?= e((string)$result['score']) ?></p></div>
  <div class="bg-slate-900 p-4 rounded border border-slate-800"><p>Doğru</p><p class="text-2xl font-black"><?= (int)$result['correct_count'] ?></p></div>
  <div class="bg-slate-900 p-4 rounded border border-slate-800"><p>Yanlış</p><p class="text-2xl font-black"><?= (int)$result['wrong_count'] ?></p></div>
</div>
<a href="/dashboard" class="inline-block mt-6 px-4 py-2 bg-blue-600 rounded">Panele dön</a>
