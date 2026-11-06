<?php require __DIR__ . '/../partials/toolbar.php'; ?>
<div class="card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr><th style="width:70px">No</th><th>Nama</th><th>Email</th><th>Peran</th><th>Status</th><th style="width:170px">Aksi</th></tr>
      </thead>
      <tbody>
      <?php if (!$result['rows']): ?>
        <tr><td colspan="6" class="text-center text-body-secondary py-4">Belum ada data.</td></tr>
      <?php endif; ?>
      <?php foreach ($result['rows'] as $i => $row): ?>
        <tr>
          <td><?= ($result['page'] - 1) * $result['perPage'] + $i + 1 ?></td>
          <td><?= e($row['nama']) ?><?php if ((int) $row['id_user'] === Auth::id()): ?> <span class="badge text-bg-info">Anda</span><?php endif; ?></td>
          <td><?= e($row['email']) ?></td>
          <td><span class="badge text-bg-secondary"><?= e(User::ROLES[$row['role']] ?? $row['role']) ?></span></td>
          <td>
            <?php if ((int) $row['status_aktif'] === 1): ?><span class="badge text-bg-success">Aktif</span>
            <?php else: ?><span class="badge text-bg-danger">Nonaktif</span><?php endif; ?>
          </td>
          <td>
            <?php if ((int) $row['id_user'] === Auth::id()): ?>
              <a class="btn btn-sm btn-outline-primary" href="<?= e(url('pengguna/edit', ['id' => $row['id_user']])) ?>">Ubah</a>
            <?php else: ?>
              <?php $id = $row['id_user']; $name = $row['nama']; require __DIR__ . '/../partials/row_actions.php'; ?>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require __DIR__ . '/../partials/pagination.php'; ?>
