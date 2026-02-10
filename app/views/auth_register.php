<div class="max-w-md mx-auto bg-slate-900 border border-slate-800 rounded-xl p-6">
  <h1 class="text-2xl font-bold">Kayıt Ol</h1>
  <?php if (!empty($error)): ?><p class="text-red-400 mt-3"><?= e($error) ?></p><?php endif; ?>
  <form method="post" class="mt-4 space-y-3">
    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
    <input name="name" placeholder="Ad Soyad" class="w-full p-2 rounded bg-slate-800 border border-slate-700" required>
    <input name="email" type="email" placeholder="E-posta" class="w-full p-2 rounded bg-slate-800 border border-slate-700" required>
    <input name="password" type="password" placeholder="Şifre (min 8)" class="w-full p-2 rounded bg-slate-800 border border-slate-700" required>
    <select name="role" class="w-full p-2 rounded bg-slate-800 border border-slate-700">
      <option value="student">Öğrenci</option>
      <option value="teacher">Öğretmen</option>
    </select>
    <button class="w-full p-2 rounded bg-blue-600">Hesap Oluştur</button>
  </form>
</div>
