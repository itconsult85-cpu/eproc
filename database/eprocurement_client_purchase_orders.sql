-- Jalankan setelah database/eprocurement.sql dan database/eprocurement_finance_procurement.sql.
CREATE TABLE IF NOT EXISTS client_purchase_orders (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  quotation_id INT UNSIGNED NOT NULL,
  company_id INT UNSIGNED NOT NULL,
  po_no VARCHAR(100) NOT NULL UNIQUE,
  po_date DATE NOT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'received',
  subtotal DECIMAL(18,2) NOT NULL DEFAULT 0,
  tax_percent DECIMAL(5,2) NOT NULL DEFAULT 0,
  tax_amount DECIMAL(18,2) NOT NULL DEFAULT 0,
  grand_total DECIMAL(18,2) NOT NULL DEFAULT 0,
  notes TEXT NULL,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  INDEX idx_client_po_quotation (quotation_id),
  INDEX idx_client_po_company (company_id),
  CONSTRAINT fk_client_po_quotation FOREIGN KEY (quotation_id) REFERENCES quotations(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fk_client_po_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS client_purchase_order_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  client_purchase_order_id INT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NULL,
  product_name VARCHAR(180) NOT NULL,
  description TEXT NULL,
  quantity DECIMAL(18,2) NOT NULL DEFAULT 1,
  unit VARCHAR(30) NOT NULL DEFAULT 'pcs',
  unit_price DECIMAL(18,2) NOT NULL DEFAULT 0,
  discount_percent DECIMAL(5,2) NOT NULL DEFAULT 0,
  line_total DECIMAL(18,2) NOT NULL DEFAULT 0,
  INDEX idx_client_poi_po (client_purchase_order_id),
  CONSTRAINT fk_client_poi_po FOREIGN KEY (client_purchase_order_id) REFERENCES client_purchase_orders(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE purchase_orders ADD COLUMN client_purchase_order_id INT UNSIGNED NULL AFTER vendor_id, ADD INDEX idx_purchase_orders_client_po (client_purchase_order_id), ADD CONSTRAINT fk_purchase_orders_client_po FOREIGN KEY (client_purchase_order_id) REFERENCES client_purchase_orders(id) ON DELETE SET NULL ON UPDATE CASCADE;

INSERT IGNORE INTO permissions (permission_key, label, group_name, created_at) VALUES
('client_po.view', 'Lihat PO IN klien', 'Penjualan', NOW()),
('client_po.create', 'Buat PO IN klien', 'Penjualan', NOW()),
('client_po.edit', 'Edit PO IN klien', 'Penjualan', NOW()),
('client_po.delete', 'Hapus PO IN klien', 'Penjualan', NOW());
