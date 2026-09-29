-- Jalankan setelah database/eprocurement.sql dan database/eprocurement_auth.sql.
-- Tabel BAST menyimpan snapshot item dari quotation atau purchase_order agar isi PDF tetap historis.
CREATE TABLE IF NOT EXISTS bast_documents (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  bast_no VARCHAR(100) NOT NULL UNIQUE,
  source_type VARCHAR(30) NOT NULL,
  source_id INT UNSIGNED NOT NULL,
  source_no VARCHAR(100) NOT NULL,
  source_title VARCHAR(220) NULL,
  source_date DATE NULL,
  company_id INT UNSIGNED NOT NULL,
  handover_date DATE NOT NULL,
  location VARCHAR(180) NULL,
  recipient_name VARCHAR(160) NOT NULL,
  recipient_position VARCHAR(120) NULL,
  handed_over_by VARCHAR(160) NULL,
  handed_over_position VARCHAR(120) NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'completed',
  items_json LONGTEXT NOT NULL,
  notes TEXT NULL,
  created_by VARCHAR(120) NULL,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  INDEX idx_bast_source (source_type, source_id),
  INDEX idx_bast_company (company_id),
  INDEX idx_bast_handover_date (handover_date),
  CONSTRAINT fk_bast_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO permissions (permission_key, label, group_name, created_at) VALUES
('bast.view', 'Lihat berita acara serah terima', 'Pengadaan', NOW()),
('bast.create', 'Buat berita acara serah terima', 'Pengadaan', NOW()),
('bast.edit', 'Edit berita acara serah terima', 'Pengadaan', NOW()),
('bast.delete', 'Hapus berita acara serah terima', 'Pengadaan', NOW()),
('bast.export', 'Unduh PDF berita acara serah terima', 'Pengadaan', NOW());
