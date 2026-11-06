<?php /** Variabel: $route, $label, $q. Judul halaman, kotak cari, dan tombol Tambah. */ ?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
  <h1 class="h4 mb-0">Data <?= e($label) ?></h1>
  <a href="<?= e(url("$route/create")) ?>" class="btn btn-primary">+ Tambah <?= e($label) ?></a>
</div>
<form method="get" action="index.php" class="row g-2 mb-3">
  <input type="hidden" name="r" value="<?= e($route) ?>/index">
  <div class="col-sm-8 col-md-6">
    <input type="search" name="q" value="<?= e($q) ?>" class="form-control" placeholder="Cari <?= e(strtolower($label)) ?>..." aria-label="Cari">
  </div>
  <div class="col-auto">
    <button class="btn btn-outline-primary" type="submit">Cari</button>
    <?php if ($q !== ''): ?><a class="btn btn-link" href="<?= e(url("$route/index")) ?>">Reset</a><?php endif; ?>
  </div>
</form>
