-- Jalankan setelah database/eprocurement.sql pada database yang sudah berjalan.
-- Aman dijalankan berulang kali.
CREATE TABLE IF NOT EXISTS quotation_negotiations (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  quotation_id INT UNSIGNED NOT NULL,
  round_no INT UNSIGNED NOT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'pending',
  proposed_by VARCHAR(120) NOT NULL DEFAULT 'system',
  customer_message TEXT NULL,
  internal_notes TEXT NULL,
  snapshot_json LONGTEXT NOT NULL,
  subtotal DECIMAL(18,2) NOT NULL DEFAULT 0,
  tax_amount DECIMAL(18,2) NOT NULL DEFAULT 0,
  grand_total DECIMAL(18,2) NOT NULL DEFAULT 0,
  created_at DATETIME NULL,
  responded_at DATETIME NULL,
  responded_by VARCHAR(120) NULL,
  INDEX idx_negotiations_quotation_round (quotation_id, round_no),
  CONSTRAINT fk_negotiations_quotation FOREIGN KEY (quotation_id) REFERENCES quotations(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
