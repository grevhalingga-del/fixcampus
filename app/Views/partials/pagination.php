<?php
/** Variabel: $result (page, pages, total, perPage), $route, $q */
$page = $result['page'];
$pages = $result['pages'];
$from = $result['total'] === 0 ? 0 : ($page - 1) * $result['perPage'] + 1;
$to = min($page * $result['perPage'], $result['total']);
$link = fn(int $p) => url("$route/index", array_filter(['q' => $q, 'page' => $p > 1 ? $p : null]));
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
  <div class="text-body-secondary small">Menampilkan <?= $from ?>-<?= $to ?> dari <?= (int) $result['total'] ?> data</div>
  <?php if ($pages > 1): ?>
  <nav aria-label="Halaman">
    <ul class="pagination pagination-sm mb-0">
      <li class="page-item<?= $page <= 1 ? ' disabled' : '' ?>"><a class="page-link" href="<?= e($link($page - 1)) ?>">&laquo;</a></li>
      <?php for ($p = 1; $p <= $pages; $p++): ?>
        <li class="page-item<?= $p === $page ? ' active' : '' ?>"><a class="page-link" href="<?= e($link($p)) ?>"><?= $p ?></a></li>
      <?php endfor; ?>
      <li class="page-item<?= $page >= $pages ? ' disabled' : '' ?>"><a class="page-link" href="<?= e($link($page + 1)) ?>">&raquo;</a></li>
    </ul>
  </nav>
  <?php endif; ?>
</div>
