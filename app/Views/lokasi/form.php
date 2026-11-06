<h1 class="h4 mb-3"><?= e($title) ?></h1>
<div class="card form-card">
  <div class="card-body">
    <?php require __DIR__ . '/../partials/form_error.php'; ?>
    <form method="post" action="<?= e(url($route . '/' . ($mode === 'create' ? 'store' : 'update'))) ?>" novalidate>
      <?= Csrf::field() ?>
      <?php if ($mode === 'edit'): ?><input type="hidden" name="id" value="<?= (int) $row['id_lokasi'] ?>"><?php endif; ?>

      <div class="mb-3">
        <label for="gedung" class="form-label">Gedung <span class="text-danger">*</span></label>
        <input type="text" id="gedung" name="gedung" maxlength="50" class="form-control<?= invalid($errors, 'gedung') ?>"
               value="<?= e($row['gedung'] ?? '') ?>" placeholder="Contoh: Gedung A" autofocus>
        <?= feedback($errors, 'gedung') ?>
      </div>
      <div class="mb-3">
        <label for="ruangan" class="form-label">Ruangan <span class="text-danger">*</span></label>
        <input type="text" id="ruangan" name="ruangan" maxlength="50" class="form-control<?= invalid($errors, 'ruangan') ?>"
               value="<?= e($row['ruangan'] ?? '') ?>" placeholder="Contoh: Ruang A101">
        <?= feedback($errors, 'ruangan') ?>
      </div>

      <?php require __DIR__ . '/../partials/form_actions.php'; ?>
    </form>
  </div>
</div>
