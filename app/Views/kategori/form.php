<h1 class="h4 mb-3"><?= e($title) ?></h1>
<div class="card form-card">
  <div class="card-body">
    <?php require __DIR__ . '/../partials/form_error.php'; ?>
    <form method="post" action="<?= e(url($route . '/' . ($mode === 'create' ? 'store' : 'update'))) ?>" novalidate>
      <?= Csrf::field() ?>
      <?php if ($mode === 'edit'): ?><input type="hidden" name="id" value="<?= (int) $row['id_kategori'] ?>"><?php endif; ?>

      <div class="mb-3">
        <label for="nama_kategori" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
        <input type="text" id="nama_kategori" name="nama_kategori" maxlength="50" class="form-control<?= invalid($errors, 'nama_kategori') ?>"
               value="<?= e($row['nama_kategori'] ?? '') ?>" placeholder="Contoh: Listrik/Lampu" autofocus>
        <?= feedback($errors, 'nama_kategori') ?>
      </div>

      <?php require __DIR__ . '/../partials/form_actions.php'; ?>
    </form>
  </div>
</div>
