-- EPROC Authentication & RBAC schema
-- Compatible with MariaDB 10.4+ / MySQL 8+
-- Prefer: php spark migrate. This file is provided for manual database deployment.

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(80) NOT NULL,
  `email` varchar(160) NOT NULL,
  `full_name` varchar(160) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `avatar_path` varchar(500) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `failed_login_attempts` smallint(5) NOT NULL DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `last_login_ip` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`), UNIQUE KEY `username` (`username`), UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `permission_key` varchar(100) NOT NULL,
  `label` varchar(160) NOT NULL,
  `group_name` varchar(80) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`), UNIQUE KEY `permission_key` (`permission_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `user_permissions` (
  `user_id` int(10) unsigned NOT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`permission_id`), KEY `permission_id` (`permission_id`),
  CONSTRAINT `fk_user_permissions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_user_permissions_permission` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `auth_audit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `username` varchar(80) DEFAULT NULL,
  `event` varchar(80) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`), KEY `user_id` (`user_id`), KEY `event` (`event`),
  CONSTRAINT `fk_auth_audit_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT IGNORE INTO `permissions` (`permission_key`,`label`,`group_name`,`created_at`) VALUES
('dashboard.view','Lihat dashboard','Dashboard',NOW()),
('companies.view','Lihat perusahaan','Perusahaan',NOW()),('companies.create','Tambah perusahaan','Perusahaan',NOW()),('companies.edit','Edit perusahaan','Perusahaan',NOW()),('companies.delete','Hapus perusahaan','Perusahaan',NOW()),
('products.view','Lihat produk','Produk',NOW()),('products.create','Tambah produk','Produk',NOW()),('products.edit','Edit produk','Produk',NOW()),('products.delete','Hapus produk','Produk',NOW()),
('quotations.view','Lihat penawaran','Penawaran',NOW()),('quotations.create','Tambah penawaran','Penawaran',NOW()),('quotations.edit','Edit penawaran','Penawaran',NOW()),('quotations.status','Ubah status penawaran','Penawaran',NOW()),('quotations.delete','Hapus penawaran','Penawaran',NOW()),('quotations.export','Ekspor penawaran','Penawaran',NOW()),
('settings.quotation','Kelola setting quotation','Konfigurasi',NOW()),('users.manage','Kelola pengguna dan akses','Konfigurasi',NOW());

-- Tidak ada password default yang disimpan. Setelah migrasi, buka /setup untuk membuat superadmin pertama.
