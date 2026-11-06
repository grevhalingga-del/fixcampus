<?php require __DIR__ . '/../partials/toolbar.php'; ?>
<div class="card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr><th style="width:70px">No</th><th>Nama Kategori</th><th style="width:170px">Aksi</th></tr>
      </thead>
      <tbody>
      <?php if (!$result['rows']): ?>
        <tr><td colspan="3" class="text-center text-body-secondary py-4">Belum ada data.</td></tr>
      <?php endif; ?>
      <?php foreach ($result['rows'] as $i => $row): ?>
        <tr>
          <td><?= ($result['page'] - 1) * $result['perPage'] + $i + 1 ?></td>
          <td><?= e($row['nama_kategori']) ?></td>
          <td><?php $id = $row['id_kategori']; $name = $row['nama_kategori']; require __DIR__ . '/../partials/row_actions.php'; ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require __DIR__ . '/../partials/pagination.php'; ?>
