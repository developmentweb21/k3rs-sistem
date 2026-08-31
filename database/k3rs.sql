CREATE DATABASE IF NOT EXISTS k3rs CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE k3rs;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  nama_lengkap VARCHAR(150) NOT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user',
  unit_kerja VARCHAR(100) DEFAULT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS master_kategori (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, nama VARCHAR(150) NOT NULL UNIQUE);
CREATE TABLE IF NOT EXISTS master_unit (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, nama VARCHAR(150) NOT NULL UNIQUE);
CREATE TABLE IF NOT EXISTS master_checklist (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, nama VARCHAR(255) NOT NULL UNIQUE);
CREATE TABLE IF NOT EXISTS app_settings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  app_name VARCHAR(150) NOT NULL DEFAULT 'SIRAMA',
  alamat TEXT NULL,
  logo LONGTEXT NULL,
  icon VARCHAR(100) NOT NULL DEFAULT 'fa-shield-halved',
  header_text VARCHAR(150) NULL,
  footer_text VARCHAR(200) NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS laporan_insiden (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, user_id INT UNSIGNED NOT NULL, kategori VARCHAR(150) NOT NULL, tanggal_kejadian DATE NOT NULL, lokasi VARCHAR(200) NOT NULL, kronologi TEXT NOT NULL, tindakan_awal TEXT NOT NULL, status ENUM('menunggu','diproses','selesai') NOT NULL DEFAULT 'menunggu', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, CONSTRAINT fk_insiden_user FOREIGN KEY (user_id) REFERENCES users(id));
CREATE TABLE IF NOT EXISTS laporan_kesehatan (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, user_id INT UNSIGNED NOT NULL, nama_karyawan VARCHAR(150) NOT NULL, unit_kerja VARCHAR(100) NOT NULL, diagnosa VARCHAR(255) NOT NULL, hari_tidak_masuk INT UNSIGNED NOT NULL DEFAULT 0, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, CONSTRAINT fk_kesehatan_user FOREIGN KEY (user_id) REFERENCES users(id));
CREATE TABLE IF NOT EXISTS laporan_checklist (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, user_id INT UNSIGNED NOT NULL, periode CHAR(7) NOT NULL, tanggal_pengisian DATE NOT NULL, unit_kerja VARCHAR(100) NOT NULL, jumlah_sesuai INT UNSIGNED NOT NULL DEFAULT 0, total_item INT UNSIGNED NOT NULL DEFAULT 0, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, CONSTRAINT fk_checklist_user FOREIGN KEY (user_id) REFERENCES users(id));

INSERT IGNORE INTO app_settings (app_name, alamat, icon, header_text, footer_text) VALUES ('SIRAMA', '', 'fa-shield-halved', 'Sistem Pelaporan RS', '@2026 SIRAMA by saleh mahmud');

INSERT IGNORE INTO users (username,password,nama_lengkap,role,unit_kerja) VALUES
('admin','$2y$10$gUJUcQ/b0cz5VIqGQsJB3.w6yoRrnFx1mhxHA/p6WZe8W/Io4kAxi','Administrator K3RS','admin','Manajemen'),
('user','$2y$10$vVcilBX6f0KH3k7cnIUGTOTwWX4Nbf.rJQdpM55OeJ7owxPXD1iIm','Perawat Dummy','user','IGD');
INSERT IGNORE INTO master_kategori (nama) VALUES ('Kejadian K3 Umum'),('Near Miss'),('Insiden B3'),('Accident Man'),('Accident Sarpras'),('Kecelakaan Lalu Lintas');
INSERT IGNORE INTO master_unit (nama) VALUES ('IGD'),('ICU'),('Rawat Inap Mawar'),('Poli Gigi'),('Farmasi'),('Gizi / Dapur');
INSERT IGNORE INTO master_checklist (nama) VALUES ('APAR tersedia dan belum kedaluwarsa'),('Jalur evakuasi tidak terhalang'),('Lantai licin memiliki tanda peringatan'),('Kotak P3K tersedia di unit'),('Sampah medis dibuang dengan tepat');
