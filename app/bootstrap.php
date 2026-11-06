<?php
/**
 * Bootstrap aplikasi: konstanta, autoload, helper, sesi, dan penangan error.
 * Dipanggil sekali oleh public/index.php.
 */
define('BASE_PATH', dirname(__DIR__));

date_default_timezone_set('Asia/Jakarta');

// Autoload sederhana: nama kelas = nama berkas di app/Core, app/Controllers, atau app/Models.
spl_autoload_register(function (string $class): void {
    foreach (['Core', 'Controllers', 'Models'] as $dir) {
        $file = BASE_PATH . "/app/$dir/$class.php";
        if (is_file($file)) {
            require $file;
            return;
        }
    }
});

require BASE_PATH . '/app/Core/helpers.php';

// Sesi dengan cookie yang lebih aman.
session_name('FIXCAMPUS_SID');
session_set_cookie_params(['lifetime' => 0, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax']);
session_start();

// Semua error yang tidak tertangani ditampilkan lewat halaman ramah pengguna.
set_exception_handler(function (Throwable $e): void {
    http_response_code(500);
    $debug = (bool) config('debug', false);
    $pesan = $e instanceof mysqli_sql_exception
        ? 'Tidak dapat terhubung ke database. Pastikan MySQL di XAMPP berstatus Running, database "fixcampus" sudah diimpor, dan pengaturan di config/database.php benar.'
        : 'Terjadi kesalahan pada aplikasi.';
    echo '<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><title>Kesalahan - FixCampus</title>',
         '<link rel="stylesheet" href="', e(asset('css/bootstrap.min.css')), '"></head><body class="bg-light"><div class="container py-5" style="max-width:720px">',
         '<div class="alert alert-danger"><h5 class="alert-heading">Terjadi kesalahan</h5><p class="mb-0">', e($pesan), '</p></div>';
    if ($debug) {
        echo '<pre class="small bg-white border p-3">', e($e->getMessage()), "\n", e($e->getFile() . ':' . $e->getLine()), '</pre>';
    }
    echo '</div></body></html>';
});
