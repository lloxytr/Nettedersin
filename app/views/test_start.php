<h1 class="text-3xl font-bold"><?= e($test['title']) ?></h1>
<form method="post" action="/test/<?= (int)$test['id'] ?>/submit" class="space-y-4 mt-4">
  <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
  <input type="hidden" name="duration_seconds" id="duration_seconds" value="0">
  <?php foreach ($questions as $i => $q): ?>
  <div class="bg-slate-900 border border-slate-800 rounded p-4">
    <p class="font-semibold"><?= ($i+1) ?>) <?= e($q['question_text']) ?></p>
    <?php foreach (['A','B','C','D'] as $opt): $field='option_' . strtolower($opt); ?>
      <label class="block mt-1"><input type="radio" name="q[<?= (int)$q['id'] ?>]" value="<?= $opt ?>"> <?= $opt ?>) <?= e($q[$field]) ?></label>
    <?php endforeach; ?>
  </div>
  <?php endforeach; ?>
  <button class="px-4 py-2 bg-blue-600 rounded">Testi Bitir</button>
</form>
<script>
const started=Date.now();
document.querySelector('form').addEventListener('submit',()=>{
  document.getElementById('duration_seconds').value=Math.floor((Date.now()-started)/1000);
});
</script>
