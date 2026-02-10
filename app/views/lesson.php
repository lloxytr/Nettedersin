<h1 class="text-2xl font-bold"><?= e($lesson['title']) ?></h1>
<p class="text-slate-400 text-sm"><?= e($lesson['course_title']) ?></p>
<?php if (!$hasAccess): ?>
<div class="bg-amber-950 border border-amber-700 rounded p-4 mt-4">Ders erişimi için paket satın almanız gerekiyor.</div>
<?php else: ?>
<div class="grid md:grid-cols-3 gap-4 mt-4">
  <div class="md:col-span-2 bg-slate-900 border border-slate-800 rounded-xl p-4" x-data="{rate:1}">
    <video id="video" controls preload="metadata" class="w-full rounded" <?= !empty($lesson['video_path']) ? 'src="' . e($lesson['video_path']) . '"' : '' ?>></video>
    <div class="mt-2 flex items-center gap-2 text-sm">
      <label>Hız</label>
      <select x-model="rate" @change="document.getElementById('video').playbackRate=rate" class="bg-slate-800 border border-slate-700 rounded p-1">
        <option>0.75</option><option>1</option><option>1.25</option><option>1.5</option><option>2</option>
      </select>
    </div>
    <form method="post" action="/ders/<?= (int)$lesson['id'] ?>/ilerleme" class="mt-4 flex gap-2">
      <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="watched_seconds" id="watched_seconds" value="<?= (int)($progress['watched_seconds'] ?? 0) ?>">
      <button name="completed" value="0" class="px-3 py-2 bg-slate-700 rounded">İlerlemeyi Kaydet</button>
      <button name="completed" value="1" class="px-3 py-2 bg-emerald-600 rounded">Tamamlandı</button>
    </form>
  </div>
  <aside class="bg-slate-900 border border-slate-800 rounded-xl p-4">
    <h3 class="font-semibold">Ekler</h3>
    <?php if (!empty($lesson['pdf_path'])): ?><a class="text-blue-400 text-sm" href="<?= e($lesson['pdf_path']) ?>" target="_blank">PDF Notu Aç</a><?php endif; ?>
    <p class="text-sm mt-2">İzlenen: <?= (int)($progress['watched_seconds'] ?? 0) ?> sn</p>
  </aside>
</div>

<div class="mt-6 bg-slate-900 border border-slate-800 rounded-xl p-4">
  <h3 class="font-semibold">Yorum / Soru</h3>
  <form method="post" action="/ders/<?= (int)$lesson['id'] ?>/yorum" class="mt-3 space-y-2">
    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
    <textarea name="comment_body" required class="w-full h-24 rounded bg-slate-800 border border-slate-700 p-2"></textarea>
    <button class="px-3 py-2 bg-blue-600 rounded">Gönder (moderasyonlu)</button>
  </form>
  <div class="mt-4 space-y-2">
    <?php foreach ($comments as $c): ?>
      <div class="border border-slate-800 rounded p-2 text-sm"><strong><?= e($c['full_name']) ?>:</strong> <?= e($c['comment_body']) ?></div>
    <?php endforeach; ?>
  </div>
</div>
<script>
const v=document.getElementById('video');
if(v){v.addEventListener('timeupdate',()=>{document.getElementById('watched_seconds').value=Math.floor(v.currentTime);});}
</script>
<?php endif; ?>
