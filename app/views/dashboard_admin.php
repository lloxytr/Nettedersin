<h1 class="text-3xl font-bold">Admin Paneli</h1>

<section class="mt-4">
  <h2 class="text-xl font-semibold mb-2">İçerik Onay</h2>
  <div class="space-y-2">
    <?php foreach ($pendingCourses as $c): ?>
    <div class="bg-slate-900 border border-slate-800 rounded p-3 flex items-center justify-between">
      <div><strong><?= e($c['title']) ?></strong> <span class="text-slate-400 text-sm">(<?= e($c['teacher_name']) ?>)</span></div>
      <form method="post" action="/admin/course-status" class="flex gap-2">
        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="course_id" value="<?= (int)$c['id'] ?>">
        <button name="status" value="published" class="px-3 py-1 bg-emerald-600 rounded">Yayınla</button>
        <button name="status" value="draft" class="px-3 py-1 bg-slate-700 rounded">Taslağa Al</button>
      </form>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="mt-8">
  <h2 class="text-xl font-semibold mb-2">Ödeme / Sipariş</h2>
  <div class="space-y-2">
    <?php foreach ($orders as $o): ?>
    <div class="bg-slate-900 border border-slate-800 rounded p-3 flex items-center justify-between">
      <div>#<?= (int)$o['id'] ?> - <?= e($o['full_name']) ?> - <?= e($o['plan_name']) ?> - <strong><?= e($o['status']) ?></strong></div>
      <?php if ($o['status'] !== 'paid'): ?>
      <form method="post" action="/admin/order-paid">
        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="order_id" value="<?= (int)$o['id'] ?>">
        <button class="px-3 py-1 bg-blue-600 rounded">Manuel Paid</button>
      </form>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="mt-8">
  <h2 class="text-xl font-semibold mb-2">Kullanıcılar</h2>
  <div class="space-y-2">
    <?php foreach ($users as $u): ?>
    <div class="bg-slate-900 border border-slate-800 rounded p-3 flex items-center justify-between">
      <div><?= e($u['full_name']) ?> (<?= e($u['role']) ?>) - <?= e($u['status']) ?></div>
      <form method="post" action="/admin/user-status" class="flex gap-2">
        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="user_id" value="<?= (int)$u['id'] ?>">
        <button name="status" value="active" class="px-3 py-1 bg-emerald-700 rounded">Aktif</button>
        <button name="status" value="frozen" class="px-3 py-1 bg-amber-700 rounded">Dondur</button>
      </form>
    </div>
    <?php endforeach; ?>
  </div>
</section>
