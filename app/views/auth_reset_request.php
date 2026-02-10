<div class="max-w-md mx-auto bg-slate-900 border border-slate-800 rounded-xl p-6">
  <h1 class="text-2xl font-bold">Şifre Sıfırlama</h1>
  <?php if (!empty($message)): ?><p class="text-emerald-400 mt-3"><?= e($message) ?></p><?php endif; ?>
  <form method="post" class="mt-4 space-y-3">
    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
    <input name="email" type="email" placeholder="E-posta" class="w-full p-2 rounded bg-slate-800 border border-slate-700" required>
    <button class="w-full p-2 rounded bg-blue-600">Reset Linki Üret</button>
  </form>
</div>
