<?php
// FixCampus - Uji Environment (PHP + MySQLi + MariaDB + folder upload)
// Taruh di: C:\xampp\htdocs\fixcampus\test_koneksi.php
// Buka di browser: http://localhost/fixcampus/test_koneksi.php

mysqli_report(MYSQLI_REPORT_OFF);

$HOST = 'localhost';
$USER = 'root';
$PASS = '';              // default XAMPP: kosong
$DB   = 'fixcampus';
$TABEL_WAJIB = ['users','kategori','lokasi','fasilitas','laporan','penugasan','riwayat_status','notifikasi'];

$cek = [];   // [nama pemeriksaan, hasil (bool), keterangan]
$cek[] = ['Versi PHP (butuh 8.0 atau lebih baru)', version_compare(PHP_VERSION, '8.0.0', '>='), PHP_VERSION];
$cek[] = ['Ekstensi mysqli aktif', extension_loaded('mysqli'), ''];
$cek[] = ['Ekstensi mbstring aktif', extension_loaded('mbstring'), ''];
$cek[] = ['Ekstensi fileinfo aktif (validasi upload)', extension_loaded('fileinfo'), ''];
$cek[] = ['Fungsi password_hash tersedia', function_exists('password_hash'), ''];

$dirFoto = __DIR__ . '/storage/foto_laporan';
$cek[] = ['Folder storage/foto_laporan ada dan dapat ditulis', is_dir($dirFoto) && is_writable($dirFoto), $dirFoto];

$koneksi = @new mysqli($HOST, $USER, $PASS, $DB);
$koneksiOk = !$koneksi->connect_errno;
$cek[] = ["Koneksi MySQLi ke database $DB berhasil", $koneksiOk, $koneksiOk ? '' : $koneksi->connect_error];

$jumlah = [];
if ($koneksiOk) {
    $koneksi->set_charset('utf8mb4');
    $res = $koneksi->query('SHOW TABLES');
    $ada = [];
    while ($r = $res->fetch_row()) { $ada[] = $r[0]; }
    foreach ($TABEL_WAJIB as $t) {
        $oke = in_array($t, $ada, true);
        $cek[] = ["Tabel $t ada", $oke, ''];
        if ($oke) { $jumlah[$t] = (int)$koneksi->query("SELECT COUNT(*) FROM `$t`")->fetch_row()[0]; }
    }
    $cek[] = ['Versi MariaDB/MySQL', true, $koneksi->server_info];
}

$semua = true;
foreach ($cek as $c) { if (!$c[1]) { $semua = false; } }
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>FixCampus - Uji Environment</title>
<style>
  body{font-family:Arial,sans-serif;background:#fbf3e8;color:#222;margin:0;padding:24px}
  h1{font-size:20px;margin:0 0 4px;color:#7a1020}
  .sub{font-size:12px;color:#555;margin-bottom:12px}
  table{border-collapse:collapse;background:#fff;margin-bottom:14px;font-size:13px}
  th,td{border:1px solid #bbb;padding:5px 10px;text-align:left}
  th{background:#eee}
  .ok{color:#0a7a2f;font-weight:bold}.gagal{color:#c00;font-weight:bold}
  .banner{display:inline-block;padding:8px 14px;border-radius:4px;font-weight:bold}
  .banner.ok{background:#d9f2e0}.banner.gagal{background:#fbdcdc}
</style>
</head>
<body>
<h1>FixCampus - Uji Environment</h1>
<div class="sub">Server: <?= htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? '-') ?> | Waktu: <?= date('d-m-Y H:i:s') ?></div>

<table>
  <tr><th>Pemeriksaan</th><th>Hasil</th><th>Keterangan</th></tr>
  <?php foreach ($cek as [$nama, $ok, $ket]): ?>
  <tr>
    <td><?= htmlspecialchars($nama) ?></td>
    <td class="<?= $ok ? 'ok' : 'gagal' ?>"><?= $ok ? 'OK' : 'GAGAL' ?></td>
    <td><?= htmlspecialchars($ket) ?></td>
  </tr>
  <?php endforeach; ?>
</table>

<?php if ($jumlah): ?>
<table>
  <tr><th>Tabel</th><th>Jumlah baris</th></tr>
  <?php foreach ($jumlah as $t => $n): ?>
  <tr><td><?= htmlspecialchars($t) ?></td><td><?= $n ?></td></tr>
  <?php endforeach; ?>
</table>
<?php endif; ?>

<div class="banner <?= $semua ? 'ok' : 'gagal' ?>">
  <?= $semua ? 'Environment siap digunakan.' : 'Environment belum siap, periksa baris yang GAGAL.' ?>
</div>
<p class="sub">Hapus berkas ini sebelum sistem dipakai pengguna sebenarnya.</p>
</body>
</html>
