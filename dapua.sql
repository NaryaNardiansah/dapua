-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 05 Jan 2026 pada 08.52
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dapua`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `analytics_tables`
--

CREATE TABLE `analytics_tables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('dapur-sakura-cache-setting_allowed_file_types', 's:16:\"jpg,jpeg,png,gif\";', 1767560945),
('dapur-sakura-cache-setting_email_verification_required', 'b:0;', 1767560945),
('dapur-sakura-cache-setting_maintenance_mode', 'a:2:{s:6:\"driver\";s:4:\"file\";s:5:\"store\";s:8:\"database\";}', 1767560945),
('dapur-sakura-cache-setting_max_file_size', 's:4:\"2048\";', 1767560945),
('dapur-sakura-cache-setting_notification_email', 's:29:\"notifications@dapursakura.com\";', 1767560945),
('dapur-sakura-cache-setting_registration_enabled', 'b:1;', 1767560945),
('dapur-sakura-cache-setting_site_address', 's:27:\"Jl. Contoh No. 123, Jakarta\";', 1767560945),
('dapur-sakura-cache-setting_site_description', 's:33:\"Restoran Jepang dan Lokal Terbaik\";', 1767560945),
('dapur-sakura-cache-setting_site_email', 's:21:\"dapursakura@gmail.com\";', 1767560945),
('dapur-sakura-cache-setting_site_logo', 's:16:\"/images/logo.png\";', 1767560945),
('dapur-sakura-cache-setting_site_name', 's:12:\"Dapur Sakura\";', 1767560945),
('dapur-sakura-cache-setting_site_phone', 's:17:\"+62 812-3456-7890\";', 1767560945),
('dapur-sakura-cache-setting_social_facebook', 's:0:\"\";', 1767560945),
('dapur-sakura-cache-setting_social_instagram', 's:0:\"\";', 1767560945),
('dapur-sakura-cache-setting_social_twitter', 's:0:\"\";', 1767560945),
('dapur-sakura-cache-setting_social_whatsapp', 's:0:\"\";', 1767560945),
('dapur-sakura-cache-setting_support_email', 's:23:\"support@dapursakura.com\";', 1767560945),
('dapur-sakura-cache-setting_timezone', 's:12:\"Asia/Jakarta\";', 1767560945),
('dapur-sakura-cache-shipping_setting_free_shipping_min', 's:1:\"0\";', 1767563749),
('dapur-sakura-cache-shipping_setting_max_shipping_distance', 's:3:\"100\";', 1767601618),
('dapur-sakura-cache-shipping_setting_shipping_base', 's:4:\"3000\";', 1767601293),
('dapur-sakura-cache-shipping_setting_shipping_per_km', 's:4:\"1000\";', 1767601293),
('dapur-sakura-cache-shipping_setting_shipping_radius', 's:2:\"50\";', 1767563749),
('dapur-sakura-cache-shipping_setting_store_lat', 's:9:\"-0.905980\";', 1767601293),
('dapur-sakura-cache-shipping_setting_store_lng', 's:10:\"100.356112\";', 1767601293);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `color` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT 0,
  `path` varchar(255) DEFAULT NULL,
  `view_count` int(11) NOT NULL DEFAULT 0,
  `total_sales` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_quantity_sold` int(11) NOT NULL DEFAULT 0,
  `product_count` int(11) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_trending` tinyint(1) NOT NULL DEFAULT 0,
  `promotional_text` varchar(255) DEFAULT NULL,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`settings`)),
  `featured_until` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `color`, `image`, `banner`, `icon`, `description`, `meta_title`, `meta_description`, `keywords`, `is_active`, `sort_order`, `created_at`, `updated_at`, `parent_id`, `level`, `path`, `view_count`, `total_sales`, `total_quantity_sold`, `product_count`, `is_featured`, `is_trending`, `promotional_text`, `settings`, `featured_until`) VALUES
(1, 'Ayam Krispi', 'ayam-krispi', '#f472b6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '2026-01-04 18:13:33', '2026-01-05 06:01:25', NULL, 0, NULL, 0, 30000.00, 2, 0, 0, 0, NULL, NULL, NULL),
(2, 'Ayam Katsu', 'ayam-katsu', '#fb7185', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-01-04 18:13:33', '2026-01-05 07:43:13', NULL, 0, NULL, 0, 20000.00, 1, 0, 0, 0, NULL, NULL, NULL),
(3, 'Aneka Mie Olahan', 'aneka-mie-olahan', '#f9a8d4', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 2, '2026-01-04 18:13:33', '2026-01-05 07:41:32', NULL, 0, NULL, 0, 25000.00, 1, 0, 0, 0, NULL, NULL, NULL),
(4, 'Menu Lokal', 'menu-lokal', '#fda4af', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 3, '2026-01-04 18:13:33', '2026-01-04 18:13:33', NULL, 0, NULL, 0, 0.00, 0, 0, 0, 0, NULL, NULL, NULL),
(5, 'Aneka Minuman', 'aneka-minuman', '#fce7f3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 4, '2026-01-04 18:13:33', '2026-01-04 18:13:33', NULL, 0, NULL, 0, 0.00, 0, 0, 0, 0, NULL, NULL, NULL),
(6, 'Paket Hemat', 'paket-hemat', '#fda4d4', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 5, '2026-01-04 18:13:33', '2026-01-04 18:13:33', NULL, 0, NULL, 0, 0.00, 0, 0, 0, 0, NULL, NULL, NULL),
(7, 'Paket Combo', 'paket-combo', '#fbcfe8', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 6, '2026-01-04 18:13:33', '2026-01-04 18:13:33', NULL, 0, NULL, 0, 0.00, 0, 0, 0, 0, NULL, NULL, NULL),
(8, 'Menu Istimewa', 'menu-istimewa', '#f43f5e', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 7, '2026-01-04 18:13:33', '2026-01-04 18:13:33', NULL, 0, NULL, 0, 0.00, 0, 0, 0, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('percentage','fixed','free_shipping') NOT NULL,
  `value` decimal(10,2) DEFAULT NULL,
  `minimum_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `maximum_discount` decimal(10,2) DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `usage_limit_per_user` int(11) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `starts_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `applicable_products` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`applicable_products`)),
  `applicable_categories` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`applicable_categories`)),
  `applicable_users` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`applicable_users`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `coupon_usages`
--

CREATE TABLE `coupon_usages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `coupon_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `used_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `customer_segments`
--

CREATE TABLE `customer_segments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `criteria` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`criteria`)),
  `color` varchar(7) NOT NULL DEFAULT '#3B82F6',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `customer_segment_assignments`
--

CREATE TABLE `customer_segment_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `customer_segment_id` bigint(20) UNSIGNED NOT NULL,
  `segment_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`segment_data`)),
  `score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `delivery_zones`
--

CREATE TABLE `delivery_zones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `polygon_coordinates` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`polygon_coordinates`)),
  `base_rate` decimal(10,2) NOT NULL DEFAULT 5000.00,
  `per_km_rate` decimal(10,2) NOT NULL DEFAULT 2000.00,
  `multiplier` decimal(3,2) NOT NULL DEFAULT 1.00,
  `max_distance_km` int(11) NOT NULL DEFAULT 50,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `color` varchar(255) NOT NULL DEFAULT '#EC4899',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `delivery_zones`
--

INSERT INTO `delivery_zones` (`id`, `name`, `slug`, `description`, `polygon_coordinates`, `base_rate`, `per_km_rate`, `multiplier`, `max_distance_km`, `is_active`, `color`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Zona Pusat Kota', 'pusat-kota', 'Area pusat kota dengan tarif standar', '[[-0.95,100.4],[-0.94,100.4],[-0.94,100.42],[-0.95,100.42],[-0.95,100.4]]', 5000.00, 2000.00, 1.00, 15, 1, '#EC4899', 1, '2026-01-04 18:13:33', '2026-01-04 18:13:33'),
(2, 'Zona Pinggiran', 'pinggiran', 'Area pinggiran kota dengan tarif lebih tinggi', '[[-0.96,100.38],[-0.93,100.38],[-0.93,100.44],[-0.96,100.44],[-0.96,100.38]]', 8000.00, 3000.00, 1.50, 25, 1, '#F59E0B', 2, '2026-01-04 18:13:33', '2026-01-04 18:13:33'),
(3, 'Zona Luar Kota', 'luar-kota', 'Area luar kota dengan tarif premium', '[[-0.98,100.35],[-0.92,100.35],[-0.92,100.45],[-0.98,100.45],[-0.98,100.35]]', 12000.00, 4000.00, 2.00, 50, 1, '#EF4444', 3, '2026-01-04 18:13:33', '2026-01-04 18:13:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `driver_locations`
--

CREATE TABLE `driver_locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `driver_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `accuracy` decimal(8,2) DEFAULT NULL,
  `speed` decimal(8,2) DEFAULT NULL,
  `heading` decimal(5,2) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'online',
  `last_seen_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"61f13da5-6385-4855-9d2b-bd8146a5866a\",\"displayName\":\"App\\\\Notifications\\\\CustomNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:1;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\CustomNotification\\\":4:{s:5:\\\"title\\\";s:39:\\\"Update Status Pesanan - DS-20260105-001\\\";s:7:\\\"message\\\";s:142:\\\"Driver Siti Memey telah mengubah status pesanan DS-20260105-001 dari \'dikirim\' menjadi \'selesai\'. Pesanan sekarang telah selesai dan diterima.\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:2;s:10:\\\"order_code\\\";s:15:\\\"DS-20260105-001\\\";s:10:\\\"old_status\\\";s:7:\\\"dikirim\\\";s:10:\\\"new_status\\\";s:7:\\\"selesai\\\";s:9:\\\"driver_id\\\";i:11;s:11:\\\"driver_name\\\";s:10:\\\"Siti Memey\\\";s:10:\\\"updated_at\\\";s:19:\\\"2026-01-05 03:48:34\\\";}s:2:\\\"id\\\";s:36:\\\"62c75854-58c9-428f-b72c-0322e418bfae\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:8:\\\"database\\\";}}\"},\"createdAt\":1767559714,\"delay\":null}', 0, NULL, 1767559714, 1767559714),
(2, 'default', '{\"uuid\":\"92c9f86f-ce69-4571-8f6c-cac9f719e91c\",\"displayName\":\"App\\\\Notifications\\\\CustomNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:1;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\CustomNotification\\\":4:{s:5:\\\"title\\\";s:39:\\\"Update Status Pesanan - DS-20260105-001\\\";s:7:\\\"message\\\";s:142:\\\"Driver Siti Memey telah mengubah status pesanan DS-20260105-001 dari \'dikirim\' menjadi \'selesai\'. Pesanan sekarang telah selesai dan diterima.\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:2;s:10:\\\"order_code\\\";s:15:\\\"DS-20260105-001\\\";s:10:\\\"old_status\\\";s:7:\\\"dikirim\\\";s:10:\\\"new_status\\\";s:7:\\\"selesai\\\";s:9:\\\"driver_id\\\";i:11;s:11:\\\"driver_name\\\";s:10:\\\"Siti Memey\\\";s:10:\\\"updated_at\\\";s:19:\\\"2026-01-05 03:48:34\\\";}s:2:\\\"id\\\";s:36:\\\"62c75854-58c9-428f-b72c-0322e418bfae\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1767559714,\"delay\":null}', 0, NULL, 1767559714, 1767559714),
(3, 'default', '{\"uuid\":\"c3203398-75bb-4985-b1be-715f30a3afbf\",\"displayName\":\"App\\\\Notifications\\\\CustomNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:10;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\CustomNotification\\\":4:{s:5:\\\"title\\\";s:39:\\\"Update Status Pesanan - DS-20260105-001\\\";s:7:\\\"message\\\";s:142:\\\"Driver Siti Memey telah mengubah status pesanan DS-20260105-001 dari \'dikirim\' menjadi \'selesai\'. Pesanan sekarang telah selesai dan diterima.\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:2;s:10:\\\"order_code\\\";s:15:\\\"DS-20260105-001\\\";s:10:\\\"old_status\\\";s:7:\\\"dikirim\\\";s:10:\\\"new_status\\\";s:7:\\\"selesai\\\";s:9:\\\"driver_id\\\";i:11;s:11:\\\"driver_name\\\";s:10:\\\"Siti Memey\\\";s:10:\\\"updated_at\\\";s:19:\\\"2026-01-05 03:48:34\\\";}s:2:\\\"id\\\";s:36:\\\"c48ecc5a-6384-472d-869c-019dfb3fca04\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:8:\\\"database\\\";}}\"},\"createdAt\":1767559714,\"delay\":null}', 0, NULL, 1767559714, 1767559714),
(4, 'default', '{\"uuid\":\"84ea55b2-429d-453a-8b4c-cf262c591fa4\",\"displayName\":\"App\\\\Notifications\\\\CustomNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:10;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\CustomNotification\\\":4:{s:5:\\\"title\\\";s:39:\\\"Update Status Pesanan - DS-20260105-001\\\";s:7:\\\"message\\\";s:142:\\\"Driver Siti Memey telah mengubah status pesanan DS-20260105-001 dari \'dikirim\' menjadi \'selesai\'. Pesanan sekarang telah selesai dan diterima.\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:2;s:10:\\\"order_code\\\";s:15:\\\"DS-20260105-001\\\";s:10:\\\"old_status\\\";s:7:\\\"dikirim\\\";s:10:\\\"new_status\\\";s:7:\\\"selesai\\\";s:9:\\\"driver_id\\\";i:11;s:11:\\\"driver_name\\\";s:10:\\\"Siti Memey\\\";s:10:\\\"updated_at\\\";s:19:\\\"2026-01-05 03:48:34\\\";}s:2:\\\"id\\\";s:36:\\\"c48ecc5a-6384-472d-869c-019dfb3fca04\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1767559714,\"delay\":null}', 0, NULL, 1767559714, 1767559714),
(5, 'default', '{\"uuid\":\"98ddb5c8-0400-40a5-8f9f-4613fcd24c02\",\"displayName\":\"App\\\\Notifications\\\\CustomNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:1;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\CustomNotification\\\":4:{s:5:\\\"title\\\";s:39:\\\"Update Status Pesanan - DS-20260105-002\\\";s:7:\\\"message\\\";s:142:\\\"Driver Siti Memey telah mengubah status pesanan DS-20260105-002 dari \'dikirim\' menjadi \'selesai\'. Pesanan sekarang telah selesai dan diterima.\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:9;s:10:\\\"order_code\\\";s:15:\\\"DS-20260105-002\\\";s:10:\\\"old_status\\\";s:7:\\\"dikirim\\\";s:10:\\\"new_status\\\";s:7:\\\"selesai\\\";s:9:\\\"driver_id\\\";i:11;s:11:\\\"driver_name\\\";s:10:\\\"Siti Memey\\\";s:10:\\\"updated_at\\\";s:19:\\\"2026-01-05 14:45:08\\\";}s:2:\\\"id\\\";s:36:\\\"23929977-57be-4329-8829-c56e345247e1\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:8:\\\"database\\\";}}\"},\"createdAt\":1767599108,\"delay\":null}', 0, NULL, 1767599108, 1767599108),
(6, 'default', '{\"uuid\":\"bf1fc735-50fe-43b5-96f1-eb2e75d46770\",\"displayName\":\"App\\\\Notifications\\\\CustomNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:1;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\CustomNotification\\\":4:{s:5:\\\"title\\\";s:39:\\\"Update Status Pesanan - DS-20260105-002\\\";s:7:\\\"message\\\";s:142:\\\"Driver Siti Memey telah mengubah status pesanan DS-20260105-002 dari \'dikirim\' menjadi \'selesai\'. Pesanan sekarang telah selesai dan diterima.\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:9;s:10:\\\"order_code\\\";s:15:\\\"DS-20260105-002\\\";s:10:\\\"old_status\\\";s:7:\\\"dikirim\\\";s:10:\\\"new_status\\\";s:7:\\\"selesai\\\";s:9:\\\"driver_id\\\";i:11;s:11:\\\"driver_name\\\";s:10:\\\"Siti Memey\\\";s:10:\\\"updated_at\\\";s:19:\\\"2026-01-05 14:45:08\\\";}s:2:\\\"id\\\";s:36:\\\"23929977-57be-4329-8829-c56e345247e1\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1767599108,\"delay\":null}', 0, NULL, 1767599108, 1767599108),
(7, 'default', '{\"uuid\":\"d22cfed7-3bb3-4fd4-a430-bcd0278b8cdf\",\"displayName\":\"App\\\\Notifications\\\\CustomNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:10;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\CustomNotification\\\":4:{s:5:\\\"title\\\";s:39:\\\"Update Status Pesanan - DS-20260105-002\\\";s:7:\\\"message\\\";s:142:\\\"Driver Siti Memey telah mengubah status pesanan DS-20260105-002 dari \'dikirim\' menjadi \'selesai\'. Pesanan sekarang telah selesai dan diterima.\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:9;s:10:\\\"order_code\\\";s:15:\\\"DS-20260105-002\\\";s:10:\\\"old_status\\\";s:7:\\\"dikirim\\\";s:10:\\\"new_status\\\";s:7:\\\"selesai\\\";s:9:\\\"driver_id\\\";i:11;s:11:\\\"driver_name\\\";s:10:\\\"Siti Memey\\\";s:10:\\\"updated_at\\\";s:19:\\\"2026-01-05 14:45:08\\\";}s:2:\\\"id\\\";s:36:\\\"c890195e-eccf-4e4d-8411-5c6c295647fa\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:8:\\\"database\\\";}}\"},\"createdAt\":1767599108,\"delay\":null}', 0, NULL, 1767599108, 1767599108),
(8, 'default', '{\"uuid\":\"612a0093-6295-46a1-bd88-ab4f56ff4500\",\"displayName\":\"App\\\\Notifications\\\\CustomNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:10;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\CustomNotification\\\":4:{s:5:\\\"title\\\";s:39:\\\"Update Status Pesanan - DS-20260105-002\\\";s:7:\\\"message\\\";s:142:\\\"Driver Siti Memey telah mengubah status pesanan DS-20260105-002 dari \'dikirim\' menjadi \'selesai\'. Pesanan sekarang telah selesai dan diterima.\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:9;s:10:\\\"order_code\\\";s:15:\\\"DS-20260105-002\\\";s:10:\\\"old_status\\\";s:7:\\\"dikirim\\\";s:10:\\\"new_status\\\";s:7:\\\"selesai\\\";s:9:\\\"driver_id\\\";i:11;s:11:\\\"driver_name\\\";s:10:\\\"Siti Memey\\\";s:10:\\\"updated_at\\\";s:19:\\\"2026-01-05 14:45:08\\\";}s:2:\\\"id\\\";s:36:\\\"c890195e-eccf-4e4d-8411-5c6c295647fa\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1767599108,\"delay\":null}', 0, NULL, 1767599108, 1767599108);

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `loyalty_points`
--

CREATE TABLE `loyalty_points` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `loyalty_programs`
--

CREATE TABLE `loyalty_programs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `loyalty_rewards`
--

CREATE TABLE `loyalty_rewards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `loyalty_transactions`
--

CREATE TABLE `loyalty_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_09_30_161215_create_categories_table', 1),
(5, '2025_09_30_161215_create_products_table', 1),
(6, '2025_09_30_161215_z_add_basic_columns_to_categories_table', 1),
(7, '2025_09_30_161216_create_orders_table', 1),
(8, '2025_09_30_161217_create_order_items_table', 1),
(9, '2025_09_30_161646_add_is_admin_to_users_table', 1),
(10, '2025_09_30_172436_create_wishlists_table', 1),
(11, '2025_09_30_173511_create_reviews_table', 1),
(12, '2025_09_30_195751_add_order_code_to_orders_table', 1),
(13, '2025_09_30_200635_add_deleted_at_to_products_table', 1),
(14, '2025_10_01_052725_add_is_blocked_to_users_table', 1),
(15, '2025_10_01_053232_create_shipping_settings_table', 1),
(16, '2025_10_03_212638_add_tracking_fields_to_orders_table', 1),
(17, '2025_10_03_212654_create_delivery_zones_table', 1),
(18, '2025_10_03_212710_add_is_driver_to_users_table', 1),
(19, '2025_10_03_214500_add_realtime_fields_to_orders_table', 1),
(20, '2025_10_03_220645_add_photo_to_users_table', 1),
(21, '2025_10_03_224506_create_order_timeline_table', 1),
(22, '2025_10_03_224525_create_notifications_table', 1),
(23, '2025_10_03_230537_fix_duplicate_columns_in_orders_table', 1),
(24, '2025_10_03_231451_create_order_chats_table', 1),
(25, '2025_10_03_231515_create_driver_locations_table', 1),
(26, '2025_10_04_051635_create_analytics_table', 1),
(27, '2025_10_04_070735_create_missing_analytics_tables', 1),
(28, '2025_10_04_071014_fix_click_heatmaps_table', 1),
(29, '2025_10_04_072430_drop_analytics_tables', 1),
(30, '2025_10_04_073949_add_address_fields_to_users_table', 1),
(31, '2025_10_04_081132_fix_categories_table_structure', 1),
(32, '2025_10_04_085010_add_advanced_fields_to_products_table', 1),
(33, '2025_10_04_180414_create_roles_and_user_roles_tables', 1),
(34, '2025_10_04_181919_add_license_number_to_users_table', 1),
(35, '2025_10_04_193556_create_analytics_tables', 1),
(36, '2025_10_04_194627_add_missing_enhanced_dashboard_tables', 1),
(37, '2025_10_04_194939_add_group_column_to_system_settings', 1),
(38, '2025_10_04_204339_create_coupons_table', 1),
(39, '2025_10_04_204420_create_coupon_usages_table', 1),
(40, '2025_10_04_204805_create_stock_movements_table', 1),
(41, '2025_10_04_204939_create_stock_alerts_table', 1),
(42, '2025_10_04_205534_create_notification_templates_table', 1),
(43, '2025_10_04_205921_create_customer_segments_table', 1),
(44, '2025_10_04_205951_create_customer_segment_assignments_table', 1),
(45, '2025_10_05_032441_create_loyalty_programs_table', 1),
(46, '2025_10_05_032448_create_loyalty_points_table', 1),
(47, '2025_10_05_032454_create_loyalty_rewards_table', 1),
(48, '2025_10_05_032458_create_loyalty_transactions_table', 1),
(49, '2025_10_05_033402_create_system_logs_table', 1),
(50, '2025_10_05_033407_create_system_alerts_table', 1),
(51, '2025_10_05_033411_create_system_metrics_table', 1),
(52, '2025_10_10_182913_add_icon_to_categories_table', 1),
(53, '2025_10_13_181828_add_total_quantity_sold_to_categories_table', 1),
(54, '2025_10_14_161107_create_settings_table', 1),
(55, '2025_11_10_131118_update_settings_table_add_columns', 1),
(56, '2025_11_10_132101_add_tax_and_service_fee_to_orders_table', 1),
(57, '2025_11_10_174722_ensure_settings_table_has_columns', 1),
(58, '2026_01_05_014958_add_social_columns_to_users_table', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `channel` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `error_message` text DEFAULT NULL,
  `retry_count` int(11) NOT NULL DEFAULT 0,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `notifications`
--

INSERT INTO `notifications` (`id`, `order_id`, `user_id`, `type`, `channel`, `title`, `message`, `data`, `status`, `scheduled_at`, `sent_at`, `delivered_at`, `error_message`, `retry_count`, `metadata`, `created_at`, `updated_at`) VALUES
(3, 11, 14, 'email', 'order_update', 'Update Pesanan #DS-20260105-004 - Dapur Sakura', 'Halo sintia cantik,\n\nPesanan #DS-20260105-004 Anda sedang dikirim. Driver: Siti Memey ( ).\n\nAnda dapat melacak pesanan Anda di: http://localhost.dapursakura.com:8000/tracking/TRK-160936FC\n\nTerima kasih telah memilih Dapur Sakura!\n\nSalam,\nTim Dapur Sakura', '[]', 'sent', '2026-01-05 07:41:40', '2026-01-05 07:41:40', NULL, NULL, 0, NULL, '2026-01-05 07:41:40', '2026-01-05 07:41:40'),
(4, 11, 14, 'email', 'order_update', 'Update Pesanan #DS-20260105-004 - Dapur Sakura', 'Halo sintia cantik,\n\nPesanan #DS-20260105-004 Anda sedang dikirim. Driver: Siti Memey ( ).\n\nAnda dapat melacak pesanan Anda di: http://localhost.dapursakura.com:8000/tracking/TRK-160936FC\n\nTerima kasih telah memilih Dapur Sakura!\n\nSalam,\nTim Dapur Sakura', '[]', 'sent', '2026-01-05 07:41:45', '2026-01-05 07:41:45', NULL, NULL, 0, NULL, '2026-01-05 07:41:45', '2026-01-05 07:41:45'),
(5, 9, 11, 'email', 'order_update', 'Update Pesanan #DS-20260105-002 - Dapur Sakura', 'Halo Siti Memey,\n\nPesanan #DS-20260105-002 Anda sedang dikirim. Driver: Siti Memey ( ).\n\nAnda dapat melacak pesanan Anda di: http://localhost.dapursakura.com:8000/tracking/TRK-CA027CEB\n\nTerima kasih telah memilih Dapur Sakura!\n\nSalam,\nTim Dapur Sakura', '[]', 'sent', '2026-01-05 07:43:17', '2026-01-05 07:43:17', NULL, NULL, 0, NULL, '2026-01-05 07:43:17', '2026-01-05 07:43:17'),
(6, 9, 1, 'in_app', 'order_status_update', 'Update Status Pesanan - DS-20260105-002', 'Driver Siti Memey telah mengubah status pesanan DS-20260105-002 dari \'dikirim\' menjadi \'selesai\'. Pesanan sekarang telah selesai dan diterima.', '{\"order_id\":9,\"order_code\":\"DS-20260105-002\",\"old_status\":\"dikirim\",\"new_status\":\"selesai\",\"driver_id\":11,\"driver_name\":\"Siti Memey\",\"updated_at\":\"2026-01-05 14:45:08\"}', 'sent', NULL, '2026-01-05 07:45:08', NULL, NULL, 0, NULL, '2026-01-05 07:45:08', '2026-01-05 07:45:08'),
(7, 9, 10, 'in_app', 'order_status_update', 'Update Status Pesanan - DS-20260105-002', 'Driver Siti Memey telah mengubah status pesanan DS-20260105-002 dari \'dikirim\' menjadi \'selesai\'. Pesanan sekarang telah selesai dan diterima.', '{\"order_id\":9,\"order_code\":\"DS-20260105-002\",\"old_status\":\"dikirim\",\"new_status\":\"selesai\",\"driver_id\":11,\"driver_name\":\"Siti Memey\",\"updated_at\":\"2026-01-05 14:45:08\"}', 'sent', NULL, '2026-01-05 07:45:08', NULL, NULL, 0, NULL, '2026-01-05 07:45:08', '2026-01-05 07:45:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notification_templates`
--

CREATE TABLE `notification_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `variables` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variables`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `send_email` tinyint(1) NOT NULL DEFAULT 0,
  `send_sms` tinyint(1) NOT NULL DEFAULT 0,
  `send_push` tinyint(1) NOT NULL DEFAULT 1,
  `send_whatsapp` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_code` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `subtotal` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `shipping_fee` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `discount_total` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `grand_total` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'unpaid',
  `midtrans_order_id` varchar(255) DEFAULT NULL,
  `midtrans_transaction_id` varchar(255) DEFAULT NULL,
  `tracking_code` varchar(255) DEFAULT NULL,
  `tracking_url` varchar(255) DEFAULT NULL,
  `assigned_at` timestamp NULL DEFAULT NULL,
  `driver_id` bigint(20) UNSIGNED DEFAULT NULL,
  `picked_up_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `estimated_delivery_at` timestamp NULL DEFAULT NULL,
  `delivery_photo` text DEFAULT NULL,
  `delivery_notes` text DEFAULT NULL,
  `order_notes` text DEFAULT NULL,
  `delivery_instructions` text DEFAULT NULL,
  `is_cancellable` tinyint(1) NOT NULL DEFAULT 1,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `customer_notified` tinyint(1) NOT NULL DEFAULT 0,
  `last_status_update` timestamp NULL DEFAULT NULL,
  `order_confirmed_at` timestamp NULL DEFAULT NULL,
  `preparation_started_at` timestamp NULL DEFAULT NULL,
  `preparation_completed_at` timestamp NULL DEFAULT NULL,
  `out_for_delivery_at` timestamp NULL DEFAULT NULL,
  `driver_arrived_at` timestamp NULL DEFAULT NULL,
  `estimated_preparation_time` timestamp NULL DEFAULT NULL,
  `estimated_delivery_time` timestamp NULL DEFAULT NULL,
  `estimated_delivery_minutes` int(11) DEFAULT NULL,
  `communication_log` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`communication_log`)),
  `special_requests` text DEFAULT NULL,
  `customer_confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `customer_confirmed_at` timestamp NULL DEFAULT NULL,
  `customer_feedback` text DEFAULT NULL,
  `customer_rating` int(11) DEFAULT NULL,
  `weather_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`weather_data`)),
  `delay_reason` text DEFAULT NULL,
  `delay_minutes` int(11) NOT NULL DEFAULT 0,
  `payment_reference` varchar(255) DEFAULT NULL,
  `payment_confirmed_at` timestamp NULL DEFAULT NULL,
  `refund_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`refund_data`)),
  `performance_metrics` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`performance_metrics`)),
  `total_preparation_time_minutes` int(11) DEFAULT NULL,
  `total_delivery_time_minutes` int(11) DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `share_token` varchar(255) DEFAULT NULL,
  `social_shares` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`social_shares`)),
  `device_token` varchar(255) DEFAULT NULL,
  `platform` varchar(255) DEFAULT NULL,
  `app_metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`app_metadata`)),
  `status_history` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`status_history`)),
  `delivery_rating` int(11) DEFAULT NULL,
  `delivery_feedback` text DEFAULT NULL,
  `delivery_zone` varchar(255) DEFAULT NULL,
  `zone_multiplier` decimal(3,2) NOT NULL DEFAULT 1.00,
  `recipient_name` varchar(255) NOT NULL,
  `recipient_phone` varchar(255) NOT NULL,
  `address_line` varchar(255) NOT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `distance_meters` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `order_code`, `user_id`, `status`, `subtotal`, `shipping_fee`, `discount_total`, `grand_total`, `payment_method`, `payment_status`, `midtrans_order_id`, `midtrans_transaction_id`, `tracking_code`, `tracking_url`, `assigned_at`, `driver_id`, `picked_up_at`, `delivered_at`, `estimated_delivery_at`, `delivery_photo`, `delivery_notes`, `order_notes`, `delivery_instructions`, `is_cancellable`, `cancelled_at`, `cancellation_reason`, `customer_notified`, `last_status_update`, `order_confirmed_at`, `preparation_started_at`, `preparation_completed_at`, `out_for_delivery_at`, `driver_arrived_at`, `estimated_preparation_time`, `estimated_delivery_time`, `estimated_delivery_minutes`, `communication_log`, `special_requests`, `customer_confirmed`, `customer_confirmed_at`, `customer_feedback`, `customer_rating`, `weather_data`, `delay_reason`, `delay_minutes`, `payment_reference`, `payment_confirmed_at`, `refund_data`, `performance_metrics`, `total_preparation_time_minutes`, `total_delivery_time_minutes`, `qr_code`, `share_token`, `social_shares`, `device_token`, `platform`, `app_metadata`, `status_history`, `delivery_rating`, `delivery_feedback`, `delivery_zone`, `zone_multiplier`, `recipient_name`, `recipient_phone`, `address_line`, `latitude`, `longitude`, `distance_meters`, `created_at`, `updated_at`) VALUES
(8, 'DS-20260105-001', 13, 'dikirim', 30000, 4874, 0, 34874, 'midtrans', 'paid', 'ORDER-8-1767592832', NULL, 'TRK-EA225E8C', 'http://localhost.dapursakura.com:8000/tracking/TRK-EA225E8C', '2026-01-05 06:01:25', 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1.00, 'Cewek Sasimo', '082181746774', 'Lokasi Peta (-0.9107797395986116, 100.37226938575313)', -0.9107797, 100.3722694, 1575, '2026-01-05 06:00:32', '2026-01-05 06:01:25'),
(9, 'DS-20260105-002', 11, 'selesai', 20000, 3000, 0, 23000, 'midtrans', 'paid', 'ORDER-9-1767598019', NULL, 'TRK-CA027CEB', 'http://localhost.dapursakura.com:8000/tracking/TRK-CA027CEB', '2026-01-05 07:43:13', 11, NULL, '2026-01-05 07:45:08', NULL, NULL, 'cantek', NULL, NULL, 1, NULL, NULL, 0, '2026-01-05 07:45:08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1.00, 'Siti Memey', '082286904493', 'Lokasi Peta (, )', NULL, NULL, NULL, '2026-01-05 07:26:59', '2026-01-05 07:45:08'),
(10, 'DS-20260105-003', 11, 'pending', 15000, 3867, 0, 18867, 'midtrans', 'unpaid', 'ORDER-10-1767598303', NULL, 'TRK-32D73B09', 'http://localhost.dapursakura.com:8000/tracking/TRK-32D73B09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1.00, 'Siti Memey', '+6282181746774', 'Lokasi Peta (-0.9135665420709524, 100.35430532632148)', -0.9135665, 100.3543053, 1293, '2026-01-05 07:31:43', '2026-01-05 07:31:43'),
(11, 'DS-20260105-004', 14, 'dikirim', 25000, 11993, 0, 36993, 'midtrans', 'paid', 'ORDER-11-1767598750', NULL, 'TRK-160936FC', 'http://localhost.dapursakura.com:8000/tracking/TRK-160936FC', '2026-01-05 07:41:40', 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1.00, 'sintia cantik', '081234567891', 'Lokasi Peta (-0.9275741084560555, 100.43405869119823)', -0.9275741, 100.4340587, 8646, '2026-01-05 07:39:10', '2026-01-05 07:41:40');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_chats`
--

CREATE TABLE `order_chats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `sender_type` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `message_type` varchar(255) NOT NULL DEFAULT 'text',
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `is_system_message` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `unit_price` int(10) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `line_total` int(10) UNSIGNED NOT NULL,
  `selected_variant` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`selected_variant`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `unit_price`, `quantity`, `line_total`, `selected_variant`, `created_at`, `updated_at`) VALUES
(8, 8, 1, 'Ayam Krispi Saos Korea', 15000, 2, 30000, NULL, '2026-01-05 06:00:32', '2026-01-05 06:00:32'),
(9, 9, 4, 'Katsu Original', 20000, 1, 20000, NULL, '2026-01-05 07:26:59', '2026-01-05 07:26:59'),
(10, 10, 1, 'Ayam Krispi Saos Korea', 15000, 1, 15000, NULL, '2026-01-05 07:31:43', '2026-01-05 07:31:43'),
(11, 11, 7, 'Kwetyau Paket Komplit', 25000, 1, 25000, NULL, '2026-01-05 07:39:10', '2026-01-05 07:39:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_timeline`
--

CREATE TABLE `order_timeline` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `color` varchar(255) NOT NULL DEFAULT '#6B7280',
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `triggered_by` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_automatic` tinyint(1) NOT NULL DEFAULT 0,
  `is_visible_to_customer` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `order_timeline`
--

INSERT INTO `order_timeline` (`id`, `order_id`, `status`, `title`, `description`, `icon`, `color`, `timestamp`, `metadata`, `triggered_by`, `user_id`, `is_automatic`, `is_visible_to_customer`, `created_at`, `updated_at`) VALUES
(6, 8, 'dikirim', 'Pesanan Dikirim', 'Driver sedang dalam perjalanan mengantarkan pesanan Anda.', 'fas fa-truck', '#F59E0B', '2026-01-05 06:01:47', '{\"driver_name\":\"Siti Memey\",\"notes\":\"Driver ditugaskan oleh admin\"}', 'admin', 1, 0, 1, '2026-01-05 06:01:47', '2026-01-05 06:01:47'),
(7, 11, 'dikirim', 'Pesanan Dikirim', 'Driver sedang dalam perjalanan mengantarkan pesanan Anda.', 'fas fa-truck', '#F59E0B', '2026-01-05 07:41:40', '{\"driver_name\":\"Siti Memey\",\"notes\":\"Driver ditugaskan oleh admin\"}', 'admin', 10, 0, 1, '2026-01-05 07:41:40', '2026-01-05 07:41:40'),
(8, 11, 'dikirim', 'Pesanan Dikirim', 'Driver sedang dalam perjalanan mengantarkan pesanan Anda.', 'fas fa-truck', '#F59E0B', '2026-01-05 07:41:45', '{\"driver_name\":\"Siti Memey\",\"notes\":\"Driver ditugaskan oleh admin\"}', 'admin', 10, 0, 1, '2026-01-05 07:41:45', '2026-01-05 07:41:45'),
(9, 9, 'dikirim', 'Pesanan Dikirim', 'Driver sedang dalam perjalanan mengantarkan pesanan Anda.', 'fas fa-truck', '#F59E0B', '2026-01-05 07:43:17', '{\"driver_name\":\"Siti Memey\",\"notes\":\"Driver ditugaskan oleh admin\"}', 'admin', 10, 0, 1, '2026-01-05 07:43:17', '2026-01-05 07:43:17'),
(10, 9, 'selesai', 'Pesanan Selesai', 'Pesanan Anda telah berhasil diterima. Terima kasih!', 'fas fa-check-circle', '#10B981', '2026-01-05 07:45:08', '{\"notes\":\"cantek\",\"driver_updated\":true,\"photo_path\":null}', 'driver', 11, 0, 1, '2026-01-05 07:45:08', '2026-01-05 07:45:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `weight` decimal(8,2) DEFAULT NULL,
  `dimensions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dimensions`)),
  `description` text DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `specifications` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`specifications`)),
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `view_count` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `cart_count` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `purchase_count` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `total_sales` decimal(10,2) NOT NULL DEFAULT 0.00,
  `image_path` varchar(255) DEFAULT NULL,
  `gallery` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery`)),
  `video_url` varchar(255) DEFAULT NULL,
  `price` int(10) UNSIGNED NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `min_stock` int(11) NOT NULL DEFAULT 5,
  `track_stock` tinyint(1) NOT NULL DEFAULT 1,
  `is_best_seller` tinyint(1) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `featured_until` timestamp NULL DEFAULT NULL,
  `is_new` tinyint(1) NOT NULL DEFAULT 0,
  `is_on_sale` tinyint(1) NOT NULL DEFAULT 0,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `sale_start` timestamp NULL DEFAULT NULL,
  `sale_end` timestamp NULL DEFAULT NULL,
  `variants` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variants`)),
  `variant_options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variant_options`)),
  `variant_prices` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variant_prices`)),
  `variant_stock` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variant_stock`)),
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`settings`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `sku`, `barcode`, `weight`, `dimensions`, `description`, `short_description`, `specifications`, `tags`, `meta_title`, `meta_description`, `meta_keywords`, `view_count`, `cart_count`, `purchase_count`, `total_sales`, `image_path`, `gallery`, `video_url`, `price`, `stock`, `min_stock`, `track_stock`, `is_best_seller`, `is_featured`, `featured_until`, `is_new`, `is_on_sale`, `sale_price`, `sale_start`, `sale_end`, `variants`, `variant_options`, `variant_prices`, `variant_stock`, `settings`, `is_active`, `sort_order`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Ayam Krispi Saos Korea', 'ayam-krispi-saos-korea', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[]', NULL, NULL, NULL, 0, 0, 2, 30000.00, NULL, NULL, NULL, 18000, 0, 5, 1, 0, 0, NULL, 0, 1, 15000.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '2026-01-04 18:13:33', '2026-01-05 06:01:25', NULL),
(2, 1, 'Ayam Krispi Saos Teriyaki', 'ayam-krispi-saos-teriyaki', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 18000.00, NULL, NULL, NULL, 18000, 0, 5, 1, 0, 0, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-01-04 18:13:33', '2026-01-04 21:05:46', NULL),
(3, 1, 'Ayam Krispi Lada Hitam', 'ayam-krispi-lada-hitam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, NULL, NULL, 18000, 0, 5, 1, 0, 0, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 2, '2026-01-04 18:13:33', '2026-01-04 18:13:33', NULL),
(4, 2, 'Katsu Original', 'katsu-original', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 20000.00, NULL, NULL, NULL, 20000, 0, 5, 1, 0, 0, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 3, '2026-01-04 18:13:33', '2026-01-05 07:43:13', NULL),
(5, 2, 'Katsu Teriyaki', 'katsu-teriyaki', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, NULL, NULL, 22000, 0, 5, 1, 0, 0, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 4, '2026-01-04 18:13:33', '2026-01-04 18:13:33', NULL),
(6, 2, 'Katsu Lada Hitam', 'katsu-lada-hitam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, NULL, NULL, 22000, 0, 5, 1, 0, 0, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 5, '2026-01-04 18:13:33', '2026-01-04 18:13:33', NULL),
(7, 3, 'Kwetyau Paket Komplit', 'kwetyau-paket-komplit', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 25000.00, NULL, NULL, NULL, 25000, 0, 5, 1, 0, 0, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 6, '2026-01-04 18:13:33', '2026-01-05 07:41:32', NULL),
(8, 3, 'Mie Becek', 'mie-becek', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, NULL, NULL, 20000, 0, 5, 1, 0, 0, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 7, '2026-01-04 18:13:33', '2026-01-04 18:13:33', NULL),
(9, 3, 'Mie Goreng Extra Hot', 'mie-goreng-extra-hot', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, NULL, NULL, 20000, 0, 5, 1, 0, 0, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 8, '2026-01-04 18:13:33', '2026-01-04 18:13:33', NULL),
(10, 4, 'Ayam Geprek Sambal Mantah', 'ayam-geprek-sambal-mantah', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, NULL, NULL, 18000, 0, 5, 1, 0, 0, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 9, '2026-01-04 18:13:33', '2026-01-04 18:13:33', NULL),
(11, 4, 'Ayam Geprek Sambal Terasi', 'ayam-geprek-sambal-terasi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, NULL, NULL, 18000, 0, 5, 1, 0, 0, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 10, '2026-01-04 18:13:33', '2026-01-04 18:13:33', NULL),
(12, 5, 'Teh Es', 'teh-es', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, NULL, NULL, 5000, 0, 5, 1, 0, 0, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 11, '2026-01-04 18:13:33', '2026-01-04 18:13:33', NULL),
(13, 5, 'Milk Ice', 'milk-ice', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, NULL, NULL, 10000, 0, 5, 1, 0, 0, NULL, 0, 0, NULL, NULL, NULL, '[\"strawberry\",\"matcha\",\"chocolate\",\"vanilla\"]', NULL, NULL, NULL, NULL, 1, 12, '2026-01-04 18:13:33', '2026-01-04 18:13:33', NULL),
(14, 5, 'Ice Lemontea', 'ice-lemontea', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, NULL, NULL, 8000, 0, 5, 1, 0, 0, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 13, '2026-01-04 18:13:33', '2026-01-04 18:13:33', NULL),
(15, 6, 'Ayam Korea + Free Es Teh', 'ayam-korea-free-es-teh', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, NULL, NULL, 20000, 0, 5, 1, 0, 0, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 14, '2026-01-04 18:13:33', '2026-01-04 18:13:33', NULL),
(16, 6, 'Ayam Katsu + Free Es Teh', 'ayam-katsu-free-es-teh', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, NULL, NULL, 22000, 0, 5, 1, 0, 0, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 15, '2026-01-04 18:13:33', '2026-01-04 18:13:33', NULL),
(17, 6, 'Ayam Lada Hitam + Free Es Teh', 'ayam-lada-hitam-free-es-teh', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, NULL, NULL, 20000, 0, 5, 1, 0, 0, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 16, '2026-01-04 18:13:33', '2026-01-04 18:13:33', NULL),
(18, 7, '2 Ayam Krispi + 1 Kwetyau + 2 Nasi Gila + 2 Es Teh + 2 Milk Ice', '2-ayam-krispi-1-kwetyau-2-nasi-gila-2-es-teh-2-milk-ice', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, NULL, NULL, 95000, 0, 5, 1, 0, 0, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 17, '2026-01-04 18:13:33', '2026-01-04 18:13:33', NULL),
(19, 8, 'Nasi Gila Dapur Sakura', 'nasi-gila-dapur-sakura', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, NULL, NULL, 25000, 0, 5, 1, 1, 0, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 18, '2026-01-04 18:13:33', '2026-01-04 18:13:33', NULL),
(20, 7, 'Siti Memey', 'siti-memey', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[]', NULL, NULL, NULL, 0, 0, 0, 0.00, 'products/LYWSMhvKbWZHA2lOdfY2UyGGJlpnHzyxup6nEfiQ.jpg', NULL, NULL, 100000, 0, 5, 1, 1, 1, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '2026-01-04 18:26:20', '2026-01-04 18:30:34', '2026-01-04 18:30:34');

-- --------------------------------------------------------

--
-- Struktur dari tabel `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `permissions`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin', 'Full access to all system features', '[\"users.view\",\"users.create\",\"users.edit\",\"users.delete\",\"products.view\",\"products.create\",\"products.edit\",\"products.delete\",\"orders.view\",\"orders.edit\",\"orders.delete\",\"categories.view\",\"categories.create\",\"categories.edit\",\"categories.delete\",\"drivers.view\",\"drivers.create\",\"drivers.edit\",\"drivers.delete\",\"reports.view\",\"settings.edit\"]', 1, '2026-01-04 18:13:33', '2026-01-04 18:13:33'),
(2, 'Driver', 'driver', 'Driver role for delivery management', '[\"orders.view\",\"orders.update_status\",\"delivery.track\",\"delivery.update_location\",\"delivery.mark_complete\"]', 1, '2026-01-04 18:13:33', '2026-01-04 18:13:33'),
(3, 'Customer', 'customer', 'Regular customer role', '[\"orders.create\",\"orders.view_own\",\"products.view\",\"profile.edit\"]', 1, '2026-01-04 18:13:33', '2026-01-04 18:13:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `shipping_settings`
--

CREATE TABLE `shipping_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `shipping_settings`
--

INSERT INTO `shipping_settings` (`id`, `key`, `value`, `description`, `created_at`, `updated_at`) VALUES
(1, 'store_lat', '-0.905980', 'Latitude toko', '2026-01-04 18:13:33', '2026-01-04 20:55:49'),
(2, 'store_lng', '100.356112', 'Longitude toko', '2026-01-04 18:13:33', '2026-01-04 20:55:49'),
(3, 'shipping_base', '3000', 'Tarif dasar ongkir (Rp)', '2026-01-04 18:13:33', '2026-01-04 20:55:49'),
(4, 'shipping_per_km', '1000', 'Tarif per kilometer (Rp)', '2026-01-04 18:13:33', '2026-01-04 20:55:49'),
(5, 'shipping_radius', '50', 'Radius layanan (km)', '2026-01-04 18:13:33', '2026-01-04 18:13:33'),
(6, 'free_shipping_min', '0', 'Minimum belanja gratis ongkir (Rp)', '2026-01-04 18:13:33', '2026-01-04 18:13:33'),
(7, 'max_shipping_distance', '100', 'Maksimum jarak pengiriman (km)', '2026-01-04 18:13:33', '2026-01-04 18:13:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `stock_alerts`
--

CREATE TABLE `stock_alerts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `threshold` int(11) NOT NULL,
  `current_stock` int(11) NOT NULL,
  `alert_type` enum('low_stock','out_of_stock','overstock') NOT NULL,
  `status` enum('active','acknowledged','resolved') NOT NULL,
  `message` text DEFAULT NULL,
  `acknowledged_at` timestamp NULL DEFAULT NULL,
  `acknowledged_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('in','out','adjustment','transfer') NOT NULL,
  `quantity` int(11) NOT NULL,
  `previous_stock` int(11) NOT NULL,
  `new_stock` int(11) NOT NULL,
  `reference_type` varchar(255) DEFAULT NULL,
  `reference_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `system_alerts`
--

CREATE TABLE `system_alerts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `system_logs`
--

CREATE TABLE `system_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `system_metrics`
--

CREATE TABLE `system_metrics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_driver` tinyint(1) NOT NULL DEFAULT 0,
  `driver_license` varchar(255) DEFAULT NULL,
  `vehicle_type` varchar(255) DEFAULT NULL,
  `vehicle_number` varchar(255) DEFAULT NULL,
  `current_latitude` decimal(10,8) DEFAULT NULL,
  `current_longitude` decimal(11,8) DEFAULT NULL,
  `last_location_update` timestamp NULL DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `photo` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `license_number` varchar(255) DEFAULT NULL,
  `is_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `provider_id` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `is_admin`, `is_driver`, `driver_license`, `vehicle_type`, `vehicle_number`, `current_latitude`, `current_longitude`, `last_location_update`, `is_available`, `photo`, `phone`, `license_number`, `is_blocked`, `email_verified_at`, `password`, `provider`, `provider_id`, `avatar`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@dapur-sakura.test', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, '2026-01-04 18:13:33', '$2y$12$IO6bi9fHKEMISMLQup5VjuvnTiqWNLwjbTjK/DPdHTlNNfK75aKb.', NULL, NULL, NULL, NULL, '2026-01-04 18:13:33', '2026-01-04 18:13:33'),
(8, 'Dedi Kurniawan', 'dedi.kurniawan@dapursakura.com', 0, 1, 'SIM123456792', 'Motor', 'BA 1237 JKL', -0.94740000, 100.41750000, '2026-01-04 17:43:35', 1, NULL, '081234567893', NULL, 0, NULL, '$2y$12$odK.3Y5p.aYTdwrvmHgJzucM6seAR7cyrmpod3txBQZffhyJh5IAO', NULL, NULL, NULL, NULL, '2026-01-04 18:13:35', '2026-01-04 18:13:35'),
(9, 'Eka Putri', 'eka.putri@dapursakura.com', 0, 1, 'SIM123456793', 'Sepeda', 'BA 1238 MNO', -0.94750000, 100.41760000, '2026-01-04 17:58:35', 1, NULL, '081234567894', NULL, 0, NULL, '$2y$12$DOE8LCgWql4jLkZVjgkKqu1jwZw/lzNFSqmSNUaC0qXiq.wJ3Ca36', NULL, NULL, NULL, NULL, '2026-01-04 18:13:35', '2026-01-04 18:13:35'),
(10, 'Muhammad Narya Nardiansah', 'naryanardiansah948@gmail.com', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, '2026-01-04 18:51:03', '$2y$12$xqF38v1Bptvv2s5hdGf9veKTWt0jrlLY/KwwIh5hOdAJOxLVOvjEC', 'google', '116891198216598309739', NULL, 'e9YY4pkvUdsQPZGwn1gBGLOUMYHuigR8yqsft4qkF2aA6AGZ9GcEbPxgHqBG', '2026-01-04 18:51:03', '2026-01-04 19:02:29'),
(11, 'Siti Memey', 'sitimemey58@gmail.com', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, '2026-01-04 18:58:23', '$2y$12$g/0v0NzyYbOGGBcfvyplie1EhSd9BkHxOlxmWnSqjG52/LqVSt/mW', 'google', '100969454318889645275', NULL, '0dPmO6PmcOklaPX7IyjIkPUKlTiNDW0s0OT4V9QIwqsjSJCrTpjDonjVhDTL', '2026-01-04 18:58:23', '2026-01-04 20:08:43'),
(12, 'Test Customer', 'testcustomer@example.com', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, NULL, '$2y$12$W/Uifen2OR1vW6hHp1.C2eJIXJCc4xLFXwSt5oiFymdHeCZViDBaO', NULL, NULL, NULL, NULL, '2026-01-04 20:26:32', '2026-01-04 20:26:32'),
(13, 'Cewek Sasimo', 'ceweksasimo@gmail.com', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, '2026-01-04 20:42:57', '$2y$12$RztaBKHAwh1Bli9bb739peRYIf2P.WuAB7gSqrontHPLtUEpLlOcy', 'google', '118226621922830392994', NULL, 'hA2IgfVRjnzWuXUwJbroOBc6mbV3sJ7ODRhMfeH4C0jNxYhJcpggl74FbEMA', '2026-01-04 20:42:57', '2026-01-04 20:42:57'),
(14, 'sintia cantik', 'tia@gmail.com', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, NULL, '$2y$12$77s0ndJ2H5GiYuJrDD3GvOmICLHzcLX19F0eebhAnf5TMZVedWJY2', NULL, NULL, NULL, NULL, '2026-01-05 07:34:30', '2026-01-05 07:34:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_roles`
--

CREATE TABLE `user_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(3, 10, 1, NULL, NULL),
(8, 11, 2, NULL, NULL),
(9, 12, 3, NULL, NULL),
(10, 13, 3, NULL, NULL),
(11, 14, 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `wishlists`
--

INSERT INTO `wishlists` (`id`, `user_id`, `product_id`, `created_at`, `updated_at`) VALUES
(3, 13, 1, '2026-01-05 05:36:15', '2026-01-05 05:36:15'),
(4, 13, 2, '2026-01-05 05:36:34', '2026-01-05 05:36:34');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `analytics_tables`
--
ALTER TABLE `analytics_tables`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Indeks untuk tabel `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_code_unique` (`code`);

--
-- Indeks untuk tabel `coupon_usages`
--
ALTER TABLE `coupon_usages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupon_usages_coupon_id_order_id_unique` (`coupon_id`,`order_id`),
  ADD KEY `coupon_usages_user_id_foreign` (`user_id`),
  ADD KEY `coupon_usages_order_id_foreign` (`order_id`);

--
-- Indeks untuk tabel `customer_segments`
--
ALTER TABLE `customer_segments`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `customer_segment_assignments`
--
ALTER TABLE `customer_segment_assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_segment_assignments_user_id_customer_segment_id_unique` (`user_id`,`customer_segment_id`),
  ADD KEY `customer_segment_assignments_customer_segment_id_score_index` (`customer_segment_id`,`score`);

--
-- Indeks untuk tabel `delivery_zones`
--
ALTER TABLE `delivery_zones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `delivery_zones_slug_unique` (`slug`);

--
-- Indeks untuk tabel `driver_locations`
--
ALTER TABLE `driver_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `driver_locations_driver_id_last_seen_at_index` (`driver_id`,`last_seen_at`),
  ADD KEY `driver_locations_order_id_last_seen_at_index` (`order_id`,`last_seen_at`),
  ADD KEY `driver_locations_status_last_seen_at_index` (`status`,`last_seen_at`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `loyalty_points`
--
ALTER TABLE `loyalty_points`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `loyalty_programs`
--
ALTER TABLE `loyalty_programs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `loyalty_rewards`
--
ALTER TABLE `loyalty_rewards`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `loyalty_transactions`
--
ALTER TABLE `loyalty_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_order_id_type_index` (`order_id`,`type`),
  ADD KEY `notifications_user_id_status_index` (`user_id`,`status`),
  ADD KEY `notifications_scheduled_at_status_index` (`scheduled_at`,`status`);

--
-- Indeks untuk tabel `notification_templates`
--
ALTER TABLE `notification_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `notification_templates_name_unique` (`name`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_share_token_unique` (`share_token`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_order_code_index` (`order_code`),
  ADD KEY `orders_driver_id_foreign` (`driver_id`);

--
-- Indeks untuk tabel `order_chats`
--
ALTER TABLE `order_chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_chats_order_id_created_at_index` (`order_id`,`created_at`),
  ADD KEY `order_chats_sender_id_sender_type_index` (`sender_id`,`sender_type`);

--
-- Indeks untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indeks untuk tabel `order_timeline`
--
ALTER TABLE `order_timeline`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_timeline_user_id_foreign` (`user_id`),
  ADD KEY `order_timeline_order_id_timestamp_index` (`order_id`,`timestamp`),
  ADD KEY `order_timeline_order_id_status_index` (`order_id`,`status`),
  ADD KEY `order_timeline_status_index` (`status`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indeks untuk tabel `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reviews_user_id_product_id_unique` (`user_id`,`product_id`),
  ADD KEY `reviews_product_id_foreign` (`product_id`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_slug_unique` (`slug`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indeks untuk tabel `shipping_settings`
--
ALTER TABLE `shipping_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shipping_settings_key_unique` (`key`);

--
-- Indeks untuk tabel `stock_alerts`
--
ALTER TABLE `stock_alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_alerts_product_id_foreign` (`product_id`),
  ADD KEY `stock_alerts_acknowledged_by_foreign` (`acknowledged_by`),
  ADD KEY `stock_alerts_status_created_at_index` (`status`,`created_at`),
  ADD KEY `stock_alerts_alert_type_status_index` (`alert_type`,`status`);

--
-- Indeks untuk tabel `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movements_user_id_foreign` (`user_id`),
  ADD KEY `stock_movements_product_id_created_at_index` (`product_id`,`created_at`),
  ADD KEY `stock_movements_type_created_at_index` (`type`,`created_at`),
  ADD KEY `stock_movements_reference_type_reference_id_index` (`reference_type`,`reference_id`);

--
-- Indeks untuk tabel `system_alerts`
--
ALTER TABLE `system_alerts`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `system_metrics`
--
ALTER TABLE `system_metrics`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indeks untuk tabel `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_roles_user_id_role_id_unique` (`user_id`,`role_id`),
  ADD KEY `user_roles_role_id_foreign` (`role_id`);

--
-- Indeks untuk tabel `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wishlists_user_id_product_id_unique` (`user_id`,`product_id`),
  ADD KEY `wishlists_product_id_foreign` (`product_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `analytics_tables`
--
ALTER TABLE `analytics_tables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `coupon_usages`
--
ALTER TABLE `coupon_usages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `customer_segments`
--
ALTER TABLE `customer_segments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `customer_segment_assignments`
--
ALTER TABLE `customer_segment_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `delivery_zones`
--
ALTER TABLE `delivery_zones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `driver_locations`
--
ALTER TABLE `driver_locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `loyalty_points`
--
ALTER TABLE `loyalty_points`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `loyalty_programs`
--
ALTER TABLE `loyalty_programs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `loyalty_rewards`
--
ALTER TABLE `loyalty_rewards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `loyalty_transactions`
--
ALTER TABLE `loyalty_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT untuk tabel `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `notification_templates`
--
ALTER TABLE `notification_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `order_chats`
--
ALTER TABLE `order_chats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `order_timeline`
--
ALTER TABLE `order_timeline`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `shipping_settings`
--
ALTER TABLE `shipping_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `stock_alerts`
--
ALTER TABLE `stock_alerts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `system_alerts`
--
ALTER TABLE `system_alerts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `system_metrics`
--
ALTER TABLE `system_metrics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `coupon_usages`
--
ALTER TABLE `coupon_usages`
  ADD CONSTRAINT `coupon_usages_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coupon_usages_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coupon_usages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `customer_segment_assignments`
--
ALTER TABLE `customer_segment_assignments`
  ADD CONSTRAINT `customer_segment_assignments_customer_segment_id_foreign` FOREIGN KEY (`customer_segment_id`) REFERENCES `customer_segments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_segment_assignments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `driver_locations`
--
ALTER TABLE `driver_locations`
  ADD CONSTRAINT `driver_locations_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `driver_locations_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `order_chats`
--
ALTER TABLE `order_chats`
  ADD CONSTRAINT `order_chats_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_chats_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `order_timeline`
--
ALTER TABLE `order_timeline`
  ADD CONSTRAINT `order_timeline_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_timeline_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `stock_alerts`
--
ALTER TABLE `stock_alerts`
  ADD CONSTRAINT `stock_alerts_acknowledged_by_foreign` FOREIGN KEY (`acknowledged_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_alerts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
