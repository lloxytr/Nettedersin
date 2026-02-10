<div class="max-w-md mx-auto bg-slate-900 border border-slate-800 rounded-xl p-6">
  <h1 class="text-2xl font-bold">Yeni Şifre Belirle</h1>
  <?php if (!empty($error)): ?><p class="text-red-400 mt-3"><?= e($error) ?></p><?php endif; ?>
  <?php if (!empty($message)): ?><p class="text-emerald-400 mt-3"><?= e($message) ?></p><?php endif; ?>
  <form method="post" class="mt-4 space-y-3">
    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
    <input type="hidden" name="token" value="<?= e($token ?? '') ?>">
    <input name="password" type="password" placeholder="Yeni şifre" class="w-full p-2 rounded bg-slate-800 border border-slate-700" required>
    <button class="w-full p-2 rounded bg-blue-600">Güncelle</button>
  </form>
</div>
