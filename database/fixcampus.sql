-- FixCampus - Skema Database (MySQL/MariaDB)
-- Sumber: PRD FixCampus, Tugas 3 (BPMN & Use Case), Tugas 5 (Arsitektur & UI/UX)
-- Cara pakai: phpMyAdmin > Import, atau jalankan seluruh isi pada tab SQL.

CREATE DATABASE IF NOT EXISTS fixcampus CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fixcampus;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS notifikasi, riwayat_status, penugasan, laporan, fasilitas, lokasi, kategori, users;
SET FOREIGN_KEY_CHECKS = 1;

-- F-01 Login dan peran (Pelapor, Admin, Teknisi)
CREATE TABLE users (
  id_user INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,            -- hasil password_hash() bcrypt
  role ENUM('pelapor','admin','teknisi') NOT NULL,
  no_hp VARCHAR(20) NULL,
  status_aktif TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Data master (PRD bagian 9: siapkan sebelum sistem dipakai)
CREATE TABLE kategori (
  id_kategori INT AUTO_INCREMENT PRIMARY KEY,
  nama_kategori VARCHAR(50) NOT NULL UNIQUE  -- Listrik, AC, Mebel, Proyektor, Pintu, Toilet, Jaringan
) ENGINE=InnoDB;

CREATE TABLE lokasi (
  id_lokasi INT AUTO_INCREMENT PRIMARY KEY,
  gedung VARCHAR(50) NOT NULL,
  ruangan VARCHAR(50) NOT NULL,
  UNIQUE KEY uq_lokasi (gedung, ruangan)
) ENGINE=InnoDB;

CREATE TABLE fasilitas (
  id_fasilitas INT AUTO_INCREMENT PRIMARY KEY,
  nama_fasilitas VARCHAR(100) NOT NULL,
  id_kategori INT NOT NULL,
  id_lokasi INT NOT NULL,
  FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori),
  FOREIGN KEY (id_lokasi) REFERENCES lokasi(id_lokasi)
) ENGINE=InnoDB;

-- F-02, F-04, F-05 Laporan kerusakan (inti sistem)
CREATE TABLE laporan (
  id_laporan INT AUTO_INCREMENT PRIMARY KEY,
  no_laporan VARCHAR(20) NOT NULL UNIQUE,    -- contoh: FC-20261103-001
  id_pelapor INT NOT NULL,
  id_fasilitas INT NOT NULL,
  id_kategori INT NOT NULL,
  id_lokasi INT NOT NULL,
  deskripsi TEXT NOT NULL,
  tingkat_kondisi ENUM('ringan','sedang','berat') NOT NULL DEFAULT 'ringan',
  foto_kerusakan VARCHAR(255) NULL,
  status ENUM('Diajukan','Diverifikasi','Ditugaskan','Diproses','Selesai','Ditolak') NOT NULL DEFAULT 'Diajukan',
  prioritas ENUM('rendah','sedang','tinggi') NULL,   -- diisi admin saat verifikasi
  alasan_penolakan TEXT NULL,
  tanggal_lapor DATETIME DEFAULT CURRENT_TIMESTAMP,
  tanggal_selesai DATETIME NULL,
  FOREIGN KEY (id_pelapor) REFERENCES users(id_user),
  FOREIGN KEY (id_fasilitas) REFERENCES fasilitas(id_fasilitas),
  FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori),
  FOREIGN KEY (id_lokasi) REFERENCES lokasi(id_lokasi),
  INDEX idx_status (status),
  INDEX idx_prioritas (prioritas),
  INDEX idx_tanggal (tanggal_lapor)
) ENGINE=InnoDB;

-- F-06, F-07, F-08, F-09 Penugasan dan catatan perbaikan
CREATE TABLE penugasan (
  id_penugasan INT AUTO_INCREMENT PRIMARY KEY,
  id_laporan INT NOT NULL,
  id_teknisi INT NOT NULL,
  id_admin INT NOT NULL,
  tanggal_tugas DATETIME DEFAULT CURRENT_TIMESTAMP,
  catatan_tindakan TEXT NULL,
  foto_hasil VARCHAR(255) NULL,
  tanggal_selesai DATETIME NULL,
  FOREIGN KEY (id_laporan) REFERENCES laporan(id_laporan) ON DELETE CASCADE,
  FOREIGN KEY (id_teknisi) REFERENCES users(id_user),
  FOREIGN KEY (id_admin) REFERENCES users(id_user)
) ENGINE=InnoDB;

-- F-03, G-04 Riwayat setiap perubahan status (jejak maintenance)
CREATE TABLE riwayat_status (
  id_riwayat INT AUTO_INCREMENT PRIMARY KEY,
  id_laporan INT NOT NULL,
  status_lama VARCHAR(20) NULL,
  status_baru VARCHAR(20) NOT NULL,
  keterangan TEXT NULL,
  id_user INT NOT NULL,                      -- siapa yang mengubah
  waktu DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_laporan) REFERENCES laporan(id_laporan) ON DELETE CASCADE,
  FOREIGN KEY (id_user) REFERENCES users(id_user)
) ENGINE=InnoDB;

-- F-13 Notifikasi dalam sistem (Could)
CREATE TABLE notifikasi (
  id_notifikasi INT AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL,
  id_laporan INT NULL,
  pesan VARCHAR(255) NOT NULL,
  sudah_dibaca TINYINT(1) NOT NULL DEFAULT 0,
  waktu DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE,
  FOREIGN KEY (id_laporan) REFERENCES laporan(id_laporan) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ===== DATA AWAL =====
-- Password semua akun contoh: password123 (hash bcrypt). Ganti setelah login pertama.
INSERT INTO users (nama, email, password, role) VALUES
('Dinda Pelapor', 'dinda@fixcampus.test', '$2y$10$kcyw90Il8c7BGFDLpNFE..kaR/O4IZt.oN3gytwRpwiDMaFceq99O', 'pelapor'),
('Rudi Admin',    'rudi@fixcampus.test',  '$2y$10$kcyw90Il8c7BGFDLpNFE..kaR/O4IZt.oN3gytwRpwiDMaFceq99O', 'admin'),
('Andi Teknisi',  'andi@fixcampus.test',  '$2y$10$kcyw90Il8c7BGFDLpNFE..kaR/O4IZt.oN3gytwRpwiDMaFceq99O', 'teknisi');

INSERT INTO kategori (nama_kategori) VALUES
('Listrik/Lampu'),('AC'),('Mebel (Kursi/Meja)'),('Proyektor'),('Pintu/Jendela'),('Toilet'),('Jaringan');

INSERT INTO lokasi (gedung, ruangan) VALUES
('Gedung A','Ruang A101'),('Gedung A','Ruang A102'),('Gedung B','Lab Komputer 1'),('Gedung B','Toilet Lantai 1');

INSERT INTO fasilitas (nama_fasilitas, id_kategori, id_lokasi) VALUES
('Lampu LED Ruang A101', 1, 1),
('AC Ruang A102', 2, 2),
('Proyektor Lab Komputer 1', 4, 3),
('Kran Toilet Lantai 1', 6, 4);
