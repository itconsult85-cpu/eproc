-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 25, 2026 at 11:09 AM
-- Server version: 10.11.19-MariaDB-cll-lve
-- PHP Version: 8.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `k5193684_eproc`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth_audit_logs`
--

CREATE TABLE `auth_audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `username` varchar(80) DEFAULT NULL,
  `event` varchar(80) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_audit_logs`
--

INSERT INTO `auth_audit_logs` (`id`, `user_id`, `username`, `event`, `ip_address`, `user_agent`, `details`, `created_at`) VALUES
(1, 1, 'kokuo', 'login_success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, '2026-09-22 19:07:21'),
(2, 1, 'kokuo', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, '2026-09-22 19:10:18'),
(3, 2, 'dewi', 'login_success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, '2026-09-22 19:10:38'),
(4, 2, 'dewi', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, '2026-09-22 20:48:45'),
(5, 1, 'kokuo', 'login_success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, '2026-09-22 20:48:51'),
(6, 1, 'kokuo', 'login_success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, '2026-09-23 16:18:46'),
(7, 1, 'kokuo', 'login_success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, '2026-09-24 05:39:39'),
(8, 1, 'kokuo', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, '2026-09-24 09:20:48'),
(9, 3, 'ogonk', 'login_success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, '2026-09-24 09:20:56'),
(10, 3, 'ogonk', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, '2026-09-24 09:49:07'),
(11, 1, 'kokuo', 'login_success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, '2026-09-24 09:49:16'),
(12, 1, 'kokuo', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, '2026-09-24 09:56:04'),
(13, 3, 'ogonk', 'login_success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, '2026-09-24 09:56:08'),
(14, 3, 'ogonk', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, '2026-09-24 10:31:19'),
(15, 1, 'kokuo', 'login_success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, '2026-09-24 10:31:29'),
(16, NULL, 'admin', 'login_failed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', 'Invalid credentials or locked account', '2026-09-25 03:58:58'),
(17, 1, 'kokuo', 'login_success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, '2026-09-25 03:59:06'),
(18, 1, 'kokuo', 'login_success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', NULL, '2026-09-29 09:31:29'),
(19, 1, 'kokuo', 'login_success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', NULL, '2026-09-29 15:15:15'),
(20, 1, 'kokuo', 'login_success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', NULL, '2026-09-30 01:30:48'),
(21, 1, 'kokuo', 'login_success', '180.252.82.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', NULL, '2026-10-01 05:48:02'),
(22, 1, 'kokuo', 'login_success', '180.252.82.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', NULL, '2026-10-01 16:39:42'),
(23, 1, 'kokuo', 'logout', '180.252.82.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', NULL, '2026-10-01 16:42:25'),
(24, 1, 'kokuo', 'login_success', '180.252.82.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', NULL, '2026-10-01 16:47:18'),
(25, 1, 'kokuo', 'login_success', '180.252.82.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', NULL, '2026-10-02 14:13:57'),
(26, NULL, 'sofyan.trisentosaraya.co.id', 'login_failed', '180.243.179.237', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'Invalid credentials or locked account', '2026-10-02 14:18:31'),
(27, 3, 'ogonk', 'login_success', '180.243.179.237', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', NULL, '2026-10-02 14:19:30'),
(28, 3, 'ogonk', 'logout', '180.243.179.237', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', NULL, '2026-10-02 16:32:59'),
(29, 3, 'ogonk', 'login_success', '180.243.179.237', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', NULL, '2026-10-02 16:33:02'),
(30, 3, 'ogonk', 'logout', '180.243.179.237', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', NULL, '2026-10-02 18:06:32'),
(31, 1, 'kokuo', 'login_success', '180.243.179.237', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', NULL, '2026-10-05 19:57:30'),
(32, 1, 'kokuo', 'login_success', '180.252.94.19', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', NULL, '2026-10-07 13:32:10'),
(33, 1, 'kokuo', 'login_success', '180.252.94.19', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', NULL, '2026-10-07 17:55:19'),
(34, 1, 'kokuo', 'login_success', '180.252.94.19', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', NULL, '2026-10-08 18:47:51'),
(35, 1, 'kokuo', 'logout', '180.252.94.19', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', NULL, '2026-10-08 19:27:39'),
(36, 1, 'kokuo', 'login_success', '180.252.94.19', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', NULL, '2026-10-08 19:29:46'),
(37, NULL, 'admin', 'login_failed', '110.138.135.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', 'Invalid credentials or locked account', '2026-10-09 08:38:15'),
(38, 1, 'kokuo', 'login_success', '180.252.88.63', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', NULL, '2026-10-12 16:14:34'),
(39, 1, 'kokuo', 'login_success', '182.8.249.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', NULL, '2026-10-17 00:29:58'),
(40, 1, 'kokuo', 'login_success', '182.8.249.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', NULL, '2026-10-17 04:50:03'),
(41, 1, 'kokuo', 'login_success', '180.252.90.167', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', NULL, '2026-10-23 11:12:37'),
(42, 1, 'kokuo', 'login_success', '125.161.197.209', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', NULL, '2026-10-29 21:06:27'),
(43, 2, 'dewi', 'login_success', '125.161.197.209', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', NULL, '2026-10-29 21:08:13'),
(44, 2, 'dewi', 'logout', '125.161.197.209', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', NULL, '2026-10-29 22:33:31'),
(45, 1, 'kokuo', 'login_success', '180.252.87.49', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', NULL, '2026-11-02 12:04:49'),
(46, 1, 'kokuo', 'login_success', '180.252.87.49', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', NULL, '2026-11-03 09:48:23'),
(47, 1, 'kokuo', 'login_success', '182.8.5.62', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', NULL, '2026-11-14 15:40:10'),
(48, 2, 'dewi', 'login_success', '125.161.199.116', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', NULL, '2026-11-14 15:44:46'),
(49, 2, 'dewi', 'logout', '125.161.199.116', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', NULL, '2026-11-14 15:58:34'),
(50, 1, 'kokuo', 'logout', '182.8.5.62', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', NULL, '2026-11-14 17:19:58'),
(51, 1, 'kokuo', 'login_success', '182.8.5.62', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', NULL, '2026-11-14 17:20:02'),
(52, 1, 'kokuo', 'login_success', '182.8.5.62', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', NULL, '2026-11-14 21:10:15'),
(53, 1, 'kokuo', 'login_success', '180.243.178.38', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', NULL, '2026-11-19 13:24:17'),
(54, 2, 'dewi', 'login_success', '180.243.178.38', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', NULL, '2026-11-20 13:59:49'),
(55, 2, 'dewi', 'login_success', '110.138.115.133', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', NULL, '2026-11-21 10:15:22'),
(56, 2, 'dewi', 'logout', '110.138.115.133', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', NULL, '2026-11-21 10:28:45'),
(57, 1, 'kokuo', 'login_success', '204.51.42.198', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7_12 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/157.0.8103.37 Mobile/15E148 Safari/604.1', NULL, '2026-11-21 10:43:27'),
(58, 1, 'kokuo', 'login_success', '204.51.40.131', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', NULL, '2026-11-25 10:51:12');

-- --------------------------------------------------------

--
-- Table structure for table `auth_login_rate_limits`
--

CREATE TABLE `auth_login_rate_limits` (
  `ip_address` varchar(45) NOT NULL,
  `failed_attempts` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `last_attempt_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_login_rate_limits`
--

INSERT INTO `auth_login_rate_limits` (`ip_address`, `failed_attempts`, `locked_until`, `last_attempt_at`) VALUES
('110.138.135.148', 1, NULL, '2026-10-09 08:38:15');

-- --------------------------------------------------------

--
-- Table structure for table `bast_documents`
--

CREATE TABLE `bast_documents` (
  `id` int(10) UNSIGNED NOT NULL,
  `bast_no` varchar(100) NOT NULL,
  `source_type` varchar(30) NOT NULL,
  `source_id` int(10) UNSIGNED NOT NULL,
  `source_no` varchar(100) NOT NULL,
  `source_title` varchar(220) DEFAULT NULL,
  `source_date` date DEFAULT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `handover_date` date NOT NULL,
  `location` varchar(180) DEFAULT NULL,
  `recipient_name` varchar(160) NOT NULL,
  `recipient_position` varchar(120) DEFAULT NULL,
  `handed_over_by` varchar(160) DEFAULT NULL,
  `handed_over_position` varchar(120) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'completed',
  `items_json` longtext NOT NULL,
  `notes` text DEFAULT NULL,
  `created_by` varchar(120) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_delivery_notes`
--

CREATE TABLE `client_delivery_notes` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_purchase_order_id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `delivery_no` varchar(100) NOT NULL,
  `delivery_date` date NOT NULL,
  `destination` varchar(220) DEFAULT NULL,
  `recipient_name` varchar(160) NOT NULL,
  `recipient_position` varchar(120) DEFAULT NULL,
  `delivered_by` varchar(160) DEFAULT NULL,
  `delivered_position` varchar(120) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'draft',
  `items_json` longtext NOT NULL,
  `notes` text DEFAULT NULL,
  `created_by` varchar(120) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_purchase_orders`
--

CREATE TABLE `client_purchase_orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `quotation_id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `po_no` varchar(100) NOT NULL,
  `po_date` date NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'received',
  `subtotal` decimal(18,2) NOT NULL DEFAULT 0.00,
  `tax_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(18,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_purchase_order_items`
--

CREATE TABLE `client_purchase_order_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_purchase_order_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `product_name` varchar(180) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` decimal(18,2) NOT NULL DEFAULT 1.00,
  `unit` varchar(30) NOT NULL DEFAULT 'pcs',
  `unit_price` decimal(18,2) NOT NULL DEFAULT 0.00,
  `discount_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(18,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(160) NOT NULL,
  `quotation_prefix` varchar(12) DEFAULT 'CCIP',
  `quotation_code` varchar(30) DEFAULT NULL,
  `quotation_sequence` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `address` text DEFAULT NULL,
  `phone` varchar(40) DEFAULT NULL,
  `email` varchar(160) DEFAULT NULL,
  `pic_name` varchar(120) DEFAULT NULL,
  `pic_phone` varchar(40) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `quotation_prefix`, `quotation_code`, `quotation_sequence`, `address`, `phone`, `email`, `pic_name`, `pic_phone`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'PT. INDONESIA CHEMICAL ALUMINA', 'CCIP', NULL, 5, 'Jl. Trans Kalimantan, Dusun Piasak-Desa Pedalaman,\r\nKecamatan Tayan Hilir, Kabupaten Sanggau 78564,\r\nKalimantan Barat Indonesia', '(62-21) 780 3340', 'escm@pt-ca.com', 'Siapa aja', '(62-21) 780 3340', '', '2026-09-21 14:37:57', '2026-09-21 14:37:57'),
(2, 'PT. BORNEO ALUMINA INDONESIA', 'CCIP', NULL, 1, 'Smelter Grade Alumina Refinery (SGAR)\r\nJl. Poros Bukit Batu RT 009 / RW 003\r\nDusun Kembang Lada, Desa Bukit Batu\r\nKec. Sungai Kunyit, Kab. Mempawah\r\nProv. Kalimantan Barat, 78971', '', 'procurement-team@bai.id', 'Siapa aja', '(62-21) 780 3340', '', '2026-09-21 15:56:24', '2026-09-21 21:01:01');

-- --------------------------------------------------------

--
-- Table structure for table `company_bank_accounts`
--

CREATE TABLE `company_bank_accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `bank_name` varchar(120) NOT NULL,
  `account_name` varchar(160) NOT NULL,
  `account_number` varchar(80) NOT NULL,
  `branch` varchar(120) DEFAULT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'IDR',
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  `is_default` tinyint(4) NOT NULL DEFAULT 0,
  `notes` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-09-21-000001', 'App\\Database\\Migrations\\CreateEprocurementTables', 'default', 'App', 1790001322, 1),
(2, '2026-09-21-000002', 'App\\Database\\Migrations\\AddQuotationSettingsAndMedia', 'default', 'App', 1790002176, 2),
(3, '2026-09-21-000003', 'App\\Database\\Migrations\\AddGenericDatasheetFields', 'default', 'App', 1790002924, 3),
(4, '2026-09-22-000004', 'App\\Database\\Migrations\\CreateQuotationStatusLogs', 'default', 'App', 1790019592, 4),
(5, '2026-09-23-000005', 'App\\Database\\Migrations\\CreateAuthenticationTables', 'default', 'App', 1790103968, 5),
(6, '2026-09-24-000006', 'App\\Database\\Migrations\\AddQuotationNumberConfiguration', 'default', 'App', 1790182599, 6),
(7, '2026-09-24-000007', 'App\\Database\\Migrations\\CreateQuotationNegotiations', 'default', 'App', 1790236703, 7),
(8, '2026-09-24-000008', 'App\\Database\\Migrations\\CreateFinanceProcurementModules', 'default', 'App', 1790245861, 8),
(9, '2026-09-24-000009', 'App\\Database\\Migrations\\AddBankAndTaxFields', 'default', 'App', 1790246791, 9),
(10, '2026-09-24-000010', 'App\\Database\\Migrations\\CreateCompanyBankAccounts', 'default', 'App', 1790248504, 10),
(11, '2026-09-24-000011', 'App\\Database\\Migrations\\AddMasterQuotationSnapshot', 'default', 'App', 1790249384, 11),
(12, '2026-09-24-000012', 'App\\Database\\Migrations\\AddFinalQuotationSnapshot', 'default', 'App', 1790249951, 12),
(13, '2026-09-24-000013', 'App\\Database\\Migrations\\RestoreQuotationMasterAndFinalizeSnapshots', 'default', 'App', 1790249951, 12),
(14, '2026-09-29-000008', 'App\\Database\\Migrations\\CreateTenderDocuments', 'default', 'App', 1790674192, 13),
(15, '2026-09-29-000009', 'App\\Database\\Migrations\\RemoveTenderDocumentCompanyId', 'default', 'App', 1790675812, 14),
(16, '2026-09-29-000010', 'App\\Database\\Migrations\\CreateAuthLoginRateLimits', 'default', 'App', 1790678665, 15),
(17, '2026-09-29-000011', 'App\\Database\\Migrations\\CreateBastDocuments', 'default', 'App', 1790680325, 16),
(18, '2026-09-29-000012', 'App\\Database\\Migrations\\CreateClientPurchaseOrders', 'default', 'App', 1790680328, 16),
(19, '2026-09-29-000013', 'App\\Database\\Migrations\\AddClientPoToPurchaseOrders', 'default', 'App', 1790680330, 16),
(20, '2026-09-29-000014', 'App\\Database\\Migrations\\CreateClientDeliveryNotes', 'default', 'App', 1790732236, 17);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `type` varchar(40) NOT NULL,
  `title` varchar(180) NOT NULL,
  `message` text NOT NULL,
  `url` varchar(500) DEFAULT NULL,
  `due_at` datetime DEFAULT NULL,
  `is_read` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `url`, `due_at`, `is_read`, `created_at`) VALUES
(1, NULL, 'quotation_expired', 'Quotation kadaluarsa', 'Quotation Q-03702/TRE-BAI/X/2026 telah melewati masa berlaku.', '/quotations/356a192b7913b04c54574d18c28d46e6395428ab', '2026-10-17 00:29:58', 1, '2026-10-17 00:29:58'),
(2, NULL, 'quotation_expired', 'Quotation kadaluarsa', 'Quotation CCIP00108/TRE-ICA/X/2026 telah melewati masa berlaku.', '/quotations/da4b9237bacccdf19c0760cab7aec4a8359010b0', '2026-10-23 11:12:37', 1, '2026-10-23 11:12:37'),
(3, NULL, 'quotation_expired', 'Quotation kadaluarsa', 'Quotation CCIP00223/TRE-ICA/X/2026 telah melewati masa berlaku.', '/quotations/77de68daecd823babbb58edb1c8e14d7106e83bb', '2026-11-03 09:48:23', 1, '2026-11-03 09:48:23'),
(4, NULL, 'quotation_expired', 'Quotation kadaluarsa', 'Quotation CCIP00102/TRE-ICA/XI/2026 telah melewati masa berlaku.', '/quotations/1b6453892473a467d07372d45eb05abc2031647a', '2026-11-14 15:40:11', 1, '2026-11-14 15:40:11'),
(5, NULL, 'quotation_expired', 'Quotation kadaluarsa', 'Quotation CCIP00202/TRE-ICA/XI/2026 telah melewati masa berlaku.', '/quotations/ac3478d69a3c81fa62e60f5c3696165a4e5e6ac4', '2026-11-14 15:40:11', 1, '2026-11-14 15:40:11'),
(6, NULL, 'quotation_expired', 'Quotation kadaluarsa', 'Quotation Q-03702/TRE-BAI/X/2026 telah melewati masa berlaku.', '/quotations/356a192b7913b04c54574d18c28d46e6395428ab', '2026-11-14 17:19:38', 0, '2026-11-14 17:19:38'),
(7, NULL, 'quotation_expired', 'Quotation kadaluarsa', 'Quotation CCIP00108/TRE-ICA/X/2026 telah melewati masa berlaku.', '/quotations/da4b9237bacccdf19c0760cab7aec4a8359010b0', '2026-11-14 17:19:38', 0, '2026-11-14 17:19:38'),
(8, NULL, 'quotation_expired', 'Quotation kadaluarsa', 'Quotation CCIP00223/TRE-ICA/X/2026 telah melewati masa berlaku.', '/quotations/77de68daecd823babbb58edb1c8e14d7106e83bb', '2026-11-14 17:19:38', 0, '2026-11-14 17:19:38'),
(9, NULL, 'quotation_expired', 'Quotation kadaluarsa', 'Quotation CCIP00102/TRE-ICA/XI/2026 telah melewati masa berlaku.', '/quotations/1b6453892473a467d07372d45eb05abc2031647a', '2026-11-19 13:24:18', 0, '2026-11-19 13:24:18'),
(10, NULL, 'quotation_expired', 'Quotation kadaluarsa', 'Quotation CCIP00202/TRE-ICA/XI/2026 telah melewati masa berlaku.', '/quotations/ac3478d69a3c81fa62e60f5c3696165a4e5e6ac4', '2026-11-19 13:24:18', 0, '2026-11-19 13:24:18'),
(11, NULL, 'quotation_expired', 'Quotation kadaluarsa', 'Quotation CCIP00623/TRE-ICA/IX/2026 telah melewati masa berlaku.', '/quotations/c1dfd96eea8cc2b62785275bca38ac261256e278', '2026-11-19 13:49:49', 0, '2026-11-19 13:49:49');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `permission_key` varchar(100) NOT NULL,
  `label` varchar(160) NOT NULL,
  `group_name` varchar(80) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `permission_key`, `label`, `group_name`, `created_at`) VALUES
(1, 'dashboard.view', 'Lihat dashboard', 'Dashboard', '2026-09-22 19:06:07'),
(2, 'companies.view', 'Lihat perusahaan', 'Perusahaan', '2026-09-22 19:06:07'),
(3, 'companies.create', 'Tambah perusahaan', 'Perusahaan', '2026-09-22 19:06:07'),
(4, 'companies.edit', 'Edit perusahaan', 'Perusahaan', '2026-09-22 19:06:07'),
(5, 'companies.delete', 'Hapus perusahaan', 'Perusahaan', '2026-09-22 19:06:07'),
(6, 'products.view', 'Lihat produk', 'Produk', '2026-09-22 19:06:07'),
(7, 'products.create', 'Tambah produk', 'Produk', '2026-09-22 19:06:07'),
(8, 'products.edit', 'Edit produk', 'Produk', '2026-09-22 19:06:07'),
(9, 'products.delete', 'Hapus produk', 'Produk', '2026-09-22 19:06:07'),
(10, 'quotations.view', 'Lihat penawaran', 'Penawaran', '2026-09-22 19:06:07'),
(11, 'quotations.create', 'Tambah penawaran', 'Penawaran', '2026-09-22 19:06:07'),
(12, 'quotations.edit', 'Edit penawaran', 'Penawaran', '2026-09-22 19:06:07'),
(13, 'quotations.status', 'Ubah status penawaran', 'Penawaran', '2026-09-22 19:06:07'),
(14, 'quotations.delete', 'Hapus penawaran', 'Penawaran', '2026-09-22 19:06:07'),
(15, 'quotations.export', 'Ekspor penawaran', 'Penawaran', '2026-09-22 19:06:07'),
(16, 'settings.quotation', 'Kelola setting quotation', 'Konfigurasi', '2026-09-22 19:06:07'),
(17, 'users.manage', 'Kelola pengguna dan akses', 'Konfigurasi', '2026-09-22 19:06:07'),
(18, 'proforma.view', 'Lihat proforma invoice', 'Keuangan', '2026-09-24 10:31:00'),
(19, 'proforma.edit', 'Kelola pembayaran proforma', 'Keuangan', '2026-09-24 10:31:00'),
(20, 'proforma.export', 'Unduh proforma invoice', 'Keuangan', '2026-09-24 10:31:00'),
(21, 'vendors.view', 'Lihat vendor', 'Pengadaan', '2026-09-24 10:31:00'),
(22, 'vendors.create', 'Tambah vendor', 'Pengadaan', '2026-09-24 10:31:00'),
(23, 'vendors.edit', 'Edit vendor', 'Pengadaan', '2026-09-24 10:31:01'),
(24, 'vendors.delete', 'Hapus vendor', 'Pengadaan', '2026-09-24 10:31:01'),
(25, 'purchase_orders.view', 'Lihat purchase order', 'Pengadaan', '2026-09-24 10:31:01'),
(26, 'purchase_orders.create', 'Tambah purchase order', 'Pengadaan', '2026-09-24 10:31:01'),
(27, 'purchase_orders.edit', 'Edit purchase order', 'Pengadaan', '2026-09-24 10:31:01'),
(28, 'purchase_orders.delete', 'Hapus purchase order', 'Pengadaan', '2026-09-24 10:31:01'),
(29, 'vendor_bills.view', 'Lihat tagihan vendor', 'Keuangan', '2026-09-24 10:31:01'),
(30, 'vendor_bills.create', 'Tambah tagihan vendor', 'Keuangan', '2026-09-24 10:31:01'),
(31, 'vendor_bills.edit', 'Edit tagihan vendor', 'Keuangan', '2026-09-24 10:31:01'),
(32, 'vendor_bills.delete', 'Hapus tagihan vendor', 'Keuangan', '2026-09-24 10:31:01'),
(33, 'notifications.view', 'Lihat notifikasi', 'Sistem', '2026-09-24 10:31:01'),
(34, 'tenders.view', 'Lihat dokumen tender', 'Tender', '2026-09-29 16:29:51'),
(35, 'tenders.create', 'Tambah dokumen tender', 'Tender', '2026-09-29 16:29:51'),
(36, 'tenders.edit', 'Edit dokumen tender', 'Tender', '2026-09-29 16:29:51'),
(37, 'tenders.delete', 'Hapus dokumen tender', 'Tender', '2026-09-29 16:29:51'),
(38, 'bast.view', 'Lihat berita acara serah terima', 'Pengadaan', '2026-09-29 11:12:03'),
(39, 'bast.create', 'Buat berita acara serah terima', 'Pengadaan', '2026-09-29 11:12:04'),
(40, 'bast.edit', 'Edit berita acara serah terima', 'Pengadaan', '2026-09-29 11:12:04'),
(41, 'bast.delete', 'Hapus berita acara serah terima', 'Pengadaan', '2026-09-29 11:12:04'),
(42, 'bast.export', 'Unduh PDF berita acara serah terima', 'Pengadaan', '2026-09-29 11:12:05'),
(43, 'client_po.view', 'Lihat PO IN klien', 'Penjualan', NULL),
(44, 'client_po.create', 'Buat PO IN klien', 'Penjualan', NULL),
(45, 'client_po.edit', 'Edit PO IN klien', 'Penjualan', NULL),
(46, 'client_po.delete', 'Hapus PO IN klien', 'Penjualan', NULL),
(47, 'client_delivery_note.view', 'Lihat surat jalan client', 'Penjualan', NULL),
(48, 'client_delivery_note.create', 'Buat surat jalan client', 'Penjualan', NULL),
(49, 'client_delivery_note.edit', 'Edit surat jalan client', 'Penjualan', NULL),
(50, 'client_delivery_note.delete', 'Hapus surat jalan client', 'Penjualan', NULL),
(51, 'client_delivery_note.export', 'Unduh PDF surat jalan client', 'Penjualan', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `sku` varchar(80) DEFAULT NULL,
  `name` varchar(180) NOT NULL,
  `brand` varchar(120) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `datasheet` text DEFAULT NULL,
  `technical_specs` text DEFAULT NULL,
  `applications` text DEFAULT NULL,
  `standards` text DEFAULT NULL,
  `datasheet_file_path` varchar(500) DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `image_path` varchar(500) DEFAULT NULL,
  `video_url` varchar(500) DEFAULT NULL,
  `video_path` varchar(500) DEFAULT NULL,
  `cost_price` decimal(18,2) NOT NULL DEFAULT 0.00,
  `selling_price` decimal(18,2) NOT NULL DEFAULT 0.00,
  `store_name` varchar(160) DEFAULT NULL,
  `store_url` varchar(500) DEFAULT NULL,
  `store_phone` varchar(40) DEFAULT NULL,
  `store_pic` varchar(120) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `company_id`, `sku`, `name`, `brand`, `description`, `datasheet`, `technical_specs`, `applications`, `standards`, `datasheet_file_path`, `image_url`, `image_path`, `video_url`, `video_path`, `cost_price`, `selling_price`, `store_name`, `store_url`, `store_phone`, `store_pic`, `is_active`, `created_at`, `updated_at`) VALUES
(1, NULL, '12312313', 'Safety Boot Cheetah 7288C', 'Cheetah', 'Sepatu  Safety  Cheetah  7288  adalah model  pull-up  rigger  boot  yang   dirancang untuk kepraktisan dan perlindungan maksimal di  area  kerja ekstrem.  Dibuat dari bahan kulit asli (genuine  leather)  berkualitas tinggi yang  tahan lama,  sepatu ini dilengkapi dengan pelindung jari baja (Steel Toe Cap) yang mampu menahan benturan hingga 200 Joules. Dilengkapi dengan sol  luar Dual  Density  Polyurethane  (PU)  yang memberikan kenyamanan ekstra sekaligus ketahanan terhadap minyak dan permukaan licin. Desain slip-on memudahkan pemakaian dan pelepasan secara cepat, sangat direkomendasikan untuk pekerja di sektor pertambangan, minyak dan gas, serta konstruksi berat                                ', '                    Antistatic, Oil Resistant (Tahan Minyak), Slip Resistant (Anti Licin), Shock Absorbing                                ', '[{\"label\":\"Nama Produk\",\"value\":\"Sepatu Safety Boot Cheetah\"},{\"label\":\"Model\\/Type\",\"value\":\"7288 (Pull-up Rigger Boot)\"},{\"label\":\"Warna\",\"value\":\"Cokelat (Brown)\"},{\"label\":\"Material Utama\",\"value\":\"Premium Genuine Leather (Kulit Asli)\"},{\"label\":\"Pelindung Jari (Toe Cap)\",\"value\":\"Steel Toe Cap (Tahan benturan 200 Joules\"},{\"label\":\"Material Sol Luar\",\"value\":\"Dual Density PU (Polyurethane) Outsole\"}]', '                    Pertambangan, Minyak & Gas, Konstruksi, Manufaktur, Perkebunan                                ', 'SNI 7079, EN ISO 20345', NULL, NULL, '/uploads/products/1790005099_0b7858e624e9d3f7bed2.png', NULL, NULL, 500000.00, 737000.00, 'Test', '', '089182918291', 'Test', 1, '2026-09-21 15:38:21', '2026-09-24 09:26:26'),
(2, NULL, '21321312', 'Safety Boot Cougar 1911 Toe Cap', 'Cougar', 'Sepatu Boots Safety Yellow Cougar 1911 Toecap dirancang khusus untuk memberikan perlindungan maksimal pada kaki pekerja di lingkungan yang keras dan menantang. Dilengkapi dengan pelindung jari baja (Steel Toe Cap) yang kuat untuk menahan benturan dan material PVC/Karet berkualitas tinggi yang sepenuhnya tahan air (waterproof). Sepatu ini juga memiliki fitur sol luar yang anti-slip, menjadikannya pilihan ideal untuk industri berat, konstruksi, pertambangan, dan pertanian.                                ', '                    Waterproof (Tahan Air), Anti-Slip (Anti Licin), Oil Resistant (Tahan Minyak), Chemical Resistant (Tahan Bahan Kimia Ringan)                                ', '[{\"label\":\"Nama Produk\",\"value\":\"Sepatu Boots Safety Cougar\"},{\"label\":\"Model\\/Tipe\",\"value\":\"1911 Toecap\"},{\"label\":\"Warna\",\"value\":\"Kuning (Yellow) dengan Sol Abu-abu\"},{\"label\":\"Material Utama\",\"value\":\"PVC \\/ Rubber (Karet Berkualitas Tinggi)\"},{\"label\":\"Pelindung Jari (Toe Cap)\",\"value\":\"Steel Toe Cap (Baja Pelindung Jari)\"}]', '                    Konstruksi, Pertambangan, Perkebunan, Industri Minyak & Gas, Pabrik                                ', 'CE EN ISO 20345 (Tersertifikasi)', NULL, NULL, '/uploads/products/1790005772_b3cb0edff48380c13d00.png', NULL, NULL, 175000.00, 317000.00, 'Test', '', '089182918291', 'Test', 1, '2026-09-21 15:49:32', '2026-09-24 09:26:37'),
(3, NULL, '', 'Axial Fan ', '', '                    Axial fan (kipas aksial) adalah jenis kipas industri yang mengalirkan udara secara tegak lurus melewati baling-balingnya. Arah udara masuk dan keluar sejajar dengan poros (aksis) putaran kipas.\r\nPrinsip kerja ini mirip dengan baling-baling pesawat terbang atau kipas angin rumah tangga biasa, di mana udara didorong lurus ke depan atau dihisap lurus ke belakang\r\nKarakteristik Utama Axial Fan\r\n• Volume Udara Besar: Kipas ini sangat efektif untuk memindahkan atau mengalirkan udara dalam volume yang sangat besar (high flow rate).\r\n• Tekanan Rendah: Axial fan dirancang untuk lingkungan yang tidak membutuhkan tekanan udara tinggi (low static pressure). Kipas ini kurang cocok jika harus dihubungkan dengan pipa saluran udara (ducting) yang terlalu panjang atau berliku.\r\n• Hemat Energi: Konsumsi daya listriknya relatif rendah dibandingkan dengan kipas sentrifugal (blower) untuk kapasitas volume udara yang sama                                                                                                                                                                                                                                                                                     ', '                                        Arti Kode Model \"LTF 4D-300  \r\n4D: Mengindikasikan tipe motor 4 Pole (kecepatan putaran menengah, sekitar 1300-an RPM) \r\nD merujuk pada arus Three Phase (380V)\r\n\r\nDesain & Material\r\n• Arah Aliran Udara: Menghisap atau meniup udara secara sejajar dengan poros baling-baling (Axial).\r\n• Material Baling-Baling: Pelat besi/baja tipis yang dilapisi cat antikarat (sheet steel powder coated), dirancang kuat untuk pemakaian industrial kontinu.\r\n• Sistem Bearing: Menggunakan dual ball bearing untuk menjaga putaran tetap stabil dan tahan lama pada suhu panas mesin pendingin.\r\n• Proteksi Grid: Dilengkapi jaring besi pengaman (grill mesh) pada sisi luar baling-baling                                                                                                                                                                                                                                                                                                           ', '[{\"label\":\"Model\",\"value\":\"LTF 4D-300 (Tipe 4D menandakan motor 4-Pole dengan sistem listrik 3 Phase)\"},{\"label\":\"Ukuran Diameter\",\"value\":\"12 Inchi atau 300 mm\"},{\"label\":\"Tegangan Listrik (Voltage)\",\"value\":\"380 Volt AC\"},{\"label\":\"Frekuensi\",\"value\":\"50  Hz\"},{\"label\":\"Fase Listrik\",\"value\":\"3 Phase\"},{\"label\":\"Konsumsi Daya (Power)\",\"value\":\"100 Watt\"},{\"label\":\"Kuat Arus (Current)\",\"value\":\"0,24 Ampere\"},{\"label\":\"Kecepatan Putaran (Speed)\",\"value\":\"± 1360 RPM hingga 1380 RPM\"}]', '                    • Sistem Pendingin (Cooling): Dipasang pada unit kondensor AC besar, evaporator cold storage (gudang pendingin), radiator genset, dan panel listrik untuk membuang panas mesin.\r\n• Sirkulasi Udara (Ventilasi): Dipasang di dinding pabrik, gudang, atau ruang bawah tanah (basement) untuk membuang udara panas/kotor dan menghisap udara segar dari luar.\r\n                                                                                                                                                                                                                                                            ', '', NULL, NULL, '/uploads/products/1790930834_72674bab4ae406aaf003.png', NULL, NULL, 775000.00, 1292000.00, 'LESTARI REFRIGERATION PARTS', 'https://www.tokopedia.com/lestarirefrigeration/axial-fan-ltf-12-ltf-4d-300mm-3-phase?utm_source=google&utm_medium=organic&utm_campaign=pdp', '', '', 1, '2026-10-02 15:30:32', '2026-10-02 17:08:35'),
(4, NULL, '', 'CKE Drum Fan 24 Inch', 'CKE', 'CKE Drum Fan 24 Inch 380V 3 Phase – Blower Tabung Exhaust Industri Low Noise (tipe DFL-EX24/3-YL) merupakan kipas angin tabung industrial yang dirancang khusus untuk mengalirkan sirkulasi udara pada ruangan besar. Alat ini beroperasi menggunakan listrik industri 380V (3 Phase) dengan daya motor yang sangat efisien, yaitu sebesar 255 Watt. Kipas ini ideal dipasang pada area pabrik, gudang, workshop, rumah sakit, hingga dapur komersial untuk membuang udara pengap atau memasok udara segar.', 'Catatan Penting Aplikasi & Pemasangan\r\n• Metode Pemasangan: Fleksibel. Unit dapat ditanam langsung pada dinding bangunan atau disambungkan ke sistem pipa saluran udara (pipa ducting) menggunakan selang fleksibel (flexible hose).\r\n• Lingkungan Kerja: Cocok untuk keperluan sirkulasi udara di area pabrik, gudang, bengkel (workshop), rumah sakit, laboratorium, dan ruang publik.\r\n• Sertifikasi Mutu: Terdaftar dan terverifikasi oleh SNI (Standar Nasional Indonesia), KAN (Komite Akreditasi Nasional - Laboratorium Penguji), serta terdaftar sebagai Member of AMCA International', '[{\"label\":\"Kode Barang\",\"value\":\"DFL-EX24\\/3-YL\"},{\"label\":\"Ukuran Baling-Baling\",\"value\":\"24 inci\"},{\"label\":\"Daya Listrik\",\"value\":\"255 Watt\"},{\"label\":\"Tegangan \\/ Fase\",\"value\":\"380 Volt \\/ 3 Phase\"},{\"label\":\"Kecepatan Putaran\",\"value\":\"1.400 RPM\"},{\"label\":\"Kapasitas Udara\",\"value\":\"7.200 CMH\"},{\"label\":\"Tingkat Kebisingan\",\"value\":\"64 dB (Lebih senyap)\"},{\"label\":\"Bahan Casing & Baling\",\"value\":\"Besi tebal\"},{\"label\":\"Kumparan Motor\",\"value\":\"100% Kawat Tembaga\"},{\"label\":\"Berat Bersih\",\"value\":\"32,29 kg\"}]', 'Aplikasi fungsi utama dari CKE Drum Fan 24 Inch 380V adalah untuk mengontrol kualitas udara di dalam ruangan luas dengan dua cara kerja:\r\n• Sistem Exhaust (Pembuangan): Menyedot dan membuang udara panas, pengap, debu, asap pabrik, serta bau menyengat keluar ruangan.\r\n• Sistem Blower (Pemasokan): Meniupkan dan menyalurkan udara segar dari luar ke dalam area yang minim ventilasi.\r\nBerikut adalah tempat-tempat utama yang paling sering menggunakan alat ini:\r\n• Pabrik dan Gudang: Menghilangkan hawa panas dari mesin produksi dan menjaga suhu barang tetap stabil.\r\n• Dapur Komersial / Restoran: Membuang asap masakan berlebih dan uap panas dari kompor besar.\r\n• Basemen dan Area Parkir: Membuang gas beracun emisi kendaraan (karbon monoksida) agar area tidak pengap.\r\n• Bengkel Las / Bubut: Menyedot asap kimia berbahaya dan debu sisa produksi agar pekerja tetap aman.\r\n• Peternakan Modern (Closed House): Mengatur sirkulasi udara dan menjaga suhu kandang ayam atau sapi agar tidak stres.\r\nApakah Anda berencana memasang kipas ini di area pabrik, gudang, atau dapur komersial? Jika Anda punya ukuran dimensi ruangan (panjang x lebar x tinggi), saya bisa bantu hitungkan apakah satu unit kipas ini sudah cukup atau belum.\r\n', '', NULL, NULL, '/uploads/products/1790939027_516b7ccdb7cc4d11a668.jpg', NULL, NULL, 3557280.00, 5785128.00, 'CKE Kipas Industrial', 'https://www.tokopedia.com/cke-kipas-industrial/drum-fan-extra-low-noise-24-inch-380v-blower-tabung-axial-drum-fan?extParam=ivf%3Dfalse%26keyword%3Dexhaust+fan+24+inch+3+phase%26search_id%3D20261001034750352FE25DAF686B2C8DX8%26src%3Dsearch', '', '', 1, '2026-10-02 18:03:47', '2026-10-02 18:03:47'),
(5, NULL, '', 'TSI 8530 DustTrak II Aerosol Monitor', 'TSI', 'TSI DustTrak™ II Aerosol Monitor 8530 adalah fotometer laser hamburan cahaya berbasis baterai desktop yang menyediakan pembacaan massa aerosol secara real-time sekaligus pencatatan data (data-logging). Dirancang khusus untuk memonitor kontaminan seperti debu, asap, uap, dan kabut secara presisi di berbagai kondisi lingkungan kerja. Instrumen ini menggunakan sistem udara pelindung (sheath air system) yang mengisolasi partikel aerosol di dalam ruang optik untuk menjaga kebersihan lensa.', 'Paket unit standar sudah termasuk: Carrying case, Baterai Li-Ion, Software TrakPro, Adaptor AC, Kabel USB, Zero filter, Tutup Inlet, dan Impactor Kit (PM1, PM2.5, PM4, PM10).', '[{\"label\":\"Tipe Sensor\",\"value\":\"Fotometer hamburan cahaya 90°\"},{\"label\":\"Rentang Konsentrasi\",\"value\":\"0.001 hingga 400 mg\\/m³\"},{\"label\":\"Rentang Ukuran Partikel\",\"value\":\"0.1 hingga 10 μm\"},{\"label\":\"Resolusi\",\"value\":\"±0.1% dari pembacaan atau 0.001 mg\\/m³\"},{\"label\":\"Stabilitas Titik Nol (Zero Stability)\",\"value\":\"±0.002 mg\\/m³ per 24 jam dengan time constant 10 detik\"},{\"label\":\"Pengambilan Sampel (Gravimetrik)\",\"value\":\"Kartrid (kaset filter) 37 mm lepas-pasang (filter user-supplied)\"},{\"label\":\"Laju Aliran (Flow Rate)\",\"value\":\"Set pabrik 3.0 L\\/menit (Dapat diatur 1.40 hingga 3.0 L\\/menit)\"},{\"label\":\"Akurasi Aliran\",\"value\":\"±5% dari titik pengaturan internal\"},{\"label\":\"Suhu Operasional\",\"value\":\"0 hingga 50°C (32 hingga 120°F)\"},{\"label\":\"Suhu Penyimpanan\",\"value\":\"-20 hingga 60°C (-4 hingga 140°F)\"},{\"label\":\"Kelembapan Operasional\",\"value\":\"0 hingga 95% RH, non-kondensasi\"},{\"label\":\"Penyimpanan Data (Data Logging)\",\"value\":\"5 MB (Lebih dari 60.000 titik data \\/ ± 45 hari pada interval 1 menit)\"},{\"label\":\"Interval Pencatatan (Log Interval)\",\"value\":\"Dapat disesuaikan pengguna dari 1 detik hingga 1 jam\"},{\"label\":\"Layar Tampilan\",\"value\":\"Layar sentuh berwarna VGA 5.7 inci (Color Touchscreen)\"},{\"label\":\"Komunikasi & Antarmuka\",\"value\":\"USB (Host & Device) dan koneksi Ethernet\"},{\"label\":\"Output Analog\",\"value\":\"0 hingga 5 V atau 4 hingga 20 mA (Skala dapat diatur pengguna)\"},{\"label\":\"Output Alarm\",\"value\":\"Relai (relay) atau buzzer dengan Saklar MOSFET\"},{\"label\":\"Sumber Daya\",\"value\":\"Adaptor AC 115-240 VAC atau Baterai Lithium-ion 7800 mAH (opsional)\"},{\"label\":\"Dimensi Fisik\",\"value\":\"13.5 x 21.6 x 22.4 cm (5.3 x 8.5 x 8.8 inci)\"},{\"label\":\"Berat Tanpa Baterai\",\"value\":\"1.6 kg (3.5 lb)\"},{\"label\":\"Berat Dengan Baterai\",\"value\":\"2.0 kg (4.4 lb)\"}]', 'Survei K3 / Higiene Industri, Investigasi Kualitas Udara Dalam Ruangan (IAQ), Pemantauan Lingkungan Terbuka (Outdoor), Penilaian Dasar & Tren Analisis, Evaluasi Kontrol Emisi Teknik, Pemantauan Paparan Debu Proyek Konstruksi.', 'CE (EN 61236-1:2006 Immunity & Emissions), MCERTS (untuk konfigurasi dengan Environmental Enclosure 8535), Kalibrasi Pabrik berstandar ISO 12103-1.', NULL, NULL, '/uploads/products/1791356497_5f0c84c50165985e4b7b.png', NULL, NULL, 184000000.00, 425000000.00, 'CV GIHON JUMA SENTOSA', '', '+6281375912389', 'Rosmaulina Sembiring', 1, '2026-10-07 14:01:37', '2026-10-08 18:53:53'),
(6, NULL, '', 'REFILL FIRE EXTING DRY CHEM TROLLEY 25KG', '', 'REFILL,FIRE EXTING:DRY CHEM;TROLLEY;25KG', '', '[{\"label\":\"Nama Layanan\",\"value\":\"Refill \\/ Isi Ulang Alat Pemadam Api  (APAR)\"},{\"label\":\"Media Pengisian\",\"value\":\"ABC Dry Chemical Powder (Serbuk Kimia  Kering)\"},{\"label\":\"Kelas Kebakaran\",\"value\":\"A (Benda Padat), B (Cairan\\/Gas), C  (Elektrikal)\"},{\"label\":\"Kompabilitas Tabung\",\"value\":\"Semua merk dan ukuran tabung (1 Kg  hingga 90 Kg)\"},{\"label\":\"Gas Pendorong\",\"value\":\"Nitrogen (N2)\"},{\"label\":\"Tekanan Kerja\",\"value\":\"15 - 18 Bar (Tergantung spesifikasi  tabung)\"},{\"label\":\"Sertiikasi Media\",\"value\":\"Sesuai Standar Nasional Indonesia (SNI)\"}]', 'Layanan isi ulang (refill) Alat Pemadam Api Ringan (APAR) menggunakan media ABC Dry Chemical Powder berkualitas tinggi. Layanan ini kompatibel untuk tabung pemadam api berbagai ukuran dan merek. Media serbuk kimia kering ini dirancang khusus dan sangat efektif untuk memadamkan kebakaran Kelas A (benda padat), Kelas B (cairan dan gas mudah terbakar), serta Kelas C (peralatan elektrikal). Proses pengisian ulang dilakukan oleh teknisi profesional sesuai standar keamanan ketat, memastikan tekanan tabung (Nitrogen) stabil dan APAR Anda kembali berfungsi optimal saat keadaan darurat serta sangat aman untuk melindungi ruang server, panel listrik, area pertambangan, laboratorium, dan area industri berat lainnya.', '', NULL, NULL, '/uploads/products/1792730360_f9f104fa109b4a4bb701.png', NULL, NULL, 35000.00, 3350000.00, '', '', '', '', 1, '2026-10-23 11:39:20', '2026-10-23 11:39:20'),
(7, NULL, '', 'Safety Boots Dunlop Acifort', 'DUNLOP', 'Sepatu Boots Safety Yellow Dunlop Acifort Heavy Duty dirancang khusus untuk memberikan perlindungan maksimal pada kaki pekerja di lingkungan industri berat dan konstruksi. Dilengkapi dengan pelindung jari baja (Steel Toe Cap) yang kuat untuk menahan benturan dan menggunakan inovasi material Acifort® yang memadukan PVC berkualitas tinggi dengan Karet Nitrile (High Nitrile) serta polimer khusus, menciptakan ketahanan ekstrem terhadap cairan kimia berbahaya, minyak industri, serta risiko gesekan fisik. Sepatu ini juga memiliki fitur sol luar yang anti-slip, menjadikannya pilihan ideal untuk industri berat, konstruksi, pertambangan, dan pertanian.', '', '[{\"label\":\"Nama Produk\",\"value\":\"Sepatu Boots Safety Dunlop Acifort\"},{\"label\":\"Model\\/Tipe\",\"value\":\"Heavy Duty Full Safety (A4422B1 \\/ A442231)\"},{\"label\":\"Warna\",\"value\":\"Kuning Terang (Yellow Shaft) dengan Sol Hitam (Black Outsole)\"},{\"label\":\"Material Utama\",\"value\":\"Acifort® (PVC Compound dengan Kandungan High Nitrile Rubber & Polymers)\"},{\"label\":\"Pelindung Jari (Toe Cap)\",\"value\":\"Steel Toe Cap (Baja Pelindung Jari Menahan Benturan Hingga 200 Joule)\"},{\"label\":\"Standar Keamanan\",\"value\":\"CE EN ISO 20345:2011 S5 AN SRA Certified\"},{\"label\":\"Fitur Utama\",\"value\":\"100% Waterproof, Tahan Minyak\\/Lemak Industri, Chemical Resistant, Antistatis, Penyerap Hentakan Tumit\"},{\"label\":\"Aplikasi\\/Penggunaan\",\"value\":\"Konstruksi, Pertambangan, Perkebunan, Industri Minyak & Gas, Pabrik\"},{\"label\":\"Ukuran Tersedia\",\"value\":\"38 - 45 (Standar Internasional)\"}]', '', '', NULL, NULL, '/uploads/products/1793596210_24caf7041b8baef14fd4.png', NULL, NULL, 0.00, 685000.00, '', '', '', '', 1, '2026-11-02 12:10:10', '2026-11-02 12:10:34'),
(8, NULL, '', 'Pakaian Kerja Biasa', 'NO BRAND', 'Pakaian Kerja Biasa adalah seragam setelan lapangan standar berwarna navy blue (biru dongker) yang menonjolkan desain profesional namun tetap mengutamakan aspek keselamatan K3. Seragam ini memiliki potongan yang rapi dengan pita reflektif di bagian punggung dan celana untuk keamanan harian. Cocok digunakan oleh pekerja lapangan, teknisi, maupun supervisor yang membutuhkan seragam yang tangguh, nyaman, namun tetap elegan.', '', '[{\"label\":\"Nama Produk\",\"value\":\"Pakaian Kerja Lapangan (Standard)\"},{\"label\":\"Model\\/Type\",\"value\":\"Seragam Setelan (Two-Piece: Kemeja & Celana)\"},{\"label\":\"Warna\",\"value\":\"Navy Blue (Biru Dongker)\"},{\"label\":\"Material Utama\",\"value\":\"Ripstop Pernusa\\/Verlando Bultop\"},{\"label\":\"Fitur Keamanan\",\"value\":\"Pita Reflektif horizontal di punggung dan paha\"},{\"label\":\"Detail Baju\",\"value\":\"Kemeja kerah standar, 2 saku dada berpenutup dengan aksen reflektif tipis\"},{\"label\":\"Aplikasi \\/ Penggunaan\",\"value\":\"Teknisi, Operator Pabrik, Supervisor Lapangan, Maintenance\"},{\"label\":\"Atribut Tambahan\",\"value\":\"Logo Safety K3 di bagian lengan\"}]', '', '', NULL, NULL, '/uploads/products/1793603481_984871693530a596fdf8.png', NULL, NULL, 285000.00, 295000.00, '', '', '', '', 1, '2026-11-02 14:11:21', '2026-11-02 14:11:21'),
(9, NULL, '', 'Pakaian Kerja ERG', 'NO BRAND', 'Pakaian Kerja ERG dirancang secara khusus untuk tim Tanggap Darurat (Emergency Response Group). Seragam two-piece ini memiliki kombinasi warna merah dominan dengan aksen hitam di pundak, serta tulisan \"Emergency Response Group\" yang jelas di bagian punggung untuk identifikasi cepat saat keadaan darurat. Dilengkapi dengan pita reflektif dan lengan yang dapat dilipat, seragam ini menawarkan perlindungan, fleksibilitas, dan identitas yang tegas bagi tim penyelamat di lapangan.', '', '[{\"label\":\"Nama Produk\",\"value\":\"Pakaian Kerja Tim Tanggap Darurat (ERG)\"},{\"label\":\"Model\\/Type\",\"value\":\"Seragam Setelan (Two-Piece: Kemeja & Celana)\"},{\"label\":\"Warna\",\"value\":\"Merah kombinasi Hitam (Pundak & Lengan Atas)\"},{\"label\":\"Material Utama\",\"value\":\"Ripstop Pernusa\\/Verlando Bultop\"},{\"label\":\"Fitur Keamanan\",\"value\":\"Pita Reflektif, Teks \\\"Emergency Response Group\\\" di Punggung\"},{\"label\":\"Detail Desain\",\"value\":\"Terdapat tali pengait lengan (bisa digulung), logo safety di lengan\"},{\"label\":\"Aplikasi \\/ Penggunaan\",\"value\":\"Tim Rescue, Evakuasi, Pemadam Kebakaran Internal, Medis Darurat\"}]', '', '', NULL, NULL, '/uploads/products/1793603653_a497969481826e322f92.png', NULL, NULL, 285000.00, 295000.00, '', '', '', '', 1, '2026-11-02 14:14:13', '2026-11-02 14:14:13'),
(10, NULL, '', 'Pakaian Kerja HSE-PTL ', 'NO BRAND', 'Pakaian Kerja HSE-PTL adalah seragam lapangan two-piece (kemeja dan celana) yang dirancang khusus untuk tim Health, Safety, and Environment (HSE) dan PTL. Dibuat dengan kombinasi warna merah dan hitam yang mencolok, seragam ini dilengkapi dengan pita reflektif (scotlight) visibilitas tinggi di bagian dada, lengan, dan celana untuk memastikan keamanan pekerja di area minim cahaya. Material yang digunakan breathable dan tahan lama, mendukung mobilitas tinggi di area operasional lapangan.', '', '[{\"label\":\"Nama Produk\",\"value\":\"Pakaian Kerja HSE - PTL\"},{\"label\":\"Model\\/Type\",\"value\":\"Seragam Setelan (Two-Piece: Kemeja & Celana)\"},{\"label\":\"Warna\",\"value\":\"Merah kombinasi Hitam\"},{\"label\":\"Material Utama\",\"value\":\"Ripstop Pernusa\\/Verlando Bultop\"},{\"label\":\"Fitur Keamanan\",\"value\":\"Pita Reflektif (High-Visibility Scotlight) di dada, lengan, dan paha\"},{\"label\":\"Saku\",\"value\":\"2 Saku dada (berpenutup) dan saku celana kargo\"},{\"label\":\"Aplikasi \\/ Penggunaan\",\"value\":\"Pengawas Lapangan, Tim HSE, Konstruksi, Tambang\"},{\"label\":\"Atribut Tambahan\",\"value\":\"Patch \\/ Logo Safety K3 di lengan\"}]', '', '', NULL, NULL, '/uploads/products/1793603811_b55cbf24da7e5a4d6dee.png', NULL, NULL, 285000.00, 295000.00, '', '', '', '', 1, '2026-11-02 14:16:51', '2026-11-02 14:16:51'),
(11, NULL, '', 'Wearpack Coverall Safety ', 'NO BRAND', 'Wearpack Coverall Safety adalah pakaian kerja model terusan (one-piece) berwarna navy blue (biru dongker) yang memberikan perlindungan menyeluruh untuk tubuh pekerja dari leher hingga mata kaki. Desain coverall sangat ideal untuk mencegah tersangkutnya pakaian pada mesin atau peralatan berat. Dilengkapi dengan pita reflektif di punggung, lengan, dan kaki, serta kompartemen saku yang fungsional untuk menyimpan peralatan kerja berukuran kecil.', '', '[{\"label\":\"Nama Produk\",\"value\":\"Wearpack Coverall Safety\"},{\"label\":\"Model\\/Type\",\"value\":\"One-Piece Coverall (Terusan)\"},{\"label\":\"Warna\",\"value\":\"Navy Blue (Biru Dongker)\"},{\"label\":\"Material Utama\",\"value\":\"Ripstop Pernusa\\/Verlando Bultop\"},{\"label\":\"Fitur Keamanan\",\"value\":\"Pita Reflektif (Scotlight) di punggung, pergelangan tangan, dan lutut\\/paha\"},{\"label\":\"Sistem Penutup\",\"value\":\"Resleting depan (Zipper) dengan penutup kain (flap)\"},{\"label\":\"Aplikasi \\/ Penggunaan\",\"value\":\"Mekanik, Bengkel, Minyak & Gas, Manufaktur Berat\"},{\"label\":\"Detail Saku\",\"value\":\"2 Saku dada, saku samping celana, dan saku belakang\"},{\"label\":\"Atribut Tambahan\",\"value\":\"Logo Safety K3 di bagian lengan\"}]', '', '', NULL, NULL, '/uploads/products/1793604043_20bea4e2d1844e33ea80.png', NULL, NULL, 285000.00, 325000.00, '', '', '', '', 1, '2026-11-02 14:20:43', '2026-11-02 14:20:43'),
(12, NULL, '', 'REFILL FIRE EXTING CO TROLLEY 6.8KG', '', 'REFILL FIRE EXTING CO TROLLEY 6.8KG', '', '[{\"label\":\"Nama Layanan\",\"value\":\"Isi Ulang (Refill) APAR CO₂\"},{\"label\":\"Model \\/ Media\",\"value\":\"Carbon Dioxide (CO₂) Premium Clean Agent\"},{\"label\":\"Kelas Kebakaran\",\"value\":\"Kelas B (Bahan Cair\\/Gas) & Kelas C (Elektrikal)\"},{\"label\":\"Kapasitas Tersedia\",\"value\":\"2 kg, 3 kg, 5 kg, 6.8 kg, 9 kg dan lainnya\"},{\"label\":\"Standar Uji Kebocoran\",\"value\":\"Leak Immersion Test & Penimbangan Digital Akurat\"},{\"label\":\"Standar Kepatuhan\",\"value\":\"Sesuai NFPA 10 & Regulasi K3 Kebakaran Nasional\"},{\"label\":\"Fitur Layanan Utama\",\"value\":\"Pembersihan Tabung, Penggantian Safety Pin & Seal Baru, Garansi Tekanan\"},{\"label\":\"Aplikasi Penempatan\",\"value\":\"Ruang Server IT, Ruang Panel Listrik, Laboratorium, Pertambangan , Pabrik, Industri Medis\"}]', 'Layanan Isi Ulang (Refill) APAR CO₂ (Carbon Dioxide) dirancang khusus untuk memastikan sistem proteksi kebakaran Anda selalu dalam kondisi prima dan siap pakai. Menggunakan media Karbon Dioksida murni berkualitas tinggi tanpa campuran, sangat efektif untuk memadamkan kebakaran Kelas B (cairan mudah terbakar) dan Kelas C (peralatan elektrikal bertegangan). Media CO₂ bersifat clean agent, tidak meninggalkan residu setelah digunakan, tidak konduktif, serta sangat aman untuk melindungi ruang server, panel listrik, area pertambangan, laboratorium, dan area industri berat lainnya.', '', NULL, NULL, '/uploads/products/1795070893_670eed6d8972c4551834.png', NULL, NULL, 306000.00, 695000.00, 'MULTI TEKNINDO MANDIRI', '', '085711579378', '', 1, '2026-11-19 13:48:13', '2026-11-19 13:49:41');

-- --------------------------------------------------------

--
-- Table structure for table `product_company_access`
--

CREATE TABLE `product_company_access` (
  `product_id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `product_company_access`
--

INSERT INTO `product_company_access` (`product_id`, `company_id`, `created_at`) VALUES
(6, 1, '2026-10-23 11:39:20'),
(7, 1, '2026-11-02 12:10:34'),
(8, 1, '2026-11-02 14:11:21'),
(9, 1, '2026-11-02 14:14:13'),
(10, 1, '2026-11-02 14:16:51'),
(11, 1, '2026-11-02 14:20:43'),
(12, 1, '2026-11-19 13:49:41');

-- --------------------------------------------------------

--
-- Table structure for table `proforma_invoices`
--

CREATE TABLE `proforma_invoices` (
  `id` int(10) UNSIGNED NOT NULL,
  `quotation_id` int(10) UNSIGNED NOT NULL,
  `invoice_no` varchar(80) NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `payment_status` varchar(20) NOT NULL DEFAULT 'unpaid',
  `payment_date` date DEFAULT NULL,
  `payment_method` varchar(80) DEFAULT NULL,
  `payment_reference` varchar(160) DEFAULT NULL,
  `proof_path` varchar(500) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `client_purchase_order_id` int(10) UNSIGNED DEFAULT NULL,
  `po_no` varchar(80) NOT NULL,
  `po_date` date NOT NULL,
  `expected_date` date DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'draft',
  `subtotal` decimal(18,2) NOT NULL DEFAULT 0.00,
  `tax_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `use_ppn` tinyint(4) DEFAULT 0,
  `ppn_percent` decimal(5,2) DEFAULT 11.00,
  `use_pph` tinyint(4) DEFAULT 0,
  `pph_percent` decimal(5,2) DEFAULT 0.00,
  `tax_amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `pph_amount` decimal(18,2) DEFAULT 0.00,
  `grand_total` decimal(18,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `vendor_id`, `client_purchase_order_id`, `po_no`, `po_date`, `expected_date`, `status`, `subtotal`, `tax_percent`, `use_ppn`, `ppn_percent`, `use_pph`, `pph_percent`, `tax_amount`, `pph_amount`, `grand_total`, `notes`, `created_at`, `updated_at`) VALUES
(4, 1, NULL, 'PO-00129/TRE-GJS/X/2026', '2026-10-29', '2026-10-30', 'approved', 5000.00, 11.00, 1, 11.00, 0, 0.00, 550.00, 0.00, 5550.00, 'vcjhgfhg', '2026-10-29 22:22:32', '2026-10-29 22:22:32');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `purchase_order_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `product_name` varchar(180) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` decimal(18,2) NOT NULL DEFAULT 1.00,
  `unit` varchar(30) NOT NULL DEFAULT 'pcs',
  `unit_price` decimal(18,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(18,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_order_items`
--

INSERT INTO `purchase_order_items` (`id`, `purchase_order_id`, `product_id`, `product_name`, `description`, `quantity`, `unit`, `unit_price`, `line_total`) VALUES
(5, 4, 5, 'TSI / TSI 8530 DustTrak II Aerosol Monitor', 'Alar Ukur Debu', 1.00, 'pcs', 5000.00, 5000.00);

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `quotation_no` varchar(60) NOT NULL,
  `customer_name` varchar(180) DEFAULT NULL,
  `customer_address` text DEFAULT NULL,
  `customer_phone` varchar(40) DEFAULT NULL,
  `attention` varchar(180) DEFAULT NULL,
  `title` varchar(220) NOT NULL,
  `issue_date` date DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `validity_days` int(5) DEFAULT NULL,
  `payment_terms` varchar(255) DEFAULT NULL,
  `delivery_terms` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'draft',
  `subtotal` decimal(18,2) NOT NULL DEFAULT 0.00,
  `tax_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(18,2) NOT NULL DEFAULT 0.00,
  `bank_account_id` int(10) UNSIGNED DEFAULT NULL,
  `master_snapshot_json` longtext DEFAULT NULL,
  `final_snapshot_json` longtext DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotations`
--

INSERT INTO `quotations` (`id`, `company_id`, `quotation_no`, `customer_name`, `customer_address`, `customer_phone`, `attention`, `title`, `issue_date`, `valid_until`, `validity_days`, `payment_terms`, `delivery_terms`, `notes`, `status`, `subtotal`, `tax_percent`, `tax_amount`, `grand_total`, `bank_account_id`, `master_snapshot_json`, `final_snapshot_json`, `created_at`, `updated_at`) VALUES
(1, 2, 'Q-03702/TRE-BAI/X/2026', 'PT. BORNEO ALUMINA INDONESIA', 'Smelter Grade Alumina Refinery (SGAR)\r\nJl. Poros Bukit Batu RT 009 / RW 003\r\nDusun Kembang Lada, Desa Bukit Batu\r\nKec. Sungai Kunyit, Kab. Mempawah\r\nProv. Kalimantan Barat, 78971', '', 'Admin Procurement', 'Penawaran Harga CM26091537', '2026-10-02', '2026-10-12', 10, 'Transfer 45 day after invoice', '10 days', '                	Quotation Terms and Conditions:		\r\nPrice		: In IDR, Include TAX 11%,	\r\nTerms of payment		: 45 day after invoice	\r\nQuotation Validity		: 15 days	\r\nDelivery		: Free On site BAI Mempawah 10-14 days after PO	\r\nWarranty		: 12 Month	', 'draft', 16241000.00, 11.00, 1786510.00, 18027510.00, NULL, NULL, NULL, '2026-10-02 17:16:45', '2026-10-02 17:16:45'),
(2, 1, 'CCIP00108/TRE-ICA/X/2026', 'PT. INDONESIA CHEMICAL ALUMINA', 'Jl. Trans Kalimantan, Dusun Piasak-Desa Pedalaman,\r\nKecamatan Tayan Hilir, Kabupaten Sanggau 78564,\r\nKalimantan Barat Indonesia', '(62-21) 780 3340', 'Bpk Awang Zainudin', ' Surat Penawaran Harga Tender T2026080081', '2026-10-08', '2026-10-18', 10, 'Transfer 45 day after invoice', '10 days', 'Product Indent 75-90 days.                                                                                ', 'sent', 425000000.00, 11.00, 46750000.00, 471750000.00, NULL, '{\"payment_terms\":\"Transfer 45 day after invoice\",\"delivery_terms\":\"10 days\",\"notes\":\"Product Indent 75-90 days.                                                                                \",\"tax_percent\":11,\"subtotal\":425000000,\"tax_amount\":46750000,\"grand_total\":471750000,\"items\":[{\"quotation_id\":2,\"product_id\":\"5\",\"product_name\":\"TSI 8530 DustTrak II Aerosol Monitor\",\"description\":\"Pengadaan Alat Ukur Debu Lingkungan Kerja Tahun 2026\",\"quantity\":1,\"unit\":\"UNT\",\"unit_price\":425000000,\"discount_percent\":0,\"line_total\":425000000}]}', NULL, '2026-10-07 14:23:21', '2026-10-08 20:02:50'),
(3, 1, 'CCIP00223/TRE-ICA/X/2026', 'PT. INDONESIA CHEMICAL ALUMINA', 'Jl. Trans Kalimantan, Dusun Piasak-Desa Pedalaman,\r\nKecamatan Tayan Hilir, Kabupaten Sanggau 78564,\r\nKalimantan Barat Indonesia', '(62-21) 780 3340', 'Domitila Eta', 'Surat Penawaran Tender T2026100040', '2026-10-23', '2026-11-02', 10, 'Transfer 45 day after invoice', '10 days', '                                                                ', 'sent', 13400000.00, 11.00, 1474000.00, 14874000.00, NULL, '{\"payment_terms\":\"Transfer 45 day after invoice\",\"delivery_terms\":\"10 days\",\"notes\":\"                                                                \",\"tax_percent\":11,\"subtotal\":13400000,\"tax_amount\":1474000,\"grand_total\":14874000,\"items\":[{\"quotation_id\":3,\"product_id\":\"6\",\"product_name\":\"REFILL FIRE EXTING DRY CHEM TROLLEY 25KG\",\"description\":\"\",\"quantity\":4,\"unit\":\"pcs\",\"unit_price\":3350000,\"discount_percent\":0,\"line_total\":13400000}]}', NULL, '2026-10-23 11:42:08', '2026-11-02 14:32:17'),
(4, 1, 'CCIP00102/TRE-ICA/XI/2026', 'PT. INDONESIA CHEMICAL ALUMINA', 'Jl. Trans Kalimantan, Dusun Piasak-Desa Pedalaman,\r\nKecamatan Tayan Hilir, Kabupaten Sanggau 78564,\r\nKalimantan Barat Indonesia', '(62-21) 780 3340', 'Benediktus Beny', 'Surat Penawaran Tender T2026080013-1', '2026-11-02', '2026-11-12', 10, 'Transfer 45 day after invoice', '10 days', '                                ', 'sent', 41100000.00, 11.00, 4521000.00, 45621000.00, NULL, '{\"payment_terms\":\"Transfer 45 day after invoice\",\"delivery_terms\":\"10 days\",\"notes\":\"                                \",\"tax_percent\":11,\"subtotal\":41100000,\"tax_amount\":4521000,\"grand_total\":45621000,\"items\":[{\"quotation_id\":4,\"product_id\":\"7\",\"product_name\":\"Safety Boots Dunlop Acifort\",\"description\":\"BOOTS,SAFETY:HD;40;HIGH NITRILE\",\"quantity\":60,\"unit\":\"pcs\",\"unit_price\":685000,\"discount_percent\":0,\"line_total\":41100000}]}', NULL, '2026-11-02 12:13:29', '2026-11-02 14:31:57'),
(5, 1, 'CCIP00202/TRE-ICA/XI/2026', 'PT. INDONESIA CHEMICAL ALUMINA', 'Jl. Trans Kalimantan, Dusun Piasak-Desa Pedalaman,\r\nKecamatan Tayan Hilir, Kabupaten Sanggau 78564,\r\nKalimantan Barat Indonesia', '(62-21) 780 3340', 'Abdul Muad', 'Surat Penawaran Tender T2026090117', '2026-11-02', '2026-11-12', 10, 'Transfer 45 day after invoice', '10 days', 'Ukuran akan mengikuti data dari TOR.', 'draft', 297455000.00, 11.00, 32720050.00, 330175050.00, NULL, NULL, NULL, '2026-11-02 14:25:31', '2026-11-02 14:25:31'),
(6, 1, 'CCIP00623/TRE-ICA/IX/2026', 'PT. INDONESIA CHEMICAL ALUMINA', 'Jl. Trans Kalimantan, Dusun Piasak-Desa Pedalaman,\r\nKecamatan Tayan Hilir, Kabupaten Sanggau 78564,\r\nKalimantan Barat Indonesia', '(62-21) 780 3340', 'Siapa aja', 'Surat Penawaran Harga Tender T2026080031', '2026-09-23', '2026-10-03', 10, 'Transfer 45 day after invoice', '10 days', '                                ', 'negotiation', 3475000.00, 11.00, 382250.00, 3857250.00, NULL, '{\"payment_terms\":\"Transfer 45 day after invoice\",\"delivery_terms\":\"10 days\",\"notes\":\"                                \",\"tax_percent\":11,\"subtotal\":3475000,\"tax_amount\":382250,\"grand_total\":3857250,\"items\":[{\"quotation_id\":6,\"product_id\":\"12\",\"product_name\":\"REFILL FIRE EXTING CO TROLLEY 6.8KG\",\"description\":\"\",\"quantity\":5,\"unit\":\"pcs\",\"unit_price\":695000,\"discount_percent\":0,\"line_total\":3475000}]}', NULL, '2026-11-19 13:49:49', '2026-11-19 13:51:41');

-- --------------------------------------------------------

--
-- Table structure for table `quotation_items`
--

CREATE TABLE `quotation_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `quotation_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `product_name` varchar(180) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` decimal(12,2) NOT NULL DEFAULT 1.00,
  `unit` varchar(30) NOT NULL DEFAULT 'pcs',
  `unit_price` decimal(18,2) NOT NULL DEFAULT 0.00,
  `discount_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(18,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotation_items`
--

INSERT INTO `quotation_items` (`id`, `quotation_id`, `product_id`, `product_name`, `description`, `quantity`, `unit`, `unit_price`, `discount_percent`, `line_total`) VALUES
(1, 1, 3, 'Axial Fan ', 'Fan;Axial;LTF;4D300;12IN;3PH  - Brand : LTF  - Model : 4D-300  - Size : 12 Inch  - Phase : 3 Phase  - Voltage : 380 V  - Application : Industrial  - Mounting : Wall / Duct  - Material : Metal  - Function : Air Flow  - Origin : China / Equivalent', 12.00, 'pcs', 1292000.00, 0.00, 15504000.00),
(2, 1, 1, 'Safety Boot Cheetah 7288C', 'Fan;Axial;LTF;4D300;12IN;3PH  - Brand : LTF  - Model : 4D-300  - Size : 12 Inch  - Phase : 3 Phase  - Voltage : 380 V  - Application : Industrial  - Mounting : Wall / Duct  - Material : Metal  - Function : Air Flow  - Origin : China / Equivalent', 1.00, 'pcs', 737000.00, 0.00, 737000.00),
(14, 2, 5, 'TSI 8530 DustTrak II Aerosol Monitor', 'Pengadaan Alat Ukur Debu Lingkungan Kerja Tahun 2026', 1.00, 'UNT', 425000000.00, 0.00, 425000000.00),
(18, 3, 6, 'REFILL FIRE EXTING DRY CHEM TROLLEY 25KG', '', 4.00, 'pcs', 3350000.00, 0.00, 13400000.00),
(20, 4, 7, 'Safety Boots Dunlop Acifort', 'BOOTS,SAFETY:HD;40;HIGH NITRILE', 60.00, 'pcs', 685000.00, 0.00, 41100000.00),
(21, 5, 8, 'Pakaian Kerja Biasa', 'Pakaian Kerja Biasa', 961.00, 'pcs', 295000.00, 0.00, 283495000.00),
(22, 5, 10, 'Pakaian Kerja HSE-PTL ', 'Pakaian Kerja HSE dan PTL', 29.00, 'pcs', 295000.00, 0.00, 8555000.00),
(23, 5, 9, 'Pakaian Kerja ERG', 'Pakaian Kerja ERG', 4.00, 'pcs', 295000.00, 0.00, 1180000.00),
(24, 5, 11, 'Wearpack Coverall Safety ', 'Pakaian Kerja Wearpack', 13.00, 'pcs', 325000.00, 0.00, 4225000.00),
(26, 6, 12, 'REFILL FIRE EXTING CO TROLLEY 6.8KG', '', 5.00, 'pcs', 695000.00, 0.00, 3475000.00);

-- --------------------------------------------------------

--
-- Table structure for table `quotation_negotiations`
--

CREATE TABLE `quotation_negotiations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quotation_id` int(10) UNSIGNED NOT NULL,
  `round_no` int(10) UNSIGNED NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `proposed_by` varchar(120) NOT NULL DEFAULT 'system',
  `customer_message` text DEFAULT NULL,
  `internal_notes` text DEFAULT NULL,
  `snapshot_json` longtext NOT NULL,
  `subtotal` decimal(18,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(18,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime DEFAULT NULL,
  `responded_at` datetime DEFAULT NULL,
  `responded_by` varchar(120) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotation_negotiations`
--

INSERT INTO `quotation_negotiations` (`id`, `quotation_id`, `round_no`, `status`, `proposed_by`, `customer_message`, `internal_notes`, `snapshot_json`, `subtotal`, `tax_amount`, `grand_total`, `created_at`, `responded_at`, `responded_by`) VALUES
(1, 6, 1, 'pending', 'kokuo', 'Yth. TRISENTOSA RAYA ESOLUSI, Harga penawaran total Rp3.475.000 tidak termasuk PPN, Kami ajukan harga total agar menjadi Rp3.000.000 tidak termasuk PPN. Proses refill sampai pengiriman ke site PT ICA 10 hari sesuai penawaran terhitung dari tanggal PO resmi di terima. Metode pembayaran 45 hari terhitung dari tanggal invoice lengkap di terima PT ICA. Jika di setujui mohon konfirmasi secepatnya agar dapat kami proses PO. terimakasih', NULL, '{\"payment_terms\":\"Transfer 45 day after invoice\",\"delivery_terms\":\"10 days\",\"notes\":\"\",\"tax_percent\":11,\"items\":[{\"quotation_id\":6,\"product_id\":12,\"product_name\":\"REFILL FIRE EXTING CO TROLLEY 6.8KG\",\"description\":\"REFILL FIRE EXTING CO TROLLEY 6.8KG\",\"quantity\":5,\"unit\":\"pcs\",\"unit_price\":600000,\"discount_percent\":0,\"line_total\":3000000}]}', 3000000.00, 330000.00, 3330000.00, '2026-11-19 13:51:41', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quotation_settings`
--

CREATE TABLE `quotation_settings` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `company_name` varchar(180) DEFAULT NULL,
  `office_1` varchar(255) DEFAULT NULL,
  `office_2` varchar(255) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `email` varchar(160) DEFAULT NULL,
  `tax_id` varchar(80) DEFAULT NULL,
  `bank_name` varchar(120) DEFAULT NULL,
  `bank_account_name` varchar(160) DEFAULT NULL,
  `bank_account_number` varchar(80) DEFAULT NULL,
  `bank_branch` varchar(120) DEFAULT NULL,
  `logo_path` varchar(500) DEFAULT NULL,
  `signature_path` varchar(500) DEFAULT NULL,
  `stamp_path` varchar(500) DEFAULT NULL,
  `signer_name` varchar(120) DEFAULT NULL,
  `signer_phone` varchar(40) DEFAULT NULL,
  `default_payment_terms` varchar(255) DEFAULT NULL,
  `default_validity_days` int(5) NOT NULL DEFAULT 10,
  `default_delivery_terms` varchar(255) DEFAULT NULL,
  `default_tax_percent` decimal(5,2) NOT NULL DEFAULT 11.00,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotation_settings`
--

INSERT INTO `quotation_settings` (`id`, `company_name`, `office_1`, `office_2`, `phone`, `email`, `tax_id`, `bank_name`, `bank_account_name`, `bank_account_number`, `bank_branch`, `logo_path`, `signature_path`, `stamp_path`, `signer_name`, `signer_phone`, `default_payment_terms`, `default_validity_days`, `default_delivery_terms`, `default_tax_percent`, `created_at`, `updated_at`) VALUES
(0, 'PT. TRISENTOSA RAYA ESOLUSI', 'Gedung Masindo lantai III,  Jl. Mampang Prapatan Raya no. 73A Jakarta Selatan', 'Kawasan Industri CCIP Blok K1, No. 3A Cicau, Cikarang Pusat, Kab Bekasi', '(021) 798 9670/2215 7445', 'cs@trisentosaraya.co.id', '076.249.385.6-014.000', NULL, NULL, NULL, NULL, '/uploads/settings/1790230010_43579b7fe165b34e355c.png', '/uploads/settings/1790230010_b5e75764ecaabf43e174.jpeg', NULL, 'Eko Sutanto', '0812-8598-2399', 'Transfer 45 day after invoice', 10, '10 days', 11.00, '2026-09-24 06:06:50', '2026-09-24 06:06:50');

-- --------------------------------------------------------

--
-- Table structure for table `quotation_status_logs`
--

CREATE TABLE `quotation_status_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quotation_id` int(10) UNSIGNED NOT NULL,
  `from_status` varchar(30) DEFAULT NULL,
  `to_status` varchar(30) NOT NULL,
  `changed_by` varchar(120) NOT NULL DEFAULT 'system',
  `reason` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotation_status_logs`
--

INSERT INTO `quotation_status_logs` (`id`, `quotation_id`, `from_status`, `to_status`, `changed_by`, `reason`, `created_at`) VALUES
(1, 1, NULL, 'draft', 'ogonk', 'Quotation dibuat.', '2026-10-02 17:16:45'),
(2, 2, NULL, 'draft', 'kokuo', 'Quotation dibuat.', '2026-10-07 14:23:21'),
(3, 2, 'draft', 'sent', 'kokuo', 'Diubah melalui tombol aksi quotation.', '2026-10-08 20:02:50'),
(4, 3, NULL, 'draft', 'kokuo', 'Quotation dibuat.', '2026-10-23 11:42:08'),
(5, 4, NULL, 'draft', 'kokuo', 'Quotation dibuat.', '2026-11-02 12:13:29'),
(6, 5, NULL, 'draft', 'kokuo', 'Quotation dibuat.', '2026-11-02 14:25:31'),
(7, 4, 'draft', 'sent', 'kokuo', 'Diubah melalui tombol aksi quotation.', '2026-11-02 14:31:57'),
(8, 3, 'draft', 'sent', 'kokuo', 'Diubah melalui tombol aksi quotation.', '2026-11-02 14:32:17'),
(9, 6, NULL, 'draft', 'kokuo', 'Quotation dibuat.', '2026-11-19 13:49:49'),
(10, 6, 'draft', 'negotiation', 'kokuo', 'Negosiasi putaran 1 diajukan.', '2026-11-19 13:51:41');

-- --------------------------------------------------------

--
-- Table structure for table `tender_documents`
--

CREATE TABLE `tender_documents` (
  `id` int(10) UNSIGNED NOT NULL,
  `tender_no` varchar(100) DEFAULT NULL,
  `title` varchar(220) NOT NULL,
  `procurement_method` varchar(80) DEFAULT NULL,
  `issuer_name` varchar(180) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `submission_deadline` datetime DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'draft',
  `contact_name` varchar(120) DEFAULT NULL,
  `contact_email` varchar(160) DEFAULT NULL,
  `contact_phone` varchar(40) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `file_path` varchar(500) DEFAULT NULL,
  `original_file_name` varchar(255) DEFAULT NULL,
  `file_mime` varchar(120) DEFAULT NULL,
  `file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` varchar(120) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tender_documents`
--

INSERT INTO `tender_documents` (`id`, `tender_no`, `title`, `procurement_method`, `issuer_name`, `description`, `issue_date`, `valid_until`, `submission_deadline`, `status`, `contact_name`, `contact_email`, `contact_phone`, `notes`, `file_path`, `original_file_name`, `file_mime`, `file_size`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '76.249.385.6-014.000', 'NPWP', 'PAJAK', 'KPP PRATAMA JAKARTA MAMPANG PRAPATAN', 'PERSYARATAN DOKUMEN TENDER', '2016-05-31', NULL, NULL, 'open', '', '', '', '', 'private/tenders/1790679008_f64111dced0274b61250.jpeg', 'NPWP 16 DIGIT.jpeg', 'image/jpeg', 254392, 'kokuo', '2026-09-29 10:50:08', '2026-09-29 10:50:08'),
(2, '', 'NIB Berbasis Resiko', 'Legal', '', '', '2019-08-30', NULL, NULL, 'open', '', '', '', '', 'private/tenders/1794646156_d7be26c955c8cba76883.pdf', 'NIB Resiko Terbaru 2025.pdf', 'application/pdf', 744065, 'dewi', '2026-11-14 15:49:16', '2026-11-14 15:49:16'),
(3, '', 'Akta Pendirian Perusahaan', 'Legal', '', '', '2016-05-13', NULL, NULL, 'awarded', '', '', '', '', 'private/tenders/1794646258_ab4cc5842a689f6b5e55.pdf', 'Akta NotarisT.R.E[Pendirian2016].pdf', 'application/pdf', 4455541, 'dewi', '2026-11-14 15:50:58', '2026-11-14 15:50:58'),
(4, '', 'Akta Perubahan Pertama (Notaris Getri)', '', '', '', '2021-04-13', NULL, NULL, 'awarded', '', '', '', '', 'private/tenders/1794646611_70f86cced2222a11db4a.pdf', 'AKTA PERUBAHAN PT. TRE_opt.pdf', 'application/pdf', 3042575, 'dewi', '2026-11-14 15:56:51', '2026-11-14 15:56:51'),
(5, '', 'Akta Perubahan 2025', 'Legal', '', '', '2025-06-20', NULL, NULL, 'awarded', '', '', '', '', 'private/tenders/1794646669_c0490df699d4effa0147.pdf', 'Akte Perubahan PT. TRE 2025.pdf', 'application/pdf', 1007821, 'dewi', '2026-11-14 15:57:49', '2026-11-14 15:57:49');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
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
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `full_name`, `password_hash`, `role`, `avatar_path`, `is_active`, `failed_login_attempts`, `locked_until`, `last_login_at`, `last_login_ip`, `created_at`, `updated_at`) VALUES
(1, 'kokuo', 'imam@trisentosaraya.co.id', 'Kokuo', '$2y$10$cCVuOr.jYaJtPsUj8DZEQee2U94RF7ZQzuWTktXUGiM0lhplb7UPe', 'superadmin', NULL, 1, 0, NULL, '2026-11-25 10:51:12', '204.51.40.131', '2026-09-22 19:07:07', '2026-11-25 10:51:12'),
(2, 'dewi', 'dewi.yulia@trisentosaraya.co.id', 'Dewi Yulia', '$2y$10$Rz0oj0h6hMg/hOo3f9ay1ePW0k0yBdI2mfDR2SdWKiRueX4J/C8K2', 'admin', NULL, 1, 0, NULL, '2026-11-21 10:15:22', '110.138.115.133', '2026-09-22 19:09:57', '2026-11-21 10:15:22'),
(3, 'ogonk', 'sofyan@trisentosaraya.co.id', 'Sofyan', '$2y$10$0oByzvQpkg82pV1u6KgCkOp44x7JVIa160GHx6q5xWiT7K2HvwHhq', 'admin', NULL, 1, 0, NULL, '2026-10-02 16:33:02', '180.243.179.237', '2026-09-24 09:20:37', '2026-11-21 10:44:21');

-- --------------------------------------------------------

--
-- Table structure for table `user_company_access`
--

CREATE TABLE `user_company_access` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_company_access`
--

INSERT INTO `user_company_access` (`user_id`, `company_id`, `created_at`) VALUES
(1, 1, '2026-10-07 19:33:42'),
(1, 2, '2026-10-07 19:33:42'),
(2, 1, '2026-10-29 21:07:02'),
(2, 2, '2026-10-29 21:07:02'),
(3, 1, '2026-11-21 10:44:21'),
(3, 2, '2026-11-21 10:44:21');

-- --------------------------------------------------------

--
-- Table structure for table `user_permissions`
--

CREATE TABLE `user_permissions` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_permissions`
--

INSERT INTO `user_permissions` (`user_id`, `permission_id`) VALUES
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 5),
(2, 6),
(2, 7),
(2, 8),
(2, 9),
(2, 10),
(2, 11),
(2, 12),
(2, 13),
(2, 14),
(2, 15),
(2, 16),
(2, 17),
(2, 18),
(2, 19),
(2, 20),
(2, 21),
(2, 22),
(2, 23),
(2, 24),
(2, 25),
(2, 26),
(2, 27),
(2, 28),
(2, 29),
(2, 30),
(2, 31),
(2, 32),
(2, 33),
(2, 34),
(2, 35),
(2, 36),
(2, 37),
(2, 38),
(2, 39),
(2, 40),
(2, 41),
(2, 42),
(2, 43),
(2, 44),
(2, 45),
(2, 46),
(2, 47),
(2, 48),
(2, 49),
(2, 50),
(2, 51),
(3, 1),
(3, 2),
(3, 3),
(3, 4),
(3, 5),
(3, 6),
(3, 7),
(3, 8),
(3, 9),
(3, 10),
(3, 11),
(3, 12),
(3, 13),
(3, 14),
(3, 15),
(3, 16),
(3, 17),
(3, 18),
(3, 19),
(3, 20),
(3, 21),
(3, 22),
(3, 23),
(3, 24),
(3, 25),
(3, 26),
(3, 27),
(3, 28),
(3, 29),
(3, 30),
(3, 31),
(3, 32),
(3, 33),
(3, 34),
(3, 35),
(3, 36),
(3, 37),
(3, 38),
(3, 39),
(3, 40),
(3, 41),
(3, 42),
(3, 43),
(3, 44),
(3, 45),
(3, 46),
(3, 47),
(3, 48),
(3, 49),
(3, 50),
(3, 51);

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(180) NOT NULL,
  `code` varchar(60) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(80) DEFAULT NULL,
  `email` varchar(160) DEFAULT NULL,
  `pic_name` varchar(120) DEFAULT NULL,
  `payment_terms` varchar(255) DEFAULT NULL,
  `bank_name` varchar(120) DEFAULT NULL,
  `bank_account_name` varchar(160) DEFAULT NULL,
  `bank_account_number` varchar(80) DEFAULT NULL,
  `bank_branch` varchar(120) DEFAULT NULL,
  `tax_id` varchar(80) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`, `code`, `address`, `phone`, `email`, `pic_name`, `payment_terms`, `bank_name`, `bank_account_name`, `bank_account_number`, `bank_branch`, `tax_id`, `notes`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'CV GIHON JUMA SENTOSA', '', 'JL. Letjen Jamin Ginting KM 9,5 Gg. Pinem No 08 Kel.Mangga Kec. Medan Tuntungan, Medan - 20141 ', '081375912389', 'gihonjumasentosa@gmail.com', 'Rosmalina Sembiring', '', '', '', '', '', '', '', 1, '2026-10-29 21:42:38', '2026-10-29 21:42:38');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_bills`
--

CREATE TABLE `vendor_bills` (
  `id` int(10) UNSIGNED NOT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `purchase_order_id` int(10) UNSIGNED DEFAULT NULL,
  `bill_no` varchar(80) NOT NULL,
  `bill_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `use_ppn` tinyint(4) DEFAULT 0,
  `ppn_percent` decimal(5,2) DEFAULT 11.00,
  `ppn_amount` decimal(18,2) DEFAULT 0.00,
  `use_pph` tinyint(4) DEFAULT 0,
  `pph_percent` decimal(5,2) DEFAULT 0.00,
  `pph_amount` decimal(18,2) DEFAULT 0.00,
  `net_amount` decimal(18,2) DEFAULT 0.00,
  `status` varchar(20) NOT NULL DEFAULT 'unpaid',
  `paid_date` date DEFAULT NULL,
  `payment_reference` varchar(160) DEFAULT NULL,
  `proof_path` varchar(500) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_audit_logs`
--
ALTER TABLE `auth_audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event` (`event`);

--
-- Indexes for table `auth_login_rate_limits`
--
ALTER TABLE `auth_login_rate_limits`
  ADD PRIMARY KEY (`ip_address`),
  ADD KEY `locked_until` (`locked_until`);

--
-- Indexes for table `bast_documents`
--
ALTER TABLE `bast_documents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bast_no` (`bast_no`),
  ADD KEY `source_id` (`source_id`),
  ADD KEY `source_type` (`source_type`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `handover_date` (`handover_date`);

--
-- Indexes for table `client_delivery_notes`
--
ALTER TABLE `client_delivery_notes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `delivery_no` (`delivery_no`),
  ADD KEY `client_purchase_order_id` (`client_purchase_order_id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `delivery_date` (`delivery_date`);

--
-- Indexes for table `client_purchase_orders`
--
ALTER TABLE `client_purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `po_no` (`po_no`),
  ADD KEY `quotation_id` (`quotation_id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `client_purchase_order_items`
--
ALTER TABLE `client_purchase_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_purchase_order_id` (`client_purchase_order_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `company_bank_accounts`
--
ALTER TABLE `company_bank_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_is_read` (`user_id`,`is_read`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permission_key` (`permission_key`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sku` (`sku`),
  ADD KEY `idx_products_company_id` (`company_id`);

--
-- Indexes for table `product_company_access`
--
ALTER TABLE `product_company_access`
  ADD PRIMARY KEY (`product_id`,`company_id`),
  ADD KEY `idx_product_company_access_company_id` (`company_id`);

--
-- Indexes for table `proforma_invoices`
--
ALTER TABLE `proforma_invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_no` (`invoice_no`),
  ADD KEY `quotation_id` (`quotation_id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `po_no` (`po_no`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `idx_purchase_orders_client_po` (`client_purchase_order_id`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_id` (`purchase_order_id`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_quotations_quotation_no` (`quotation_no`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quotation_id` (`quotation_id`);

--
-- Indexes for table `quotation_negotiations`
--
ALTER TABLE `quotation_negotiations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quotation_id_round_no` (`quotation_id`,`round_no`);

--
-- Indexes for table `quotation_settings`
--
ALTER TABLE `quotation_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quotation_status_logs`
--
ALTER TABLE `quotation_status_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quotation_id` (`quotation_id`);

--
-- Indexes for table `tender_documents`
--
ALTER TABLE `tender_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `valid_until` (`valid_until`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_company_access`
--
ALTER TABLE `user_company_access`
  ADD PRIMARY KEY (`user_id`,`company_id`),
  ADD KEY `idx_user_company_access_company_id` (`company_id`);

--
-- Indexes for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`user_id`,`permission_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `vendor_bills`
--
ALTER TABLE `vendor_bills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bill_no` (`bill_no`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auth_audit_logs`
--
ALTER TABLE `auth_audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `bast_documents`
--
ALTER TABLE `bast_documents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_delivery_notes`
--
ALTER TABLE `client_delivery_notes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_purchase_orders`
--
ALTER TABLE `client_purchase_orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_purchase_order_items`
--
ALTER TABLE `client_purchase_order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `company_bank_accounts`
--
ALTER TABLE `company_bank_accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `proforma_invoices`
--
ALTER TABLE `proforma_invoices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `quotation_items`
--
ALTER TABLE `quotation_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `quotation_negotiations`
--
ALTER TABLE `quotation_negotiations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `quotation_status_logs`
--
ALTER TABLE `quotation_status_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tender_documents`
--
ALTER TABLE `tender_documents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vendor_bills`
--
ALTER TABLE `vendor_bills`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bast_documents`
--
ALTER TABLE `bast_documents`
  ADD CONSTRAINT `bast_documents_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `client_delivery_notes`
--
ALTER TABLE `client_delivery_notes`
  ADD CONSTRAINT `client_delivery_notes_client_purchase_order_id_foreign` FOREIGN KEY (`client_purchase_order_id`) REFERENCES `client_purchase_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `client_delivery_notes_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `client_purchase_orders`
--
ALTER TABLE `client_purchase_orders`
  ADD CONSTRAINT `client_purchase_orders_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `client_purchase_orders_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `client_purchase_order_items`
--
ALTER TABLE `client_purchase_order_items`
  ADD CONSTRAINT `client_purchase_order_items_client_purchase_order_id_foreign` FOREIGN KEY (`client_purchase_order_id`) REFERENCES `client_purchase_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `product_company_access`
--
ALTER TABLE `product_company_access`
  ADD CONSTRAINT `fk_product_company_access_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_product_company_access_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `proforma_invoices`
--
ALTER TABLE `proforma_invoices`
  ADD CONSTRAINT `proforma_invoices_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `fk_purchase_orders_client_po` FOREIGN KEY (`client_purchase_order_id`) REFERENCES `client_purchase_orders` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_orders_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quotations`
--
ALTER TABLE `quotations`
  ADD CONSTRAINT `quotations_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD CONSTRAINT `quotation_items_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quotation_negotiations`
--
ALTER TABLE `quotation_negotiations`
  ADD CONSTRAINT `quotation_negotiations_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quotation_status_logs`
--
ALTER TABLE `quotation_status_logs`
  ADD CONSTRAINT `quotation_status_logs_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_company_access`
--
ALTER TABLE `user_company_access`
  ADD CONSTRAINT `fk_user_company_access_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_company_access_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vendor_bills`
--
ALTER TABLE `vendor_bills`
  ADD CONSTRAINT `vendor_bills_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
