<?php
/**
 * Konfigurasi aplikasi FixCampus.
 * Nilai di bawah adalah bawaan XAMPP (user root, tanpa password).
 * Untuk mengubahnya di komputer sendiri, buat config/database.local.php
 * (berkas itu tidak ikut diunggah ke GitHub) yang mengembalikan array dengan kunci yang ingin diganti.
 */
return [
    'host'    => 'localhost',
    'user'    => 'root',
    'pass'    => '',
    'name'    => 'fixcampus',
    'charset' => 'utf8mb4',
    'debug'   => true,   // true = tampilkan pesan error rinci (ubah ke false saat dipakai pengguna sebenarnya)
];
