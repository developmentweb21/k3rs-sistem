USE k3rs;
CREATE TABLE IF NOT EXISTS menus (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  slug VARCHAR(50) NOT NULL UNIQUE,
  icon VARCHAR(80) NOT NULL DEFAULT 'fa-circle',
  urutan INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1
);
CREATE TABLE IF NOT EXISTS menu_roles (
  menu_id INT UNSIGNED NOT NULL,
  role ENUM('admin','user') NOT NULL,
  PRIMARY KEY (menu_id, role),
  CONSTRAINT fk_menu_roles_menu FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE
);
INSERT IGNORE INTO menus (id,nama,slug,icon,urutan,is_active) VALUES
(1,'Dashboard','dashboard','fa-chart-line',1,1),(2,'Lapor Insiden','insiden','fa-triangle-exclamation',2,1),(3,'Lapor Kesehatan','kesehatan','fa-notes-medical',3,1),(4,'Checklist K3','checklist','fa-list-check',4,1),(5,'Riwayat','riwayat','fa-clock-rotate-left',5,1),(6,'Verifikasi','verifikasi','fa-clipboard-check',2,1),(7,'Pegawai','pegawai','fa-users',3,1),(8,'Master Data','master','fa-database',4,1),(9,'Laporan','laporan','fa-file-lines',5,1);
INSERT IGNORE INTO menu_roles (menu_id,role) VALUES (1,'admin'),(1,'user'),(2,'user'),(3,'user'),(4,'user'),(5,'user'),(5,'admin'),(6,'admin'),(7,'admin'),(8,'admin'),(9,'admin');
