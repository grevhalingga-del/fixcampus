<div class="card shadow-sm login-card">
  <div class="card-body p-4">
    <div class="text-center mb-4">
      <h1 class="h3 text-primary mb-1">FixCampus</h1>
      <p class="text-body-secondary small mb-0">Sistem Informasi Pemeliharaan Fasilitas Kampus</p>
    </div>

    <?php if ($error !== ''): ?>
      <div class="alert alert-danger py-2" role="alert"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= e(url('auth/attempt')) ?>" novalidate>
      <?= Csrf::field() ?>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= e($email) ?>" autocomplete="username" autofocus required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" autocomplete="current-password" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Masuk</button>
    </form>
  </div>
</div>
<p class="text-center text-body-secondary small mt-3 mb-0">Belum punya akun? Hubungi admin sarana-prasarana.</p>
