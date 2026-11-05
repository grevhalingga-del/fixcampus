<?php
// Salin berkas ini menjadi config/database.php lalu sesuaikan isinya.
// config/database.php tidak ikut diunggah ke GitHub (lihat .gitignore).
mysqli_report(MYSQLI_REPORT_OFF);

function koneksi(): mysqli {
    $db = new mysqli('localhost', 'root', '', 'fixcampus');
    if ($db->connect_errno) {
        die('Koneksi database gagal: ' . htmlspecialchars($db->connect_error));
    }
    $db->set_charset('utf8mb4');
    return $db;
}
