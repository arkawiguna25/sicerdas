CREATE DATABASE IF NOT EXISTS sicerdas_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sicerdas_db;

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS notifikasi, aktivitas_log, penilaian, laporan, dokumen, sk_ppkh, sk_penetapan,
  telaah_permohonan, permohonan, tarlam, reklamasi_hutan, lahan_kompensasi, rehabilitasi_das,
  ippkh, perusahaan, ebook, users;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(150) NOT NULL,
  username VARCHAR(80) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','petugas') NOT NULL DEFAULT 'petugas',
  status ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE perusahaan (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama_perusahaan VARCHAR(180) NOT NULL,
  alamat TEXT NULL,
  telepon VARCHAR(30) NULL,
  email VARCHAR(120) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE ippkh (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  perusahaan_id INT UNSIGNED NOT NULL,
  nomor_ippkh VARCHAR(100) NOT NULL UNIQUE,
  tanggal_penerbitan DATE NULL,
  kegunaan_izin TEXT NULL,
  luas_kawasan DECIMAL(12,2) DEFAULT 0,
  provinsi VARCHAR(100) NULL,
  status_izin VARCHAR(50) DEFAULT 'Aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (perusahaan_id) REFERENCES perusahaan(id) ON DELETE RESTRICT
);

CREATE TABLE rehabilitasi_das (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ippkh_id INT UNSIGNED NULL,
  nomor_ippkh VARCHAR(100) NULL,
  nama_perusahaan VARCHAR(180) NOT NULL,
  nomor_sk VARCHAR(100) NULL,
  tanggal_sk DATE NULL,
  jenis_kegiatan VARCHAR(150) NOT NULL,
  luas_lahan DECIMAL(12,2) DEFAULT 0,
  provinsi VARCHAR(100) NULL,
  kabupaten VARCHAR(100) NULL,
  kecamatan VARCHAR(100) NULL,
  desa VARCHAR(100) NULL,
  koordinat VARCHAR(100) NULL,
  status_kegiatan VARCHAR(50) DEFAULT 'Diajukan',
  keterangan TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (ippkh_id) REFERENCES ippkh(id) ON DELETE SET NULL
);

CREATE TABLE lahan_kompensasi (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ippkh_id INT UNSIGNED NULL,
  nomor_permohonan VARCHAR(100) NOT NULL,
  nama_perusahaan VARCHAR(180) NOT NULL,
  lokasi_lahan TEXT NULL,
  luas_lahan DECIMAL(12,2) DEFAULT 0,
  provinsi VARCHAR(100) NULL,
  kabupaten VARCHAR(100) NULL,
  status_lahan VARCHAR(50) DEFAULT 'Diajukan',
  tanggal_pengajuan DATE NULL,
  dokumen_pendukung VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (ippkh_id) REFERENCES ippkh(id) ON DELETE SET NULL
);

CREATE TABLE reklamasi_hutan (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ippkh_id INT UNSIGNED NULL,
  nama_perusahaan VARCHAR(180) NOT NULL,
  nomor_izin VARCHAR(100) NOT NULL,
  luas_lahan DECIMAL(12,2) DEFAULT 0,
  lokasi_reklamasi TEXT NULL,
  jenis_tanaman VARCHAR(150) NULL,
  tahun_kegiatan INT NULL,
  persentase_penyelesaian TINYINT UNSIGNED DEFAULT 0,
  status_reklamasi VARCHAR(50) DEFAULT 'Diajukan',
  dokumentasi VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (ippkh_id) REFERENCES ippkh(id) ON DELETE SET NULL
);

CREATE TABLE tarlam (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ippkh_id INT UNSIGNED NULL,
  nomor_kegiatan VARCHAR(100) NOT NULL,
  nama_perusahaan VARCHAR(180) NOT NULL,
  lokasi TEXT NULL,
  luas_area DECIMAL(12,2) DEFAULT 0,
  jenis_kegiatan VARCHAR(150) NULL,
  tahun_kegiatan INT NULL,
  status VARCHAR(50) DEFAULT 'Diajukan',
  keterangan TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (ippkh_id) REFERENCES ippkh(id) ON DELETE SET NULL
);

CREATE TABLE permohonan (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nomor_permohonan VARCHAR(100) NOT NULL UNIQUE,
  tanggal_permohonan DATE NOT NULL,
  nama_pemohon VARCHAR(180) NOT NULL,
  ippkh_id INT UNSIGNED NULL,
  nomor_ippkh VARCHAR(100) NULL,
  jenis_permohonan VARCHAR(150) NOT NULL,
  perihal TEXT NULL,
  provinsi VARCHAR(100) NULL,
  kabupaten VARCHAR(100) NULL,
  kecamatan VARCHAR(100) NULL,
  luas_lahan DECIMAL(12,2) DEFAULT 0,
  deskripsi TEXT NULL,
  dokumen_pendukung VARCHAR(255) NULL,
  status ENUM('Draft','Diajukan','Dalam Pemeriksaan','Perlu Perbaikan','Disetujui','Ditolak','Selesai') DEFAULT 'Draft',
  dibuat_oleh INT UNSIGNED NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (ippkh_id) REFERENCES ippkh(id) ON DELETE SET NULL,
  FOREIGN KEY (dibuat_oleh) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE telaah_permohonan (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  permohonan_id INT UNSIGNED NOT NULL,
  petugas_id INT UNSIGNED NULL,
  kelengkapan_dokumen ENUM('Lengkap','Tidak Lengkap') DEFAULT 'Tidak Lengkap',
  catatan TEXT NULL,
  keputusan ENUM('Menunggu','Disetujui','Ditolak','Perlu Perbaikan') DEFAULT 'Menunggu',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (permohonan_id) REFERENCES permohonan(id) ON DELETE CASCADE,
  FOREIGN KEY (petugas_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE sk_penetapan (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nomor_sk VARCHAR(100) NOT NULL,
  tanggal_sk DATE NOT NULL,
  nama_perusahaan VARCHAR(180) NOT NULL,
  nomor_ippkh VARCHAR(100) NULL,
  jenis_penetapan VARCHAR(150) NULL,
  luas_lahan DECIMAL(12,2) DEFAULT 0,
  provinsi VARCHAR(100) NULL,
  status_sk VARCHAR(50) DEFAULT 'Aktif',
  dokumen_sk VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE sk_ppkh (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nomor_sk_ppkh VARCHAR(100) NOT NULL,
  tanggal_penerbitan DATE NOT NULL,
  nama_perusahaan VARCHAR(180) NOT NULL,
  nomor_ippkh VARCHAR(100) NULL,
  luas_kawasan DECIMAL(12,2) DEFAULT 0,
  lokasi TEXT NULL,
  status VARCHAR(50) DEFAULT 'Aktif',
  file_dokumen VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE laporan (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nomor_laporan VARCHAR(100) NOT NULL UNIQUE,
  rehabilitasi_id INT UNSIGNED NULL,
  nama_perusahaan VARCHAR(180) NOT NULL,
  jenis_kegiatan VARCHAR(150) NULL,
  tanggal_laporan DATE NOT NULL,
  luas_realisasi DECIMAL(12,2) DEFAULT 0,
  jumlah_tanaman INT UNSIGNED DEFAULT 0,
  jenis_tanaman VARCHAR(150) NULL,
  persentase_keberhasilan TINYINT UNSIGNED DEFAULT 0,
  dokumentasi_foto VARCHAR(255) NULL,
  catatan_petugas TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (rehabilitasi_id) REFERENCES rehabilitasi_das(id) ON DELETE SET NULL
);

CREATE TABLE penilaian (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  laporan_id INT UNSIGNED NOT NULL,
  penilai_id INT UNSIGNED NULL,
  nilai DECIMAL(5,2) DEFAULT 0,
  predikat VARCHAR(50) NULL,
  catatan TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (laporan_id) REFERENCES laporan(id) ON DELETE CASCADE,
  FOREIGN KEY (penilai_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE dokumen (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(180) NOT NULL,
  kategori VARCHAR(100) NULL,
  nama_file VARCHAR(255) NOT NULL,
  uploaded_by INT UNSIGNED NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE ebook (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(180) NOT NULL,
  penulis VARCHAR(150) NULL,
  deskripsi TEXT NULL,
  nama_file VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE notifikasi (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  judul VARCHAR(180) NOT NULL,
  pesan TEXT NULL,
  dibaca TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE aktivitas_log (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  aksi VARCHAR(100) NOT NULL,
  entitas VARCHAR(100) NULL,
  entitas_id INT UNSIGNED NULL,
  detail TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

INSERT INTO users (nama, username, password, role) VALUES
('Administrator SICERDAS', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Petugas Lapangan', 'petugas', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'petugas');

INSERT INTO perusahaan (nama_perusahaan, alamat) VALUES
('PT Contoh Nusantara', 'Jakarta'),
('PT Hijau Lestari', 'Bandung');

INSERT INTO permohonan (nomor_permohonan, tanggal_permohonan, nama_pemohon, nomor_ippkh, jenis_permohonan, perihal, provinsi, kabupaten, luas_lahan, status, dibuat_oleh)
VALUES
('REQ-2026-0001', '2026-11-20', 'PT Contoh Nusantara', 'IPPKH/001', 'Rehabilitasi DAS', 'Permohonan kegiatan rehabilitasi', 'Jawa Barat', 'Bandung', 125.50, 'Dalam Pemeriksaan', 1),
('REQ-2026-0002', '2026-11-21', 'PT Hijau Lestari', 'IPPKH/002', 'Lahan Kompensasi', 'Permohonan lahan kompensasi', 'Jawa Tengah', 'Semarang', 80.00, 'Disetujui', 1);
