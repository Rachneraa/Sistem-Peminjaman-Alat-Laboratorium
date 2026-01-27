-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 27, 2026 at 01:29 AM
-- Server version: 8.0.43
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `peminjaman_alat`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_stok_setelah_peminjaman` (IN `p_borrowing_id` INT)   BEGIN
                DECLARE done INT DEFAULT FALSE;
                DECLARE v_tool_id INT;
                DECLARE v_jumlah INT;
                
                DECLARE cur CURSOR FOR 
                    SELECT tool_id, jumlah 
                    FROM borrowing_details 
                    WHERE borrowing_id = p_borrowing_id;
                    
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
                
                START TRANSACTION;
                
                OPEN cur;
                
                read_loop: LOOP
                    FETCH cur INTO v_tool_id, v_jumlah;
                    IF done THEN
                        LEAVE read_loop;
                    END IF;
                    
                    UPDATE tools 
                    SET stok = stok - v_jumlah 
                    WHERE id = v_tool_id;
                    
                END LOOP;
                
                CLOSE cur;
                
                COMMIT;
            END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_stok_setelah_pengembalian` (IN `p_borrowing_id` INT)   BEGIN
                DECLARE done INT DEFAULT FALSE;
                DECLARE v_tool_id INT;
                DECLARE v_jumlah INT;
                
                DECLARE cur CURSOR FOR 
                    SELECT tool_id, jumlah 
                    FROM borrowing_details 
                    WHERE borrowing_id = p_borrowing_id;
                    
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
                
                START TRANSACTION;
                
                OPEN cur;
                
                read_loop: LOOP
                    FETCH cur INTO v_tool_id, v_jumlah;
                    IF done THEN
                        LEAVE read_loop;
                    END IF;
                    
                    UPDATE tools 
                    SET stok = stok + v_jumlah 
                    WHERE id = v_tool_id;
                    
                END LOOP;
                
                CLOSE cur;
                
                COMMIT;
            END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `hitung_denda` (`tanggal_pinjam` DATE, `tanggal_kembali` DATE, `batas_hari` INT) RETURNS DECIMAL(10,2) DETERMINISTIC BEGIN
                DECLARE hari_terlambat INT;
                DECLARE denda DECIMAL(10,2);
                DECLARE denda_per_hari DECIMAL(10,2) DEFAULT 5000;
                
                SET hari_terlambat = DATEDIFF(tanggal_kembali, DATE_ADD(tanggal_pinjam, INTERVAL batas_hari DAY));
                
                IF hari_terlambat > 0 THEN
                    SET denda = hari_terlambat * denda_per_hari;
                ELSE
                    SET denda = 0;
                END IF;
                
                RETURN denda;
            END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `aktivitas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_id` bigint UNSIGNED DEFAULT NULL,
  `detail` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `aktivitas`, `model_type`, `model_id`, `detail`, `created_at`, `updated_at`) VALUES
(1, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-09 00:13:26', '2026-01-09 00:13:26'),
(2, 1, 'Menambah alat: Solder', 'App\\Models\\Tool', 1, NULL, '2026-01-09 00:23:58', '2026-01-09 00:23:58'),
(3, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-09 00:24:17', '2026-01-09 00:24:17'),
(4, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-09 00:24:21', '2026-01-09 00:24:21'),
(5, 3, 'Mengajukan peminjaman #1', 'App\\Models\\Borrowing', 1, NULL, '2026-01-09 07:25:07', '2026-01-09 07:25:07'),
(6, 3, 'Mengajukan peminjaman baru', 'App\\Models\\Borrowing', 1, NULL, '2026-01-09 00:25:07', '2026-01-09 00:25:07'),
(7, 3, 'Mengajukan peminjaman #2', 'App\\Models\\Borrowing', 2, NULL, '2026-01-09 07:25:07', '2026-01-09 07:25:07'),
(8, 3, 'Mengajukan peminjaman baru', 'App\\Models\\Borrowing', 2, NULL, '2026-01-09 00:25:07', '2026-01-09 00:25:07'),
(9, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-09 00:25:16', '2026-01-09 00:25:16'),
(10, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-09 00:25:21', '2026-01-09 00:25:21'),
(11, 3, 'Status peminjaman #1 diubah menjadi ditolak', 'App\\Models\\Borrowing', 1, NULL, '2026-01-09 07:25:31', '2026-01-09 07:25:31'),
(12, 2, 'Menolak peminjaman ID: 1', 'App\\Models\\Borrowing', 1, NULL, '2026-01-09 00:25:31', '2026-01-09 00:25:31'),
(13, 3, 'Status peminjaman #2 diubah menjadi disetujui', 'App\\Models\\Borrowing', 2, NULL, '2026-01-09 07:25:34', '2026-01-09 07:25:34'),
(14, 2, 'Menyetujui peminjaman ID: 2', 'App\\Models\\Borrowing', 2, NULL, '2026-01-09 00:25:34', '2026-01-09 00:25:34'),
(15, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-09 00:25:54', '2026-01-09 00:25:54'),
(16, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-09 00:25:58', '2026-01-09 00:25:58'),
(17, 3, 'Status peminjaman #2 diubah menjadi dikembalikan', 'App\\Models\\Borrowing', 2, NULL, '2026-01-09 07:26:25', '2026-01-09 07:26:25'),
(18, 3, 'Mengembalikan peminjaman ID: 2', 'App\\Models\\ReturnModel', 1, NULL, '2026-01-09 00:26:25', '2026-01-09 00:26:25'),
(19, 3, 'Mengajukan peminjaman #3', 'App\\Models\\Borrowing', 3, NULL, '2026-01-09 07:37:20', '2026-01-09 07:37:20'),
(20, 3, 'Mengajukan peminjaman baru', 'App\\Models\\Borrowing', 3, NULL, '2026-01-09 00:37:20', '2026-01-09 00:37:20'),
(21, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-09 00:37:23', '2026-01-09 00:37:23'),
(22, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-09 00:37:28', '2026-01-09 00:37:28'),
(23, 3, 'Status peminjaman #3 diubah menjadi disetujui', 'App\\Models\\Borrowing', 3, NULL, '2026-01-09 07:37:34', '2026-01-09 07:37:34'),
(24, 2, 'Menyetujui peminjaman ID: 3', 'App\\Models\\Borrowing', 3, NULL, '2026-01-09 00:37:34', '2026-01-09 00:37:34'),
(25, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-09 00:37:45', '2026-01-09 00:37:45'),
(26, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-09 00:37:50', '2026-01-09 00:37:50'),
(27, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-09 00:37:58', '2026-01-09 00:37:58'),
(28, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-09 00:38:03', '2026-01-09 00:38:03'),
(29, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-09 00:39:48', '2026-01-09 00:39:48'),
(30, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-09 00:39:54', '2026-01-09 00:39:54'),
(31, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-09 00:42:05', '2026-01-09 00:42:05'),
(32, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-09 00:42:09', '2026-01-09 00:42:09'),
(33, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-09 00:42:28', '2026-01-09 00:42:28'),
(34, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-09 00:44:16', '2026-01-09 00:44:16'),
(35, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 07:27:27', '2026-01-10 07:27:27'),
(36, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 07:35:15', '2026-01-10 07:35:15'),
(37, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-10 07:35:21', '2026-01-10 07:35:21'),
(38, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-10 07:38:16', '2026-01-10 07:38:16'),
(39, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 07:38:20', '2026-01-10 07:38:20'),
(40, 3, 'Mengajukan peminjaman #4', 'App\\Models\\Borrowing', 4, NULL, '2026-01-10 14:39:03', '2026-01-10 14:39:03'),
(41, 3, 'Mengajukan peminjaman baru', 'App\\Models\\Borrowing', 4, NULL, '2026-01-10 07:39:03', '2026-01-10 07:39:03'),
(42, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 07:39:07', '2026-01-10 07:39:07'),
(43, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 07:39:12', '2026-01-10 07:39:12'),
(44, 3, 'Status peminjaman #4 diubah menjadi disetujui', 'App\\Models\\Borrowing', 4, NULL, '2026-01-10 14:39:24', '2026-01-10 14:39:24'),
(45, 2, 'Menyetujui peminjaman ID: 4', 'App\\Models\\Borrowing', 4, NULL, '2026-01-10 07:39:24', '2026-01-10 07:39:24'),
(46, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 07:39:31', '2026-01-10 07:39:31'),
(47, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-10 07:39:35', '2026-01-10 07:39:35'),
(48, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-10 07:39:40', '2026-01-10 07:39:40'),
(49, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 07:40:30', '2026-01-10 07:40:30'),
(50, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 07:43:19', '2026-01-10 07:43:19'),
(51, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 07:43:24', '2026-01-10 07:43:24'),
(52, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 07:53:47', '2026-01-10 07:53:47'),
(53, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 07:53:52', '2026-01-10 07:53:52'),
(54, 3, 'Status peminjaman #3 diubah menjadi dikembalikan', 'App\\Models\\Borrowing', 3, NULL, '2026-01-10 14:54:01', '2026-01-10 14:54:01'),
(55, 2, 'Memproses pengembalian peminjaman ID: 3', 'App\\Models\\ReturnModel', 2, NULL, '2026-01-10 07:54:01', '2026-01-10 07:54:01'),
(56, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 07:55:47', '2026-01-10 07:55:47'),
(57, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 07:55:52', '2026-01-10 07:55:52'),
(58, 3, 'Mengajukan peminjaman #5', 'App\\Models\\Borrowing', 5, NULL, '2026-01-10 14:56:19', '2026-01-10 14:56:19'),
(59, 3, 'Mengajukan peminjaman baru', 'App\\Models\\Borrowing', 5, NULL, '2026-01-10 07:56:19', '2026-01-10 07:56:19'),
(60, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 07:56:34', '2026-01-10 07:56:34'),
(61, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 07:56:39', '2026-01-10 07:56:39'),
(62, 3, 'Status peminjaman #5 diubah menjadi disetujui', 'App\\Models\\Borrowing', 5, NULL, '2026-01-10 14:56:47', '2026-01-10 14:56:47'),
(63, 2, 'Menyetujui peminjaman ID: 5', 'App\\Models\\Borrowing', 5, NULL, '2026-01-10 07:56:47', '2026-01-10 07:56:47'),
(64, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 08:48:51', '2026-01-10 08:48:51'),
(65, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 08:48:58', '2026-01-10 08:48:58'),
(66, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 08:49:22', '2026-01-10 08:49:22'),
(67, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 08:49:28', '2026-01-10 08:49:28'),
(68, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 08:49:58', '2026-01-10 08:49:58'),
(69, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 08:50:03', '2026-01-10 08:50:03'),
(70, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 08:50:12', '2026-01-10 08:50:12'),
(71, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 08:50:17', '2026-01-10 08:50:17'),
(72, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 08:50:55', '2026-01-10 08:50:55'),
(73, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 08:51:00', '2026-01-10 08:51:00'),
(74, 2, 'Mengirim pengingat pengembalian ke peminjam ID: 3 untuk peminjaman #5', 'App\\Models\\Borrowing', 5, NULL, '2026-01-10 08:53:11', '2026-01-10 08:53:11'),
(75, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 08:53:14', '2026-01-10 08:53:14'),
(76, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 08:53:19', '2026-01-10 08:53:19'),
(77, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 09:21:40', '2026-01-10 09:21:40'),
(78, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 09:21:46', '2026-01-10 09:21:46'),
(79, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 09:21:58', '2026-01-10 09:21:58'),
(80, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-10 09:22:03', '2026-01-10 09:22:03'),
(81, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-10 09:22:07', '2026-01-10 09:22:07'),
(82, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 09:22:11', '2026-01-10 09:22:11'),
(83, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 09:32:17', '2026-01-10 09:32:17'),
(84, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-10 09:32:23', '2026-01-10 09:32:23'),
(85, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-10 09:33:37', '2026-01-10 09:33:37'),
(86, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-10 09:35:29', '2026-01-10 09:35:29'),
(87, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-10 09:35:47', '2026-01-10 09:35:47'),
(88, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 09:35:52', '2026-01-10 09:35:52'),
(89, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 09:53:50', '2026-01-10 09:53:50'),
(90, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 09:53:58', '2026-01-10 09:53:58'),
(91, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 09:54:30', '2026-01-10 09:54:30'),
(92, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 09:54:34', '2026-01-10 09:54:34'),
(93, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 10:01:24', '2026-01-10 10:01:24'),
(94, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 10:01:28', '2026-01-10 10:01:28'),
(95, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 10:01:31', '2026-01-10 10:01:31'),
(96, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-10 10:01:34', '2026-01-10 10:01:34'),
(97, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-10 10:02:11', '2026-01-10 10:02:11'),
(98, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 10:02:15', '2026-01-10 10:02:15'),
(99, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 10:02:19', '2026-01-10 10:02:19'),
(100, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 10:02:23', '2026-01-10 10:02:23'),
(101, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 10:02:43', '2026-01-10 10:02:43'),
(102, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 10:02:47', '2026-01-10 10:02:47'),
(105, 3, 'Mengajukan peminjaman #7', 'App\\Models\\Borrowing', 7, NULL, '2026-01-10 17:09:23', '2026-01-10 17:09:23'),
(106, 3, 'Mengajukan peminjaman baru', 'App\\Models\\Borrowing', 7, NULL, '2026-01-10 10:09:23', '2026-01-10 10:09:23'),
(107, 3, 'Mengajukan peminjaman #8', 'App\\Models\\Borrowing', 8, NULL, '2026-01-10 17:09:43', '2026-01-10 17:09:43'),
(108, 3, 'Mengajukan peminjaman baru', 'App\\Models\\Borrowing', 8, NULL, '2026-01-10 10:09:43', '2026-01-10 10:09:43'),
(109, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 10:09:49', '2026-01-10 10:09:49'),
(110, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 10:09:54', '2026-01-10 10:09:54'),
(111, 3, 'Status peminjaman #8 diubah menjadi disetujui', 'App\\Models\\Borrowing', 8, NULL, '2026-01-10 17:10:06', '2026-01-10 17:10:06'),
(112, 2, 'Menyetujui peminjaman ID: 8', 'App\\Models\\Borrowing', 8, NULL, '2026-01-10 10:10:06', '2026-01-10 10:10:06'),
(113, 3, 'Status peminjaman #7 diubah menjadi disetujui', 'App\\Models\\Borrowing', 7, NULL, '2026-01-10 17:10:14', '2026-01-10 17:10:14'),
(114, 2, 'Menyetujui peminjaman ID: 7', 'App\\Models\\Borrowing', 7, NULL, '2026-01-10 10:10:14', '2026-01-10 10:10:14'),
(115, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 10:11:06', '2026-01-10 10:11:06'),
(116, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-10 10:11:10', '2026-01-10 10:11:10'),
(117, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-10 10:21:19', '2026-01-10 10:21:19'),
(118, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 10:21:22', '2026-01-10 10:21:22'),
(119, 3, 'Status peminjaman #5 diubah menjadi dikembalikan', 'App\\Models\\Borrowing', 5, NULL, '2026-01-10 17:24:10', '2026-01-10 17:24:10'),
(120, 3, 'Mengembalikan peminjaman ID: 5', 'App\\Models\\ReturnModel', 3, NULL, '2026-01-10 10:24:10', '2026-01-10 10:24:10'),
(121, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 10:24:24', '2026-01-10 10:24:24'),
(122, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 10:24:30', '2026-01-10 10:24:30'),
(123, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 10:24:42', '2026-01-10 10:24:42'),
(124, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-10 10:24:47', '2026-01-10 10:24:47'),
(125, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-10 16:26:48', '2026-01-10 16:26:48'),
(126, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-10 16:27:52', '2026-01-10 16:27:52'),
(127, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 16:27:57', '2026-01-10 16:27:57'),
(128, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 16:28:24', '2026-01-10 16:28:24'),
(129, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 16:28:28', '2026-01-10 16:28:28'),
(130, 2, 'Mengirim pengingat pengembalian ke peminjam ID: 3 untuk peminjaman #4', 'App\\Models\\Borrowing', 4, NULL, '2026-01-10 16:37:32', '2026-01-10 16:37:32'),
(131, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 16:38:19', '2026-01-10 16:38:19'),
(132, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 16:38:24', '2026-01-10 16:38:24'),
(133, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 16:49:47', '2026-01-10 16:49:47'),
(134, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 16:49:52', '2026-01-10 16:49:52'),
(135, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 17:00:22', '2026-01-10 17:00:22'),
(136, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 17:00:27', '2026-01-10 17:00:27'),
(137, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 17:00:39', '2026-01-10 17:00:39'),
(138, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 17:00:47', '2026-01-10 17:00:47'),
(139, 2, 'Mengirim notifikasi estimasi denda ke peminjam ID: 3 untuk peminjaman #4 (Denda: Rp 5.000)', 'App\\Models\\Borrowing', 4, NULL, '2026-01-10 17:13:23', '2026-01-10 17:13:23'),
(140, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 17:13:27', '2026-01-10 17:13:27'),
(141, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 17:13:33', '2026-01-10 17:13:33'),
(142, 3, 'Status peminjaman #7 diubah menjadi dikembalikan', 'App\\Models\\Borrowing', 7, NULL, '2026-01-11 00:14:30', '2026-01-11 00:14:30'),
(143, 3, 'Mengembalikan peminjaman ID: 7', 'App\\Models\\ReturnModel', 4, NULL, '2026-01-10 17:14:30', '2026-01-10 17:14:30'),
(144, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 17:14:37', '2026-01-10 17:14:37'),
(145, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 17:14:42', '2026-01-10 17:14:42'),
(146, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 17:15:04', '2026-01-10 17:15:04'),
(147, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-10 17:15:08', '2026-01-10 17:15:08'),
(148, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-10 17:15:56', '2026-01-10 17:15:56'),
(149, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 17:16:00', '2026-01-10 17:16:00'),
(150, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 17:16:09', '2026-01-10 17:16:09'),
(151, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-10 17:16:16', '2026-01-10 17:16:16'),
(152, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-10 17:27:21', '2026-01-10 17:27:21'),
(153, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-10 17:27:25', '2026-01-10 17:27:25'),
(154, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-10 17:28:42', '2026-01-10 17:28:42'),
(155, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 17:28:46', '2026-01-10 17:28:46'),
(156, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-10 18:51:37', '2026-01-10 18:51:37'),
(157, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 18:51:42', '2026-01-10 18:51:42'),
(158, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-10 20:04:32', '2026-01-10 20:04:32'),
(159, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-11 05:15:56', '2026-01-11 05:15:56'),
(160, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-11 05:16:49', '2026-01-11 05:16:49'),
(161, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-11 05:16:53', '2026-01-11 05:16:53'),
(162, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-11 05:17:25', '2026-01-11 05:17:25'),
(163, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-11 19:23:31', '2026-01-11 19:23:31'),
(164, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-11 19:31:06', '2026-01-11 19:31:06'),
(165, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-11 19:31:10', '2026-01-11 19:31:10'),
(166, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-11 19:32:03', '2026-01-11 19:32:03'),
(167, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-11 19:32:08', '2026-01-11 19:32:08'),
(168, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-11 19:43:33', '2026-01-11 19:43:33'),
(169, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-11 19:43:38', '2026-01-11 19:43:38'),
(170, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-11 20:19:08', '2026-01-11 20:19:08'),
(171, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-11 20:20:16', '2026-01-11 20:20:16'),
(172, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-11 20:20:27', '2026-01-11 20:20:27'),
(173, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-11 20:20:31', '2026-01-11 20:20:31'),
(174, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-11 20:21:29', '2026-01-11 20:21:29'),
(175, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-11 20:21:43', '2026-01-11 20:21:43'),
(176, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-11 20:22:03', '2026-01-11 20:22:03'),
(177, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-11 21:09:59', '2026-01-11 21:09:59'),
(178, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-11 21:10:14', '2026-01-11 21:10:14'),
(179, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-11 21:10:34', '2026-01-11 21:10:34'),
(180, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-11 21:10:46', '2026-01-11 21:10:46'),
(181, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-11 21:10:52', '2026-01-11 21:10:52'),
(182, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-11 22:20:50', '2026-01-11 22:20:50'),
(183, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-11 22:20:55', '2026-01-11 22:20:55'),
(184, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-11 23:34:23', '2026-01-11 23:34:23'),
(185, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-11 23:34:59', '2026-01-11 23:34:59'),
(186, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-11 23:44:26', '2026-01-11 23:44:26'),
(187, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-11 23:46:01', '2026-01-11 23:46:01'),
(188, 1, 'Menambah alat: Monitor', 'App\\Models\\Tool', 2, NULL, '2026-01-11 23:49:50', '2026-01-11 23:49:50'),
(189, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-11 23:50:00', '2026-01-11 23:50:00'),
(190, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-11 23:50:04', '2026-01-11 23:50:04'),
(191, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-11 23:50:36', '2026-01-11 23:50:36'),
(192, 5, 'Registrasi akun baru', 'App\\Models\\User', 5, NULL, '2026-01-12 00:04:44', '2026-01-12 00:04:44'),
(193, 5, 'Logout dari sistem', 'App\\Models\\User', 5, NULL, '2026-01-12 00:04:55', '2026-01-12 00:04:55'),
(194, 5, 'Login ke sistem', 'App\\Models\\User', 5, NULL, '2026-01-12 00:19:41', '2026-01-12 00:19:41'),
(195, 5, 'Logout dari sistem', 'App\\Models\\User', 5, NULL, '2026-01-12 00:19:47', '2026-01-12 00:19:47'),
(196, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-12 05:18:35', '2026-01-12 05:18:35'),
(197, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-12 05:18:56', '2026-01-12 05:18:56'),
(198, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-12 05:19:05', '2026-01-12 05:19:05'),
(199, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-12 06:07:44', '2026-01-12 06:07:44'),
(200, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-12 06:08:12', '2026-01-12 06:08:12'),
(201, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-12 18:02:51', '2026-01-12 18:02:51'),
(202, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-12 18:04:53', '2026-01-12 18:04:53'),
(203, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-12 18:04:59', '2026-01-12 18:04:59'),
(204, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-12 18:36:56', '2026-01-12 18:36:56'),
(205, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-12 18:37:09', '2026-01-12 18:37:09'),
(206, 2, 'Mengirim notifikasi estimasi denda ke peminjam ID: 3 untuk peminjaman #8 (Denda: Rp 10.000)', 'App\\Models\\Borrowing', 8, NULL, '2026-01-12 18:37:40', '2026-01-12 18:37:40'),
(207, 2, 'Mengirim notifikasi estimasi denda ke peminjam ID: 3 untuk peminjaman #8 (Denda: Rp 10.000)', 'App\\Models\\Borrowing', 8, NULL, '2026-01-12 18:37:42', '2026-01-12 18:37:42'),
(208, 2, 'Mengirim notifikasi estimasi denda ke peminjam ID: 3 untuk peminjaman #8 (Denda: Rp 10.000)', 'App\\Models\\Borrowing', 8, NULL, '2026-01-12 18:37:43', '2026-01-12 18:37:43'),
(209, 2, 'Mengirim notifikasi estimasi denda ke peminjam ID: 3 untuk peminjaman #8 (Denda: Rp 10.000)', 'App\\Models\\Borrowing', 8, NULL, '2026-01-12 18:37:58', '2026-01-12 18:37:58'),
(210, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-12 18:38:08', '2026-01-12 18:38:08'),
(211, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-12 18:38:14', '2026-01-12 18:38:14'),
(212, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-12 18:39:31', '2026-01-12 18:39:31'),
(213, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-12 18:39:45', '2026-01-12 18:39:45'),
(214, 1, 'Menambah user baru: Dedi', 'App\\Models\\User', 6, NULL, '2026-01-12 18:40:34', '2026-01-12 18:40:34'),
(215, 1, 'Menambah alat: Mouse', 'App\\Models\\Tool', 3, NULL, '2026-01-12 18:41:17', '2026-01-12 18:41:17'),
(216, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-12 18:41:22', '2026-01-12 18:41:22'),
(217, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-12 18:41:33', '2026-01-12 18:41:33'),
(218, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-13 00:11:40', '2026-01-13 00:11:40'),
(219, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-13 00:12:00', '2026-01-13 00:12:00'),
(220, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-13 09:20:56', '2026-01-13 09:20:56'),
(221, 1, 'Mengupdate alat: Solder', 'App\\Models\\Tool', 1, NULL, '2026-01-13 09:21:31', '2026-01-13 09:21:31'),
(222, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-13 09:22:01', '2026-01-13 09:22:01'),
(223, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-13 09:22:25', '2026-01-13 09:22:25'),
(224, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-13 09:23:44', '2026-01-13 09:23:44'),
(225, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-13 09:24:03', '2026-01-13 09:24:03'),
(226, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-13 17:36:42', '2026-01-13 17:36:42'),
(227, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-13 18:03:36', '2026-01-13 18:03:36'),
(228, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-13 18:03:59', '2026-01-13 18:03:59'),
(229, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-13 18:04:29', '2026-01-13 18:04:29'),
(230, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-13 18:04:34', '2026-01-13 18:04:34'),
(231, 3, 'Mengajukan peminjaman #9', 'App\\Models\\Borrowing', 9, NULL, '2026-01-14 01:05:08', '2026-01-14 01:05:08'),
(232, 3, 'Mengajukan peminjaman baru', 'App\\Models\\Borrowing', 9, NULL, '2026-01-13 18:05:08', '2026-01-13 18:05:08'),
(233, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-13 18:05:16', '2026-01-13 18:05:16'),
(234, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-13 18:05:23', '2026-01-13 18:05:23'),
(235, 3, 'Status peminjaman #9 diubah menjadi disetujui', 'App\\Models\\Borrowing', 9, NULL, '2026-01-14 01:05:41', '2026-01-14 01:05:41'),
(236, 2, 'Menyetujui peminjaman ID: 9', 'App\\Models\\Borrowing', 9, NULL, '2026-01-13 18:05:41', '2026-01-13 18:05:41'),
(237, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-13 18:06:10', '2026-01-13 18:06:10'),
(238, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-13 18:07:32', '2026-01-13 18:07:32'),
(239, 1, 'Menambah user baru: Rendi', 'App\\Models\\User', 7, NULL, '2026-01-13 18:08:19', '2026-01-13 18:08:19'),
(240, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-13 18:09:08', '2026-01-13 18:09:08'),
(241, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-13 18:09:17', '2026-01-13 18:09:17'),
(242, 3, 'Mengajukan peminjaman #10', 'App\\Models\\Borrowing', 10, NULL, '2026-01-14 01:10:15', '2026-01-14 01:10:15'),
(243, 3, 'Mengajukan peminjaman baru', 'App\\Models\\Borrowing', 10, NULL, '2026-01-13 18:10:16', '2026-01-13 18:10:16'),
(244, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-13 18:10:28', '2026-01-13 18:10:28'),
(245, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-13 18:14:48', '2026-01-13 18:14:48'),
(246, 3, 'Status peminjaman #10 diubah menjadi disetujui', 'App\\Models\\Borrowing', 10, NULL, '2026-01-14 01:14:58', '2026-01-14 01:14:58'),
(247, 2, 'Menyetujui peminjaman ID: 10', 'App\\Models\\Borrowing', 10, NULL, '2026-01-13 18:14:58', '2026-01-13 18:14:58'),
(248, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-13 18:18:12', '2026-01-13 18:18:12'),
(249, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-13 18:22:17', '2026-01-13 18:22:17'),
(250, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-13 18:22:27', '2026-01-13 18:22:27'),
(251, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-13 18:29:22', '2026-01-13 18:29:22'),
(252, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-13 18:31:42', '2026-01-13 18:31:42'),
(253, 3, 'Mengajukan peminjaman #11', 'App\\Models\\Borrowing', 11, NULL, '2026-01-14 01:32:23', '2026-01-14 01:32:23'),
(254, 3, 'Mengajukan peminjaman baru', 'App\\Models\\Borrowing', 11, NULL, '2026-01-13 18:32:23', '2026-01-13 18:32:23'),
(255, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-13 18:32:30', '2026-01-13 18:32:30'),
(256, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-13 18:32:36', '2026-01-13 18:32:36'),
(257, 3, 'Status peminjaman #11 diubah menjadi disetujui', 'App\\Models\\Borrowing', 11, NULL, '2026-01-14 01:32:41', '2026-01-14 01:32:41'),
(258, 2, 'Menyetujui peminjaman ID: 11', 'App\\Models\\Borrowing', 11, NULL, '2026-01-13 18:32:41', '2026-01-13 18:32:41'),
(259, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-13 18:32:46', '2026-01-13 18:32:46'),
(260, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-13 18:32:54', '2026-01-13 18:32:54'),
(261, 1, 'Mengupdate user: Admin', 'App\\Models\\User', 1, NULL, '2026-01-13 18:33:15', '2026-01-13 18:33:15'),
(262, 1, 'Mengupdate user: Petugas', 'App\\Models\\User', 2, NULL, '2026-01-13 18:33:30', '2026-01-13 18:33:30'),
(263, 1, 'Mengupdate user: Peminjam', 'App\\Models\\User', 3, NULL, '2026-01-13 18:33:43', '2026-01-13 18:33:43'),
(264, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-13 18:33:50', '2026-01-13 18:33:50'),
(265, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-13 18:34:03', '2026-01-13 18:34:03'),
(266, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-13 18:34:08', '2026-01-13 18:34:08'),
(267, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-13 18:37:32', '2026-01-13 18:37:32'),
(268, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-13 18:38:58', '2026-01-13 18:38:58'),
(269, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-13 18:39:21', '2026-01-13 18:39:21'),
(270, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-13 18:39:37', '2026-01-13 18:39:37'),
(271, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-13 18:39:51', '2026-01-13 18:39:51'),
(272, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-13 18:51:34', '2026-01-13 18:51:34'),
(273, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-13 18:51:56', '2026-01-13 18:51:56'),
(274, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-13 18:55:44', '2026-01-13 18:55:44'),
(275, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-13 18:55:56', '2026-01-13 18:55:56'),
(276, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-13 20:35:52', '2026-01-13 20:35:52'),
(277, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-13 20:36:01', '2026-01-13 20:36:01'),
(278, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-13 21:00:43', '2026-01-13 21:00:43'),
(279, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-13 21:06:11', '2026-01-13 21:06:11'),
(280, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-13 21:06:25', '2026-01-13 21:06:25'),
(281, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-13 21:06:36', '2026-01-13 21:06:36'),
(282, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-13 21:09:23', '2026-01-13 21:09:23'),
(283, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-13 21:14:48', '2026-01-13 21:14:48'),
(284, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-13 21:14:57', '2026-01-13 21:14:57'),
(285, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-13 21:15:20', '2026-01-13 21:15:20'),
(286, 5, 'Login ke sistem', 'App\\Models\\User', 5, NULL, '2026-01-13 21:15:26', '2026-01-13 21:15:26'),
(287, 5, 'Logout dari sistem', 'App\\Models\\User', 5, NULL, '2026-01-13 21:21:14', '2026-01-13 21:21:14'),
(288, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-13 21:21:21', '2026-01-13 21:21:21'),
(289, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-13 21:45:22', '2026-01-13 21:45:22'),
(290, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-13 21:45:29', '2026-01-13 21:45:29'),
(291, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-13 21:53:45', '2026-01-13 21:53:45'),
(292, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-13 21:53:49', '2026-01-13 21:53:49'),
(293, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-13 21:54:09', '2026-01-13 21:54:09'),
(294, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-13 21:54:13', '2026-01-13 21:54:13'),
(295, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-13 21:54:17', '2026-01-13 21:54:17'),
(296, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-13 21:54:22', '2026-01-13 21:54:22'),
(297, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 14:55:55', '2026-01-14 14:55:55'),
(298, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 15:01:58', '2026-01-14 15:01:58'),
(299, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 15:08:01', '2026-01-14 15:08:01'),
(300, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-14 15:08:15', '2026-01-14 15:08:15'),
(301, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 15:08:43', '2026-01-14 15:08:43'),
(302, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-14 15:08:50', '2026-01-14 15:08:50'),
(303, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-14 15:10:13', '2026-01-14 15:10:13'),
(304, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 15:10:24', '2026-01-14 15:10:24'),
(305, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 15:10:45', '2026-01-14 15:10:45'),
(306, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-14 15:10:57', '2026-01-14 15:10:57'),
(307, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-14 15:14:40', '2026-01-14 15:14:40'),
(308, 6, 'Login ke sistem', 'App\\Models\\User', 6, NULL, '2026-01-14 15:14:49', '2026-01-14 15:14:49'),
(309, 6, 'Logout dari sistem', 'App\\Models\\User', 6, NULL, '2026-01-14 15:14:53', '2026-01-14 15:14:53'),
(310, 5, 'Login ke sistem', 'App\\Models\\User', 5, NULL, '2026-01-14 15:14:59', '2026-01-14 15:14:59'),
(311, 5, 'Logout dari sistem', 'App\\Models\\User', 5, NULL, '2026-01-14 15:36:06', '2026-01-14 15:36:06'),
(312, 5, 'Login ke sistem', 'App\\Models\\User', 5, NULL, '2026-01-14 15:37:16', '2026-01-14 15:37:16'),
(313, 5, 'Logout dari sistem', 'App\\Models\\User', 5, NULL, '2026-01-14 15:37:21', '2026-01-14 15:37:21'),
(314, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-14 15:37:26', '2026-01-14 15:37:26'),
(315, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-14 15:37:30', '2026-01-14 15:37:30'),
(316, 6, 'Login ke sistem', 'App\\Models\\User', 6, NULL, '2026-01-14 15:37:55', '2026-01-14 15:37:55'),
(317, 6, 'Logout dari sistem', 'App\\Models\\User', 6, NULL, '2026-01-14 15:38:00', '2026-01-14 15:38:00'),
(318, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-14 15:38:09', '2026-01-14 15:38:09'),
(319, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-14 15:38:58', '2026-01-14 15:38:58'),
(320, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-14 15:39:09', '2026-01-14 15:39:09'),
(321, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-14 15:42:33', '2026-01-14 15:42:33'),
(322, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 15:42:43', '2026-01-14 15:42:43'),
(323, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 15:42:48', '2026-01-14 15:42:48'),
(324, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-14 15:42:55', '2026-01-14 15:42:55'),
(325, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-14 15:44:38', '2026-01-14 15:44:38'),
(326, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-14 15:44:46', '2026-01-14 15:44:46'),
(327, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-14 15:49:14', '2026-01-14 15:49:14'),
(328, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 15:49:23', '2026-01-14 15:49:23'),
(329, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 15:49:33', '2026-01-14 15:49:33'),
(330, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-14 15:49:42', '2026-01-14 15:49:42'),
(331, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-14 15:50:08', '2026-01-14 15:50:08'),
(332, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-14 15:50:16', '2026-01-14 15:50:16'),
(333, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-14 15:50:30', '2026-01-14 15:50:30'),
(334, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 15:50:37', '2026-01-14 15:50:37'),
(335, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 15:54:49', '2026-01-14 15:54:49'),
(336, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 15:54:56', '2026-01-14 15:54:56'),
(337, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 15:55:08', '2026-01-14 15:55:08'),
(338, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-14 15:55:15', '2026-01-14 15:55:15'),
(339, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-14 15:55:40', '2026-01-14 15:55:40'),
(340, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-14 15:55:49', '2026-01-14 15:55:49'),
(341, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-14 17:46:43', '2026-01-14 17:46:43'),
(342, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 17:46:52', '2026-01-14 17:46:52'),
(343, 3, 'Status peminjaman #8 diubah menjadi dikembalikan', 'App\\Models\\Borrowing', 8, NULL, '2026-01-15 00:47:27', '2026-01-15 00:47:27'),
(344, 3, 'Mengembalikan peminjaman ID: 8', 'App\\Models\\ReturnModel', 5, NULL, '2026-01-14 17:47:27', '2026-01-14 17:47:27'),
(345, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 17:47:34', '2026-01-14 17:47:34'),
(346, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-14 17:47:40', '2026-01-14 17:47:40'),
(347, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-14 17:49:08', '2026-01-14 17:49:08'),
(348, 6, 'Login ke sistem', 'App\\Models\\User', 6, NULL, '2026-01-14 17:50:48', '2026-01-14 17:50:48'),
(349, 6, 'Logout dari sistem', 'App\\Models\\User', 6, NULL, '2026-01-14 18:14:30', '2026-01-14 18:14:30'),
(350, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 18:14:35', '2026-01-14 18:14:35'),
(351, 3, 'Status peminjaman #11 diubah menjadi dikembalikan', 'App\\Models\\Borrowing', 11, NULL, '2026-01-15 01:14:46', '2026-01-15 01:14:46'),
(352, 3, 'Mengembalikan peminjaman ID: 11', 'App\\Models\\ReturnModel', 6, NULL, '2026-01-14 18:14:46', '2026-01-14 18:14:46'),
(353, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 18:14:51', '2026-01-14 18:14:51'),
(354, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-14 18:15:00', '2026-01-14 18:15:00'),
(355, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-14 18:20:17', '2026-01-14 18:20:17'),
(356, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 18:32:51', '2026-01-14 18:32:51'),
(357, 3, 'Status peminjaman #10 diubah menjadi menunggu_pengembalian', 'App\\Models\\Borrowing', 10, NULL, '2026-01-15 01:39:27', '2026-01-15 01:39:27'),
(358, 3, 'Mengajukan pengembalian peminjaman ID: 10', 'App\\Models\\Borrowing', 10, NULL, '2026-01-14 18:39:27', '2026-01-14 18:39:27'),
(359, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 18:39:33', '2026-01-14 18:39:33'),
(360, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-14 18:39:39', '2026-01-14 18:39:39'),
(361, 3, 'Status peminjaman #10 diubah menjadi dikembalikan', 'App\\Models\\Borrowing', 10, NULL, '2026-01-15 01:40:14', '2026-01-15 01:40:14'),
(362, 2, 'Memproses pengembalian peminjaman ID: 10', 'App\\Models\\ReturnModel', 7, NULL, '2026-01-14 18:40:14', '2026-01-14 18:40:14'),
(363, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-14 18:40:32', '2026-01-14 18:40:32'),
(364, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-14 18:47:24', '2026-01-14 18:47:24'),
(365, 1, 'Mengupdate pengembalian ID: 6', 'App\\Models\\ReturnModel', 6, NULL, '2026-01-14 18:58:37', '2026-01-14 18:58:37'),
(366, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-14 19:01:44', '2026-01-14 19:01:44'),
(367, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 19:01:49', '2026-01-14 19:01:49'),
(368, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 19:17:12', '2026-01-14 19:17:12'),
(369, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-14 19:17:17', '2026-01-14 19:17:17'),
(370, 1, 'Menambah alat: Keyboard', 'App\\Models\\Tool', 4, NULL, '2026-01-14 19:17:51', '2026-01-14 19:17:51'),
(371, 1, 'Menambah kategori: Elektronik', 'App\\Models\\Category', 5, NULL, '2026-01-14 19:18:43', '2026-01-14 19:18:43'),
(372, 1, 'Menambah alat: Earphone', 'App\\Models\\Tool', 5, NULL, '2026-01-14 19:19:14', '2026-01-14 19:19:14'),
(373, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-14 19:19:19', '2026-01-14 19:19:19'),
(374, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 19:19:24', '2026-01-14 19:19:24'),
(375, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 19:23:17', '2026-01-14 19:23:17'),
(376, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 21:29:44', '2026-01-14 21:29:44'),
(377, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 21:37:49', '2026-01-14 21:37:49'),
(378, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-14 21:38:07', '2026-01-14 21:38:07'),
(379, 3, 'Status peminjaman #5 diubah menjadi disetujui', 'App\\Models\\Borrowing', 5, NULL, '2026-01-15 04:38:20', '2026-01-15 04:38:20'),
(380, 1, 'Menghapus pengembalian ID: 3', NULL, NULL, NULL, '2026-01-14 21:38:21', '2026-01-14 21:38:21'),
(381, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-14 21:38:29', '2026-01-14 21:38:29'),
(382, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-14 21:38:44', '2026-01-14 21:38:44'),
(383, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-14 21:54:14', '2026-01-14 21:54:14'),
(384, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-14 21:54:23', '2026-01-14 21:54:23'),
(385, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-14 21:54:30', '2026-01-14 21:54:30'),
(386, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-14 22:17:19', '2026-01-14 22:17:19'),
(387, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 22:17:47', '2026-01-14 22:17:47'),
(388, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 22:43:55', '2026-01-14 22:43:55'),
(389, 6, 'Login ke sistem', 'App\\Models\\User', 6, NULL, '2026-01-14 22:44:01', '2026-01-14 22:44:01'),
(390, 6, 'Logout dari sistem', 'App\\Models\\User', 6, NULL, '2026-01-14 22:45:39', '2026-01-14 22:45:39'),
(391, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 22:45:44', '2026-01-14 22:45:44'),
(392, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 22:47:32', '2026-01-14 22:47:32'),
(393, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 22:48:04', '2026-01-14 22:48:04'),
(394, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-14 22:52:42', '2026-01-14 22:52:42'),
(395, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-14 22:52:51', '2026-01-14 22:52:51'),
(396, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-14 23:25:22', '2026-01-14 23:25:22'),
(397, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-14 23:25:33', '2026-01-14 23:25:33'),
(398, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-15 00:18:03', '2026-01-15 00:18:03'),
(399, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-15 00:18:09', '2026-01-15 00:18:09'),
(400, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-18 05:35:47', '2026-01-18 05:35:47'),
(401, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-18 05:51:20', '2026-01-18 05:51:20'),
(402, 6, 'Login ke sistem', 'App\\Models\\User', 6, NULL, '2026-01-18 05:51:27', '2026-01-18 05:51:27'),
(403, 6, 'Logout dari sistem', 'App\\Models\\User', 6, NULL, '2026-01-18 06:30:48', '2026-01-18 06:30:48'),
(404, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-18 18:43:03', '2026-01-18 18:43:03'),
(405, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-18 18:46:10', '2026-01-18 18:46:10'),
(406, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-18 18:46:17', '2026-01-18 18:46:17'),
(407, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-18 18:46:38', '2026-01-18 18:46:38'),
(408, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-18 18:48:40', '2026-01-18 18:48:40'),
(409, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-18 18:50:36', '2026-01-18 18:50:36'),
(410, 6, 'Login ke sistem', 'App\\Models\\User', 6, NULL, '2026-01-18 19:05:52', '2026-01-18 19:05:52'),
(411, 6, 'Logout dari sistem', 'App\\Models\\User', 6, NULL, '2026-01-18 20:53:15', '2026-01-18 20:53:15'),
(412, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-18 20:53:22', '2026-01-18 20:53:22'),
(413, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-18 21:09:37', '2026-01-18 21:09:37'),
(414, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-18 21:09:43', '2026-01-18 21:09:43'),
(415, 1, 'Menambah user baru: Riko', 'App\\Models\\User', 8, NULL, '2026-01-18 21:10:31', '2026-01-18 21:10:31'),
(416, 1, 'Menambah user baru: Rio', 'App\\Models\\User', 9, NULL, '2026-01-18 21:10:59', '2026-01-18 21:10:59'),
(417, 1, 'Mengupdate user: Riko Setiawan', 'App\\Models\\User', 8, NULL, '2026-01-18 21:11:29', '2026-01-18 21:11:29'),
(418, 1, 'Mengupdate alat: Earphone', 'App\\Models\\Tool', 5, NULL, '2026-01-18 21:12:23', '2026-01-18 21:12:23'),
(419, 1, 'Mengupdate pengembalian ID: 7', 'App\\Models\\ReturnModel', 7, NULL, '2026-01-18 21:13:03', '2026-01-18 21:13:03'),
(420, 1, 'Menambah kategori: Aksesoris', 'App\\Models\\Category', 6, NULL, '2026-01-18 21:13:25', '2026-01-18 21:13:25'),
(421, 1, 'Menghapus kategori: Aksesoris', NULL, NULL, NULL, '2026-01-18 21:13:32', '2026-01-18 21:13:32'),
(422, 1, 'Menghapus user: Rio', NULL, NULL, NULL, '2026-01-18 21:13:43', '2026-01-18 21:13:43'),
(423, 1, 'Menghapus user: Riko Setiawan', NULL, NULL, NULL, '2026-01-18 21:13:48', '2026-01-18 21:13:48'),
(424, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-18 21:13:51', '2026-01-18 21:13:51'),
(425, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-18 21:13:57', '2026-01-18 21:13:57'),
(426, 3, 'Mengajukan peminjaman #12', 'App\\Models\\Borrowing', 12, NULL, '2026-01-19 04:17:49', '2026-01-19 04:17:49'),
(427, 3, 'Mengajukan peminjaman baru', 'App\\Models\\Borrowing', 12, NULL, '2026-01-18 21:17:49', '2026-01-18 21:17:49'),
(428, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-18 21:17:56', '2026-01-18 21:17:56'),
(429, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-18 21:18:05', '2026-01-18 21:18:05'),
(430, 3, 'Status peminjaman #12 diubah menjadi disetujui', 'App\\Models\\Borrowing', 12, NULL, '2026-01-19 04:18:08', '2026-01-19 04:18:08'),
(431, 2, 'Menyetujui peminjaman ID: 12', 'App\\Models\\Borrowing', 12, NULL, '2026-01-18 21:18:08', '2026-01-18 21:18:08'),
(432, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-18 21:18:15', '2026-01-18 21:18:15'),
(433, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-18 21:18:22', '2026-01-18 21:18:22'),
(434, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-18 21:18:52', '2026-01-18 21:18:52'),
(435, 6, 'Login ke sistem', 'App\\Models\\User', 6, NULL, '2026-01-18 21:18:59', '2026-01-18 21:18:59'),
(436, 6, 'Logout dari sistem', 'App\\Models\\User', 6, NULL, '2026-01-18 21:19:18', '2026-01-18 21:19:18'),
(437, 7, 'Login ke sistem', 'App\\Models\\User', 7, NULL, '2026-01-18 21:19:25', '2026-01-18 21:19:25'),
(438, 7, 'Logout dari sistem', 'App\\Models\\User', 7, NULL, '2026-01-18 21:20:10', '2026-01-18 21:20:10'),
(439, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-18 21:20:15', '2026-01-18 21:20:15'),
(440, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-18 21:20:21', '2026-01-18 21:20:21'),
(441, 6, 'Login ke sistem', 'App\\Models\\User', 6, NULL, '2026-01-18 21:20:29', '2026-01-18 21:20:29'),
(442, 6, 'Logout dari sistem', 'App\\Models\\User', 6, NULL, '2026-01-18 21:20:37', '2026-01-18 21:20:37'),
(443, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-18 21:20:43', '2026-01-18 21:20:43'),
(444, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-18 21:37:49', '2026-01-18 21:37:49'),
(445, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-18 21:37:54', '2026-01-18 21:37:54'),
(446, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-18 21:42:43', '2026-01-18 21:42:43'),
(447, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-18 21:42:48', '2026-01-18 21:42:48'),
(448, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-18 23:14:42', '2026-01-18 23:14:42'),
(449, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-18 23:14:46', '2026-01-18 23:14:46'),
(450, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-18 23:32:45', '2026-01-18 23:32:45'),
(451, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-18 23:36:05', '2026-01-18 23:36:05'),
(452, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-19 00:51:57', '2026-01-19 00:51:57'),
(453, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-19 00:52:26', '2026-01-19 00:52:26'),
(454, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-19 00:52:50', '2026-01-19 00:52:50'),
(455, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-19 00:53:47', '2026-01-19 00:53:47'),
(456, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-19 00:54:11', '2026-01-19 00:54:11'),
(457, 6, 'Login ke sistem', 'App\\Models\\User', 6, NULL, '2026-01-19 00:54:16', '2026-01-19 00:54:16'),
(458, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-19 09:28:53', '2026-01-19 09:28:53'),
(459, 3, 'Mengajukan peminjaman #13', 'App\\Models\\Borrowing', 13, NULL, '2026-01-19 16:29:19', '2026-01-19 16:29:19'),
(460, 3, 'Mengajukan peminjaman baru', 'App\\Models\\Borrowing', 13, NULL, '2026-01-19 09:29:19', '2026-01-19 09:29:19');
INSERT INTO `activity_logs` (`id`, `user_id`, `aktivitas`, `model_type`, `model_id`, `detail`, `created_at`, `updated_at`) VALUES
(461, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-19 09:29:42', '2026-01-19 09:29:42'),
(462, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-19 09:29:50', '2026-01-19 09:29:50'),
(463, 3, 'Status peminjaman #13 diubah menjadi disetujui', 'App\\Models\\Borrowing', 13, NULL, '2026-01-19 16:29:54', '2026-01-19 16:29:54'),
(464, 2, 'Menyetujui peminjaman ID: 13', 'App\\Models\\Borrowing', 13, NULL, '2026-01-19 09:29:54', '2026-01-19 09:29:54'),
(465, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-19 09:31:20', '2026-01-19 09:31:20'),
(466, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-19 09:31:27', '2026-01-19 09:31:27'),
(467, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-19 09:36:20', '2026-01-19 09:36:20'),
(468, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-19 09:36:27', '2026-01-19 09:36:27'),
(469, 3, 'Status peminjaman #9 diubah menjadi menunggu_pengembalian', 'App\\Models\\Borrowing', 9, NULL, '2026-01-19 16:36:36', '2026-01-19 16:36:36'),
(470, 3, 'Mengajukan pengembalian peminjaman ID: 9', 'App\\Models\\Borrowing', 9, NULL, '2026-01-19 09:36:36', '2026-01-19 09:36:36'),
(471, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-19 09:36:43', '2026-01-19 09:36:43'),
(472, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-19 09:36:49', '2026-01-19 09:36:49'),
(473, 3, 'Status peminjaman #9 diubah menjadi dikembalikan', 'App\\Models\\Borrowing', 9, NULL, '2026-01-19 16:37:07', '2026-01-19 16:37:07'),
(474, 2, 'Memproses pengembalian peminjaman ID: 9', 'App\\Models\\ReturnModel', 8, NULL, '2026-01-19 09:37:07', '2026-01-19 09:37:07'),
(475, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-19 09:41:38', '2026-01-19 09:41:38'),
(476, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-19 09:41:43', '2026-01-19 09:41:43'),
(477, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-19 09:41:50', '2026-01-19 09:41:50'),
(478, 6, 'Login ke sistem', 'App\\Models\\User', 6, NULL, '2026-01-19 09:41:56', '2026-01-19 09:41:56'),
(479, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-19 17:48:08', '2026-01-19 17:48:08'),
(480, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-19 18:00:32', '2026-01-19 18:00:32'),
(481, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-19 18:00:40', '2026-01-19 18:00:40'),
(482, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-19 18:05:22', '2026-01-19 18:05:22'),
(483, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-19 18:05:29', '2026-01-19 18:05:29'),
(484, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-19 18:33:09', '2026-01-19 18:33:09'),
(485, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-19 18:33:16', '2026-01-19 18:33:16'),
(486, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-19 18:50:39', '2026-01-19 18:50:39'),
(487, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-19 18:50:45', '2026-01-19 18:50:45'),
(488, 3, 'Status peminjaman #12 diubah menjadi menunggu_pengembalian', 'App\\Models\\Borrowing', 12, NULL, '2026-01-20 01:51:00', '2026-01-20 01:51:00'),
(489, 3, 'Mengajukan pengembalian peminjaman ID: 12', 'App\\Models\\Borrowing', 12, NULL, '2026-01-19 18:51:00', '2026-01-19 18:51:00'),
(490, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-19 18:51:04', '2026-01-19 18:51:04'),
(491, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-19 18:51:13', '2026-01-19 18:51:13'),
(492, 3, 'Status peminjaman #12 diubah menjadi dikembalikan', 'App\\Models\\Borrowing', 12, NULL, '2026-01-20 01:51:23', '2026-01-20 01:51:23'),
(493, 2, 'Memproses pengembalian peminjaman ID: 12', 'App\\Models\\ReturnModel', 9, NULL, '2026-01-19 18:51:23', '2026-01-19 18:51:23'),
(494, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-19 19:12:09', '2026-01-19 19:12:09'),
(495, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-19 19:14:19', '2026-01-19 19:14:19'),
(496, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-19 19:14:53', '2026-01-19 19:14:53'),
(497, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-19 19:15:14', '2026-01-19 19:15:14'),
(498, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-19 20:17:55', '2026-01-19 20:17:55'),
(499, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-19 20:18:04', '2026-01-19 20:18:04'),
(500, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-19 21:09:00', '2026-01-19 21:09:00'),
(501, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-19 21:09:12', '2026-01-19 21:09:12'),
(502, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-19 21:11:43', '2026-01-19 21:11:43'),
(503, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-19 21:11:50', '2026-01-19 21:11:50'),
(504, 1, 'Menghapus alat: Kipas', NULL, NULL, NULL, '2026-01-20 00:10:57', '2026-01-20 00:10:57'),
(505, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-20 19:28:01', '2026-01-20 19:28:01'),
(506, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-20 19:28:23', '2026-01-20 19:28:23'),
(507, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-20 20:10:09', '2026-01-20 20:10:09'),
(508, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-20 20:21:20', '2026-01-20 20:21:20'),
(509, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-20 20:22:34', '2026-01-20 20:22:34'),
(510, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-20 20:22:41', '2026-01-20 20:22:41'),
(511, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-20 20:28:38', '2026-01-20 20:28:38'),
(512, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-20 20:28:50', '2026-01-20 20:28:50'),
(513, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-20 20:35:35', '2026-01-20 20:35:35'),
(514, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-20 20:35:45', '2026-01-20 20:35:45'),
(515, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-20 20:37:01', '2026-01-20 20:37:01'),
(516, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-20 20:37:08', '2026-01-20 20:37:08'),
(517, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-20 20:37:26', '2026-01-20 20:37:26'),
(518, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-20 20:39:58', '2026-01-20 20:39:58'),
(519, 1, 'Menambah alat: Remote', 'App\\Models\\Tool', 10, NULL, '2026-01-20 20:41:44', '2026-01-20 20:41:44'),
(520, 1, 'Mengupdate alat: Remote', 'App\\Models\\Tool', 10, NULL, '2026-01-20 20:41:57', '2026-01-20 20:41:57'),
(521, 1, 'Mengupdate alat: Remote', 'App\\Models\\Tool', 10, NULL, '2026-01-20 20:42:44', '2026-01-20 20:42:44'),
(522, 1, 'Mengupdate alat: Remote', 'App\\Models\\Tool', 10, NULL, '2026-01-20 20:55:16', '2026-01-20 20:55:16'),
(523, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-20 21:31:36', '2026-01-20 21:31:36'),
(524, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-20 21:31:48', '2026-01-20 21:31:48'),
(525, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-20 21:32:34', '2026-01-20 21:32:34'),
(526, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-20 21:32:55', '2026-01-20 21:32:55'),
(527, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-20 21:33:51', '2026-01-20 21:33:51'),
(528, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-20 21:33:57', '2026-01-20 21:33:57'),
(529, 3, 'Status peminjaman #13 diubah menjadi menunggu_pengembalian', 'App\\Models\\Borrowing', 13, NULL, '2026-01-21 04:34:12', '2026-01-21 04:34:12'),
(530, 3, 'Mengajukan pengembalian peminjaman ID: 13', 'App\\Models\\Borrowing', 13, NULL, '2026-01-20 21:34:12', '2026-01-20 21:34:12'),
(531, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-20 21:34:17', '2026-01-20 21:34:17'),
(532, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-20 21:34:25', '2026-01-20 21:34:25'),
(533, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-20 21:34:37', '2026-01-20 21:34:37'),
(534, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-20 21:34:43', '2026-01-20 21:34:43'),
(535, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-20 21:36:15', '2026-01-20 21:36:15'),
(536, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-20 21:36:27', '2026-01-20 21:36:27'),
(537, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-20 21:36:33', '2026-01-20 21:36:33'),
(538, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-20 21:36:41', '2026-01-20 21:36:41'),
(539, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-20 21:37:10', '2026-01-20 21:37:10'),
(540, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-20 21:37:16', '2026-01-20 21:37:16'),
(541, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-20 21:46:54', '2026-01-20 21:46:54'),
(542, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-20 21:47:00', '2026-01-20 21:47:00'),
(543, 1, 'Mengupdate alat: Remote', 'App\\Models\\Tool', 10, NULL, '2026-01-20 21:48:22', '2026-01-20 21:48:22'),
(544, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-20 21:48:25', '2026-01-20 21:48:25'),
(545, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-20 21:48:49', '2026-01-20 21:48:49'),
(546, 3, 'Mengajukan peminjaman #14', 'App\\Models\\Borrowing', 14, NULL, '2026-01-21 04:49:19', '2026-01-21 04:49:19'),
(547, 3, 'Mengajukan peminjaman baru', 'App\\Models\\Borrowing', 14, NULL, '2026-01-20 21:49:19', '2026-01-20 21:49:19'),
(548, 3, 'Mengajukan peminjaman #15', 'App\\Models\\Borrowing', 15, NULL, '2026-01-21 04:49:43', '2026-01-21 04:49:43'),
(549, 3, 'Mengajukan peminjaman baru', 'App\\Models\\Borrowing', 15, NULL, '2026-01-20 21:49:43', '2026-01-20 21:49:43'),
(550, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-20 21:49:52', '2026-01-20 21:49:52'),
(551, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-20 21:50:07', '2026-01-20 21:50:07'),
(552, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-20 21:50:19', '2026-01-20 21:50:19'),
(553, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-20 21:50:33', '2026-01-20 21:50:33'),
(554, 3, 'Status peminjaman #15 diubah menjadi disetujui', 'App\\Models\\Borrowing', 15, NULL, '2026-01-21 04:50:37', '2026-01-21 04:50:37'),
(555, 2, 'Menyetujui peminjaman ID: 15', 'App\\Models\\Borrowing', 15, NULL, '2026-01-20 21:50:37', '2026-01-20 21:50:37'),
(556, 3, 'Status peminjaman #14 diubah menjadi ditolak', 'App\\Models\\Borrowing', 14, NULL, '2026-01-21 04:50:53', '2026-01-21 04:50:53'),
(557, 2, 'Menolak peminjaman ID: 14', 'App\\Models\\Borrowing', 14, NULL, '2026-01-20 21:50:53', '2026-01-20 21:50:53'),
(558, 2, 'Logout dari sistem', 'App\\Models\\User', 2, NULL, '2026-01-20 21:51:43', '2026-01-20 21:51:43'),
(559, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-20 21:51:48', '2026-01-20 21:51:48'),
(560, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-20 22:33:35', '2026-01-20 22:33:35'),
(561, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-20 22:33:47', '2026-01-20 22:33:47'),
(562, 1, 'Mengupdate alat: Contoh Alat 1', 'App\\Models\\Tool', 8, NULL, '2026-01-20 22:34:08', '2026-01-20 22:34:08'),
(563, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-20 22:34:15', '2026-01-20 22:34:15'),
(564, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-20 22:34:22', '2026-01-20 22:34:22'),
(565, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-20 22:34:39', '2026-01-20 22:34:39'),
(566, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-20 22:34:45', '2026-01-20 22:34:45'),
(567, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-20 22:34:54', '2026-01-20 22:34:54'),
(568, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-20 22:35:05', '2026-01-20 22:35:05'),
(569, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-20 22:35:36', '2026-01-20 22:35:36'),
(570, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-20 22:35:41', '2026-01-20 22:35:41'),
(571, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-20 22:39:08', '2026-01-20 22:39:08'),
(572, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-20 22:39:14', '2026-01-20 22:39:14'),
(573, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-20 23:58:40', '2026-01-20 23:58:40'),
(574, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-21 00:09:06', '2026-01-21 00:09:06'),
(575, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-21 00:09:19', '2026-01-21 00:09:19'),
(576, 12, 'Login ke sistem', 'App\\Models\\User', 12, NULL, '2026-01-21 00:27:21', '2026-01-21 00:27:21'),
(577, 12, 'Logout dari sistem', 'App\\Models\\User', 12, NULL, '2026-01-21 00:29:22', '2026-01-21 00:29:22'),
(578, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-21 00:44:02', '2026-01-21 00:44:02'),
(579, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-21 08:09:03', '2026-01-21 08:09:03'),
(580, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-21 09:32:52', '2026-01-21 09:32:52'),
(581, 6, 'Login ke sistem', 'App\\Models\\User', 6, NULL, '2026-01-21 09:33:01', '2026-01-21 09:33:01'),
(582, 6, 'Logout dari sistem', 'App\\Models\\User', 6, NULL, '2026-01-21 09:34:25', '2026-01-21 09:34:25'),
(583, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-21 09:34:30', '2026-01-21 09:34:30'),
(584, 1, 'Menambah user baru: Tes', 'App\\Models\\User', 13, NULL, '2026-01-21 09:34:57', '2026-01-21 09:34:57'),
(585, 1, 'Mengupdate alat: Contoh Alat 2', 'App\\Models\\Tool', 9, NULL, '2026-01-21 09:35:28', '2026-01-21 09:35:28'),
(586, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-21 09:51:33', '2026-01-21 09:51:33'),
(587, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-21 09:51:45', '2026-01-21 09:51:45'),
(588, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-21 09:53:57', '2026-01-21 09:53:57'),
(589, 6, 'Login ke sistem', 'App\\Models\\User', 6, NULL, '2026-01-21 09:54:26', '2026-01-21 09:54:26'),
(590, 6, 'Logout dari sistem', 'App\\Models\\User', 6, NULL, '2026-01-21 10:29:08', '2026-01-21 10:29:08'),
(591, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-21 10:33:49', '2026-01-21 10:33:49'),
(592, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-21 11:07:22', '2026-01-21 11:07:22'),
(593, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-21 11:07:28', '2026-01-21 11:07:28'),
(594, 2, 'Login ke sistem', 'App\\Models\\User', 2, NULL, '2026-01-21 17:37:52', '2026-01-21 17:37:52'),
(595, 6, 'Login ke sistem', 'App\\Models\\User', 6, NULL, '2026-01-21 17:38:22', '2026-01-21 17:38:22'),
(596, 6, 'Logout dari sistem', 'App\\Models\\User', 6, NULL, '2026-01-21 18:01:21', '2026-01-21 18:01:21'),
(597, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-21 18:01:27', '2026-01-21 18:01:27'),
(598, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-21 18:37:22', '2026-01-21 18:37:22'),
(599, 6, 'Login ke sistem', 'App\\Models\\User', 6, NULL, '2026-01-21 18:37:28', '2026-01-21 18:37:28'),
(600, 6, 'Logout dari sistem', 'App\\Models\\User', 6, NULL, '2026-01-21 19:22:21', '2026-01-21 19:22:21'),
(601, 6, 'Login ke sistem', 'App\\Models\\User', 6, NULL, '2026-01-21 19:22:36', '2026-01-21 19:22:36'),
(602, 6, 'Logout dari sistem', 'App\\Models\\User', 6, NULL, '2026-01-21 19:50:01', '2026-01-21 19:50:01'),
(603, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-21 19:50:08', '2026-01-21 19:50:08'),
(604, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-21 20:04:12', '2026-01-21 20:04:12'),
(605, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-21 20:04:20', '2026-01-21 20:04:20'),
(606, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-21 20:53:50', '2026-01-21 20:53:50'),
(607, 6, 'Login ke sistem', 'App\\Models\\User', 6, NULL, '2026-01-21 20:53:58', '2026-01-21 20:53:58'),
(608, 6, 'Logout dari sistem', 'App\\Models\\User', 6, NULL, '2026-01-21 20:56:10', '2026-01-21 20:56:10'),
(609, 5, 'Login ke sistem', 'App\\Models\\User', 5, NULL, '2026-01-21 20:56:21', '2026-01-21 20:56:21'),
(610, 5, 'Logout dari sistem', 'App\\Models\\User', 5, NULL, '2026-01-21 21:51:44', '2026-01-21 21:51:44'),
(611, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-21 21:51:48', '2026-01-21 21:51:48'),
(612, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-21 23:06:59', '2026-01-21 23:06:59'),
(613, 5, 'Login ke sistem', 'App\\Models\\User', 5, NULL, '2026-01-21 23:07:09', '2026-01-21 23:07:09'),
(614, 5, 'Mengajukan peminjaman #16', 'App\\Models\\Borrowing', 16, NULL, '2026-01-22 06:07:34', '2026-01-22 06:07:34'),
(615, 5, 'Mengajukan peminjaman baru', 'App\\Models\\Borrowing', 16, NULL, '2026-01-21 23:07:34', '2026-01-21 23:07:34'),
(616, 5, 'Logout dari sistem', 'App\\Models\\User', 5, NULL, '2026-01-21 23:07:38', '2026-01-21 23:07:38'),
(617, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-21 23:31:17', '2026-01-21 23:31:17'),
(618, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-21 23:31:22', '2026-01-21 23:31:22'),
(619, 6, 'Login ke sistem', 'App\\Models\\User', 6, NULL, '2026-01-21 23:40:46', '2026-01-21 23:40:46'),
(620, 6, 'Logout dari sistem', 'App\\Models\\User', 6, NULL, '2026-01-21 23:57:20', '2026-01-21 23:57:20'),
(621, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-21 23:57:25', '2026-01-21 23:57:25'),
(622, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-25 21:06:33', '2026-01-25 21:06:33'),
(623, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-25 21:08:31', '2026-01-25 21:08:31'),
(624, 6, 'Login ke sistem', 'App\\Models\\User', 6, NULL, '2026-01-25 21:08:36', '2026-01-25 21:08:36'),
(625, 6, 'Logout dari sistem', 'App\\Models\\User', 6, NULL, '2026-01-25 21:10:50', '2026-01-25 21:10:50'),
(626, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-25 21:10:55', '2026-01-25 21:10:55'),
(627, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-25 21:13:43', '2026-01-25 21:13:43'),
(628, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-25 21:19:22', '2026-01-25 21:19:22'),
(629, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-25 21:39:58', '2026-01-25 21:39:58'),
(630, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-25 21:54:56', '2026-01-25 21:54:56'),
(631, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-25 22:14:39', '2026-01-25 22:14:39'),
(632, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-25 22:14:43', '2026-01-25 22:14:43'),
(633, 1, 'Logout dari sistem', 'App\\Models\\User', 1, NULL, '2026-01-25 22:22:53', '2026-01-25 22:22:53'),
(634, 6, 'Login ke sistem', 'App\\Models\\User', 6, NULL, '2026-01-25 22:23:00', '2026-01-25 22:23:00'),
(635, 6, 'Logout dari sistem', 'App\\Models\\User', 6, NULL, '2026-01-25 22:36:24', '2026-01-25 22:36:24'),
(636, 5, 'Login ke sistem', 'App\\Models\\User', 5, NULL, '2026-01-25 22:36:30', '2026-01-25 22:36:30'),
(637, 5, 'Logout dari sistem', 'App\\Models\\User', 5, NULL, '2026-01-25 22:36:54', '2026-01-25 22:36:54'),
(638, 3, 'Login ke sistem', 'App\\Models\\User', 3, NULL, '2026-01-25 22:36:58', '2026-01-25 22:36:58'),
(639, 3, 'Logout dari sistem', 'App\\Models\\User', 3, NULL, '2026-01-25 22:49:57', '2026-01-25 22:49:57'),
(640, 1, 'Login ke sistem', 'App\\Models\\User', 1, NULL, '2026-01-25 22:50:03', '2026-01-25 22:50:03');

-- --------------------------------------------------------

--
-- Table structure for table `borrowings`
--

CREATE TABLE `borrowings` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `jatuh_tempo` date DEFAULT NULL,
  `status` enum('menunggu','disetujui','ditolak','dikembalikan','menunggu_pengembalian') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `borrowings`
--

INSERT INTO `borrowings` (`id`, `user_id`, `tanggal_pinjam`, `tanggal_selesai`, `tanggal_kembali`, `jatuh_tempo`, `status`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 3, '2026-01-09', NULL, NULL, NULL, 'ditolak', '-', '2026-01-09 00:25:07', '2026-01-09 00:25:31'),
(2, 3, '2026-01-09', NULL, NULL, '2026-01-12', 'dikembalikan', NULL, '2026-01-09 00:25:07', '2026-01-09 00:26:25'),
(3, 3, '2026-01-09', '2026-01-11', NULL, '2026-01-11', 'dikembalikan', NULL, '2026-01-09 00:37:20', '2026-01-10 07:54:01'),
(4, 3, '2026-01-10', '2026-01-10', NULL, '2026-01-10', 'disetujui', NULL, '2026-01-10 07:39:03', '2026-01-10 07:39:24'),
(5, 3, '2026-01-10', '2026-01-10', NULL, '2026-01-10', 'disetujui', NULL, '2026-01-10 07:56:19', '2026-01-14 21:38:20'),
(7, 3, '2026-01-10', '2026-01-12', NULL, '2026-01-12', 'dikembalikan', NULL, '2026-01-10 10:09:23', '2026-01-10 17:14:30'),
(8, 3, '2026-01-10', '2026-01-11', NULL, '2026-01-11', 'dikembalikan', NULL, '2026-01-10 10:09:43', '2026-01-14 17:47:27'),
(9, 3, '2026-01-14', '2026-01-15', NULL, '2026-01-15', 'dikembalikan', NULL, '2026-01-13 18:05:08', '2026-01-19 09:37:07'),
(10, 3, '2026-01-14', '2026-01-16', NULL, '2026-01-16', 'dikembalikan', NULL, '2026-01-13 18:10:15', '2026-01-14 18:40:14'),
(11, 3, '2026-01-14', '2026-01-18', NULL, '2026-01-18', 'dikembalikan', NULL, '2026-01-13 18:32:23', '2026-01-14 18:14:46'),
(12, 3, '2026-01-19', '2026-01-22', NULL, '2026-01-22', 'dikembalikan', NULL, '2026-01-18 21:17:49', '2026-01-19 18:51:23'),
(13, 3, '2026-01-19', '2026-01-22', NULL, '2026-01-22', 'menunggu_pengembalian', NULL, '2026-01-19 09:29:19', '2026-01-20 21:34:12'),
(14, 3, '2026-01-21', '2026-01-22', NULL, NULL, 'ditolak', 'Stok habis', '2026-01-20 21:49:19', '2026-01-20 21:50:53'),
(15, 3, '2026-01-21', '2026-01-22', NULL, '2026-01-22', 'disetujui', NULL, '2026-01-20 21:49:43', '2026-01-20 21:50:37'),
(16, 5, '2026-01-22', '2026-01-23', NULL, NULL, 'menunggu', NULL, '2026-01-21 23:07:34', '2026-01-21 23:07:34');

--
-- Triggers `borrowings`
--
DELIMITER $$
CREATE TRIGGER `log_aktivitas_borrowing_insert` AFTER INSERT ON `borrowings` FOR EACH ROW BEGIN
                INSERT INTO activity_logs (user_id, aktivitas, model_type, model_id, created_at, updated_at)
                VALUES (NEW.user_id, CONCAT('Mengajukan peminjaman #', NEW.id), 'App\Models\Borrowing', NEW.id, NOW(), NOW());
            END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_aktivitas_borrowing_update` AFTER UPDATE ON `borrowings` FOR EACH ROW BEGIN
                IF OLD.status != NEW.status THEN
                    INSERT INTO activity_logs (user_id, aktivitas, model_type, model_id, created_at, updated_at)
                    VALUES (NEW.user_id, CONCAT('Status peminjaman #', NEW.id, ' diubah menjadi ', NEW.status), 'App\Models\Borrowing', NEW.id, NOW(), NOW());
                END IF;
            END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `borrowing_details`
--

CREATE TABLE `borrowing_details` (
  `id` bigint UNSIGNED NOT NULL,
  `borrowing_id` bigint UNSIGNED NOT NULL,
  `tool_id` bigint UNSIGNED NOT NULL,
  `jumlah` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `borrowing_details`
--

INSERT INTO `borrowing_details` (`id`, `borrowing_id`, `tool_id`, `jumlah`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 5, '2026-01-09 00:25:07', '2026-01-09 00:25:07'),
(2, 2, 1, 5, '2026-01-09 00:25:07', '2026-01-09 00:25:07'),
(3, 3, 1, 5, '2026-01-09 00:37:20', '2026-01-09 00:37:20'),
(4, 4, 1, 5, '2026-01-10 07:39:03', '2026-01-10 07:39:03'),
(5, 5, 1, 5, '2026-01-10 07:56:19', '2026-01-10 07:56:19'),
(7, 7, 1, 5, '2026-01-10 10:09:23', '2026-01-10 10:09:23'),
(8, 8, 1, 5, '2026-01-10 10:09:43', '2026-01-10 10:09:43'),
(9, 9, 2, 5, '2026-01-13 18:05:08', '2026-01-13 18:05:08'),
(10, 10, 3, 20, '2026-01-13 18:10:16', '2026-01-13 18:10:16'),
(11, 11, 1, 10, '2026-01-13 18:32:23', '2026-01-13 18:32:23'),
(12, 12, 5, 20, '2026-01-18 21:17:49', '2026-01-18 21:17:49'),
(13, 13, 4, 1, '2026-01-19 09:29:19', '2026-01-19 09:29:19'),
(14, 14, 10, 9, '2026-01-20 21:49:19', '2026-01-20 21:49:19'),
(15, 15, 10, 10, '2026-01-20 21:49:43', '2026-01-20 21:49:43'),
(16, 16, 9, 5, '2026-01-21 23:07:34', '2026-01-21 23:07:34');

--
-- Triggers `borrowing_details`
--
DELIMITER $$
CREATE TRIGGER `update_stok_after_borrowing_detail` AFTER INSERT ON `borrowing_details` FOR EACH ROW BEGIN
                DECLARE borrowing_status VARCHAR(20);
                
                SELECT status INTO borrowing_status 
                FROM borrowings 
                WHERE id = NEW.borrowing_id;
                
                IF borrowing_status = 'disetujui' THEN
                    UPDATE tools 
                    SET stok = stok - NEW.jumlah 
                    WHERE id = NEW.tool_id;
                END IF;
            END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `nama_kategori`, `created_at`, `updated_at`) VALUES
(1, 'Perangkat Komputer', '2026-01-09 00:20:39', '2026-01-09 00:20:39'),
(2, 'Peralatan Jaringan', '2026-01-09 00:20:39', '2026-01-09 00:20:39'),
(3, 'Peralatan Multimedia', '2026-01-09 00:20:39', '2026-01-09 00:20:39'),
(4, 'Inventaris Umum', '2026-01-09 00:20:39', '2026-01-09 00:20:39'),
(5, 'Elektronik', '2026-01-14 19:18:43', '2026-01-14 19:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2024_01_01_000001_create_users_table', 1),
(2, '2024_01_01_000002_create_categories_table', 1),
(3, '2024_01_01_000003_create_tools_table', 1),
(4, '2024_01_01_000004_create_borrowings_table', 1),
(5, '2024_01_01_000005_create_borrowing_details_table', 1),
(6, '2024_01_01_000006_create_returns_table', 1),
(7, '2024_01_01_000007_create_activity_logs_table', 1),
(8, '2024_01_01_000008_create_database_functions_and_procedures', 2),
(9, '2024_01_01_000009_create_triggers', 2),
(10, '2024_01_01_000010_create_notifications_table', 2),
(11, '2024_01_01_000011_update_borrowings_add_jatuh_tempo', 2),
(12, '2024_01_01_000012_update_tools_change_kondisi_to_status', 2),
(13, '2024_01_01_000013_update_returns_add_terlambat_hari', 2),
(14, '2026_01_09_041231_create_sessions_table', 2),
(15, '2026_01_09_041254_create_cache_table', 2),
(16, '2024_01_01_000014_add_tanggal_selesai_to_borrowings', 3),
(17, '2026_01_13_161608_add_gambar_to_tools_table', 4),
(18, '2026_01_15_011019_add_denda_kerusakan_to_returns_table', 5),
(19, '2026_01_15_013357_add_menunggu_pengembalian_to_borrowings_status', 6),
(20, '2026_01_21_044157_add_stok_rusak_to_tools_table', 7),
(21, '2026_01_21_050550_add_stok_perbaikan_to_tools_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `tipe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_baca` tinyint(1) NOT NULL DEFAULT '0',
  `related_id` bigint UNSIGNED DEFAULT NULL,
  `related_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `tipe`, `judul`, `pesan`, `status_baca`, `related_id`, `related_type`, `created_at`, `updated_at`) VALUES
(1, 3, 'peminjaman_ditolak', 'Peminjaman Ditolak', 'Peminjaman Anda (#1) telah ditolak. Alasan: -', 1, 1, 'App\\Models\\Borrowing', '2026-01-09 00:25:31', '2026-01-09 00:26:08'),
(2, 3, 'peminjaman_disetujui', 'Peminjaman Disetujui', 'Peminjaman Anda (#2) telah disetujui. Jatuh tempo: 12/01/2026', 1, 2, 'App\\Models\\Borrowing', '2026-01-09 00:25:34', '2026-01-09 00:26:05'),
(4, 3, 'peminjaman_disetujui', 'Peminjaman Disetujui', 'Peminjaman Anda (#4) telah disetujui. Jatuh tempo: 10/01/2026', 1, 4, 'App\\Models\\Borrowing', '2026-01-10 07:39:24', '2026-01-10 07:40:37'),
(5, 3, 'peminjaman_disetujui', 'Peminjaman Disetujui', 'Peminjaman Anda (#5) telah disetujui. Jatuh tempo: 10/01/2026', 1, 5, 'App\\Models\\Borrowing', '2026-01-10 07:56:47', '2026-01-10 08:49:40'),
(6, 3, 'pengingat_pengembalian', 'Pengingat Pengembalian', 'Dari Petugas: Pengingat: Peminjaman Anda (#5) akan jatuh tempo pada 10/01/2026. Segera kembalikan alat untuk menghindari denda.', 1, 5, 'App\\Models\\Borrowing', '2026-01-10 08:53:11', '2026-01-10 08:53:40'),
(7, 1, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#7) dari Peminjam menunggu persetujuan.', 1, 7, 'App\\Models\\Borrowing', '2026-01-10 10:09:23', '2026-01-10 16:27:14'),
(8, 2, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#7) dari Peminjam menunggu persetujuan.', 1, 7, 'App\\Models\\Borrowing', '2026-01-10 10:09:23', '2026-01-10 16:37:45'),
(9, 1, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#8) dari Peminjam menunggu persetujuan.', 1, 8, 'App\\Models\\Borrowing', '2026-01-10 10:09:43', '2026-01-10 16:27:14'),
(10, 2, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#8) dari Peminjam menunggu persetujuan.', 1, 8, 'App\\Models\\Borrowing', '2026-01-10 10:09:43', '2026-01-10 16:37:45'),
(11, 3, 'peminjaman_disetujui', 'Peminjaman Disetujui', 'Peminjaman Anda (#8) telah disetujui. Jatuh tempo: 11/01/2026', 1, 8, 'App\\Models\\Borrowing', '2026-01-10 10:10:06', '2026-01-10 16:28:05'),
(12, 3, 'peminjaman_disetujui', 'Peminjaman Disetujui', 'Peminjaman Anda (#7) telah disetujui. Jatuh tempo: 12/01/2026', 1, 7, 'App\\Models\\Borrowing', '2026-01-10 10:10:14', '2026-01-10 16:28:05'),
(13, 1, 'pengembalian_peminjam', 'Pengembalian oleh Peminjam', 'Peminjaman (#5) dari Peminjam telah dikembalikan. Silakan proses pengembalian.', 1, 5, 'App\\Models\\Borrowing', '2026-01-10 10:24:10', '2026-01-10 16:27:14'),
(14, 2, 'pengembalian_peminjam', 'Pengembalian oleh Peminjam', 'Peminjaman (#5) dari Peminjam telah dikembalikan. Silakan proses pengembalian.', 1, 5, 'App\\Models\\Borrowing', '2026-01-10 10:24:10', '2026-01-10 16:37:45'),
(15, 3, 'pengembalian_diproses', 'Pengembalian Diproses', 'Pengembalian peminjaman (#5) telah diproses pada 10/01/2026.', 1, 3, 'App\\Models\\ReturnModel', '2026-01-10 10:24:10', '2026-01-10 16:28:05'),
(16, 3, 'pengingat_pengembalian', 'Pengingat Pengembalian', 'Dari Petugas: Pengingat: Peminjaman Anda (#4) akan jatuh tempo pada 10/01/2026. Segera kembalikan alat untuk menghindari denda.', 1, 4, 'App\\Models\\Borrowing', '2026-01-10 16:37:32', '2026-01-10 16:49:25'),
(17, 3, 'estimasi_denda', 'Pemberitahuan Estimasi Denda', 'Dari Petugas: Pemberitahuan Denda - Peminjaman #4:\nEstimasi denda yang harus dibayar: Rp 5.000 (1 hari terlambat dari tanggal 10/01/2026).\nSegera kembalikan alat untuk menghentikan penambahan denda.', 1, 4, 'App\\Models\\Borrowing', '2026-01-10 17:13:23', '2026-01-10 17:14:09'),
(18, 1, 'pengembalian_peminjam', 'Pengembalian oleh Peminjam', 'Peminjaman (#7) dari Peminjam telah dikembalikan. Silakan proses pengembalian.', 1, 7, 'App\\Models\\Borrowing', '2026-01-10 17:14:30', '2026-01-10 17:15:20'),
(19, 2, 'pengembalian_peminjam', 'Pengembalian oleh Peminjam', 'Peminjaman (#7) dari Peminjam telah dikembalikan. Silakan proses pengembalian.', 1, 7, 'App\\Models\\Borrowing', '2026-01-10 17:14:30', '2026-01-10 17:14:51'),
(20, 3, 'pengembalian_diproses', 'Pengembalian Diproses', 'Pengembalian peminjaman (#7) telah diproses pada 11/01/2026.', 1, 4, 'App\\Models\\ReturnModel', '2026-01-10 17:14:30', '2026-01-10 18:43:48'),
(21, 3, 'estimasi_denda', 'Pemberitahuan Estimasi Denda', 'Dari Petugas: Pemberitahuan Denda - Peminjaman #8:\nEstimasi denda yang harus dibayar: Rp 10.000 (2 hari terlambat dari tanggal 11/01/2026).\nSegera kembalikan alat untuk menghentikan penambahan denda.', 1, 8, 'App\\Models\\Borrowing', '2026-01-12 18:37:40', '2026-01-12 18:38:27'),
(22, 3, 'estimasi_denda', 'Pemberitahuan Estimasi Denda', 'Dari Petugas: Pemberitahuan Denda - Peminjaman #8:\nEstimasi denda yang harus dibayar: Rp 10.000 (2 hari terlambat dari tanggal 11/01/2026).\nSegera kembalikan alat untuk menghentikan penambahan denda.', 1, 8, 'App\\Models\\Borrowing', '2026-01-12 18:37:42', '2026-01-12 18:38:27'),
(23, 3, 'estimasi_denda', 'Pemberitahuan Estimasi Denda', 'Dari Petugas: Pemberitahuan Denda - Peminjaman #8:\nEstimasi denda yang harus dibayar: Rp 10.000 (2 hari terlambat dari tanggal 11/01/2026).\nSegera kembalikan alat untuk menghentikan penambahan denda.', 1, 8, 'App\\Models\\Borrowing', '2026-01-12 18:37:43', '2026-01-12 18:38:27'),
(24, 3, 'estimasi_denda', 'Pemberitahuan Estimasi Denda', 'Dari Petugas: Pemberitahuan Denda - Peminjaman #8:\nEstimasi denda yang harus dibayar: Rp 10.000 (2 hari terlambat dari tanggal 11/01/2026).\nSegera kembalikan alat untuk menghentikan penambahan denda.', 1, 8, 'App\\Models\\Borrowing', '2026-01-12 18:37:58', '2026-01-12 18:38:27'),
(25, 1, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#9) dari Peminjam menunggu persetujuan.', 1, 9, 'App\\Models\\Borrowing', '2026-01-13 18:05:08', '2026-01-18 18:43:33'),
(26, 2, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#9) dari Peminjam menunggu persetujuan.', 1, 9, 'App\\Models\\Borrowing', '2026-01-13 18:05:08', '2026-01-13 18:05:48'),
(27, 6, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#9) dari Peminjam menunggu persetujuan.', 1, 9, 'App\\Models\\Borrowing', '2026-01-13 18:05:08', '2026-01-18 06:14:41'),
(28, 3, 'peminjaman_disetujui', 'Peminjaman Disetujui', 'Peminjaman Anda (#9) telah disetujui. Jatuh tempo: 15/01/2026', 1, 9, 'App\\Models\\Borrowing', '2026-01-13 18:05:41', '2026-01-18 21:17:04'),
(29, 1, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#10) dari Peminjam menunggu persetujuan.', 1, 10, 'App\\Models\\Borrowing', '2026-01-13 18:10:16', '2026-01-18 18:43:33'),
(30, 2, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#10) dari Peminjam menunggu persetujuan.', 1, 10, 'App\\Models\\Borrowing', '2026-01-13 18:10:16', '2026-01-13 18:15:06'),
(31, 6, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#10) dari Peminjam menunggu persetujuan.', 1, 10, 'App\\Models\\Borrowing', '2026-01-13 18:10:16', '2026-01-18 06:14:41'),
(32, 3, 'peminjaman_disetujui', 'Peminjaman Disetujui', 'Peminjaman Anda (#10) telah disetujui. Jatuh tempo: 16/01/2026', 1, 10, 'App\\Models\\Borrowing', '2026-01-13 18:14:58', '2026-01-18 21:17:04'),
(33, 1, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#11) dari Peminjam menunggu persetujuan.', 1, 11, 'App\\Models\\Borrowing', '2026-01-13 18:32:23', '2026-01-18 18:43:33'),
(34, 2, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#11) dari Peminjam menunggu persetujuan.', 1, 11, 'App\\Models\\Borrowing', '2026-01-13 18:32:23', '2026-01-13 18:40:30'),
(35, 6, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#11) dari Peminjam menunggu persetujuan.', 1, 11, 'App\\Models\\Borrowing', '2026-01-13 18:32:23', '2026-01-18 06:14:41'),
(36, 3, 'peminjaman_disetujui', 'Peminjaman Disetujui', 'Peminjaman Anda (#11) telah disetujui. Jatuh tempo: 18/01/2026', 1, 11, 'App\\Models\\Borrowing', '2026-01-13 18:32:41', '2026-01-18 21:17:04'),
(37, 1, 'pengembalian_peminjam', 'Pengembalian oleh Peminjam', 'Peminjaman (#8) dari Peminjam telah dikembalikan. Silakan proses pengembalian.', 1, 8, 'App\\Models\\Borrowing', '2026-01-14 17:47:27', '2026-01-18 18:43:33'),
(38, 2, 'pengembalian_peminjam', 'Pengembalian oleh Peminjam', 'Peminjaman (#8) dari Peminjam telah dikembalikan. Silakan proses pengembalian.', 1, 8, 'App\\Models\\Borrowing', '2026-01-14 17:47:27', '2026-01-14 17:48:44'),
(39, 6, 'pengembalian_peminjam', 'Pengembalian oleh Peminjam', 'Peminjaman (#8) dari Peminjam telah dikembalikan. Silakan proses pengembalian.', 1, 8, 'App\\Models\\Borrowing', '2026-01-14 17:47:27', '2026-01-18 06:14:41'),
(40, 3, 'pengembalian_diproses', 'Pengembalian Diproses', 'Pengembalian peminjaman (#8) telah diproses pada 15/01/2026. Denda: Rp 20.000 (4 hari terlambat)', 1, 5, 'App\\Models\\ReturnModel', '2026-01-14 17:47:28', '2026-01-18 21:17:04'),
(41, 1, 'pengembalian_peminjam', 'Pengembalian oleh Peminjam', 'Peminjaman (#11) dari Peminjam telah dikembalikan. Silakan proses pengembalian.', 1, 11, 'App\\Models\\Borrowing', '2026-01-14 18:14:46', '2026-01-18 18:43:33'),
(42, 2, 'pengembalian_peminjam', 'Pengembalian oleh Peminjam', 'Peminjaman (#11) dari Peminjam telah dikembalikan. Silakan proses pengembalian.', 1, 11, 'App\\Models\\Borrowing', '2026-01-14 18:14:46', '2026-01-18 20:59:01'),
(43, 6, 'pengembalian_peminjam', 'Pengembalian oleh Peminjam', 'Peminjaman (#11) dari Peminjam telah dikembalikan. Silakan proses pengembalian.', 1, 11, 'App\\Models\\Borrowing', '2026-01-14 18:14:46', '2026-01-18 06:14:41'),
(44, 3, 'pengembalian_diproses', 'Pengembalian Diproses', 'Pengembalian peminjaman (#11) telah diproses pada 15/01/2026.', 1, 6, 'App\\Models\\ReturnModel', '2026-01-14 18:14:46', '2026-01-18 21:17:04'),
(45, 1, 'pengembalian_peminjam', 'Pengembalian oleh Peminjam', 'Peminjaman (#10) dari Peminjam telah dikembalikan. Silakan proses pengembalian.', 1, 10, 'App\\Models\\Borrowing', '2026-01-14 18:39:27', '2026-01-18 18:43:33'),
(46, 2, 'pengembalian_peminjam', 'Pengembalian oleh Peminjam', 'Peminjaman (#10) dari Peminjam telah dikembalikan. Silakan proses pengembalian.', 1, 10, 'App\\Models\\Borrowing', '2026-01-14 18:39:27', '2026-01-18 20:59:01'),
(47, 6, 'pengembalian_peminjam', 'Pengembalian oleh Peminjam', 'Peminjaman (#10) dari Peminjam telah dikembalikan. Silakan proses pengembalian.', 1, 10, 'App\\Models\\Borrowing', '2026-01-14 18:39:27', '2026-01-18 06:14:41'),
(48, 3, 'pengembalian_diproses', 'Pengembalian Diproses', 'Pengembalian peminjaman (#10) telah diproses pada 15/01/2026.', 1, 7, 'App\\Models\\ReturnModel', '2026-01-14 18:40:14', '2026-01-18 21:17:04'),
(49, 1, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#12) dari Peminjam menunggu persetujuan.', 1, 12, 'App\\Models\\Borrowing', '2026-01-18 21:17:49', '2026-01-19 00:53:58'),
(50, 2, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#12) dari Peminjam menunggu persetujuan.', 0, 12, 'App\\Models\\Borrowing', '2026-01-18 21:17:49', '2026-01-18 21:17:49'),
(51, 6, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#12) dari Peminjam menunggu persetujuan.', 1, 12, 'App\\Models\\Borrowing', '2026-01-18 21:17:49', '2026-01-19 00:54:34'),
(52, 3, 'peminjaman_disetujui', 'Peminjaman Disetujui', 'Peminjaman Anda (#12) telah disetujui. Jatuh tempo: 22/01/2026', 1, 12, 'App\\Models\\Borrowing', '2026-01-18 21:18:08', '2026-01-18 23:03:38'),
(53, 1, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#13) dari Peminjam menunggu persetujuan.', 1, 13, 'App\\Models\\Borrowing', '2026-01-19 09:29:19', '2026-01-21 00:45:13'),
(54, 2, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#13) dari Peminjam menunggu persetujuan.', 0, 13, 'App\\Models\\Borrowing', '2026-01-19 09:29:19', '2026-01-19 09:29:19'),
(55, 6, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#13) dari Peminjam menunggu persetujuan.', 1, 13, 'App\\Models\\Borrowing', '2026-01-19 09:29:19', '2026-01-21 18:41:02'),
(56, 3, 'peminjaman_disetujui', 'Peminjaman Disetujui', 'Peminjaman Anda (#13) telah disetujui. Jatuh tempo: 22/01/2026', 0, 13, 'App\\Models\\Borrowing', '2026-01-19 09:29:54', '2026-01-19 09:29:54'),
(57, 1, 'pengembalian_peminjam', 'Pengembalian oleh Peminjam', 'Peminjaman (#9) dari Peminjam telah dikembalikan. Silakan proses pengembalian.', 1, 9, 'App\\Models\\Borrowing', '2026-01-19 09:36:36', '2026-01-21 00:45:13'),
(58, 2, 'pengembalian_peminjam', 'Pengembalian oleh Peminjam', 'Peminjaman (#9) dari Peminjam telah dikembalikan. Silakan proses pengembalian.', 0, 9, 'App\\Models\\Borrowing', '2026-01-19 09:36:36', '2026-01-19 09:36:36'),
(59, 6, 'pengembalian_peminjam', 'Pengembalian oleh Peminjam', 'Peminjaman (#9) dari Peminjam telah dikembalikan. Silakan proses pengembalian.', 1, 9, 'App\\Models\\Borrowing', '2026-01-19 09:36:36', '2026-01-21 18:41:02'),
(60, 3, 'pengembalian_diproses', 'Pengembalian Diproses', 'Pengembalian peminjaman (#9) telah diproses pada 19/01/2026. Denda: Rp 20.000 (4 hari terlambat)', 0, 8, 'App\\Models\\ReturnModel', '2026-01-19 09:37:07', '2026-01-19 09:37:07'),
(61, 1, 'pengembalian_peminjam', 'Pengembalian oleh Peminjam', 'Peminjaman (#12) dari Peminjam telah dikembalikan. Silakan proses pengembalian.', 1, 12, 'App\\Models\\Borrowing', '2026-01-19 18:51:00', '2026-01-21 00:45:13'),
(62, 2, 'pengembalian_peminjam', 'Pengembalian oleh Peminjam', 'Peminjaman (#12) dari Peminjam telah dikembalikan. Silakan proses pengembalian.', 0, 12, 'App\\Models\\Borrowing', '2026-01-19 18:51:00', '2026-01-19 18:51:00'),
(63, 6, 'pengembalian_peminjam', 'Pengembalian oleh Peminjam', 'Peminjaman (#12) dari Peminjam telah dikembalikan. Silakan proses pengembalian.', 1, 12, 'App\\Models\\Borrowing', '2026-01-19 18:51:00', '2026-01-21 18:41:02'),
(64, 3, 'pengembalian_diproses', 'Pengembalian Diproses', 'Pengembalian peminjaman (#12) telah diproses pada 20/01/2026.', 0, 9, 'App\\Models\\ReturnModel', '2026-01-19 18:51:23', '2026-01-19 18:51:23'),
(65, 12, 'alat_dibuat', 'Alat Baru Dibuat', 'Alat baru \'Remote\' telah ditambahkan oleh Admin.', 0, 10, 'App\\Models\\Tool', '2026-01-20 20:41:44', '2026-01-20 20:41:44'),
(66, 1, 'pengembalian_peminjam', 'Pengembalian oleh Peminjam', 'Peminjaman (#13) dari Peminjam telah dikembalikan. Silakan proses pengembalian.', 1, 13, 'App\\Models\\Borrowing', '2026-01-20 21:34:12', '2026-01-21 00:45:13'),
(67, 2, 'pengembalian_peminjam', 'Pengembalian oleh Peminjam', 'Peminjaman (#13) dari Peminjam telah dikembalikan. Silakan proses pengembalian.', 0, 13, 'App\\Models\\Borrowing', '2026-01-20 21:34:12', '2026-01-20 21:34:12'),
(68, 6, 'pengembalian_peminjam', 'Pengembalian oleh Peminjam', 'Peminjaman (#13) dari Peminjam telah dikembalikan. Silakan proses pengembalian.', 1, 13, 'App\\Models\\Borrowing', '2026-01-20 21:34:12', '2026-01-21 18:41:02'),
(69, 11, 'pengembalian_peminjam', 'Pengembalian oleh Peminjam', 'Peminjaman (#13) dari Peminjam telah dikembalikan. Silakan proses pengembalian.', 0, 13, 'App\\Models\\Borrowing', '2026-01-20 21:34:12', '2026-01-20 21:34:12'),
(70, 12, 'pengembalian_peminjam', 'Pengembalian oleh Peminjam', 'Peminjaman (#13) dari Peminjam telah dikembalikan. Silakan proses pengembalian.', 0, 13, 'App\\Models\\Borrowing', '2026-01-20 21:34:12', '2026-01-20 21:34:12'),
(71, 1, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#14) dari Peminjam menunggu persetujuan.', 1, 14, 'App\\Models\\Borrowing', '2026-01-20 21:49:19', '2026-01-21 00:45:13'),
(72, 2, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#14) dari Peminjam menunggu persetujuan.', 0, 14, 'App\\Models\\Borrowing', '2026-01-20 21:49:19', '2026-01-20 21:49:19'),
(73, 6, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#14) dari Peminjam menunggu persetujuan.', 1, 14, 'App\\Models\\Borrowing', '2026-01-20 21:49:19', '2026-01-21 18:41:02'),
(74, 11, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#14) dari Peminjam menunggu persetujuan.', 0, 14, 'App\\Models\\Borrowing', '2026-01-20 21:49:19', '2026-01-20 21:49:19'),
(75, 12, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#14) dari Peminjam menunggu persetujuan.', 0, 14, 'App\\Models\\Borrowing', '2026-01-20 21:49:19', '2026-01-20 21:49:19'),
(76, 1, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#15) dari Peminjam menunggu persetujuan.', 1, 15, 'App\\Models\\Borrowing', '2026-01-20 21:49:43', '2026-01-21 00:45:13'),
(77, 2, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#15) dari Peminjam menunggu persetujuan.', 0, 15, 'App\\Models\\Borrowing', '2026-01-20 21:49:43', '2026-01-20 21:49:43'),
(78, 6, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#15) dari Peminjam menunggu persetujuan.', 1, 15, 'App\\Models\\Borrowing', '2026-01-20 21:49:43', '2026-01-21 18:41:02'),
(79, 11, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#15) dari Peminjam menunggu persetujuan.', 0, 15, 'App\\Models\\Borrowing', '2026-01-20 21:49:43', '2026-01-20 21:49:43'),
(80, 12, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#15) dari Peminjam menunggu persetujuan.', 0, 15, 'App\\Models\\Borrowing', '2026-01-20 21:49:43', '2026-01-20 21:49:43'),
(81, 3, 'peminjaman_disetujui', 'Peminjaman Disetujui', 'Peminjaman Anda (#15) telah disetujui. Jatuh tempo: 22/01/2026', 0, 15, 'App\\Models\\Borrowing', '2026-01-20 21:50:37', '2026-01-20 21:50:37'),
(82, 3, 'peminjaman_ditolak', 'Peminjaman Ditolak', 'Peminjaman Anda (#14) telah ditolak. Alasan: Stok habis', 0, 14, 'App\\Models\\Borrowing', '2026-01-20 21:50:53', '2026-01-20 21:50:53'),
(83, 1, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#16) dari Yusuf menunggu persetujuan.', 1, 16, 'App\\Models\\Borrowing', '2026-01-21 23:07:34', '2026-01-25 21:24:44'),
(84, 2, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#16) dari Yusuf menunggu persetujuan.', 0, 16, 'App\\Models\\Borrowing', '2026-01-21 23:07:34', '2026-01-21 23:07:34'),
(85, 6, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#16) dari Yusuf menunggu persetujuan.', 1, 16, 'App\\Models\\Borrowing', '2026-01-21 23:07:34', '2026-01-21 23:40:57'),
(86, 11, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#16) dari Yusuf menunggu persetujuan.', 0, 16, 'App\\Models\\Borrowing', '2026-01-21 23:07:34', '2026-01-21 23:07:34'),
(87, 12, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#16) dari Yusuf menunggu persetujuan.', 0, 16, 'App\\Models\\Borrowing', '2026-01-21 23:07:34', '2026-01-21 23:07:34'),
(88, 13, 'peminjaman_baru', 'Peminjaman Baru', 'Peminjaman baru (#16) dari Yusuf menunggu persetujuan.', 0, 16, 'App\\Models\\Borrowing', '2026-01-21 23:07:34', '2026-01-21 23:07:34');

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

CREATE TABLE `returns` (
  `id` bigint UNSIGNED NOT NULL,
  `borrowing_id` bigint UNSIGNED NOT NULL,
  `tanggal_kembali` date NOT NULL,
  `denda` decimal(10,2) NOT NULL DEFAULT '0.00',
  `denda_kerusakan` decimal(10,2) NOT NULL DEFAULT '0.00',
  `terlambat_hari` int NOT NULL DEFAULT '0',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `returns`
--

INSERT INTO `returns` (`id`, `borrowing_id`, `tanggal_kembali`, `denda`, `denda_kerusakan`, `terlambat_hari`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 2, '2026-01-09', 0.00, 0.00, 0, 'Dikembalikan oleh peminjam', '2026-01-09 00:26:25', '2026-01-09 00:26:25'),
(2, 3, '2026-01-10', 0.00, 0.00, 0, NULL, '2026-01-10 07:54:01', '2026-01-10 07:54:01'),
(4, 7, '2026-01-11', 0.00, 0.00, 0, 'Dikembalikan oleh peminjam', '2026-01-10 17:14:30', '2026-01-10 17:14:30'),
(5, 8, '2026-01-15', 20000.00, 0.00, 4, 'Dikembalikan oleh peminjam', '2026-01-14 17:47:27', '2026-01-14 17:47:27'),
(6, 11, '2026-01-15', 0.00, 5000.00, 0, 'Dikembalikan oleh peminjam', '2026-01-14 18:14:46', '2026-01-14 18:58:37'),
(7, 10, '2026-01-15', 0.00, 15000.00, 0, NULL, '2026-01-14 18:40:14', '2026-01-18 21:13:03'),
(8, 9, '2026-01-19', 20000.00, 10000.00, 4, NULL, '2026-01-19 09:37:07', '2026-01-19 09:37:07'),
(9, 12, '2026-01-20', 0.00, 0.00, 0, NULL, '2026-01-19 18:51:23', '2026-01-19 18:51:23');

--
-- Triggers `returns`
--
DELIMITER $$
CREATE TRIGGER `update_stok_after_return` AFTER INSERT ON `returns` FOR EACH ROW BEGIN
                DECLARE done INT DEFAULT FALSE;
                DECLARE v_tool_id INT;
                DECLARE v_jumlah INT;
                
                DECLARE cur CURSOR FOR 
                    SELECT tool_id, jumlah 
                    FROM borrowing_details 
                    WHERE borrowing_id = NEW.borrowing_id;
                    
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
                
                OPEN cur;
                
                read_loop: LOOP
                    FETCH cur INTO v_tool_id, v_jumlah;
                    IF done THEN
                        LEAVE read_loop;
                    END IF;
                    
                    UPDATE tools 
                    SET stok = stok + v_jumlah 
                    WHERE id = v_tool_id;
                    
                END LOOP;
                
                CLOSE cur;
            END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
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
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('tOQoNcAUm7icxWjLoz3hoh2z0Q2shmAM4Q1L25hp', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiakRpZEFoMWUyWnV4QWJOWGo4WVczNTNCY1VGOUIwQm9rYU9wR29RdiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9yZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1769402705),
('ubdQFDDZuho21bDBSx6zgTKZB4c75C3Dunhbf8an', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ1ZJNFhqc3Q5a0ZRSks5T1lqQVRad0ZsenV2TVVLcXFibUxCQnF4RSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1769399642),
('xZdvxVIEkOPxluq0Ram4easPZIGwJSfGojg0kFYs', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQkYyN0cxYmVrZUk0bjBsV1hHSGdmM0wybW1JeUI2eXB3eG84RzVybSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9hY3Rpdml0eS1sb2dzIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1769406951);

-- --------------------------------------------------------

--
-- Table structure for table `tools`
--

CREATE TABLE `tools` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_alat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori_id` bigint UNSIGNED NOT NULL,
  `stok` int NOT NULL DEFAULT '0',
  `stok_rusak` int NOT NULL DEFAULT '0',
  `stok_perbaikan` int NOT NULL DEFAULT '0',
  `status` enum('tersedia','dipinjam','rusak','perbaikan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tersedia',
  `kondisi` enum('baik','rusak','perlu_perbaikan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'baik',
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tools`
--

INSERT INTO `tools` (`id`, `nama_alat`, `kategori_id`, `stok`, `stok_rusak`, `stok_perbaikan`, `status`, `kondisi`, `deskripsi`, `gambar`, `created_at`, `updated_at`) VALUES
(1, 'Solder', 4, 35, 0, 0, 'tersedia', 'baik', 'tes', 'images/tools/1768321291_⊱ ───ஓ๑♡๑ஓ ─── ⊰.jpeg', '2026-01-09 00:23:58', '2026-01-14 21:38:20'),
(2, 'Monitor', 1, 15, 0, 0, 'tersedia', 'baik', '--', NULL, '2026-01-11 23:49:50', '2026-01-19 09:37:07'),
(3, 'Mouse', 1, 50, 0, 0, 'tersedia', 'baik', '--', NULL, '2026-01-12 18:41:17', '2026-01-14 18:40:14'),
(4, 'Keyboard', 1, 39, 0, 0, 'tersedia', 'baik', '-', NULL, '2026-01-14 19:17:51', '2026-01-19 09:29:54'),
(5, 'Earphone', 5, 120, 0, 0, 'tersedia', 'baik', NULL, 'images/tools/1768795943_3d5c4ac6-8b0c-4d12-a138-a3ecf233c6ed.jpg', '2026-01-14 19:19:14', '2026-01-19 18:51:23'),
(6, 'Kipas', 5, 10, 0, 0, 'tersedia', 'baik', NULL, NULL, '2026-01-20 00:09:47', '2026-01-20 00:09:47'),
(8, 'Contoh Alat 1', 1, 10, 5, 2, 'tersedia', 'baik', NULL, NULL, '2026-01-20 00:34:53', '2026-01-20 22:34:08'),
(9, 'Contoh Alat 2', 2, 5, 2, 1, 'tersedia', 'baik', NULL, NULL, '2026-01-20 00:34:53', '2026-01-21 09:35:28'),
(10, 'Remote', 5, 0, 1, 0, 'dipinjam', 'baik', '-', NULL, '2026-01-20 20:41:44', '2026-01-20 21:50:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','petugas','peminjam') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'peminjam',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@gmail.com', NULL, '$2y$12$d2q6SKSz6GouFSHnmJDRXOFOR1IA4Tn1jL5ng4wYe5ZwCP2XGVWEO', 'admin', '7PIAs2nwL1REnwxUnkIdEn5W8n7Jumxohf6RrdYotGU8CQlmD9wIer4HW7v4', '2026-01-09 00:12:46', '2026-01-13 18:33:15'),
(2, 'Petugas', 'petugas@gmail.com', NULL, '$2y$12$iaGPN5p1S6SPGP4WgmuqCe5dWulDYdXQWC4W9CUQMAAMdJj66iPhq', 'petugas', 'USQTKkugMezucurhacNb3eGYDNFjBBpyFHR3QWaQyJnMKHAt3I08EWjGTHLr', '2026-01-09 00:12:46', '2026-01-13 18:33:30'),
(3, 'Peminjam', 'peminjam@gmail.com', NULL, '$2y$12$9cIPCZEtthD1SDG/FSFsiOCUqlZekSYNp/jJ.Aip9NkHncBoC950S', 'peminjam', 'UQWy0GkuHwaaLQsKtw0ZOwD9gqW7HtKV7Ms8W4ZWMq3UuldQcpBDrUHTvhBm', '2026-01-09 00:12:46', '2026-01-13 18:33:43'),
(5, 'Yusuf', 'yusuf@gmail.com', NULL, '$2y$12$DiSoLWMSOyLSrn5wDm11/OnsmBOtoDgadYiUeEyOQ8hTcKVsG/Zzi', 'peminjam', NULL, '2026-01-12 00:04:44', '2026-01-12 00:04:44'),
(6, 'Dedi', 'dedi@gmail.com', NULL, '$2y$12$kIpRdkNrqxXnU8YJA2b4wuCLikcHPHXlVN9zBcQLGSoFihbTznw8G', 'petugas', NULL, '2026-01-12 18:40:34', '2026-01-12 18:40:34'),
(7, 'Rendi', 'rendi@gmail.com', NULL, '$2y$12$vLzWNY1.zcVHRacvlex8pOZWb/TjX4aPnMQyDoyTrMU82KEqalK..', 'peminjam', NULL, '2026-01-13 18:08:19', '2026-01-13 18:08:19'),
(10, 'John Doe', 'john@example.com', NULL, '$2y$12$YqhI8WvBM1.BqDl4XIahieKfphfhlG3i6BqnI0N54s182aQS5bXYS', 'peminjam', NULL, '2026-01-20 00:35:40', '2026-01-20 00:35:40'),
(11, 'Jane Smith', 'jane@example.com', NULL, '$2y$12$j/PNblVdXBhulimK1veIZ.8XKeRrH4eK1efOgim0LGdkjLRo90Vlq', 'petugas', NULL, '2026-01-20 00:35:40', '2026-01-20 00:35:40'),
(12, 'Admin User', 'admin@example.com', NULL, '$2y$12$j16MFL1Ck14Tn/foyi.vFeLc8Ol92uaCqB2rNgmqPL0fIyDFpCl1O', 'admin', NULL, '2026-01-20 00:35:40', '2026-01-20 00:35:40'),
(13, 'Tes', 'tes@gmail.com', NULL, '$2y$12$Cf.m7swoZXKXxaiM4iDGI..bbBnU9wW5rulpZvipffIno/0VtggBW', 'petugas', NULL, '2026-01-21 09:34:57', '2026-01-21 09:34:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `borrowings`
--
ALTER TABLE `borrowings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `borrowings_user_id_foreign` (`user_id`);

--
-- Indexes for table `borrowing_details`
--
ALTER TABLE `borrowing_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `borrowing_details_borrowing_id_foreign` (`borrowing_id`),
  ADD KEY `borrowing_details_tool_id_foreign` (`tool_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
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
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `returns_borrowing_id_foreign` (`borrowing_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tools`
--
ALTER TABLE `tools`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tools_kategori_id_foreign` (`kategori_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=641;

--
-- AUTO_INCREMENT for table `borrowings`
--
ALTER TABLE `borrowings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `borrowing_details`
--
ALTER TABLE `borrowing_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tools`
--
ALTER TABLE `tools`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `borrowings`
--
ALTER TABLE `borrowings`
  ADD CONSTRAINT `borrowings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `borrowing_details`
--
ALTER TABLE `borrowing_details`
  ADD CONSTRAINT `borrowing_details_borrowing_id_foreign` FOREIGN KEY (`borrowing_id`) REFERENCES `borrowings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrowing_details_tool_id_foreign` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `returns`
--
ALTER TABLE `returns`
  ADD CONSTRAINT `returns_borrowing_id_foreign` FOREIGN KEY (`borrowing_id`) REFERENCES `borrowings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tools`
--
ALTER TABLE `tools`
  ADD CONSTRAINT `tools_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
