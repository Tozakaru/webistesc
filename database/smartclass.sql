-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 23 Jul 2025 pada 11.26
-- Versi server: 8.0.30
-- Versi PHP: 8.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smartclass`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `esp_devices`
--

CREATE TABLE `esp_devices` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_seen` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `ruangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `waktu_masuk` time DEFAULT NULL,
  `waktu_keluar` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id`, `mahasiswa_id`, `tanggal`, `ruangan`, `waktu_masuk`, `waktu_keluar`) VALUES
(34, 5, '2025-07-17', 'ruangan1', '13:06:35', NULL),
(35, 1, '2025-07-17', 'ruangan1', '13:13:39', NULL),
(36, 2, '2025-07-17', 'ruangan1', '13:13:52', NULL),
(37, 3, '2025-07-17', 'ruangan1', '13:14:08', NULL),
(38, 4, '2025-07-17', 'ruangan1', '13:14:30', NULL),
(39, 8, '2025-07-17', 'ruangan1', '13:22:12', NULL),
(40, 9, '2025-07-17', 'ruangan1', '13:23:21', NULL),
(41, 10, '2025-07-17', 'ruangan1', '13:23:30', NULL),
(42, 11, '2025-07-17', 'ruangan1', '13:23:41', NULL),
(43, 12, '2025-07-17', 'ruangan1', '13:23:54', '13:26:16'),
(44, 13, '2025-07-17', 'ruangan1', '13:24:12', NULL),
(45, 14, '2025-07-17', 'ruangan1', '13:24:27', NULL),
(46, 2, '2025-07-18', 'ruangan2', '12:31:44', NULL),
(47, 1, '2025-07-18', 'ruangan1', '12:33:15', NULL),
(48, 1, '2025-07-21', 'ruangan2', '12:46:38', NULL),
(49, 3, '2025-07-21', 'ruangan2', '12:56:27', NULL),
(50, 5, '2025-07-21', 'ruangan2', '13:07:23', NULL),
(51, 1, '2025-07-22', 'ruangan1', '18:31:23', '18:33:55'),
(52, 1, '2025-07-23', 'ruangan1', '01:07:08', '01:10:36'),
(53, 4, '2025-07-23', 'ruangan1', '13:23:04', '13:23:11'),
(54, 2, '2025-07-23', 'ruangan1', '13:24:15', NULL),
(55, 8, '2025-07-23', 'ruangan1', '13:48:21', '13:49:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_invalids`
--

CREATE TABLE `log_invalids` (
  `id` bigint UNSIGNED NOT NULL,
  `uid_rfid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `waktu` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `log_invalids`
--

INSERT INTO `log_invalids` (`id`, `uid_rfid`, `ruangan`, `waktu`, `created_at`, `updated_at`) VALUES
(1, 'FAREN1234', 'ruangan1', '2025-07-18 03:08:24', '2025-07-18 03:08:24', '2025-07-18 03:08:24'),
(2, 'EVAN123', 'ruangan2', '2025-07-18 03:13:58', '2025-07-18 03:13:58', '2025-07-18 03:13:58'),
(3, 'FAREN1234', 'ruangan1', '2025-07-18 05:46:48', '2025-07-18 05:46:48', '2025-07-18 05:46:48'),
(4, 'FAREN123', 'ruangan1', '2025-07-18 05:47:03', '2025-07-18 05:47:03', '2025-07-18 05:47:03'),
(5, 'EVAN123', 'ruangan2', '2025-07-18 05:49:14', '2025-07-18 05:49:14', '2025-07-18 05:49:14'),
(6, 'CFD9F167', 'ruangan1', '2025-07-22 18:07:21', '2025-07-22 18:07:21', '2025-07-22 18:07:21'),
(7, 'CFD9F167', 'ruangan1', '2025-07-22 18:09:34', '2025-07-22 18:09:34', '2025-07-22 18:09:34'),
(8, 'CFD9F167', 'ruangan1', '2025-07-22 18:10:11', '2025-07-22 18:10:11', '2025-07-22 18:10:11'),
(9, 'SUATAN12', 'ruangan2', '2025-07-23 06:46:06', '2025-07-23 06:46:06', '2025-07-23 06:46:06'),
(10, '058D504D4BD100', 'ruangan1', '2025-07-23 06:46:28', '2025-07-23 06:46:28', '2025-07-23 06:46:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswas`
--

CREATE TABLE `mahasiswas` (
  `id` bigint UNSIGNED NOT NULL,
  `nim` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('laki-laki','perempuan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelas` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uid_rfid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_uid` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `mahasiswas`
--

INSERT INTO `mahasiswas` (`id`, `nim`, `nama`, `jenis_kelamin`, `kelas`, `uid_rfid`, `status_uid`) VALUES
(1, '21024027', 'Faren Richard Nento', 'laki-laki', 'TI-1', 'EFFC871E', 1),
(2, '21024025', 'Evangelis Wagania', 'laki-laki', 'TI-1', 'A7DD7E00', 1),
(3, '21024023', 'Yehezkiel Wajongkere', 'laki-laki', 'TI-1', '51567C00', 1),
(4, '21024011', 'Cristin Natalia Pasaribu', 'perempuan', 'TI-1', 'EF617F1E', 1),
(5, '21024024', 'Aprilia Vanesa Matondong', 'laki-laki', 'TI-1', 'CFD9F167', 1),
(8, '21024010', 'Syalom Tasunaung', 'laki-laki', 'TI-1', '058D504D4BD100', 1),
(9, '21024026', 'Faisal Lausu', 'laki-laki', 'TI-1', 'FAISAL12', 1),
(10, '21024005', 'Vicky Mantiri', 'laki-laki', 'TI-1', 'VICKY12', 1),
(11, '21024019', 'Rian Pratama', 'laki-laki', 'TI-1', 'RIAN12', 1),
(12, '21024020', 'Zefanya Tumboimbela', 'laki-laki', 'TI-1', 'ZEFANYA12', 1),
(13, '21024009', 'Erika Rantung', 'laki-laki', 'TI-1', 'ERIKA12', 1),
(14, '21024001', 'Dyto Hamel', 'laki-laki', 'TI-1', 'DYTO12', 1),
(15, '21024013', 'Richmond Longdong', 'laki-laki', 'TI-1', '11111111', 1),
(16, '21024008', 'Febyanti Sanggelorang', 'perempuan', 'TI-1', '11111111', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_05_05_075657_create_mahasiswas_table', 1),
(5, '2025_05_07_080338_create_log_aktivitas_table', 1),
(6, '2025_05_21_134817_add_user_id_column_in_mahasiswas_table', 2),
(7, '2025_05_25_132628_add_ruangan_to_log_aktivitas_table', 3),
(8, '2025_07_17_135646_create_esp_devices_table', 4),
(9, '2025_07_18_100133_create_log_invalids_table', 5),
(10, '2025_07_18_101537_remove_reader_from_log_invalids_table', 6),
(11, '2025_07_21_123550_add_status_uid_to_mahasiswas_table', 7),
(12, '2025_07_22_011943_remove_user_id_from_mahasiswas_table', 8);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '2025-05-17 00:55:49', '2025-05-17 00:55:49'),
(2, 'User', '2025-05-17 00:55:49', '2025-05-17 00:55:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('25Niwtq6jTj3iSAFGolAEU5xmWN2egLkHh8Bk4kM', NULL, '10.54.65.73', 'ESP32HTTPClient', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoid1JvbTg3RHB3UEg1UFIyTVdZd0xRMERPaFhZZkExMHJvMURRTlNBWiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1753248247),
('30XtYXqrck87fgOq8o4mfAW36EgELFOgQBuS7Obj', NULL, '10.54.65.122', 'PostmanRuntime/7.44.1', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiTU5Hczh1TXlhUWd1VndUN1RNU3N6ckt3aHdNd0loRGRTZVBONVhVTyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1753249566),
('3KmhSm0ZcYj7VICrsKiPFfNf0guWFKxu7HRWJCcc', NULL, '10.54.65.73', 'ESP32HTTPClient', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiTjhKMElFQWFhZzFObGNqaXBtUVJ3cnhXNG95cTM4WnYwTjBwc0VKRiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1753249644),
('5QPihX4zO0ThFiWNkUP3p7zXctN0G2Jy13QkkipM', NULL, '10.54.65.73', 'ESP32HTTPClient', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiYmQ0NGJWUEVJckVWdmswcThobHFOMFh6Mmk5dWtYV0xnMnlZQ003bCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1753248191),
('8Om7dKG0sZhLB1v5zv8mdDanJKL0AV8ajSvPKhxC', NULL, '10.54.65.73', 'ESP32HTTPClient', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiN0dXVUxhaGY2VWkycjVFOXB2U0xaVUg4TnF1cUlZNkpFNU10VUtNRiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1753248864),
('CkCNxOQMbAiaPebIlrYcZeLrXc3VjxgKa58mE2vP', NULL, '10.54.65.73', 'ESP32HTTPClient', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiWmZXOG1BWks2NEhmWmpOd05yMDNUTzcyVGxDRmEyS1dYOEJ5UzNvbiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1753248338),
('eJJtu4EkxiwAs4gjwNrZgq92o4ayQZ2bViilovKl', NULL, '10.54.65.73', 'ESP32HTTPClient', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiakF1ZWM4dXdqZGN2MmlIMWNWNlhITTlqcXgzbW8xVmliM01GWWQ3dSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1753249121),
('fK94IkutBnNBxxIJ0nJMRlOulhwDoQWqmBCb7D0i', NULL, '10.54.65.73', 'ESP32HTTPClient', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiRndrUldQZGdTU3RJanFmZ3pmN1dMaHAwaVhCWFZlZVpLVmlYdWJ1UCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1753248255),
('GmxpCsKGwSaa0W8og4urjnNbjJEhUKvASHzHQTdq', NULL, '10.54.65.73', 'ESP32HTTPClient', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiVTN2Q0NuUkJDUmlOV3ZyeTE1RmRFZHQ2NWhSRFdXMWpZZG92MzhMWSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1753247465),
('gTHWbprqhdtGMMwnB4glxC3BkJMXq22xEoqn0yob', NULL, '10.54.65.73', 'ESP32HTTPClient', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoid2hFaDVpWkVCd0xLaUR6UFJRZ0ZmcTFmNEFSYmxmZlprT3NPZE82QSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1753247443),
('hqATjpRJf4cPDVT1MKnYlTXy25ZTjBdgXGv3uKBr', NULL, '10.54.65.73', 'ESP32HTTPClient', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiQnBmb0U4M3JDbzNNR0dWWFVsRWxDQlJZMnBDZVNGZFBOQlZQbjlZVSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1753249742),
('jaXfUAslYnEz1dezXDUEvHvkVePbwZswJsbCo3gE', NULL, '10.54.65.73', 'ESP32HTTPClient', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiNkd0QnZPR0ZLUnRRVjZtQ2VQRVFQdVpwa21nMVFHRlUwZDNzVWRoZSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1753248184),
('OxYhFh8CRtNOtUl8QrVgaZHCyu8FcXKWDDqh4pFl', NULL, '10.54.65.73', 'ESP32HTTPClient', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiZTJORGJCY05BT1RONThxZ1VyZjFwVVF4SktwalcwMHVPdUxOVXg1ZCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1753248209),
('QMtQi4pMq4C5XBBmMH6cAI8rea0LNny64vCv1ngz', NULL, '10.54.65.73', 'ESP32HTTPClient', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiVkJ4VndvNE1BV0JvTDBUTHlWYlo5R29neGx3Snd1R3JCTjd4MUxxUiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1753249701),
('sGdcIcaEcrh4ylJ9xY1NrsBTvzwMGaZpABkN8yLi', NULL, '10.54.65.73', 'ESP32HTTPClient', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoid1ROMnpld1laTWd4a1E4bHltTW5IWjR2NHczTDRQYTdSekNyckQ1ayI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1753247437),
('SmrDVdLcprf8yuORaLaVt2oIY4rQB6Chf7V3fQWb', NULL, '10.54.65.73', 'ESP32HTTPClient', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoieFNuVDZ1N2xnb3hlckJUOEZxT0tEZGF3NUJlRlBOZjh0cXNRNE80SyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1753248129),
('TMmrcOW5aB40dBl8HjZnoWanMvxtTnw0oMVtctGT', NULL, '10.54.65.73', 'ESP32HTTPClient', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiWERPNDE0OFlaeTRnUWprS3pObEFqaGVHQ3Vxb2hDVG9QTnZ6cjZZSCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1753249588),
('uW8OU3jIjGHLOVdGYXAV5sFYPzb6ijKJj2UwqeUl', NULL, '10.54.65.73', 'ESP32HTTPClient', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiTE5OTkR6dmJCMFpudVZ6RHdlVzN3YzliTm0wZml1Sm1NZ0tBZ2xuaCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1753248959),
('VoTPPBz6tLTYrEDlyYFM1eBT6O9QyLgYSQVNwxlW', 1, '10.54.65.122', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQW94cGY5MkpIT0tVYUcybWI0eDB6N0YzZFptbjNpUWdRV0cweFNLMCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTI6Imh0dHA6Ly8xMC41NC42NS4xMjI6ODAwMC9kYXNoYm9hcmQvY2hhcnQtZGF0YT9kYXlzPTciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1753249808),
('vVQUVYPF5qDvGtIol7z3JIiCarr04dGXGR5HB6EN', NULL, '10.54.65.73', 'ESP32HTTPClient', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiSms4UHdVNGY0N1hQdlVuVEFyakZsMWprdlZYNFp4amVSWm5vZnNVQiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1753248322),
('yqbUBJU58xtgpZu7eaPvUdaXzeZ9iRMa9MkAcTrW', NULL, '10.54.65.73', 'ESP32HTTPClient', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiSnNldk0yRGY3Tml2eEc5Sm1hdk5ub2V3VzY0VUZsSGh1QXRmM284YSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1753247509);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('submitted','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'submitted',
  `role_id` bigint UNSIGNED NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `status`, `role_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Teknik Informatika', 'teknikinformatika@polimdo.ac.id', NULL, '$2y$12$TzdmkpAR1lWHdHQAJ9NoB.ifHxgNtCmtNEjbm4ufy9yCeIOv6PfKi', 'approved', 1, NULL, '2025-05-17 00:55:51', '2025-07-15 10:02:17'),
(2, 'Faren Richard Nento', 'faren12@elektro.polimdo.ac.id', NULL, '$2y$12$/E4ZEgastnxii0wRRUx1NujDi0a3Lmf9BoO2QxqsisMF9CReDXrE.', 'approved', 2, NULL, '2025-05-18 21:59:26', '2025-05-23 20:39:08'),
(3, 'Testing', 'testing@gmail.com', NULL, '$2y$12$VCWJ7qP8VxHh.EzDqH4H9.gt8T77m62aDlOdkcLw2IrcQ3FPdqGzm', 'approved', 2, NULL, '2025-05-19 21:38:25', '2025-06-05 04:24:08'),
(4, 'Cristin tai', 'cristin@gmail.com', NULL, '$2y$12$tGuqLNqqlo5F6DyWhJG5SucRXMsSaWW/8eoNvGgOrjVIRzdYbUBNG', 'approved', 2, NULL, '2025-05-26 09:17:22', '2025-07-21 19:44:40'),
(5, 'Aprilia Vanesa Matondong', 'aprilia123@gmail.com', NULL, '$2y$12$sNJiGBV4ITnywjoYK5rFS.03wbT6l3cTUNN7egUzu0LHR8gDcHetS', 'approved', 2, NULL, '2025-06-05 04:00:31', '2025-06-05 04:01:15'),
(6, 'Harson Kapoh', 'harson@gmail.com', NULL, '$2y$12$oMPjbeMsoIN07xZSDvN4b.gyS1Dk7mQTVPZJGaOmZ7KwcitvEVhpC', 'approved', 2, NULL, '2025-07-21 19:38:11', '2025-07-22 18:13:54');

--
-- Indexes for dumped tables
--

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
-- Indeks untuk tabel `esp_devices`
--
ALTER TABLE `esp_devices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `esp_devices_nama_kelas_unique` (`nama_kelas`);

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
-- Indeks untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `log_aktivitas_mahasiswa_id_foreign` (`mahasiswa_id`);

--
-- Indeks untuk tabel `log_invalids`
--
ALTER TABLE `log_invalids`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `mahasiswas`
--
ALTER TABLE `mahasiswas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `esp_devices`
--
ALTER TABLE `esp_devices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT untuk tabel `log_invalids`
--
ALTER TABLE `log_invalids`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `mahasiswas`
--
ALTER TABLE `mahasiswas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
