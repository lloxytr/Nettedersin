<h1 class="text-3xl font-bold mb-6">Kurslar</h1>
<div class="grid md:grid-cols-3 gap-4">
  <?php foreach ($courses as $course): ?>
    <article class="bg-slate-900 border border-slate-800 rounded-xl p-4">
      <h2 class="font-semibold"><?= e($course['title']) ?></h2>
      <p class="text-slate-400 text-sm mt-1">Öğretmen: <?= e($course['teacher_name']) ?></p>
      <p class="text-slate-400 text-sm mt-2 line-clamp-2"><?= e($course['description']) ?></p>
      <a class="mt-4 inline-block text-blue-400" href="/kurs/<?= e($course['slug']) ?>">Kursa Git</a>
    </article>
  <?php endforeach; ?>
</div>
