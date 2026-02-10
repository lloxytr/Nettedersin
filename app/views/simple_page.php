<h1 class="text-3xl font-bold"><?= e($title) ?></h1>
<p class="text-slate-300 mt-3"><?= e($content ?? '') ?></p>
<?php if (!empty($schemaJson)): ?>
<script type="application/ld+json"><?= $schemaJson ?></script>
<?php endif; ?>
