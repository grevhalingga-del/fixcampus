<h1 class="h4 mb-1">Beranda</h1>
<p class="text-body-secondary">Halo, <?= e(Auth::user()['nama']) ?>. Anda masuk sebagai <strong><?= e(User::ROLES[Auth::role()]) ?></strong>.</p>

<?php if ($stats): ?>
  <div class="row g-3 mb-4">
    <?php foreach ($stats as $s): ?>
      <div class="col-6 col-lg-3">
        <a class="card stat-card text-decoration-none h-100" href="<?= e(url($s['route'])) ?>">
          <div class="card-body">
            <div class="text-body-secondary small"><?= e($s['label']) ?></div>
            <div class="display-6 fw-semibold text-primary"><?= (int) $s['jumlah'] ?></div>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="card">
    <div class="card-body">
      <h2 class="h6">Data master yang dapat dikelola</h2>
      <p class="mb-0 text-body-secondary">Kelola kategori, lokasi, fasilitas, dan akun pengguna melalui menu <strong>Data Master</strong>. Data ini menjadi pilihan saat pelapor membuat laporan kerusakan.</p>
    </div>
  </div>
<?php else: ?>
  <div class="card">
    <div class="card-body">
      <h2 class="h6">Akses sesuai peran</h2>
      <p class="mb-0 text-body-secondary">Fitur untuk peran Anda (<?= e(User::ROLES[Auth::role()]) ?>) disediakan pada tahap pengembangan berikutnya. Pengelolaan data master hanya tersedia bagi Admin Sarana-Prasarana.</p>
    </div>
  </div>
<?php endif; ?>
