<?php foreach (pullFlashes() as $f): ?>
  <div class="alert alert-<?= e($f['type']) ?> alert-dismissible fade show" role="alert">
    <?= e($f['msg']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
  </div>
<?php endforeach; ?>
