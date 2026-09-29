-- Jalankan setelah database/eprocurement_client_purchase_orders.sql.
CREATE TABLE IF NOT EXISTS client_delivery_notes (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  client_purchase_order_id INT UNSIGNED NOT NULL,
  company_id INT UNSIGNED NOT NULL,
  delivery_no VARCHAR(100) NOT NULL UNIQUE,
  delivery_date DATE NOT NULL,
  destination VARCHAR(220) NULL,
  recipient_name VARCHAR(160) NOT NULL,
  recipient_position VARCHAR(120) NULL,
  delivered_by VARCHAR(160) NULL,
  delivered_position VARCHAR(120) NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'draft',
  items_json LONGTEXT NOT NULL,
  notes TEXT NULL,
  created_by VARCHAR(120) NULL,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  INDEX idx_delivery_po (client_purchase_order_id),
  INDEX idx_delivery_company (company_id),
  INDEX idx_delivery_date (delivery_date),
  CONSTRAINT fk_delivery_client_po FOREIGN KEY (client_purchase_order_id) REFERENCES client_purchase_orders(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fk_delivery_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO permissions (permission_key, label, group_name, created_at) VALUES
('client_delivery_note.view', 'Lihat surat jalan client', 'Penjualan', NOW()),
('client_delivery_note.create', 'Buat surat jalan client', 'Penjualan', NOW()),
('client_delivery_note.edit', 'Edit surat jalan client', 'Penjualan', NOW()),
('client_delivery_note.delete', 'Hapus surat jalan client', 'Penjualan', NOW()),
('client_delivery_note.export', 'Unduh PDF surat jalan client', 'Penjualan', NOW());
