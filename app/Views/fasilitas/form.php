<h1 class="h4 mb-3"><?= e($title) ?></h1>
<div class="card form-card">
  <div class="card-body">
    <?php require __DIR__ . '/../partials/form_error.php'; ?>
    <form method="post" action="<?= e(url($route . '/' . ($mode === 'create' ? 'store' : 'update'))) ?>" novalidate>
      <?= Csrf::field() ?>
      <?php if ($mode === 'edit'): ?><input type="hidden" name="id" value="<?= (int) $row['id_fasilitas'] ?>"><?php endif; ?>

      <div class="mb-3">
        <label for="nama_fasilitas" class="form-label">Nama Fasilitas <span class="text-danger">*</span></label>
        <input type="text" id="nama_fasilitas" name="nama_fasilitas" maxlength="100" class="form-control<?= invalid($errors, 'nama_fasilitas') ?>"
               value="<?= e($row['nama_fasilitas'] ?? '') ?>" placeholder="Contoh: AC Ruang A102" autofocus>
        <?= feedback($errors, 'nama_fasilitas') ?>
      </div>

      <div class="mb-3">
        <label for="id_kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
        <select id="id_kategori" name="id_kategori" class="form-select<?= invalid($errors, 'id_kategori') ?>">
          <option value="">-- Pilih kategori --</option>
          <?php foreach ($kategoriList as $k): ?>
            <option value="<?= (int) $k['id_kategori'] ?>" <?= (string) ($row['id_kategori'] ?? '') === (string) $k['id_kategori'] ? 'selected' : '' ?>><?= e($k['nama_kategori']) ?></option>
          <?php endforeach; ?>
        </select>
        <?= feedback($errors, 'id_kategori') ?>
      </div>

      <div class="mb-3">
        <label for="id_lokasi" class="form-label">Lokasi <span class="text-danger">*</span></label>
        <select id="id_lokasi" name="id_lokasi" class="form-select<?= invalid($errors, 'id_lokasi') ?>">
          <option value="">-- Pilih lokasi --</option>
          <?php foreach ($lokasiList as $l): ?>
            <option value="<?= (int) $l['id_lokasi'] ?>" <?= (string) ($row['id_lokasi'] ?? '') === (string) $l['id_lokasi'] ? 'selected' : '' ?>><?= e($l['gedung']) ?> &ndash; <?= e($l['ruangan']) ?></option>
          <?php endforeach; ?>
        </select>
        <?= feedback($errors, 'id_lokasi') ?>
      </div>

      <?php require __DIR__ . '/../partials/form_actions.php'; ?>
    </form>
  </div>
</div>
