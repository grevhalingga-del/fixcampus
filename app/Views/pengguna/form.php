<?php
$isEdit = $mode === 'edit';
$isSelf = $isEdit && (int) $row['id_user'] === Auth::id();
?>
<h1 class="h4 mb-3"><?= e($title) ?></h1>
<div class="card form-card">
  <div class="card-body">
    <?php require __DIR__ . '/../partials/form_error.php'; ?>
    <form method="post" action="<?= e(url($route . '/' . ($isEdit ? 'update' : 'store'))) ?>" novalidate autocomplete="off">
      <?= Csrf::field() ?>
      <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= (int) $row['id_user'] ?>"><?php endif; ?>

      <div class="mb-3">
        <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
        <input type="text" id="nama" name="nama" maxlength="100" class="form-control<?= invalid($errors, 'nama') ?>" value="<?= e($row['nama'] ?? '') ?>" autofocus>
        <?= feedback($errors, 'nama') ?>
      </div>

      <div class="row g-3 mb-3">
        <div class="col-md-6">
          <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
          <input type="email" id="email" name="email" maxlength="100" class="form-control<?= invalid($errors, 'email') ?>" value="<?= e($row['email'] ?? '') ?>">
          <?= feedback($errors, 'email') ?>
        </div>
        <div class="col-md-6">
          <label for="no_hp" class="form-label">No. HP</label>
          <input type="text" id="no_hp" name="no_hp" maxlength="20" class="form-control<?= invalid($errors, 'no_hp') ?>" value="<?= e($row['no_hp'] ?? '') ?>" placeholder="08xxxxxxxxxx">
          <?= feedback($errors, 'no_hp') ?>
        </div>
      </div>

      <div class="row g-3 mb-3">
        <div class="col-md-6">
          <label for="role" class="form-label">Peran <span class="text-danger">*</span></label>
          <select id="role" name="role" class="form-select<?= invalid($errors, 'role') ?>" <?= $isSelf ? 'disabled' : '' ?>>
            <?php foreach ($roles as $val => $nama): ?>
              <option value="<?= e($val) ?>" <?= ($row['role'] ?? '') === $val ? 'selected' : '' ?>><?= e($nama) ?></option>
            <?php endforeach; ?>
          </select>
          <?= feedback($errors, 'role') ?>
          <?php if ($isSelf): ?><div class="form-text">Peran akun yang sedang dipakai tidak dapat diubah.</div><?php endif; ?>
        </div>
        <div class="col-md-6">
          <label for="status_aktif" class="form-label">Status</label>
          <select id="status_aktif" name="status_aktif" class="form-select" <?= $isSelf ? 'disabled' : '' ?>>
            <option value="1" <?= (string) ($row['status_aktif'] ?? '1') === '1' ? 'selected' : '' ?>>Aktif</option>
            <option value="0" <?= (string) ($row['status_aktif'] ?? '1') === '0' ? 'selected' : '' ?>>Nonaktif</option>
          </select>
        </div>
      </div>

      <hr>
      <p class="text-body-secondary small mb-2"><?= $isEdit ? 'Kosongkan password jika tidak ingin menggantinya.' : 'Password minimal 8 karakter.' ?></p>
      <div class="row g-3">
        <div class="col-md-6">
          <label for="password" class="form-label">Password <?= $isEdit ? '' : '<span class="text-danger">*</span>' ?></label>
          <input type="password" id="password" name="password" class="form-control<?= invalid($errors, 'password') ?>" autocomplete="new-password">
          <?= feedback($errors, 'password') ?>
        </div>
        <div class="col-md-6">
          <label for="password_confirm" class="form-label">Konfirmasi Password</label>
          <input type="password" id="password_confirm" name="password_confirm" class="form-control<?= invalid($errors, 'password_confirm') ?>" autocomplete="new-password">
          <?= feedback($errors, 'password_confirm') ?>
        </div>
      </div>

      <?php require __DIR__ . '/../partials/form_actions.php'; ?>
    </form>
  </div>
</div>
