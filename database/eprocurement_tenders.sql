-- Jalankan setelah database/eprocurement.sql dan database/eprocurement_auth.sql.
-- Aman dijalankan berulang kali pada database yang sudah berjalan.
CREATE TABLE IF NOT EXISTS tender_documents (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  company_id INT UNSIGNED NULL,
  tender_no VARCHAR(100) NULL,
  title VARCHAR(220) NOT NULL,
  procurement_method VARCHAR(80) NULL,
  issuer_name VARCHAR(180) NULL,
  description TEXT NULL,
  issue_date DATE NULL,
  valid_until DATE NULL,
  submission_deadline DATETIME NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'draft',
  contact_name VARCHAR(120) NULL,
  contact_email VARCHAR(160) NULL,
  contact_phone VARCHAR(40) NULL,
  notes TEXT NULL,
  file_path VARCHAR(500) NULL,
  original_file_name VARCHAR(255) NULL,
  file_mime VARCHAR(120) NULL,
  file_size BIGINT UNSIGNED NULL,
  created_by VARCHAR(120) NULL,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  INDEX idx_tender_company (company_id),
  INDEX idx_tender_status (status),
  INDEX idx_tender_valid_until (valid_until),
  CONSTRAINT fk_tender_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO permissions (permission_key, label, group_name, created_at) VALUES
('tenders.view', 'Lihat dokumen tender', 'Tender', NOW()),
('tenders.create', 'Tambah dokumen tender', 'Tender', NOW()),
('tenders.edit', 'Edit dokumen tender', 'Tender', NOW()),
('tenders.delete', 'Hapus dokumen tender', 'Tender', NOW());
