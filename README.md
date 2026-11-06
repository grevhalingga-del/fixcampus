# FixCampus
Sistem Informasi Pemeliharaan Fasilitas Kampus Berbasis Web.
Stack: PHP 8 (MVC native), MySQLi, MariaDB/MySQL, Bootstrap 5.

## Status pengembangan
| Tugas | Isi |
|---|---|
| Tugas 6 | Penentuan teknologi dan persiapan environment (`test_koneksi.php`) |
| **Tugas 7** | **Modul login/autentikasi dan CRUD data master (Kategori, Lokasi, Fasilitas, Pengguna)** |

## Menjalankan di komputer lokal (XAMPP)
1. Salin folder ini ke `C:\xampp\htdocs\fixcampus`.
2. Jalankan **Apache** dan **MySQL** di XAMPP Control Panel.
3. Buka phpMyAdmin, tab **Import**, pilih `database/fixcampus.sql`, klik **Import**.
4. (Opsional) Buka `http://localhost/fixcampus/test_koneksi.php`, semua baris harus **OK**.
5. Buka aplikasi di **http://localhost/fixcampus/** (otomatis menuju `public/`).

Pengaturan database ada di `config/database.php` (bawaan XAMPP: user `root`, password kosong).
Jika password MySQL berbeda, buat `config/database.local.php` berisi:
```php
<?php return ['pass' => 'password-anda'];
```
(berkas itu tidak ikut diunggah ke GitHub).

## Akun contoh (password semua: `password123`)
| Peran | Email |
|---|---|
| Admin Sarana-Prasarana | rudi@fixcampus.test |
| Pelapor | dinda@fixcampus.test |
| Teknisi | andi@fixcampus.test |

## Fitur Tugas 7
### Login dan autentikasi
- Form login dengan validasi (kosong, format email, kredensial salah) dan pesan error yang sama untuk email/password salah.
- Password disimpan sebagai hash bcrypt (`password_hash` / `password_verify`).
- ID sesi diganti setelah login (cegah session fixation); sesi berakhir setelah 30 menit tidak aktif.
- 5 kali gagal berturut-turut menahan percobaan login selama 60 detik.
- Akun nonaktif tidak dapat login.
- Logout lewat POST + token CSRF.
- Hak akses berbasis peran: data master hanya untuk **admin**; pelapor dan teknisi mendapat 403 bila mencoba membukanya.

### CRUD data master (admin)
| Modul | Tabel | Catatan |
|---|---|---|
| Kategori | `kategori` | nama unik |
| Lokasi | `lokasi` | gedung + ruangan unik |
| Fasilitas | `fasilitas` | relasi ke kategori dan lokasi (dropdown, JOIN di daftar) |
| Pengguna | `users` | peran, status aktif, password hash; admin tidak dapat menghapus/menonaktifkan/mengubah peran akunnya sendiri |

Setiap modul: daftar dengan **pencarian dan paginasi**, tambah, ubah, hapus (dengan konfirmasi), validasi sisi server, pesan sukses/gagal. Data yang masih dipakai tabel lain (foreign key) tidak dapat dihapus dan pengguna mendapat pesan yang jelas.

### Keamanan
MySQLi **prepared statement** di semua query, token **CSRF** di semua form POST, output di-escape (`htmlspecialchars`) untuk mencegah XSS, password tidak pernah dikirim ke view, folder `app/`, `config/`, dan `database/` ditutup dari akses web (`.htaccess`).

## Struktur proyek (MVC)
```
fixcampus/
├── app/
│   ├── Core/          Database, Router, Controller, CrudController, BaseModel, Auth, Csrf, helpers
│   ├── Controllers/   Auth, Dashboard, Kategori, Lokasi, Fasilitas, Pengguna, Error
│   ├── Models/        Kategori, Lokasi, Fasilitas, User
│   └── Views/         layouts, partials, auth, dashboard, kategori, lokasi, fasilitas, pengguna, errors
├── config/            database.php
├── database/          fixcampus.sql (skema 8 tabel + data awal)
├── public/            index.php (front controller), assets/ (Bootstrap, app.css)
├── storage/           foto_laporan, foto_perbaikan
└── test_koneksi.php   uji environment (Tugas 6)
```

Alur satu permintaan: `public/index.php` -> `Router` (daftar putih controller/aksi, cek CSRF) -> `Controller::authorize()` (login dan peran) -> aksi controller -> `Model` -> `Database` (MySQLi) -> `View`.
`CrudController` berisi alur CRUD generik; tiap modul hanya mendefinisikan model, aturan validasi, dan tampilannya.

## Catatan
Bootstrap 5.3.2 (MIT) disertakan lokal di `public/assets` agar aplikasi dapat berjalan tanpa internet.
