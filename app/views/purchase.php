<h1 class="text-3xl font-bold">Paket Satın Al</h1>
<div class="grid md:grid-cols-3 gap-4 mt-4">
<?php foreach ($plans as $p): ?>
  <div class="bg-slate-900 border border-slate-800 rounded p-4">
    <h3 class="font-semibold"><?= e($p['name']) ?></h3>
    <p class="text-2xl font-black mt-2">₺<?= e((string)$p['price']) ?></p>
    <p class="text-slate-400 text-sm"><?= (int)$p['duration_days'] ?> gün erişim</p>
    <form method="post" action="/ogrenci/satin-al" class="mt-4">
      <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="plan_id" value="<?= (int)$p['id'] ?>">
      <button class="w-full p-2 bg-blue-600 rounded">Manuel Sipariş Oluştur</button>
    </form>
  </div>
<?php endforeach; ?>
</div>

<h2 class="text-xl font-semibold mt-8">Sipariş Geçmişi</h2>
<div class="space-y-2 mt-2">
<?php foreach ($orders as $o): ?>
  <div class="bg-slate-900 border border-slate-800 rounded p-3">#<?= (int)$o['id'] ?> - <?= e($o['plan_name']) ?> - <?= e($o['status']) ?> - <?= e($o['created_at']) ?></div>
<?php endforeach; ?>
</div>
