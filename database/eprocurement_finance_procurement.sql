-- Jalankan setelah database/eprocurement.sql dan database/eprocurement_auth.sql.
-- Migration CodeIgniter utama: 2026-09-24-000008_CreateFinanceProcurementModules.php + 2026-09-24-000009_AddBankAndTaxFields.php
-- File ini disediakan sebagai SQL pendamping instalasi manual. Jalankan hanya sekali pada database baru.
CREATE TABLE IF NOT EXISTS proforma_invoices (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, quotation_id INT UNSIGNED NOT NULL, invoice_no VARCHAR(80) NOT NULL UNIQUE, invoice_date DATE NOT NULL, due_date DATE NULL, amount DECIMAL(18,2) NOT NULL DEFAULT 0, payment_status VARCHAR(20) NOT NULL DEFAULT 'unpaid', payment_date DATE NULL, payment_method VARCHAR(80) NULL, payment_reference VARCHAR(160) NULL, proof_path VARCHAR(500) NULL, notes TEXT NULL, created_at DATETIME NULL, updated_at DATETIME NULL, INDEX idx_proforma_quotation (quotation_id), CONSTRAINT fk_proforma_quotation FOREIGN KEY (quotation_id) REFERENCES quotations(id) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS vendors (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(180) NOT NULL, code VARCHAR(60) NULL, address TEXT NULL, phone VARCHAR(80) NULL, email VARCHAR(160) NULL, pic_name VARCHAR(120) NULL, payment_terms VARCHAR(255) NULL, bank_name VARCHAR(120) NULL, bank_account_name VARCHAR(160) NULL, bank_account_number VARCHAR(80) NULL, bank_branch VARCHAR(120) NULL, tax_id VARCHAR(80) NULL, notes TEXT NULL, is_active TINYINT NOT NULL DEFAULT 1, created_at DATETIME NULL, updated_at DATETIME NULL, INDEX idx_vendors_name (name)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS purchase_orders (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, vendor_id INT UNSIGNED NOT NULL, po_no VARCHAR(80) NOT NULL UNIQUE, po_date DATE NOT NULL, expected_date DATE NULL, status VARCHAR(20) NOT NULL DEFAULT 'draft', subtotal DECIMAL(18,2) NOT NULL DEFAULT 0, tax_percent DECIMAL(5,2) NOT NULL DEFAULT 0, use_ppn TINYINT NOT NULL DEFAULT 0, ppn_percent DECIMAL(5,2) NOT NULL DEFAULT 11, use_pph TINYINT NOT NULL DEFAULT 0, pph_percent DECIMAL(5,2) NOT NULL DEFAULT 0, tax_amount DECIMAL(18,2) NOT NULL DEFAULT 0, pph_amount DECIMAL(18,2) NOT NULL DEFAULT 0, grand_total DECIMAL(18,2) NOT NULL DEFAULT 0, notes TEXT NULL, created_at DATETIME NULL, updated_at DATETIME NULL, INDEX idx_po_vendor (vendor_id), CONSTRAINT fk_po_vendor FOREIGN KEY(vendor_id) REFERENCES vendors(id) ON DELETE RESTRICT ON UPDATE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS purchase_order_items (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, purchase_order_id INT UNSIGNED NOT NULL, product_id INT UNSIGNED NULL, product_name VARCHAR(180) NOT NULL, description TEXT NULL, quantity DECIMAL(18,2) NOT NULL DEFAULT 1, unit VARCHAR(30) NOT NULL DEFAULT 'pcs', unit_price DECIMAL(18,2) NOT NULL DEFAULT 0, line_total DECIMAL(18,2) NOT NULL DEFAULT 0, INDEX idx_poi_po (purchase_order_id), CONSTRAINT fk_poi_po FOREIGN KEY(purchase_order_id) REFERENCES purchase_orders(id) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS vendor_bills (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, vendor_id INT UNSIGNED NOT NULL, purchase_order_id INT UNSIGNED NULL, bill_no VARCHAR(80) NOT NULL UNIQUE, bill_date DATE NOT NULL, due_date DATE NULL, amount DECIMAL(18,2) NOT NULL DEFAULT 0, use_ppn TINYINT NOT NULL DEFAULT 0, ppn_percent DECIMAL(5,2) NOT NULL DEFAULT 11, ppn_amount DECIMAL(18,2) NOT NULL DEFAULT 0, use_pph TINYINT NOT NULL DEFAULT 0, pph_percent DECIMAL(5,2) NOT NULL DEFAULT 0, pph_amount DECIMAL(18,2) NOT NULL DEFAULT 0, net_amount DECIMAL(18,2) NOT NULL DEFAULT 0, status VARCHAR(20) NOT NULL DEFAULT 'unpaid', paid_date DATE NULL, payment_reference VARCHAR(160) NULL, proof_path VARCHAR(500) NULL, notes TEXT NULL, created_at DATETIME NULL, updated_at DATETIME NULL, INDEX idx_bill_vendor (vendor_id), CONSTRAINT fk_bill_vendor FOREIGN KEY(vendor_id) REFERENCES vendors(id) ON DELETE RESTRICT ON UPDATE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS notifications (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, user_id INT UNSIGNED NULL, type VARCHAR(40) NOT NULL, title VARCHAR(180) NOT NULL, message TEXT NOT NULL, url VARCHAR(500) NULL, due_at DATETIME NULL, is_read TINYINT NOT NULL DEFAULT 0, created_at DATETIME NULL, INDEX idx_notifications_user_read (user_id,is_read)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- Permission rows are inserted by the CodeIgniter migration to remain idempotent.

-- PO IN klien dan relasi PO OUT: lihat database/eprocurement_client_purchase_orders.sql
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

-- Surat jalan client: lihat database/eprocurement_client_delivery_notes.sql
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
