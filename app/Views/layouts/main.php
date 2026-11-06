<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title ?? 'FixCampus') ?> - FixCampus</title>
  <link rel="stylesheet" href="<?= e(asset('css/bootstrap.min.css')) ?>">
  <link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>">
</head>
<body>
<?php $user = Auth::user(); $isAdmin = Auth::role() === 'admin'; ?>
<nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="<?= e(url('dashboard/index')) ?>">FixCampus</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuUtama" aria-controls="menuUtama" aria-expanded="false" aria-label="Buka menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menuUtama">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link<?= $active === 'dashboard' ? ' active' : '' ?>" href="<?= e(url('dashboard/index')) ?>">Beranda</a>
        </li>
        <?php if ($isAdmin): ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle<?= in_array($active, ['kategori', 'lokasi', 'fasilitas', 'pengguna'], true) ? ' active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Data Master</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item<?= $active === 'kategori' ? ' active' : '' ?>" href="<?= e(url('kategori/index')) ?>">Kategori</a></li>
            <li><a class="dropdown-item<?= $active === 'lokasi' ? ' active' : '' ?>" href="<?= e(url('lokasi/index')) ?>">Lokasi</a></li>
            <li><a class="dropdown-item<?= $active === 'fasilitas' ? ' active' : '' ?>" href="<?= e(url('fasilitas/index')) ?>">Fasilitas</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item<?= $active === 'pengguna' ? ' active' : '' ?>" href="<?= e(url('pengguna/index')) ?>">Pengguna</a></li>
          </ul>
        </li>
        <?php endif; ?>
      </ul>
      <form method="post" action="<?= e(url('auth/logout')) ?>" class="d-flex align-items-center gap-2 mb-0">
        <?= Csrf::field() ?>
        <span class="navbar-text text-white">
          <?= e($user['nama'] ?? '') ?>
          <span class="badge text-bg-light ms-1"><?= e(User::ROLES[$user['role'] ?? ''] ?? '') ?></span>
        </span>
        <button type="submit" class="btn btn-sm btn-outline-light">Keluar</button>
      </form>
    </div>
  </div>
</nav>

<main class="container py-4">
  <?php require __DIR__ . '/../partials/flash.php'; ?>
  <?= $content ?>
</main>

<footer class="container pb-4 text-center text-body-secondary small">
  FixCampus &middot; Sistem Informasi Pemeliharaan Fasilitas Kampus
</footer>
<script src="<?= e(asset('js/bootstrap.bundle.min.js')) ?>"></script>
</body>
</html>
