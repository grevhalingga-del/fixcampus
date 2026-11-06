<?php /** Variabel: $route, $id, $name. Tombol Ubah dan Hapus (hapus lewat POST + CSRF + konfirmasi). */ ?>
<div class="d-flex gap-1">
  <a class="btn btn-sm btn-outline-primary" href="<?= e(url("$route/edit", ['id' => $id])) ?>">Ubah</a>
  <form method="post" action="<?= e(url("$route/delete")) ?>" class="m-0"
        onsubmit="return confirm('Hapus &quot;<?= e(addslashes($name)) ?>&quot;? Tindakan ini tidak dapat dibatalkan.');">
    <?= Csrf::field() ?>
    <input type="hidden" name="id" value="<?= (int) $id ?>">
    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
  </form>
</div>
