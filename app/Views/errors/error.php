<div class="card shadow-sm mx-auto" style="max-width:520px">
  <div class="card-body text-center py-5">
    <div class="display-5 fw-bold text-primary"><?= (int) $code ?></div>
    <h1 class="h5 mt-2"><?= e($judul) ?></h1>
    <p class="text-body-secondary"><?= e($pesan) ?></p>
    <a href="<?= e(url('')) ?>" class="btn btn-primary">Kembali ke beranda</a>
  </div>
</div>
