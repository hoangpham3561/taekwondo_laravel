-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql-taekwondo
-- Generation Time: Dec 28, 2025 at 06:36 AM
-- Server version: 8.0.42
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `taekwondo_club`
--

DELIMITER $$
--
-- Functions
--
CREATE DEFINER=`root`@`%` FUNCTION `generate_member_code` (`full_name` VARCHAR(255), `birth_date` DATE, `member_type` VARCHAR(10)) RETURNS VARCHAR(100) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci DETERMINISTIC READS SQL DATA BEGIN
    DECLARE last_name VARCHAR(100);
    DECLARE middle_names VARCHAR(255);
    DECLARE first_name VARCHAR(100);
    DECLARE name_parts TEXT;
    DECLARE name_count INT;
    DECLARE i INT DEFAULT 1;
    DECLARE result_code VARCHAR(100);
    DECLARE initials VARCHAR(10);
    
    -- Remove extra spaces and normalize name (remove multiple spaces)
    SET full_name = TRIM(full_name);
    -- Remove multiple spaces by replacing double spaces with single space
    WHILE full_name LIKE '%  %' DO
        SET full_name = REPLACE(full_name, '  ', ' ');
    END WHILE;
    SET name_parts = full_name;
    
    -- Count number of name parts
    SET name_count = (LENGTH(full_name) - LENGTH(REPLACE(full_name, ' ', '')) + 1);
    
    -- Extract last name (last part)
    SET last_name = SUBSTRING_INDEX(full_name, ' ', -1);
    SET last_name = LOWER(last_name);
    
    -- Extract middle names (all parts except first and last)
    SET middle_names = '';
    SET i = 1;
    WHILE i < name_count DO
        SET middle_names = CONCAT(middle_names, LOWER(LEFT(SUBSTRING_INDEX(SUBSTRING_INDEX(full_name, ' ', i + 1), ' ', -1), 1)));
        SET i = i + 1;
    END WHILE;
    
    -- Extract first name (first part)
    SET first_name = LOWER(LEFT(SUBSTRING_INDEX(full_name, ' ', 1), 1));
    
    -- Combine initials
    SET initials = CONCAT(first_name, middle_names);
    
    -- Generate final code: [PREFIX]_[last_name][initials]_[ddmmyy]
    SET result_code = CONCAT(
        member_type, '_',
        last_name, initials, '_',
        DATE_FORMAT(birth_date, '%d%m%y')
    );
    
    RETURN result_code;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','super_admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active_status` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bai_quyen`
--

CREATE TABLE `bai_quyen` (
  `id` int NOT NULL,
  `mo_ta` text COLLATE utf8mb4_unicode_ci,
  `so_dong_tac` int DEFAULT NULL,
  `thoi_gian_thuc_hien` int DEFAULT NULL,
  `khoi_luong_ly_thuyet` text COLLATE utf8mb4_unicode_ci,
  `ten_bai_quyen_vietnamese` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ten_bai_quyen_korean` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cap_do` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bai_quyen`
--

INSERT INTO `bai_quyen` (`id`, `mo_ta`, `so_dong_tac`, `thoi_gian_thuc_hien`, `khoi_luong_ly_thuyet`, `ten_bai_quyen_vietnamese`, `ten_bai_quyen_korean`, `cap_do`, `created_at`, `updated_at`) VALUES
(1, 'Bài quyền cơ bản đầu tiên, tượng trưng cho Trời', 20, 45, 'Lý thuyết về tư thế cơ bản và kỹ thuật đấm đá', 'Quyền số 1', '태극 1장 (Taegeuk Il-jang)', 'Cơ bản', '2025-11-30 09:09:49.490950', '2025-12-17 16:47:17.000000'),
(2, 'Bài quyền cơ bản thứ hai, tượng trưng cho Đất', 20, 45, 'Lý thuyết về di chuyển và phòng thủ', 'Quyền số 2', '태극 2장 (Taegeuk E-jang)', 'Cơ bản', '2025-11-30 09:09:49.490950', '2025-12-17 16:47:17.000000'),
(3, 'Bài quyền cơ bản thứ ba, tượng trưng cho Lửa', 20, 45, 'Lý thuyết về tấn công và phản công', 'Quyền số 3', '태극 3장 (Taegeuk Sam-jang)', 'Cơ bản', '2025-11-30 09:09:49.490950', '2025-12-17 16:47:17.000000'),
(4, 'Bài quyền cơ bản thứ tư, tượng trưng cho Gió', 20, 45, 'Lý thuyết về tốc độ và linh hoạt', 'Quyền số 4', '태극 4장 (Taegeuk Sa-jang)', 'Cơ bản', '2025-11-30 09:09:49.490950', '2025-12-17 16:47:17.000000'),
(5, 'Bài quyền cơ bản thứ năm, tượng trưng cho Nước', 20, 45, 'Lý thuyết về sự mềm mại và uyển chuyển', 'Quyền số 5', '태극 5장 (Taegeuk Oh-jang)', 'Trung cấp', '2025-11-30 09:09:49.490950', '2025-12-17 16:47:17.000000'),
(6, 'Bài quyền cơ bản thứ sáu, tượng trưng cho Sơn', 20, 45, 'Lý thuyết về sự vững chắc và ổn định', 'Quyền số 6', '태극 6장 (Taegeuk Yook-jang)', 'Trung cấp', '2025-11-30 09:09:49.490950', '2025-12-17 16:47:17.000000'),
(7, 'Bài quyền cơ bản thứ bảy, tượng trưng cho Lôi', 20, 45, 'Lý thuyết về sức mạnh và bùng nổ', 'Quyền số 7', '태극 7장 (Taegeuk Chil-jang)', 'Trung cấp', '2025-11-30 09:09:49.490950', '2025-12-17 16:47:17.000000'),
(8, 'Bài quyền cơ bản thứ tám, tượng trưng cho Phong', 20, 45, 'Lý thuyết về sự nhẹ nhàng và bay bổng', 'Quyền số 8', '태극 8장 (Taegeuk Pal-jang)', 'Trung cấp', '2025-11-30 09:09:49.490950', '2025-12-17 16:47:17.000000'),
(9, 'Bài quyền đai đen đầu tiên, tên của triều đại Koryo', 30, 60, 'Lý thuyết về lịch sử và truyền thống', 'Quyền số 9', '고려 (Koryo)', 'Nâng cao', '2025-11-30 09:09:49.490950', '2025-12-17 16:47:17.000000'),
(10, 'Bài quyền đai đen thứ hai, tên của ngọn núi Keumgang', 27, 55, 'Lý thuyết về sự kiên cường và bền bỉ', 'Quyền số 10', '금강 (Keumgang)', 'Nâng cao', '2025-11-30 09:09:49.490950', '2025-12-17 16:47:17.000000'),
(11, 'Bài quyền đai đen thứ ba, tên của ngọn núi Taebaek', 26, 50, 'Lý thuyết về sự cao quý và thanh khiết', 'Quyền số 11', '태백 (Taebaek)', 'Nâng cao', '2025-11-30 09:09:49.490950', '2025-12-17 16:47:17.000000'),
(12, 'Bài quyền đai đen thứ tư, tên của đồng bằng', 21, 45, 'Lý thuyết về sự rộng lớn và bao dung', 'Quyền số 12', '평원 (Pyongwon)', 'Nâng cao', '2025-11-30 09:09:49.490950', '2025-12-17 16:47:17.000000'),
(13, 'Bài quyền đai đen thứ năm, tên của số 10', 28, 55, 'Lý thuyết về sự hoàn thiện và toàn diện', 'Quyền số 13', '십진 (Sipjin)', 'Nâng cao', '2025-11-30 09:09:49.490950', '2025-12-17 16:47:17.000000'),
(14, 'Bài quyền đai đen thứ sáu, tên của Trái Đất', 28, 55, 'Lý thuyết về sự ổn định và vững chắc', 'Quyền số 14', '지태 (Jitae)', 'Nâng cao', '2025-11-30 09:09:49.490950', '2025-12-17 16:47:17.000000'),
(15, 'Bài quyền đai đen thứ bảy, tên của Bầu trời', 26, 50, 'Lý thuyết về sự cao xa và vô tận', 'Quyền số 15', '천권 (Chonkwon)', 'Nâng cao', '2025-11-30 09:09:49.490950', '2025-12-17 16:47:17.000000'),
(16, 'Bài quyền đai đen thứ tám, tên của Nước', 27, 55, 'Lý thuyết về sự linh hoạt và thích ứng', 'Quyền số 16', '한수 (Hanso)', 'Nâng cao', '2025-11-30 09:09:49.490950', '2025-12-17 16:47:17.000000'),
(17, 'Bài quyền đai đen thứ chín, tên của Sự thống nhất', 23, 45, 'Lý thuyết về sự hòa hợp và nhất thể', 'Quyền số 17', '일여 (Ilyeo)', 'Nâng cao', '2025-11-30 09:09:49.490950', '2025-12-17 16:47:17.000000'),
(18, 'Bài quyền kĩ thuật cơ bản đầu tiên cho cấp đai trắng', 15, 30, 'Lý thuyết về kĩ thuật cơ bản', '', NULL, '', '2025-11-30 09:09:49.490950', '2025-11-30 09:09:49.526727'),
(19, 'Bài quyền kĩ thuật cơ bản thứ hai cho cấp đai cam', 18, 35, 'Lý thuyết về kĩ thuật cơ bản nâng cao', '', NULL, '', '2025-11-30 09:09:49.490950', '2025-11-30 09:09:49.526727');

-- --------------------------------------------------------

--
-- Table structure for table `cap_dai`
--

CREATE TABLE `cap_dai` (
  `id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_sequence` int DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cap_dai`
--

INSERT INTO `cap_dai` (`id`, `name`, `color`, `order_sequence`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Cấp 10', 'White', 1, 'Đai trắng cấp 10', '2025-11-09 14:17:53.000000', '2025-11-15 15:42:21.000000'),
(2, 'Cấp 9', '#ff7300', 2, 'Đai cam cấp 9', '2025-11-09 14:17:53.000000', '2025-12-20 09:13:30.000000'),
(3, 'Cấp 8', 'Violet', 3, 'Đai tím cấp 8', '2025-11-09 14:17:53.000000', '2025-11-15 15:42:21.000000'),
(4, 'Cấp 7', 'Yellow', 4, 'Đai vàng cấp 7', '2025-11-09 14:17:53.000000', '2025-11-15 15:42:21.000000'),
(5, 'Cấp 6', 'Green', 5, 'Đai xanh lá cấp 6', '2025-11-09 14:17:53.000000', '2025-11-15 15:42:21.000000'),
(6, 'Cấp 5', 'Blue', 6, 'Đai xanh dương cấp 5', '2025-11-09 14:17:53.000000', '2025-11-15 15:42:21.000000'),
(7, 'Cấp 4', 'Red', 7, 'Đai đỏ cấp 4', '2025-11-09 14:17:53.000000', '2025-11-15 15:42:21.000000'),
(8, 'Cấp 3', '#ff0000', 8, 'Đai đỏ đen cấp 3', '2025-11-09 14:17:53.000000', '2025-12-20 09:12:46.000000'),
(9, 'Cấp 2', '#ff0000', 9, 'Đai đỏ đen cấp 2', '2025-11-09 14:17:53.000000', '2025-12-20 09:12:36.000000'),
(10, 'Cấp 1', '#ff0000', 10, 'Đai đỏ đen cấp 1', '2025-11-09 14:17:53.000000', '2025-12-20 09:12:55.000000'),
(11, 'Nhất đẳng (1 Dan)', 'Black', 11, 'Đai đen 1 đẳng', '2025-11-09 14:17:53.000000', '2025-11-15 15:42:21.000000'),
(12, 'Nhị đẳng (2 Dan)', 'Black', 12, 'Đai đen 2 đẳng', '2025-11-09 14:17:53.000000', '2025-11-15 15:42:21.000000'),
(13, 'Tam đẳng (3 Dan)', 'Black', 13, 'Đai đen 3 đẳng', '2025-11-09 14:17:53.000000', '2025-11-15 15:42:21.000000'),
(14, 'Tứ đẳng (4 Dan)', 'Black', 14, 'Đai đen 4 đẳng', '2025-11-09 14:17:53.000000', '2025-11-15 15:42:21.000000'),
(15, 'Ngũ đẳng (5 Dan)', 'Black', 15, 'Đai đen 5 đẳng', '2025-11-09 14:17:53.000000', '2025-11-15 15:42:21.000000'),
(16, 'Lục đẳng (6 Dan)', 'Black', 16, 'Đai đen 6 đẳng', '2025-11-09 14:17:53.000000', '2025-11-15 15:42:21.000000'),
(17, 'Thất đẳng (7 Dan)', 'Black', 17, 'Đai đen 7 đẳng', '2025-11-09 14:17:53.000000', '2025-11-15 15:42:21.000000'),
(18, 'Bát đẳng (8 Dan)', 'Black', 18, 'Đai đen 8 đẳng', '2025-11-09 14:17:53.000000', '2025-11-15 15:42:21.000000'),
(19, 'Cửu đẳng (9 Dan)', 'Black', 19, 'Đai đen 9 đẳng', '2025-11-09 14:17:53.000000', '2025-11-15 15:42:21.000000'),
(20, 'Thập đẳng (10 Dan)', 'Black', 20, 'Đai đen 10 đẳng', '2025-11-09 14:17:53.000000', '2025-11-15 15:42:21.000000');

-- --------------------------------------------------------

--
-- Table structure for table `cap_dai_bai_quyen`
--

CREATE TABLE `cap_dai_bai_quyen` (
  `id` int NOT NULL,
  `cap_dai_id` int NOT NULL,
  `bai_quyen_id` int NOT NULL,
  `loai_quyen` enum('bat_buoc','tu_chon','bo_sung') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bat_buoc',
  `thu_tu_uu_tien` int NOT NULL DEFAULT '1',
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cap_dai_bai_quyen`
--

INSERT INTO `cap_dai_bai_quyen` (`id`, `cap_dai_id`, `bai_quyen_id`, `loai_quyen`, `thu_tu_uu_tien`, `created_at`) VALUES
(18, 1, 18, 'bat_buoc', 1, '2025-11-30 09:09:49.629411'),
(19, 2, 19, 'bat_buoc', 1, '2025-11-30 09:09:49.629411'),
(20, 3, 1, 'bat_buoc', 1, '2025-11-30 09:09:49.629411'),
(21, 4, 2, 'bat_buoc', 1, '2025-11-30 09:09:49.629411'),
(22, 5, 3, 'bat_buoc', 1, '2025-11-30 09:09:49.629411'),
(23, 6, 4, 'bat_buoc', 1, '2025-11-30 09:09:49.629411'),
(24, 7, 5, 'bat_buoc', 1, '2025-11-30 09:09:49.629411'),
(25, 8, 6, 'bat_buoc', 1, '2025-11-30 09:09:49.629411'),
(26, 9, 7, 'bat_buoc', 1, '2025-11-30 09:09:49.629411'),
(27, 10, 8, 'bat_buoc', 1, '2025-11-30 09:09:49.629411'),
(28, 11, 9, 'bat_buoc', 1, '2025-11-30 09:09:49.629411'),
(29, 12, 10, 'bat_buoc', 1, '2025-11-30 09:09:49.629411'),
(30, 13, 11, 'bat_buoc', 1, '2025-11-30 09:09:49.629411'),
(31, 14, 12, 'bat_buoc', 1, '2025-11-30 09:09:49.629411'),
(32, 15, 13, 'bat_buoc', 1, '2025-11-30 09:09:49.629411'),
(33, 16, 14, 'bat_buoc', 1, '2025-11-30 09:09:49.629411'),
(34, 17, 15, 'bat_buoc', 1, '2025-11-30 09:09:49.629411'),
(35, 18, 16, 'bat_buoc', 1, '2025-11-30 09:09:49.629411'),
(36, 19, 17, 'bat_buoc', 1, '2025-11-30 09:09:49.629411'),
(37, 20, 17, 'bat_buoc', 1, '2025-11-30 09:09:49.629411');

-- --------------------------------------------------------

--
-- Table structure for table `cau_lac_bo`
--

CREATE TABLE `cau_lac_bo` (
  `id` int NOT NULL,
  `club_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `head_coach_id` int DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `logo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cau_lac_bo`
--

INSERT INTO `cau_lac_bo` (`id`, `club_code`, `name`, `address`, `phone`, `email`, `head_coach_id`, `description`, `logo_url`, `created_at`, `updated_at`) VALUES
(1, '_00468', 'CLB Đồng Phú', 'Đồng Phú, Bình Phước', '0123456789', 'dongphu@taekwondo.com', 1, 'CLB Taekwondo Đồng Phú - Thầy Tiến HLV trưởng', NULL, '2025-11-09 14:17:53.000000', '2025-11-09 14:17:53.000000');

-- --------------------------------------------------------

--
-- Table structure for table `chi_nhanh`
--

CREATE TABLE `chi_nhanh` (
  `id` int NOT NULL,
  `club_id` int NOT NULL,
  `branch_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chi_nhanh`
--

INSERT INTO `chi_nhanh` (`id`, `club_id`, `branch_code`, `name`, `address`, `phone`, `email`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'GXTN', 'CLB Giáo Xứ Tân Lập', 'Giáo Xứ Tân Lập, Đồng Phú', '0123456781', 'gxtn@dongphu.com', 1, '2025-11-09 14:17:53.000000', '2025-11-09 14:17:53.000000'),
(2, 1, 'THTN', 'CLB Tiểu Học Tân Lập', 'Trường Tiểu Học Tân Lập, Đồng Phú', '0123456782', 'thtn@dongphu.com', 1, '2025-11-09 14:17:53.000000', '2025-12-18 03:13:55.000000'),
(3, 1, 'THTT', 'CLB Tiểu Học Tân Tiến', 'Trường Tiểu Học Tân Tiến, Đồng Phú', '0123456783', 'thtt@dongphu.com', 1, '2025-11-09 14:17:53.000000', '2025-11-09 14:17:53.000000'),
(4, 1, 'THDP', 'CLB Tân Lợi ', 'CLB Tân Lợi ', '0123456784', 'thdp@dongphu.com', 1, '2025-11-09 14:17:53.000000', '2025-12-18 03:14:16.000000'),
(5, 1, 'THTP', 'CLB Tiểu Học Tân Phú', 'Trường Tiểu Học Tân Phú, Đồng Phú', '0123456785', 'thtp@dongphu.com', 1, '2025-11-09 14:17:53.000000', '2025-11-09 14:17:53.000000'),
(6, 1, 'THTD', 'CLB Tiểu Học Tân Lập B', 'Trường Học Tân Lập B, Đồng Phú', '0123456786', 'thtd@dongphu.com', 1, '2025-11-09 14:17:53.000000', '2025-11-09 14:17:53.000000');

-- --------------------------------------------------------

--
-- Table structure for table `chi_tiet_thanh_toan`
--

CREATE TABLE `chi_tiet_thanh_toan` (
  `id` int NOT NULL,
  `payment_id` int DEFAULT NULL,
  `tuition_package_id` int DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `final_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','bank_transfer','card','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `transaction_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chung_chi`
--

CREATE TABLE `chung_chi` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `belt_level_id` int DEFAULT NULL,
  `certificate_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issue_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `issued_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `certificate_image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_valid` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dang_ky_hoc`
--

CREATE TABLE `dang_ky_hoc` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `course_id` int DEFAULT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `enrolled_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `approved_at` datetime DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dang_ky_thi`
--

CREATE TABLE `dang_ky_thi` (
  `id` int NOT NULL,
  `test_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `current_belt_id` int DEFAULT NULL,
  `target_belt_id` int DEFAULT NULL,
  `registration_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_status` enum('paid','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `test_result` enum('pass','fail','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `score` decimal(5,2) DEFAULT NULL,
  `examiner_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `danh_gia_hoc_vien`
--

CREATE TABLE `danh_gia_hoc_vien` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `coach_id` int DEFAULT NULL,
  `course_id` int DEFAULT NULL,
  `evaluation_date` date DEFAULT NULL,
  `technique_score` decimal(3,1) DEFAULT NULL,
  `attitude_score` decimal(3,1) DEFAULT NULL,
  `progress_score` decimal(3,1) DEFAULT NULL,
  `overall_score` decimal(3,1) DEFAULT NULL,
  `comments` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `danh_gia_phan_hoi`
--

CREATE TABLE `danh_gia_phan_hoi` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `course_id` int DEFAULT NULL,
  `coach_id` int DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `feedback_type` enum('course','coach','facility','general') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_anonymous` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ;

-- --------------------------------------------------------

--
-- Table structure for table `diem_danh`
--

CREATE TABLE `diem_danh` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `course_id` int DEFAULT NULL,
  `attendance_date` date DEFAULT NULL,
  `status` enum('present','absent','late','excused') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'present',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goi_hoc_phi`
--

CREATE TABLE `goi_hoc_phi` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `duration_months` int DEFAULT NULL,
  `classes_per_week` int DEFAULT NULL,
  `club_id` int DEFAULT NULL,
  `is_active` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hoc_vien_phu_huynh`
--

CREATE TABLE `hoc_vien_phu_huynh` (
  `id` int NOT NULL,
  `student_id` int DEFAULT NULL,
  `parent_id` int DEFAULT NULL,
  `is_primary` tinyint NOT NULL DEFAULT '0',
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `huan_luyen_vien`
--

CREATE TABLE `huan_luyen_vien` (
  `id` int NOT NULL,
  `ma_hoi_vien` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã hội viên HLV',
  `ho_va_ten` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Họ và tên đầy đủ',
  `ngay_thang_nam_sinh` date DEFAULT NULL COMMENT 'Ngày tháng năm sinh',
  `ma_clb` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã câu lạc bộ',
  `ma_don_vi` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã đơn vị',
  `quyen_so` int DEFAULT NULL COMMENT 'Quyền số',
  `cap_dai_id` int DEFAULT NULL,
  `gioi_tinh` enum('Nam','Nữ') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Giới tính',
  `photo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `images` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('owner','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `experience_years` int DEFAULT NULL,
  `specialization` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `address` text COLLATE utf8mb4_unicode_ci,
  `emergency_contact_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên người liên hệ khẩn cấp',
  `emergency_contact_phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại liên hệ khẩn cấp',
  `is_active` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `huan_luyen_vien`
--

INSERT INTO `huan_luyen_vien` (`id`, `ma_hoi_vien`, `ho_va_ten`, `ngay_thang_nam_sinh`, `ma_clb`, `ma_don_vi`, `quyen_so`, `cap_dai_id`, `gioi_tinh`, `photo_url`, `images`, `phone`, `email`, `password`, `role`, `experience_years`, `specialization`, `bio`, `address`, `emergency_contact_name`, `emergency_contact_phone`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'HLV_tiếntvt_150385', 'Trần Văn Tiến', '1985-03-15', 'CLB_00468', 'DNAI', 9, 12, 'Nam', 'client/images/users/1764349251409-290991075.jpg', '[\"client/images/users/1764349251409-290991075.jpg\"]', '0987654321', 'thaytien@dongphu.com', '123456@LV23', 'owner', NULL, '', '', '', NULL, NULL, 1, '2025-11-09 14:17:53.000000', '2025-11-28 17:00:51.000000'),
(2, 'HLV_hươngtth_220790', 'Trần Thị Hương', '1990-07-22', 'CLB_00468', 'DNAI', 8, 10, 'Nữ', 'client/images/users/user-40.jpg', '[\"client/images/users/user-40.jpg\"]', '0987654322', 'huongtt@dongphu.com', '123456@LV23', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-11-09 14:17:53.000000', '2025-11-09 14:17:53.000000'),
(3, 'HLV_đứclmđ_101288', 'Lê Minh Đức', '1988-12-10', 'CLB_00468', 'DNAI', 7, 8, 'Nam', 'client/images/users/user-40.jpg', '[\"client/images/users/user-40.jpg\"]', '0987654323', 'duclm@dongphu.com', '123456@LV23', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-11-09 14:17:53.000000', '2025-11-09 14:17:53.000000'),
(4, 'HLV_tânđtt_180587', 'Đoàn Tiến Tân', '1987-05-18', 'CLB_00468', 'DNAI', 8, 10, 'Nam', 'client/images/users/user-40.jpg', '[\"client/images/users/user-40.jpg\"]', '0987654324', 'thaytan@dongphu.com', '123456@LV23', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-11-09 14:17:53.000000', '2025-11-09 14:17:53.000000'),
(5, 'HV_sangdnm_17072007', 'Đào Nguyễn Minh Sang', NULL, NULL, NULL, NULL, 11, NULL, NULL, NULL, NULL, NULL, NULL, 'admin', 10, NULL, NULL, NULL, NULL, NULL, 1, '2025-12-18 03:02:38.343818', '2025-12-18 03:02:38.343818');

-- --------------------------------------------------------

--
-- Table structure for table `ket_qua_thi`
--

CREATE TABLE `ket_qua_thi` (
  `id` int NOT NULL,
  `test_id` int DEFAULT NULL COMMENT 'ID kỳ thi',
  `ma_clb` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã CLB',
  `user_id` int DEFAULT NULL COMMENT 'ID võ sinh',
  `ma_hoi_vien` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã hội viên',
  `cap_dai_du_thi_id` int DEFAULT NULL COMMENT 'Cấp đai dự thi',
  `so_thi` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số thi',
  `ho_va_ten` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Họ và tên',
  `gioi_tinh` enum('Nam','Nữ') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Giới tính',
  `ngay_thang_nam_sinh` date DEFAULT NULL COMMENT 'Ngày tháng năm sinh',
  `ky_thuat_tan_can_ban` decimal(5,2) DEFAULT NULL COMMENT 'Kỹ thuật tấn căn bản',
  `nguyen_tac_phat_luc` decimal(5,2) DEFAULT NULL COMMENT 'Nguyên tắc phát lực',
  `can_ban_tay` decimal(5,2) DEFAULT NULL COMMENT 'Căn bản tay',
  `ky_thuat_chan` decimal(5,2) DEFAULT NULL COMMENT 'Kỹ thuật chân',
  `can_ban_tu_ve` decimal(5,2) DEFAULT NULL COMMENT 'Căn bản tự vệ',
  `bai_quyen` decimal(5,2) DEFAULT NULL COMMENT 'Bài quyền',
  `phan_the_bai_quyen` decimal(5,2) DEFAULT NULL COMMENT 'Phân thế bài quyền',
  `song_dau` decimal(5,2) DEFAULT NULL COMMENT 'Song đấu',
  `the_luc` decimal(5,2) DEFAULT NULL COMMENT 'Thể lực',
  `ket_qua` enum('Đạt','Không đạt','Chưa có kết quả') COLLATE utf8mb4_unicode_ci DEFAULT 'Chưa có kết quả' COMMENT 'Kết quả',
  `ghi_chu` text COLLATE utf8mb4_unicode_ci COMMENT 'Ghi chú',
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ket_qua_thi`
--

INSERT INTO `ket_qua_thi` (`id`, `test_id`, `ma_clb`, `user_id`, `ma_hoi_vien`, `cap_dai_du_thi_id`, `so_thi`, `ho_va_ten`, `gioi_tinh`, `ngay_thang_nam_sinh`, `ky_thuat_tan_can_ban`, `nguyen_tac_phat_luc`, `can_ban_tay`, `ky_thuat_chan`, `can_ban_tu_ve`, `bai_quyen`, `phan_the_bai_quyen`, `song_dau`, `the_luc`, `ket_qua`, `ghi_chu`, `created_at`, `updated_at`) VALUES
(1, NULL, 'CLB_00468', 7, 'HV_giangnth_090919', 9, '9001', 'Nguyễn Thị Hương Giang', 'Nam', '2019-09-08', 72.00, 72.00, 73.00, 74.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2019', '2025-12-17 09:58:44.080207', '2025-12-17 09:58:44.080207'),
(2, NULL, 'CLB_00468', 8, 'HV_myptt_170919', 9, '9002', 'Phạm Trần Thảo My', 'Nam', '2019-09-16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Chưa có kết quả', '2019', '2025-12-17 09:58:44.178741', '2025-12-17 09:58:44.178741'),
(3, NULL, 'CLB_00468', 9, 'HV_napa_050419', 9, '9003', 'Phạm An Na', 'Nam', '2019-04-04', 71.00, 72.00, 73.00, 75.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2019', '2025-12-17 09:58:44.317144', '2025-12-17 09:58:44.317144'),
(4, NULL, 'CLB_00468', 10, 'HV_truchtt_070318', 9, '9004', 'Hà Thị Thanh Trúc', 'Nam', '2018-03-06', 72.00, 73.00, 72.00, 73.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2018', '2025-12-17 09:58:44.397534', '2025-12-17 09:58:44.397534'),
(5, NULL, 'CLB_00468', 11, 'HV_myly_030218', 9, '9005', 'Lại Yến My', 'Nam', '2018-02-02', 74.00, 72.00, 72.00, 73.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2018', '2025-12-17 09:58:44.704132', '2025-12-17 09:58:44.704132'),
(6, NULL, 'CLB_00468', 12, 'HV_nganpk_011118', 9, '9006', 'Phan Khánh Ngân', 'Nam', '2018-10-31', 72.00, 72.00, 71.00, 73.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2018', '2025-12-17 09:58:44.785458', '2025-12-17 09:58:44.785458'),
(7, NULL, 'CLB_00468', 13, 'HV_nganvtb_020418', 9, '9007', 'Vũ Thị Bích Ngân', 'Nam', '2018-04-01', 71.00, 73.00, 72.00, 74.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2018', '2025-12-17 09:58:44.819928', '2025-12-17 09:58:44.819928'),
(8, NULL, 'CLB_00468', 14, 'HV_hadtn_031017', 9, '9008', 'Đoàn Thị Ngân Hà', 'Nam', '2017-10-02', 73.00, 74.00, 75.00, 72.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2017', '2025-12-17 09:58:44.856404', '2025-12-17 09:58:44.856404'),
(9, NULL, 'CLB_00468', 15, 'HV_trucvtt_210817', 9, '9009', 'Vũ Thị Thanh Trúc', 'Nam', '2017-08-20', 74.00, 75.00, 73.00, 75.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2017', '2025-12-17 09:58:44.891880', '2025-12-17 09:58:44.891880'),
(10, NULL, 'CLB_00468', 16, 'HV_nhiny_310517', 9, '9010', 'Ngọ Yến Nhi', 'Nam', '2017-05-30', 73.00, 72.00, 73.00, 73.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2017', '2025-12-17 09:58:44.931014', '2025-12-17 09:58:44.931014'),
(11, NULL, 'CLB_00468', 17, 'HV_chinnq_241017', 9, '9011', 'Nguyễn Ngọc Quế Chi', 'Nam', '2017-10-23', 72.00, 73.00, 74.00, 73.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2017', '2025-12-17 09:58:44.961102', '2025-12-17 09:58:44.961102'),
(12, NULL, 'CLB_00468', 18, 'HV_chipa_180517', 9, '9012', 'Phạm An Chi', 'Nam', '2017-05-17', 73.00, 75.00, 75.00, 75.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2017', '2025-12-17 09:58:44.993320', '2025-12-17 09:58:44.993320'),
(13, NULL, 'CLB_00468', 19, 'HV_ngandtk_140116', 9, '9013', 'Đỗ Thị Kim Ngân', 'Nam', '2016-01-13', 74.00, 73.00, 73.00, 74.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2016', '2025-12-17 09:58:45.035972', '2025-12-17 09:58:45.035972'),
(14, NULL, 'CLB_00468', 20, 'HV_thuyly_090216', 9, '9014', 'Lại Yến Thủy', 'Nam', '2016-02-08', 73.00, 72.00, 73.00, 72.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2016', '2025-12-17 09:58:45.072727', '2025-12-17 09:58:45.072727'),
(15, NULL, 'CLB_00468', 21, 'HV_phuongktb_161115', 9, '9015', 'Khấu Thị Bích Phượng', 'Nam', '2015-11-15', 72.00, 73.00, 74.00, 75.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2015', '2025-12-17 09:58:45.105591', '2025-12-17 09:58:45.105591'),
(16, NULL, 'CLB_00468', 22, 'HV_hienttt_170115', 9, '9016', 'Trần Thị Thanh Hiền', 'Nam', '2015-01-16', 72.00, 73.00, 73.00, 74.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2015', '2025-12-17 09:58:45.134126', '2025-12-17 09:58:45.134126'),
(17, NULL, 'CLB_00468', 23, 'HV_nguyenltt_101115', 9, '9017', 'Lê Trịnh Thảo Nguyên', 'Nam', '2015-11-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Chưa có kết quả', '2015', '2025-12-17 09:58:45.154789', '2025-12-17 09:58:45.154789'),
(18, NULL, 'CLB_00468', 24, 'HV_nhitny_171114', 9, '9018', 'Trần Ngọc Yến Nhi', 'Nam', '2014-11-16', 74.00, 75.00, 73.00, 74.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2014', '2025-12-17 09:58:45.177156', '2025-12-17 09:58:45.177156'),
(19, NULL, 'CLB_00468', 25, 'HV_ngocbb_291014', 9, '9019', 'Bùi Bảo Ngọc', 'Nam', '2014-10-28', 72.00, 74.00, 73.00, 74.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2014', '2025-12-17 09:58:45.200486', '2025-12-17 09:58:45.200486'),
(20, NULL, 'CLB_00468', 26, 'HV_thaodth_141014', 9, '9020', 'Đặng Thị Hương Thảo', 'Nam', '2014-10-13', 73.00, 74.00, 72.00, 73.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2014', '2025-12-17 09:58:45.222238', '2025-12-17 09:58:45.222238'),
(21, NULL, 'CLB_00468', 27, 'HV_oanhqtt_170713', 9, '9021', 'Quách Thị Thúy Oanh', 'Nam', '2013-07-16', 72.00, 73.00, 74.00, 73.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2013', '2025-12-17 09:58:45.246559', '2025-12-17 09:58:45.246559'),
(22, NULL, 'CLB_00468', 28, 'HV_uyenlhn_140313', 9, '9022', 'Lê Huỳnh Nhã Uyên', 'Nam', '2013-03-13', 72.00, 74.00, 73.00, 75.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2013', '2025-12-17 09:58:45.306760', '2025-12-17 09:58:45.306760'),
(23, NULL, 'CLB_00468', 29, 'HV_vynnp_030713', 9, '9023', 'Nguyễn Ngọc Phương Vy', 'Nam', '2013-07-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Chưa có kết quả', '2013', '2025-12-17 09:58:45.336396', '2025-12-17 09:58:45.336396'),
(24, NULL, 'CLB_00468', 30, 'HV_mittt_010413', 9, '9024', 'Trần Thị Trà Mi', 'Nam', '2013-03-31', 73.00, 74.00, 74.00, 73.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2013', '2025-12-17 09:58:45.372618', '2025-12-17 09:58:45.372618'),
(25, NULL, 'CLB_00468', 31, 'HV_truc(1)nnt_180313', 9, '9025', 'Nguyễn Ngọc Thanh Trúc(1)', 'Nam', '2013-03-17', 74.00, 75.00, 76.00, 74.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2013', '2025-12-17 09:58:45.403202', '2025-12-17 09:58:45.403202'),
(26, NULL, 'CLB_00468', 32, 'HV_linhnnt_070711', 9, '9026', 'Nguyễn Ngọc Thùy Linh', 'Nam', '2011-07-06', 74.00, 73.00, 76.00, 75.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2011', '2025-12-17 09:58:45.440695', '2025-12-17 09:58:45.440695'),
(27, NULL, 'CLB_00468', 33, 'HV_quynhndn_161111', 9, '9027', 'Nguyễn Đỗ Như Quỳnh', 'Nam', '2011-11-15', 72.00, 75.00, 73.00, 73.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2011', '2025-12-17 09:58:45.474832', '2025-12-17 09:58:45.474832'),
(28, NULL, 'CLB_00468', 34, 'HV_chamqb_260110', 9, '9028', 'Quách Bảo Châm', 'Nam', '2010-01-25', 73.00, 74.00, 72.00, 74.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2010', '2025-12-17 09:58:45.496565', '2025-12-17 09:58:45.496565'),
(29, NULL, 'CLB_00468', 35, 'HV_anhltq_180610', 9, '9029', 'Long Thị Quỳnh Anh', 'Nam', '2010-06-17', 72.00, 73.00, 71.00, 75.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2010', '2025-12-17 09:58:45.579588', '2025-12-17 09:58:45.579588'),
(30, NULL, 'CLB_00468', 36, 'HV_andtt_240910', 9, '9030', 'Đoàn Thị Thúy An', 'Nam', '2010-09-23', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Chưa có kết quả', '2010', '2025-12-17 09:58:45.598524', '2025-12-17 09:58:45.598524'),
(31, NULL, 'CLB_00468', 37, 'HV_kynnt_070210', 9, '9031', 'Nguyễn Như Thư Kỳ', 'Nam', '2010-02-06', 72.00, 75.00, 74.00, 73.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2010', '2025-12-17 09:58:45.621965', '2025-12-17 09:58:45.621965'),
(32, NULL, 'CLB_00468', 38, 'HV_vyvt_040209', 9, '9032', 'Vũ Tường Vy', 'Nam', '2009-02-03', 74.00, 73.00, 75.00, 74.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2009', '2025-12-17 09:58:45.682407', '2025-12-17 09:58:45.682407'),
(33, NULL, 'CLB_00468', 39, 'HV_huyvn_161219', 9, '9033', 'Võ Nhật Huy', 'Nam', '2019-12-15', 72.00, 73.00, 73.00, 74.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2019', '2025-12-17 09:58:45.720574', '2025-12-17 09:58:45.720574'),
(34, NULL, 'CLB_00468', 40, 'HV_khangcm_150618', 9, '9034', 'Cao Minh Khang', 'Nam', '2018-06-14', 72.00, 73.00, 72.00, 73.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2018', '2025-12-17 09:58:45.747241', '2025-12-17 09:58:45.747241'),
(35, NULL, 'CLB_00468', 41, 'HV_minhpa_060418', 9, '9035', 'Phí Anh Minh', 'Nam', '2018-04-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Chưa có kết quả', '2018', '2025-12-17 09:58:45.798875', '2025-12-17 09:58:45.798875'),
(36, NULL, 'CLB_00468', 42, 'HV_huypn_180718', 9, '9036', 'Phạm Nhật Huy', 'Nam', '2018-07-17', 73.00, 75.00, 73.00, 74.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2018', '2025-12-17 09:58:45.825728', '2025-12-17 09:58:45.825728'),
(37, NULL, 'CLB_00468', 43, 'HV_duongpt_031218', 9, '9037', 'Phan Thái Dương', 'Nam', '2018-12-02', 72.00, 73.00, 73.00, 74.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2018', '2025-12-17 09:58:45.848364', '2025-12-17 09:58:45.848364'),
(38, NULL, 'CLB_00468', 44, 'HV_nguyennpk_140918', 9, '9038', 'Nguyễn Phạm Khánh Nguyện', 'Nam', '2018-09-13', 73.00, 74.00, 74.00, 75.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2018', '2025-12-17 09:58:45.871023', '2025-12-17 09:58:45.871023'),
(39, NULL, 'CLB_00468', 45, 'HV_bachvm_090217', 9, '9039', 'Vương Minh Bách', 'Nam', '2017-02-08', 74.00, 72.00, 73.00, 74.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2017', '2025-12-17 09:58:45.894626', '2025-12-17 09:58:45.894626'),
(40, NULL, 'CLB_00468', 46, 'HV_minhta_260216', 9, '9040', 'Trần Anh Minh', 'Nam', '2016-02-25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Chưa có kết quả', '2016', '2025-12-17 09:58:45.918648', '2025-12-17 09:58:45.918648'),
(41, NULL, 'CLB_00468', 47, 'HV_khanhht_220616', 9, '9041', 'Hồ Thiên Khánh', 'Nam', '2016-06-21', 72.00, 73.00, 74.00, 73.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2016', '2025-12-17 09:58:45.944508', '2025-12-17 09:58:45.944508'),
(42, NULL, 'CLB_00468', 48, 'HV_phongn_020416', 9, '9042', 'Nguyễn Phong', 'Nam', '2016-04-01', 72.00, 72.00, 73.00, 71.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2016', '2025-12-17 09:58:45.970518', '2025-12-17 09:58:45.970518'),
(43, NULL, 'CLB_00468', 49, 'HV_hoangtm_181116', 9, '9043', 'Trần Minh Hoàng', 'Nam', '2016-11-17', 73.00, 75.00, 76.00, 75.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2016', '2025-12-17 09:58:45.990186', '2025-12-17 09:58:45.990186'),
(44, NULL, 'CLB_00468', 50, 'HV_anhndm_300616', 9, '9044', 'Nguyễn Đỗ Minh Anh', 'Nam', '2016-06-29', 74.00, 73.00, 75.00, 74.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2016', '2025-12-17 09:58:46.051575', '2025-12-17 09:58:46.051575'),
(45, NULL, 'CLB_00468', 51, 'HV_danghh_060115', 9, '9045', 'Huỳnh Hải Đăng', 'Nam', '2015-01-05', 73.00, 75.00, 74.00, 72.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2015', '2025-12-17 09:58:46.076061', '2025-12-17 09:58:46.076061'),
(46, NULL, 'CLB_00468', 52, 'HV_duclm_110815', 9, '9046', 'Lại Minh Đức', 'Nam', '2015-08-10', 73.00, 74.00, 75.00, 78.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2015', '2025-12-17 09:58:46.094189', '2025-12-17 09:58:46.094189'),
(47, NULL, 'CLB_00468', 53, 'HV_luanvnt_240715', 9, '9047', 'Vũ Nguyễn Thành Luân', 'Nam', '2015-07-23', 72.00, 73.00, 74.00, 73.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2015', '2025-12-17 09:58:46.119683', '2025-12-17 09:58:46.119683'),
(48, NULL, 'CLB_00468', 54, 'HV_baonpg_050215', 9, '9048', 'Nguyễn Phúc Gia Bảo', 'Nam', '2015-02-04', 73.00, 73.00, 74.00, 73.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2015', '2025-12-17 09:58:46.141121', '2025-12-17 09:58:46.141121'),
(49, NULL, 'CLB_00468', 55, 'HV_baodd_130115', 9, '9049', 'Đào Duy Bảo', 'Nam', '2015-01-12', 72.00, 74.00, 73.00, 72.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2015', '2025-12-17 09:58:46.163496', '2025-12-17 09:58:46.163496'),
(50, NULL, 'CLB_00468', 56, 'HV_minhhn_211014', 9, '9050', 'Huỳnh Ngọc Minh', 'Nam', '2014-10-20', 73.00, 74.00, 76.00, 74.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2014', '2025-12-17 09:58:46.186222', '2025-12-17 09:58:46.186222'),
(51, NULL, 'CLB_00468', 57, 'HV_khangvt_270614', 9, '9051', 'Vũ Tuấn Khang', 'Nam', '2014-06-26', 74.00, 73.00, 74.00, 74.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2014', '2025-12-17 09:58:46.207784', '2025-12-17 09:58:46.207784'),
(52, NULL, 'CLB_00468', 58, 'HV_hoangn_151014', 9, '9052', 'Nguyễn Hoàng', 'Nam', '2014-10-14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Chưa có kết quả', '2014', '2025-12-17 09:58:46.273052', '2025-12-17 09:58:46.273052'),
(53, NULL, 'CLB_00468', 59, 'HV_huypg_120713', 9, '9053', 'Phạm Gia Huy', 'Nam', '2013-07-11', 74.00, 76.00, 77.00, 75.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2013', '2025-12-17 09:58:46.313239', '2025-12-17 09:58:46.313239'),
(54, NULL, 'CLB_00468', 60, 'HV_minhvt_050413', 9, '9054', 'Vũ Tuấn Minh', 'Nam', '2013-04-04', 73.00, 75.00, 76.00, 74.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2013', '2025-12-17 09:58:46.333301', '2025-12-17 09:58:46.333301'),
(55, NULL, 'CLB_00468', 61, 'HV_phuhh_040213', 9, '9055', 'Hoàng Hữu Phú', 'Nam', '2013-02-03', 75.00, 76.00, 75.00, 77.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2013', '2025-12-17 09:58:46.357567', '2025-12-17 09:58:46.357567'),
(56, NULL, 'CLB_00468', 62, 'HV_tuongdm_010313', 9, '9056', 'Đặng Mạnh Tường', 'Nam', '2013-02-28', 74.00, 74.00, 75.00, 74.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2013', '2025-12-17 09:58:46.382029', '2025-12-17 09:58:46.382029'),
(57, NULL, 'CLB_00468', 63, 'HV_longdh_040513', 9, '9057', 'Đặng Hoàng Long', 'Nam', '2013-05-03', 73.00, 75.00, 74.00, 73.00, NULL, NULL, NULL, NULL, NULL, 'Đạt', '2013', '2025-12-17 09:58:46.421099', '2025-12-17 09:58:46.421099'),
(58, NULL, 'CLB_00468', 64, 'HV_nhuhtt_121118', 6, '6001', 'Huỳnh Thái Tâm Như', 'Nam', '2018-11-11', NULL, 72.00, NULL, 73.00, NULL, 72.00, 75.00, NULL, 75.00, 'Đạt', '2018', '2025-12-17 09:58:46.442931', '2025-12-17 09:58:46.442931'),
(59, NULL, 'CLB_00468', 65, 'HV_nhitg_250918', 6, '6002', 'Trần Gia Nhi', 'Nam', '2018-09-24', NULL, 73.00, NULL, 75.00, NULL, 74.00, 76.00, NULL, 75.00, 'Đạt', '2018', '2025-12-17 09:58:46.469484', '2025-12-17 09:58:46.469484'),
(60, NULL, 'CLB_00468', 66, 'HV_ngoclta_100118', 6, '6003', 'Lương Thị Ánh Ngọc', 'Nam', '2018-01-09', NULL, 74.00, NULL, 73.00, NULL, 74.00, 76.00, NULL, 76.00, 'Đạt', '2018', '2025-12-17 09:58:46.494104', '2025-12-17 09:58:46.494104'),
(61, NULL, 'CLB_00468', 67, 'HV_ngocnn_171015', 6, '6004', 'Nguyễn Như Ngọc', 'Nam', '2015-10-16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Chưa có kết quả', '2015', '2025-12-17 09:58:46.579331', '2025-12-17 09:58:46.579331'),
(62, NULL, 'CLB_00468', 68, 'HV_ngocvn_180815', 6, '6005', 'Vũ Như Ngọc', 'Nam', '2015-08-17', NULL, 75.00, NULL, 73.00, NULL, 73.00, 75.00, NULL, 75.00, 'Đạt', '2015', '2025-12-17 09:58:46.595985', '2025-12-17 09:58:46.595985'),
(63, NULL, 'CLB_00468', 69, 'HV_vybnh_201115', 6, '6006', 'Bùi Nguyễn Hà Vy', 'Nam', '2015-11-19', NULL, 74.00, NULL, 76.00, NULL, 75.00, 74.00, NULL, 74.00, 'Đạt', '2015', '2025-12-17 09:58:46.648434', '2025-12-17 09:58:46.648434'),
(64, NULL, 'CLB_00468', 70, 'HV_yenlb_240415', 6, '6007', 'Lương Bảo Yến', 'Nam', '2015-04-23', NULL, 73.00, NULL, 75.00, NULL, 74.00, 73.00, NULL, 75.00, 'Đạt', '2015', '2025-12-17 09:58:46.682485', '2025-12-17 09:58:46.682485'),
(65, NULL, 'CLB_00468', 71, 'HV_nhitpu_300714', 6, '6008', 'Tạ Phan Uyển Nhi', 'Nam', '2014-07-29', NULL, 74.00, NULL, 75.00, NULL, 73.00, 73.00, NULL, 76.00, 'Đạt', '2014', '2025-12-17 09:58:46.704693', '2025-12-17 09:58:46.704693'),
(66, NULL, 'CLB_00468', 72, 'HV_thynpb_110813', 6, '6009', 'Nguyễn Phan Bảo Thy', 'Nam', '2013-08-10', NULL, 75.00, NULL, 77.00, NULL, 76.00, 74.00, NULL, 74.00, 'Đạt', '2013', '2025-12-17 09:58:46.741943', '2025-12-17 09:58:46.741943'),
(67, NULL, 'CLB_00468', 73, 'HV_hueddt_150213', 6, '6010', 'Đàm Đỗ Thanh Huế', 'Nam', '2013-02-14', NULL, 75.00, NULL, 74.00, NULL, 79.00, 74.00, NULL, 75.00, 'Đạt', '2013', '2025-12-17 09:58:46.786731', '2025-12-17 09:58:46.786731'),
(68, NULL, 'CLB_00468', 74, 'HV_giangltt_190912', 6, '6011', 'Lưu Thị Trà Giang', 'Nam', '2012-09-18', NULL, 73.00, NULL, 74.00, NULL, 75.00, 73.00, NULL, 76.00, 'Đạt', '2012', '2025-12-17 09:58:46.810707', '2025-12-17 09:58:46.810707'),
(69, NULL, 'CLB_00468', 75, 'HV_trannnb_221012', 6, '6012', 'Nguyễn Ngọc Bảo Trân', 'Nam', '2012-10-21', NULL, 74.00, NULL, 75.00, NULL, 74.00, 75.00, NULL, 75.00, 'Đạt', '2012', '2025-12-17 09:58:46.841561', '2025-12-17 09:58:46.841561'),
(70, NULL, 'CLB_00468', 76, 'HV_giangtth_290412', 6, '6013', 'Trần Thị Hương Giang', 'Nam', '2012-04-28', NULL, 72.00, NULL, 73.00, NULL, 72.00, 75.00, NULL, 74.00, 'Đạt', '2012', '2025-12-17 09:58:46.870831', '2025-12-17 09:58:46.870831'),
(71, NULL, 'CLB_00468', 77, 'HV_thinhtc_010617', 6, '6014', 'Trần Công Thịnh', 'Nam', '2017-05-31', NULL, 74.00, NULL, 73.00, NULL, 73.00, 72.00, NULL, 75.00, 'Đạt', '2017', '2025-12-17 09:58:46.893681', '2025-12-17 09:58:46.893681'),
(72, NULL, 'CLB_00468', 78, 'HV_dungla_181215', 6, '6015', 'Lê Anh Dũng', 'Nam', '2015-12-17', NULL, 73.00, NULL, 74.00, NULL, 73.00, 71.00, NULL, 75.00, 'Đạt', '2015', '2025-12-17 09:58:46.917572', '2025-12-17 09:58:46.917572'),
(73, NULL, 'CLB_00468', 79, 'HV_lactvg_050115', 6, '6016', 'Trần Văn Gia Lạc', 'Nam', '2015-01-04', NULL, 73.00, NULL, 75.00, NULL, 75.00, 71.00, NULL, 73.00, 'Đạt', '2015', '2025-12-17 09:58:46.940075', '2025-12-17 09:58:46.940075'),
(74, NULL, 'CLB_00468', 80, 'HV_trinbm_010615', 6, '6017', 'Nguyễn Bế Minh Trí', 'Nam', '2015-05-31', NULL, 74.00, NULL, 73.00, NULL, 74.00, 73.00, NULL, 74.00, 'Đạt', '2015', '2025-12-17 09:58:46.972918', '2025-12-17 09:58:46.972918'),
(75, NULL, 'CLB_00468', 81, 'HV_huylv_220915', 6, '6018', 'Lục Văn Huy', 'Nam', '2015-09-21', NULL, 74.00, NULL, 75.00, NULL, 76.00, 74.00, NULL, 75.00, 'Đạt', '2015', '2025-12-17 09:58:47.096280', '2025-12-17 09:58:47.096280'),
(76, NULL, 'CLB_00468', 82, 'HV_duynd_180214', 6, '6019', 'Nguyễn Đức Duy', 'Nam', '2014-02-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Chưa có kết quả', '2014', '2025-12-17 09:58:47.119797', '2025-12-17 09:58:47.119797'),
(77, NULL, 'CLB_00468', 83, 'HV_taibd_070814', 6, '6020', 'Bùi Đức Tài', 'Nam', '2014-08-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Chưa có kết quả', '2014', '2025-12-17 09:58:47.135816', '2025-12-17 09:58:47.135816'),
(78, NULL, 'CLB_00468', 84, 'HV_huynbg_090213', 6, '6021', 'Nguyễn Bế Gia Huy', 'Nam', '2013-02-08', NULL, 75.00, NULL, 74.00, NULL, 74.00, 72.00, NULL, 75.00, 'Đạt', '2013', '2025-12-17 09:58:47.161245', '2025-12-17 09:58:47.161245'),
(79, NULL, 'CLB_00468', 85, 'HV_congndt_260413', 6, '6022', 'Nguyễn Đại Thành Công', 'Nam', '2013-04-25', NULL, 73.00, NULL, 74.00, NULL, 75.00, 74.00, NULL, 75.00, 'Đạt', '2013', '2025-12-17 09:58:47.187045', '2025-12-17 09:58:47.187045'),
(80, NULL, 'CLB_00468', 86, 'HV_namdc_131112', 6, '6023', 'Đoàn Chấn Nam', 'Nam', '2012-11-12', NULL, 74.00, NULL, 75.00, NULL, 77.00, 75.00, NULL, 76.00, 'Đạt', '2012', '2025-12-17 09:58:47.208690', '2025-12-17 09:58:47.208690'),
(81, NULL, 'CLB_00468', 87, 'HV_trucnt_210317', 5, '5001', 'Nguyễn Thanh Trúc', 'Nam', '2017-03-20', NULL, 75.00, NULL, 74.00, NULL, 73.00, 74.00, NULL, 73.00, 'Đạt', '2017', '2025-12-17 09:58:47.227391', '2025-12-17 09:58:47.227391'),
(82, NULL, 'CLB_00468', 88, 'HV_anhklb_070216', 5, '5002', 'Khổng Lê Bảo Anh', 'Nam', '2016-02-06', NULL, 74.00, NULL, 73.00, NULL, 75.00, 75.00, NULL, 73.00, 'Đạt', '2016', '2025-12-17 09:58:47.266213', '2025-12-17 09:58:47.266213'),
(83, NULL, 'CLB_00468', 89, 'HV_ngocptm_111216', 5, '5003', 'Phạm Thị Minh Ngọc', 'Nam', '2016-12-10', NULL, 72.00, NULL, 74.00, NULL, 74.00, 71.00, NULL, 72.00, 'Đạt', '2016', '2025-12-17 09:58:47.306207', '2025-12-17 09:58:47.306207'),
(84, NULL, 'CLB_00468', 90, 'HV_huongtng_010614', 5, '5004', 'Tô Ngọc Giang Hương', 'Nam', '2014-05-31', NULL, 75.00, NULL, 73.00, NULL, 75.00, 73.00, NULL, 74.00, 'Đạt', '2014', '2025-12-17 09:58:47.340014', '2025-12-17 09:58:47.340014'),
(85, NULL, 'CLB_00468', 91, 'HV_annna_020814', 5, '5005', 'Nguyễn Ngọc An An', 'Nam', '2014-08-01', NULL, 73.00, NULL, 72.00, NULL, 74.00, 73.00, NULL, 73.00, 'Đạt', '2014', '2025-12-17 09:58:47.359696', '2025-12-17 09:58:47.359696'),
(86, NULL, 'CLB_00468', 92, 'HV_anhnnk_151213', 5, '5006', 'Nguyễn Ngọc Kim Anh', 'Nam', '2013-12-14', NULL, 74.00, NULL, 77.00, NULL, 75.00, 73.00, NULL, 72.00, 'Đạt', '2013', '2025-12-17 09:58:47.386696', '2025-12-17 09:58:47.386696'),
(87, NULL, 'CLB_00468', 93, 'HV_trangbpm_011112', 5, '5007', 'Bùi Phạm Minh Trang', 'Nam', '2012-10-31', NULL, 72.00, NULL, 77.00, NULL, 75.00, 75.00, NULL, 74.00, 'Đạt', '2012', '2025-12-17 09:58:47.412633', '2025-12-17 09:58:47.412633'),
(88, NULL, 'CLB_00468', 94, 'HV_nhuntq_050812', 5, '5008', 'Nguyễn Trần Quỳnh Như', 'Nam', '2012-08-04', NULL, 73.00, NULL, 75.00, NULL, 73.00, 75.00, NULL, 74.00, 'Đạt', '2012', '2025-12-17 09:58:47.436516', '2025-12-17 09:58:47.436516'),
(89, NULL, 'CLB_00468', 95, 'HV_vanvnk_190412', 5, '5009', 'Võ Ngọc Khánh Vân', 'Nam', '2012-04-18', NULL, 74.00, NULL, 76.00, NULL, 75.00, 74.00, NULL, 75.00, 'Đạt', '2012', '2025-12-17 09:58:47.459549', '2025-12-17 09:58:47.459549'),
(90, NULL, 'CLB_00468', 96, 'HV_gamnth_120811', 5, '5010', 'Nông Thị Hồng Gấm', 'Nam', '2011-08-11', NULL, 74.00, NULL, 74.00, NULL, 73.00, 74.00, NULL, 74.00, 'Đạt', '2011', '2025-12-17 09:58:47.478441', '2025-12-17 09:58:47.478441'),
(91, NULL, 'CLB_00468', 97, 'HV_datha_180518', 5, '5011', 'Hà Anh Đạt', 'Nam', '2018-05-17', NULL, 72.00, NULL, 73.00, NULL, 74.00, 72.00, NULL, 73.00, 'Đạt', '2018', '2025-12-17 09:58:47.541992', '2025-12-17 09:58:47.541992'),
(92, NULL, 'CLB_00468', 98, 'HV_anhdh_170317', 5, '5012', 'Đinh Hoàng Anh', 'Nam', '2017-03-16', NULL, 73.00, NULL, 74.00, NULL, 73.00, 71.00, NULL, 74.00, 'Đạt', '2017', '2025-12-17 09:58:47.568291', '2025-12-17 09:58:47.568291'),
(93, NULL, 'CLB_00468', 99, 'HV_minhvc_250416', 5, '5013', 'Vũ Công Minh', 'Nam', '2016-04-24', NULL, 71.00, NULL, 75.00, NULL, 73.00, 73.00, NULL, 72.00, 'Đạt', '2016', '2025-12-17 09:58:47.585932', '2025-12-17 09:58:47.585932'),
(94, NULL, 'CLB_00468', 100, 'HV_haigm_141016', 5, '5014', 'Giang Minh Hải', 'Nam', '2016-10-13', NULL, 72.00, NULL, 73.00, NULL, 74.00, 72.00, NULL, 73.00, 'Đạt', '2016', '2025-12-17 09:58:47.611563', '2025-12-17 09:58:47.611563'),
(95, NULL, 'CLB_00468', 101, 'HV_minhnn_290516', 5, '5015', 'Nguyễn Nhật Minh', 'Nam', '2016-05-28', NULL, 73.00, NULL, 72.00, NULL, 73.00, 74.00, NULL, 72.00, 'Đạt', '2016', '2025-12-17 09:58:47.634642', '2025-12-17 09:58:47.634642'),
(96, NULL, 'CLB_00468', 102, 'HV_nguyenta_270516', 5, '5016', 'Trần An Nguyên', 'Nam', '2016-05-26', NULL, 72.00, NULL, 73.00, NULL, 74.00, 72.00, NULL, 74.00, 'Đạt', '2016', '2025-12-17 09:58:47.653344', '2025-12-17 09:58:47.653344'),
(97, NULL, 'CLB_00468', 103, 'HV_tritv_120115', 5, '5017', 'Tăng Văn Trí', 'Nam', '2015-01-11', NULL, 73.00, NULL, 72.00, NULL, 74.00, 73.00, NULL, 76.00, 'Đạt', '2015', '2025-12-17 09:58:47.677452', '2025-12-17 09:58:47.677452'),
(98, NULL, 'CLB_00468', 104, 'HV_datnq_190815', 5, '5018', 'Ngô Quốc Đạt', 'Nam', '2015-08-18', NULL, 71.00, NULL, 72.00, NULL, 73.00, 74.00, NULL, 74.00, 'Đạt', '2015', '2025-12-17 09:58:47.699637', '2025-12-17 09:58:47.699637'),
(99, NULL, 'CLB_00468', 105, 'HV_baoklg_060112', 5, '5019', 'Khổng Lê Gia Bảo', 'Nam', '2012-01-05', NULL, 75.00, NULL, 76.00, NULL, 78.00, 75.00, NULL, 77.00, 'Đạt', '2012', '2025-12-17 09:58:47.722168', '2025-12-17 09:58:47.722168'),
(100, NULL, 'CLB_00468', 106, 'HV_minh(1)nt_150512', 5, '5020', 'Nông Thanh Minh(1)', 'Nam', '2012-05-14', NULL, 72.00, NULL, 73.00, NULL, 74.00, 76.00, NULL, 73.00, 'Đạt', '2012', '2025-12-17 09:58:47.742434', '2025-12-17 09:58:47.742434'),
(101, NULL, 'CLB_00468', 107, 'HV_namnh_290212', 5, '5021', 'Nguyễn Hoàng Nam', 'Nam', '2012-02-28', NULL, 73.00, NULL, 74.00, NULL, 75.00, 75.00, NULL, 74.00, 'Đạt', '2012', '2025-12-17 09:58:47.772946', '2025-12-17 09:58:47.772946'),
(102, NULL, 'CLB_00468', 108, 'HV_khoihd_100412', 5, '5022', 'Huỳnh Đăng Khôi', 'Nam', '2012-04-09', NULL, 72.00, NULL, 74.00, NULL, 73.00, 74.00, NULL, 73.00, 'Đạt', '2012', '2025-12-17 09:58:47.812389', '2025-12-17 09:58:47.812389'),
(103, NULL, 'CLB_00468', 109, 'HV_nhantm_010611', 5, '5023', 'Trần Minh Nhân', 'Nam', '2011-05-31', NULL, 73.00, NULL, 73.00, NULL, 74.00, 75.00, NULL, 74.00, 'Đạt', '2011', '2025-12-17 09:58:47.853447', '2025-12-17 09:58:47.853447'),
(104, NULL, 'CLB_00468', 110, 'HV_khuehm_060218', 4, '4001', 'Hà Minh Khuê', 'Nam', '2018-02-05', NULL, 72.00, NULL, 72.00, NULL, 72.00, 71.00, 76.00, 75.00, 'Đạt', '2018', '2025-12-17 09:58:47.874141', '2025-12-17 09:58:47.874141'),
(105, NULL, 'CLB_00468', 111, 'HV_nhint_060517', 4, '4002', 'Nguyễn Tâm Nhi', 'Nam', '2017-05-05', NULL, 71.00, NULL, 71.00, NULL, 72.00, 72.00, 76.00, 75.00, 'Đạt', '2017', '2025-12-17 09:58:47.892473', '2025-12-17 09:58:47.892473'),
(106, NULL, 'CLB_00468', 112, 'HV_vyptt_141017', 4, '4003', 'Phạm Trần Thảo Vy', 'Nam', '2017-10-13', NULL, 72.00, NULL, 72.00, NULL, 72.00, 71.00, 77.00, 75.00, 'Đạt', '2017', '2025-12-17 09:58:47.915871', '2025-12-17 09:58:47.915871'),
(107, NULL, 'CLB_00468', 113, 'HV_ngocpb_210117', 4, '4004', 'Phạm Bảo Ngọc', 'Nam', '2017-01-20', NULL, 74.00, NULL, 74.00, NULL, 71.00, 71.00, 76.00, 75.00, 'Đạt', '2017', '2025-12-17 09:58:47.936902', '2025-12-17 09:58:47.936902'),
(108, NULL, 'CLB_00468', 114, 'HV_nhitnb_270717', 4, '4005', 'Trần Ngọc Bảo Nhi', 'Nam', '2017-07-26', NULL, 71.00, NULL, 72.00, NULL, 71.00, 71.00, 77.00, 75.00, 'Đạt', '2017', '2025-12-17 09:58:47.960268', '2025-12-17 09:58:47.960268'),
(109, NULL, 'CLB_00468', 115, 'HV_ngocnb_020216', 4, '4006', 'Nguyễn Bảo Ngọc', 'Nam', '2016-02-01', NULL, 71.00, NULL, 71.00, NULL, 71.00, 73.00, 76.00, 75.00, 'Đạt', '2016', '2025-12-17 09:58:47.982289', '2025-12-17 09:58:47.982289'),
(110, NULL, 'CLB_00468', 116, 'HV_hannng_040516', 4, '4007', 'Nguyễn Ngọc Gia Hân', 'Nam', '2016-05-03', NULL, 73.00, NULL, 73.00, NULL, 72.00, 72.00, 77.00, 75.00, 'Đạt', '2016', '2025-12-17 09:58:47.999332', '2025-12-17 09:58:47.999332'),
(111, NULL, 'CLB_00468', 117, 'HV_ngocnm_160615', 4, '4008', 'Ngô Minh Ngọc', 'Nam', '2015-06-15', NULL, 72.00, NULL, 73.00, NULL, 72.00, 71.00, 76.00, 75.00, 'Đạt', '2015', '2025-12-17 09:58:48.084067', '2025-12-17 09:58:48.084067'),
(112, NULL, 'CLB_00468', 118, 'HV_thumha_161013', 4, '4009', 'Mã Hoàng Anh Thư', 'Nam', '2013-10-15', NULL, 73.00, NULL, 72.00, NULL, 72.00, 71.00, 75.00, 75.00, 'Đạt', '2013', '2025-12-17 09:58:48.106007', '2025-12-17 09:58:48.106007'),
(113, NULL, 'CLB_00468', 119, 'HV_linhntt_131112', 4, '4010', 'Nông Thị Thùy Linh', 'Nam', '2012-11-12', NULL, 73.00, NULL, 73.00, NULL, 73.00, 72.00, 76.00, 76.00, 'Đạt', '2012', '2025-12-17 09:58:48.130910', '2025-12-17 09:58:48.130910'),
(114, NULL, 'CLB_00468', 120, 'HV_anhpnv_060312', 4, '4011', 'Phạm Nguyễn Vân Anh', 'Nam', '2012-03-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Chưa có kết quả', '2012', '2025-12-17 09:58:48.153372', '2025-12-17 09:58:48.153372'),
(115, NULL, 'CLB_00468', 121, 'HV_yenndn_151112', 4, '4012', 'Nguyễn Đoàn Ngọc yến', 'Nam', '2012-11-14', NULL, 74.00, NULL, 73.00, NULL, 72.00, 73.00, 76.00, 76.00, 'Đạt', '2012', '2025-12-17 09:58:48.172449', '2025-12-17 09:58:48.172449'),
(116, NULL, 'CLB_00468', 122, 'HV_ngocntb_221212', 4, '4013', 'Nguyễn Thị Bảo Ngọc', 'Nam', '2012-12-21', NULL, 73.00, NULL, 72.00, NULL, 74.00, 72.00, 77.00, 76.00, 'Đạt', '2012', '2025-12-17 09:58:48.201292', '2025-12-17 09:58:48.201292'),
(117, NULL, 'CLB_00468', 123, 'HV_thamnth_051210', 4, '4014', 'Nguyễn Thị Hồng Thắm', 'Nam', '2010-12-04', NULL, 73.00, NULL, 73.00, NULL, 72.00, 72.00, 76.00, 75.00, 'Đạt', '2010', '2025-12-17 09:58:48.226633', '2025-12-17 09:58:48.226633'),
(118, NULL, 'CLB_00468', 124, 'HV_sang(1)nvt_121217', 4, '4015', 'Nguyễn Viết Thanh Sang(1)', 'Nam', '2017-12-11', NULL, 73.00, NULL, 72.00, NULL, 72.00, 72.00, 75.00, 76.00, 'Đạt', '2017', '2025-12-17 09:58:48.249937', '2025-12-17 09:58:48.249937'),
(119, NULL, 'CLB_00468', 125, 'HV_lamppk_040717', 4, '4016', 'Phan Phước Khải Lâm', 'Nam', '2017-07-03', NULL, 71.00, NULL, 72.00, NULL, 71.00, 73.00, 76.00, 75.00, 'Đạt', '2017', '2025-12-17 09:58:48.315871', '2025-12-17 09:58:48.315871'),
(120, NULL, 'CLB_00468', 126, 'HV_anhtdt_011216', 4, '4017', 'Trần Duy Tuấn Anh', 'Nam', '2016-11-30', NULL, 71.00, NULL, 72.00, NULL, 72.00, 73.00, 78.00, 76.00, 'Đạt', '2016', '2025-12-17 09:58:48.335089', '2025-12-17 09:58:48.335089'),
(121, NULL, 'CLB_00468', 127, 'HV_thanhndc_021015', 4, '4018', 'Nguyễn Đại Công Thành', 'Nam', '2015-10-01', NULL, 71.00, NULL, 71.00, NULL, 73.00, 72.00, 77.00, 76.00, 'Đạt', '2015', '2025-12-17 09:58:48.359889', '2025-12-17 09:58:48.359889'),
(122, NULL, 'CLB_00468', 128, 'HV_phatnh_261215', 4, '4019', 'Nguyễn Hoàng Phát', 'Nam', '2015-12-25', NULL, 72.00, NULL, 72.00, NULL, 71.00, 73.00, 77.00, 76.00, 'Đạt', '2015', '2025-12-17 09:58:48.422059', '2025-12-17 09:58:48.422059'),
(123, NULL, 'CLB_00468', 129, 'HV_huylq_300815', 4, '4020', 'Lê Quốc Huy', 'Nam', '2015-08-29', NULL, 72.00, NULL, 71.00, NULL, 70.00, 73.00, 76.00, 76.00, 'Đạt', '2015', '2025-12-17 09:58:48.458968', '2025-12-17 09:58:48.458968'),
(124, NULL, 'CLB_00468', 130, 'HV_dungpt_020714', 4, '4021', 'Phan Tiến Dũng', 'Nam', '2014-07-01', NULL, 72.00, NULL, 72.00, NULL, 71.00, 72.00, 74.00, 76.00, 'Đạt', '2014', '2025-12-17 09:58:48.489548', '2025-12-17 09:58:48.489548'),
(125, NULL, 'CLB_00468', 131, 'HV_hieuld_040714', 4, '4022', 'Lục Đức Hiếu', 'Nam', '2014-07-03', NULL, 72.00, NULL, 72.00, NULL, 72.00, 72.00, 77.00, 76.00, 'Đạt', '2014', '2025-12-17 09:58:48.568178', '2025-12-17 09:58:48.568178'),
(126, NULL, 'CLB_00468', 132, 'HV_dungbt_200614', 4, '4023', 'Bùi Tiến Dũng', 'Nam', '2014-06-19', NULL, 73.00, NULL, 72.00, NULL, 71.00, 73.00, 74.00, 76.00, 'Đạt', '2014', '2025-12-17 09:58:48.586752', '2025-12-17 09:58:48.586752'),
(127, NULL, 'CLB_00468', 133, 'HV_khanhtd_220614', 4, '4024', 'Trần Duy Khánh', 'Nam', '2014-06-21', NULL, 71.00, NULL, 72.00, NULL, 71.00, 72.00, 76.00, 76.00, 'Đạt', '2014', '2025-12-17 09:58:48.611295', '2025-12-17 09:58:48.611295'),
(128, NULL, 'CLB_00468', 134, 'HV_phuocnh_250314', 4, '4025', 'Nguyễn Hữu Phước', 'Nam', '2014-03-24', NULL, 73.00, NULL, 71.00, NULL, 71.00, 71.00, 77.00, 76.00, 'Đạt', '2014', '2025-12-17 09:58:48.629179', '2025-12-17 09:58:48.629179'),
(129, NULL, 'CLB_00468', 135, 'HV_lamhp_010413', 4, '4026', 'Hà Phúc Lâm', 'Nam', '2013-03-31', NULL, 72.00, NULL, 71.00, NULL, 71.00, 73.00, 77.00, 76.00, 'Đạt', '2013', '2025-12-17 09:58:48.651702', '2025-12-17 09:58:48.651702'),
(130, NULL, 'CLB_00468', 136, 'HV_bao(1)ng_040113', 4, '4027', 'Nguyễn Gia Bảo(1)', 'Nam', '2013-01-03', NULL, 73.00, NULL, 71.00, NULL, 71.00, 73.00, 76.00, 76.00, 'Đạt', '2013', '2025-12-17 09:58:48.674863', '2025-12-17 09:58:48.674863'),
(131, NULL, 'CLB_00468', 137, 'HV_baobg_181213', 4, '4028', 'Bùi Gia Bảo', 'Nam', '2013-12-17', NULL, 73.00, NULL, 72.00, NULL, 72.00, 73.00, 78.00, 76.00, 'Đạt', '2013', '2025-12-17 09:58:48.699674', '2025-12-17 09:58:48.699674'),
(132, NULL, 'CLB_00468', 138, 'HV_baong_250313', 4, '4029', 'Nguyễn Gia Bảo', 'Nam', '2013-03-24', NULL, 72.00, NULL, 72.00, NULL, 72.00, 74.00, 74.00, 76.00, 'Đạt', '2013', '2025-12-17 09:58:48.727348', '2025-12-17 09:58:48.727348'),
(133, NULL, 'CLB_00468', 139, 'HV_namlnb_280713', 4, '4030', 'Lê Ngọc Bảo Nam', 'Nam', '2013-07-27', NULL, 73.00, NULL, 72.00, NULL, 72.00, 73.00, 77.00, 76.00, 'Đạt', '2013', '2025-12-17 09:58:48.752925', '2025-12-17 09:58:48.752925'),
(134, NULL, 'CLB_00468', 140, 'HV_phuongpt_250411', 4, '4031', 'Phạm Thanh Phương', 'Nam', '2011-04-24', NULL, 75.00, NULL, 72.00, NULL, 71.00, 73.00, 77.00, 76.00, 'Đạt', '2011', '2025-12-17 09:58:48.813058', '2025-12-17 09:58:48.813058'),
(135, NULL, 'CLB_00468', 141, 'HV_thienvm_260811', 4, '4032', 'Vũ Minh Thiện', 'Nam', '2011-08-25', NULL, 71.00, NULL, 71.00, NULL, 71.00, 72.00, 76.00, 76.00, 'Đạt', '2011', '2025-12-17 09:58:48.841706', '2025-12-17 09:58:48.841706'),
(136, NULL, 'CLB_00468', 142, 'HV_lydm_130617', 3, '3001', 'Đỗ Mai Ly', 'Nam', '2017-06-12', NULL, 73.00, NULL, 73.00, NULL, 72.00, 71.00, 77.00, 75.00, 'Đạt', '2017', '2025-12-17 09:58:48.894085', '2025-12-17 09:58:48.894085'),
(137, NULL, 'CLB_00468', 143, 'HV_nghinb_170517', 3, '3002', 'Nguyễn Bảo Nghi', 'Nam', '2017-05-16', NULL, 75.00, NULL, 75.00, NULL, 75.00, 74.00, 76.00, 75.00, 'Đạt', '2017', '2025-12-17 09:58:48.921577', '2025-12-17 09:58:48.921577'),
(138, NULL, 'CLB_00468', 144, 'HV_thupta_120716', 3, '3003', 'Phan Thị Anh Thư', 'Nam', '2016-07-11', NULL, 72.00, NULL, 72.00, NULL, 73.00, 72.00, 78.00, 76.00, 'Đạt', '2016', '2025-12-17 09:58:48.942425', '2025-12-17 09:58:48.942425'),
(139, NULL, 'CLB_00468', 145, 'HV_yenptk_180316', 3, '3004', 'Phan Thị Kim Yến', 'Nam', '2016-03-17', NULL, 72.00, NULL, 73.00, NULL, 73.00, 71.00, 77.00, 76.00, 'Đạt', '2016', '2025-12-17 09:58:48.960523', '2025-12-17 09:58:48.960523'),
(140, NULL, 'CLB_00468', 146, 'HV_lybht_040615', 3, '3005', 'Bùi Hoàng Trúc Ly', 'Nam', '2015-06-03', NULL, 72.00, NULL, 73.00, NULL, 72.00, 72.00, 76.00, 75.00, 'Đạt', '2015', '2025-12-17 09:58:48.978722', '2025-12-17 09:58:48.978722'),
(141, NULL, 'CLB_00468', 147, 'HV_nganntk_041015', 3, '3006', 'Nguyễn Thị Kim Ngân', 'Nam', '2015-10-03', NULL, 72.00, NULL, 73.00, NULL, 72.00, 72.00, 76.00, 75.00, 'Đạt', '2015', '2025-12-17 09:58:49.002250', '2025-12-17 09:58:49.002250'),
(142, NULL, 'CLB_00468', 148, 'HV_linhttt_050515', 3, '3007', 'Triệu Thị Trúc Linh', 'Nam', '2015-05-04', NULL, 73.00, NULL, 73.00, NULL, 73.00, 73.00, 77.00, 76.00, 'Đạt', '2015', '2025-12-17 09:58:49.086026', '2025-12-17 09:58:49.086026'),
(143, NULL, 'CLB_00468', 149, 'HV_tuehm_080115', 3, '3008', 'Hồ Minh Tuệ', 'Nam', '2015-01-07', NULL, 73.00, NULL, 73.00, NULL, 72.00, 72.00, 77.00, 75.00, 'Đạt', '2015', '2025-12-17 09:58:49.113313', '2025-12-17 09:58:49.113313'),
(144, NULL, 'CLB_00468', 150, 'HV_vylnt_280915', 3, '3009', 'Lê Nguyễn Thanh Vy', 'Nam', '2015-09-27', NULL, 73.00, NULL, 73.00, NULL, 72.00, 73.00, 76.00, 76.00, 'Đạt', '2015', '2025-12-17 09:58:49.132009', '2025-12-17 09:58:49.132009'),
(145, NULL, 'CLB_00468', 151, 'HV_duonghtt_031014', 3, '3010', 'Hoàng Thị Thùy Dương', 'Nam', '2014-10-02', NULL, 73.00, NULL, 72.00, NULL, 73.00, 72.00, 76.00, 76.00, 'Đạt', '2014', '2025-12-17 09:58:49.154789', '2025-12-17 09:58:49.154789'),
(146, NULL, 'CLB_00468', 152, 'HV_bichtn_080414', 3, '3011', 'Trần Ngọc Bích', 'Nam', '2014-04-07', NULL, 73.00, NULL, 73.00, NULL, 73.00, 73.00, 77.00, 76.00, 'Đạt', '2014', '2025-12-17 09:58:49.180221', '2025-12-17 09:58:49.180221'),
(147, NULL, 'CLB_00468', 153, 'HV_trangdtt_250814', 3, '3012', 'Duyên Thị Tuyết Trang', 'Nam', '2014-08-24', NULL, 73.00, NULL, 74.00, NULL, 74.00, 74.00, 78.00, 76.00, 'Đạt', '2014', '2025-12-17 09:58:49.196774', '2025-12-17 09:58:49.196774'),
(148, NULL, 'CLB_00468', 154, 'HV_ythn_160913', 3, '3013', 'Trần Hoàng Như Ý', 'Nam', '2013-09-15', NULL, 74.00, NULL, 72.00, NULL, 72.00, 72.00, 77.00, 76.00, 'Đạt', '2013', '2025-12-17 09:58:49.219259', '2025-12-17 09:58:49.219259'),
(149, NULL, 'CLB_00468', 155, 'HV_oanhptk_150712', 3, '3014', 'Phan Thị Kim Oanh', 'Nam', '2012-07-14', NULL, 74.00, NULL, 72.00, NULL, 72.00, 72.00, 76.00, 76.00, 'Đạt', '2012', '2025-12-17 09:58:49.241082', '2025-12-17 09:58:49.241082'),
(150, NULL, 'CLB_00468', 156, 'HV_trinhmp_170212', 3, '3015', 'Mai Phương Trinh', 'Nam', '2012-02-16', NULL, 73.00, NULL, 73.00, NULL, 73.00, 73.00, 77.00, 76.00, 'Đạt', '2012', '2025-12-17 09:58:49.289392', '2025-12-17 09:58:49.289392'),
(151, NULL, 'CLB_00468', 157, 'HV_quynhnp_240611', 3, '3016', 'Nguyễn Phượng Quỳnh', 'Nam', '2011-06-23', NULL, 73.00, NULL, 73.00, NULL, 73.00, 73.00, 77.00, 76.00, 'Đạt', '2011', '2025-12-17 09:58:49.326900', '2025-12-17 09:58:49.326900'),
(152, NULL, 'CLB_00468', 158, 'HV_nhuvtq_210408', 3, '3017', 'Võ Thị Quỳnh Như', 'Nam', '2008-04-20', NULL, 73.00, NULL, 72.00, NULL, 72.00, 72.00, 78.00, 76.00, 'Đạt', '2008', '2025-12-17 09:58:49.354001', '2025-12-17 09:58:49.354001'),
(153, NULL, 'CLB_00468', 159, 'HV_tanntc_200298', 3, '3018', 'Nguyễn Thị Cẩm Tân', 'Nam', '1998-02-19', NULL, 72.00, NULL, 72.00, NULL, 73.00, 72.00, 77.00, 76.00, 'Đạt', '1998', '2025-12-17 09:58:49.382158', '2025-12-17 09:58:49.382158'),
(154, NULL, 'CLB_00468', 160, 'HV_datlnt_060117', 3, '3019', 'Lê Nguyễn Thaành Đạt', 'Nam', '2017-01-05', NULL, 73.00, NULL, 74.00, NULL, 74.00, 74.00, 77.00, 76.00, 'Đạt', '2017', '2025-12-17 09:58:49.401438', '2025-12-17 09:58:49.401438'),
(155, NULL, 'CLB_00468', 161, 'HV_khanglc_100417', 3, '3020', 'Lê Chấn Khang', 'Nam', '2017-04-09', NULL, 74.00, NULL, 73.00, NULL, 72.00, 73.00, 76.00, 76.00, 'Đạt', '2017', '2025-12-17 09:58:49.425441', '2025-12-17 09:58:49.425441'),
(156, NULL, 'CLB_00468', 162, 'HV_cuongnbt_070117', 3, '3021', 'Nguyễn Bùi Thái Cường', 'Nam', '2017-01-06', NULL, 72.00, NULL, 73.00, NULL, 72.00, 72.00, 77.00, 76.00, 'Đạt', '2017', '2025-12-17 09:58:49.439702', '2025-12-17 09:58:49.439702'),
(157, NULL, 'CLB_00468', 163, 'HV_minhnd_230317', 3, '3022', 'Nguyễn Duy Minh', 'Nam', '2017-03-22', NULL, 73.00, NULL, 72.00, NULL, 72.00, 71.00, 76.00, 76.00, 'Đạt', '2017', '2025-12-17 09:58:49.456408', '2025-12-17 09:58:49.456408'),
(158, NULL, 'CLB_00468', 164, 'HV_quanhnh_280917', 3, '3023', 'Hồ Nguyễn Hoàng Quân', 'Nam', '2017-09-27', NULL, 72.00, NULL, 71.00, NULL, 71.00, 72.00, 78.00, 76.00, 'Đạt', '2017', '2025-12-17 09:58:49.537463', '2025-12-17 09:58:49.537463'),
(159, NULL, 'CLB_00468', 165, 'HV_phuocnh_040516', 3, '3024', 'Nông Hữu Phước', 'Nam', '2016-05-03', NULL, 72.00, NULL, 72.00, NULL, 72.00, 70.00, 77.00, 76.00, 'Đạt', '2016', '2025-12-17 09:58:49.582378', '2025-12-17 09:58:49.582378'),
(160, NULL, 'CLB_00468', 166, 'HV_minh(1)tb_120216', 3, '3025', 'Trần Bảo Minh(1)', 'Nam', '2016-02-11', NULL, 73.00, NULL, 72.00, NULL, 72.00, 72.00, 76.00, 76.00, 'Đạt', '2016', '2025-12-17 09:58:49.643039', '2025-12-17 09:58:49.643039'),
(161, NULL, 'CLB_00468', 167, 'HV_phucdg_210516', 3, '3026', 'Dương Gia Phúc', 'Nam', '2016-05-20', NULL, 72.00, NULL, 72.00, NULL, 72.00, 73.00, 77.00, 76.00, 'Đạt', '2016', '2025-12-17 09:58:49.666570', '2025-12-17 09:58:49.666570'),
(162, NULL, 'CLB_00468', 168, 'HV_baotng_090114', 3, '3027', 'Trần Nguyễn Gia Bảo', 'Nam', '2014-01-08', NULL, 72.00, NULL, 72.00, NULL, 72.00, 73.00, 76.00, 76.00, 'Đạt', '2014', '2025-12-17 09:58:49.691183', '2025-12-17 09:58:49.691183'),
(163, NULL, 'CLB_00468', 169, 'HV_nhanct_140914', 3, '3028', 'Chu Thiện Nhân', 'Nam', '2014-09-13', NULL, 73.00, NULL, 72.00, NULL, 73.00, 71.00, 77.00, 76.00, 'Đạt', '2014', '2025-12-17 09:58:49.708640', '2025-12-17 09:58:49.708640'),
(164, NULL, 'CLB_00468', 170, 'HV_hunglvg_230214', 3, '3029', 'Lưu Văn Gia Hưng', 'Nam', '2014-02-22', NULL, 71.00, NULL, 72.00, NULL, 72.00, 72.00, 78.00, 76.00, 'Đạt', '2014', '2025-12-17 09:58:49.730433', '2025-12-17 09:58:49.730433'),
(165, NULL, 'CLB_00468', 171, 'HV_nhattm_140813', 3, '3030', 'Trần Minh Nhật', 'Nam', '2013-08-13', NULL, 72.00, NULL, 72.00, NULL, 72.00, 72.00, 76.00, 76.00, 'Đạt', '2013', '2025-12-17 09:58:49.747232', '2025-12-17 09:58:49.747232'),
(166, NULL, 'CLB_00468', 172, 'HV_hieuhh_050711', 3, '3031', 'Hà Huy Hiếu', 'Nam', '2011-07-04', NULL, 73.00, NULL, 72.00, NULL, 73.00, 73.00, 77.00, 76.00, 'Đạt', '2011', '2025-12-17 09:58:49.778523', '2025-12-17 09:58:49.778523'),
(167, NULL, 'CLB_00468', 173, 'HV_phongnmn_311211', 3, '3032', 'Nguyễn Mã Ngọc Phong', 'Nam', '2011-12-30', NULL, 73.00, NULL, 73.00, NULL, 73.00, 73.00, 78.00, 76.00, 'Đạt', '2011', '2025-12-17 09:58:49.798966', '2025-12-17 09:58:49.798966'),
(168, NULL, 'CLB_00468', 174, 'HV_hieutv_251009', 3, '3033', 'Trần Văn Hiếu', 'Nam', '2009-10-24', NULL, 74.00, NULL, 75.00, NULL, 75.00, 75.00, 76.00, 76.00, 'Đạt', '2009', '2025-12-17 09:58:49.815677', '2025-12-17 09:58:49.815677'),
(169, NULL, 'CLB_00468', 175, 'HV_chihlq_230917', 2, '2001', 'Hà Linh Quỳnh Chi', 'Nam', '2017-09-22', NULL, 72.00, NULL, 72.00, NULL, 72.00, 72.00, 77.00, 75.00, 'Đạt', '2017', '2025-12-17 09:58:49.837398', '2025-12-17 09:58:49.837398'),
(170, NULL, 'CLB_00468', 176, 'HV_anhlk_241117', 2, '2002', 'Lục Kim Anh', 'Nam', '2017-11-23', NULL, 72.00, NULL, 73.00, NULL, 71.00, 73.00, 77.00, 75.00, 'Đạt', '2017', '2025-12-17 09:58:49.860340', '2025-12-17 09:58:49.860340'),
(171, NULL, 'CLB_00468', 177, 'HV_vynt_280917', 2, '2003', 'Nguyễn Thanh Vy', 'Nam', '2017-09-27', NULL, 74.00, NULL, 75.00, NULL, 74.00, 72.00, 76.00, 75.00, 'Đạt', '2017', '2025-12-17 09:58:49.888081', '2025-12-17 09:58:49.888081'),
(172, NULL, 'CLB_00468', 178, 'HV_tructtn_030916', 2, '2004', 'Triệu Thị Ngọc Trúc', 'Nam', '2016-09-02', NULL, 73.00, NULL, 72.00, NULL, 73.00, 72.00, 77.00, 75.00, 'Đạt', '2016', '2025-12-17 09:58:49.907556', '2025-12-17 09:58:49.907556'),
(173, NULL, 'CLB_00468', 179, 'HV_annhb_130316', 2, '2005', 'Nguyễn Hoàng Bảo An', 'Nam', '2016-03-12', NULL, 73.00, NULL, 73.00, NULL, 72.00, 72.00, 77.00, 75.00, 'Đạt', '2016', '2025-12-17 09:58:49.931398', '2025-12-17 09:58:49.931398'),
(174, NULL, 'CLB_00468', 180, 'HV_ngannnk_280315', 2, '2006', 'Nguyễn Ngọc Kim Ngân', 'Nam', '2015-03-27', NULL, 73.00, NULL, 73.00, NULL, 73.00, 72.00, 76.00, 75.00, 'Đạt', '2015', '2025-12-17 09:58:49.954185', '2025-12-17 09:58:49.954185'),
(175, NULL, 'CLB_00468', 181, 'HV_nhunt_281215', 2, '2007', 'Nguyễn Thanh Như', 'Nam', '2015-12-27', NULL, 73.00, NULL, 72.00, NULL, 72.00, 72.00, 78.00, 75.00, 'Đạt', '2015', '2025-12-17 09:58:49.973902', '2025-12-17 09:58:49.973902'),
(176, NULL, 'CLB_00468', 182, 'HV_anhnb_300115', 2, '2008', 'Hoàng Nữ Bình An', 'Nam', '2015-01-29', NULL, 73.00, NULL, 72.00, NULL, 72.00, 72.00, 77.00, 75.00, 'Đạt', '2015', '2025-12-17 09:58:49.992958', '2025-12-17 09:58:49.992958'),
(177, NULL, 'CLB_00468', 183, 'HV_nghidp_090114', 2, '2009', 'Dương Phương Nghi', 'Nam', '2014-01-08', NULL, 72.00, NULL, 72.00, NULL, 73.00, 72.00, 76.00, 76.00, 'Đạt', '2014', '2025-12-17 09:58:50.066805', '2025-12-17 09:58:50.066805'),
(178, NULL, 'CLB_00468', 184, 'HV_thulm_120414', 2, '2010', 'Lục Minh Thư', 'Nam', '2014-04-11', NULL, 73.00, NULL, 73.00, NULL, 72.00, 72.00, 77.00, 76.00, 'Đạt', '2014', '2025-12-17 09:58:50.092783', '2025-12-17 09:58:50.092783'),
(179, NULL, 'CLB_00468', 185, 'HV_anhvnh_271014', 2, '2011', 'Vũ Ngọc Hoàng Anh', 'Nam', '2014-10-26', NULL, 75.00, NULL, 75.00, NULL, 74.00, 74.00, 76.00, 76.00, 'Đạt', '2014', '2025-12-17 09:58:50.121890', '2025-12-17 09:58:50.121890'),
(180, NULL, 'CLB_00468', 186, 'HV_anhbtn_020113', 2, '2012', 'Bùi Thị Ngọc Ánh', 'Nam', '2013-01-01', NULL, 74.00, NULL, 74.00, NULL, 72.00, 72.00, 77.00, 76.00, 'Đạt', '2013', '2025-12-17 09:58:50.159796', '2025-12-17 09:58:50.159796'),
(181, NULL, 'CLB_00468', 187, 'HV_antnb_020212', 2, '2013', 'Trần Nguyễn Bảo An', 'Nam', '2012-02-01', NULL, 73.00, NULL, 73.00, NULL, 72.00, 72.00, 77.00, 76.00, 'Đạt', '2012', '2025-12-17 09:58:50.185008', '2025-12-17 09:58:50.185008'),
(182, NULL, 'CLB_00468', 188, 'HV_minhpn_160217', 2, '2014', 'Phùng Ngọc Minh', 'Nam', '2017-02-15', NULL, 73.00, NULL, 72.00, NULL, 72.00, 72.00, 78.00, 76.00, 'Đạt', '2017', '2025-12-17 09:58:50.208534', '2025-12-17 09:58:50.208534'),
(183, NULL, 'CLB_00468', 189, 'HV_liemnt_080516', 2, '2015', 'Nguyễn Thế Liêm', 'Nam', '2016-05-07', NULL, 71.00, NULL, 73.00, NULL, 73.00, 73.00, 77.00, 76.00, 'Đạt', '2016', '2025-12-17 09:58:50.241129', '2025-12-17 09:58:50.241129'),
(184, NULL, 'CLB_00468', 190, 'HV_huyhd_081215', 2, '2016', 'Hà Đức Huy', 'Nam', '2015-12-07', NULL, 73.00, NULL, 74.00, NULL, 72.00, 73.00, 76.00, 76.00, 'Đạt', '2015', '2025-12-17 09:58:50.303273', '2025-12-17 09:58:50.303273'),
(185, NULL, 'CLB_00468', 191, 'HV_hunglg_181115', 2, '2017', 'Lê Gia Hưng', 'Nam', '2015-11-17', NULL, 73.00, NULL, 73.00, NULL, 73.00, 73.00, 77.00, 76.00, 'Đạt', '2015', '2025-12-17 09:58:50.322338', '2025-12-17 09:58:50.322338'),
(186, NULL, 'CLB_00468', 192, 'HV_quannb_211114', 2, '2018', 'Nguyễn Bảo Quân', 'Nam', '2014-11-20', NULL, 73.00, NULL, 72.00, NULL, 72.00, 72.00, 77.00, 75.00, 'Đạt', '2014', '2025-12-17 09:58:50.344186', '2025-12-17 09:58:50.344186'),
(187, NULL, 'CLB_00468', 193, 'HV_khangdt_120714', 2, '2019', 'Đào Tuấn Khang', 'Nam', '2014-07-11', NULL, 73.00, NULL, 73.00, NULL, 71.00, 73.00, 78.00, 76.00, 'Đạt', '2014', '2025-12-17 09:58:50.363724', '2025-12-17 09:58:50.363724'),
(188, NULL, 'CLB_00468', 194, 'HV_thanhnc_070712', 2, '2020', 'Nguyễn Chí Thanh', 'Nam', '2012-07-06', NULL, 73.00, NULL, 72.00, NULL, 73.00, 72.00, 78.00, 76.00, 'Đạt', '2012', '2025-12-17 09:58:50.387245', '2025-12-17 09:58:50.387245'),
(189, NULL, 'CLB_00468', 195, 'HV_hungnm_201012', 2, '2021', 'Nông Mạnh Hùng', 'Nam', '2012-10-19', NULL, 72.00, NULL, 72.00, NULL, 72.00, 72.00, 77.00, 75.00, 'Đạt', '2012', '2025-12-17 09:58:50.407653', '2025-12-17 09:58:50.407653'),
(190, NULL, 'CLB_00468', 196, 'HV_phuoctt_271012', 2, '2022', 'Trần Thanh Phước', 'Nam', '2012-10-26', NULL, 73.00, NULL, 73.00, NULL, 72.00, 72.00, 77.00, 75.00, 'Đạt', '2012', '2025-12-17 09:58:50.434969', '2025-12-17 09:58:50.434969'),
(191, NULL, 'CLB_00468', 197, 'HV_tintt_081111', 2, '2023', 'Trần Thanh Tín', 'Nam', '2011-11-07', NULL, 74.00, NULL, 74.00, NULL, 74.00, 74.00, 76.00, 76.00, 'Đạt', '2011', '2025-12-17 09:58:50.456403', '2025-12-17 09:58:50.456403'),
(192, NULL, 'CLB_00468', 198, 'HV_anhhpb_280216', 1, '1001', 'Hoàng Phạm Bảo Anh', 'Nam', '2016-02-27', NULL, 72.00, NULL, 72.00, NULL, 73.00, 72.00, 77.00, 75.00, 'Đạt', '2016', '2025-12-17 09:58:50.484379', '2025-12-17 09:58:50.484379'),
(193, NULL, 'CLB_00468', 199, 'HV_chauntm_030715', 1, '1002', 'Nguyễn Thị Minh Châu', 'Nam', '2015-07-02', NULL, 74.00, NULL, 74.00, NULL, 74.00, 72.00, 76.00, 75.00, 'Đạt', '2015', '2025-12-17 09:58:50.547333', '2025-12-17 09:58:50.547333'),
(194, NULL, 'CLB_00468', 200, 'HV_thyntq_150915', 1, '1003', 'Nguyễn Thị Quỳnh Thy', 'Nam', '2015-09-14', NULL, 73.00, NULL, 73.00, NULL, 72.00, 73.00, 77.00, 75.00, 'Đạt', '2015', '2025-12-17 09:58:50.567036', '2025-12-17 09:58:50.567036'),
(195, NULL, 'CLB_00468', 201, 'HV_chittp_271214', 1, '1004', 'Trần Thị Phương Chi', 'Nam', '2014-12-26', NULL, 73.00, NULL, 72.00, NULL, 72.00, 72.00, 74.00, 75.00, 'Đạt', '2014', '2025-12-17 09:58:50.587608', '2025-12-17 09:58:50.587608'),
(196, NULL, 'CLB_00468', 202, 'HV_handng_010413', 1, '1005', 'Đinh Ngọc Gia Hân', 'Nam', '2013-03-31', NULL, 72.00, NULL, 71.00, NULL, 72.00, 72.00, 78.00, 76.00, 'Đạt', '2013', '2025-12-17 09:58:50.610799', '2025-12-17 09:58:50.610799'),
(197, NULL, 'CLB_00468', 203, 'HV_antnh_201213', 1, '1006', 'Tạ Nguyễn Hồng Ân', 'Nam', '2013-12-19', NULL, 73.00, NULL, 72.00, NULL, 72.00, 72.00, 77.00, 76.00, 'Đạt', '2013', '2025-12-17 09:58:50.637244', '2025-12-17 09:58:50.637244'),
(198, NULL, 'CLB_00468', 204, 'HV_yenttn_121112', 1, '1007', 'Triệu Thị Ngọc Yến', 'Nam', '2012-11-11', NULL, 73.00, NULL, 72.00, NULL, 73.00, 72.00, 76.00, 76.00, 'Đạt', '2012', '2025-12-17 09:58:50.662783', '2025-12-17 09:58:50.662783'),
(199, NULL, 'CLB_00468', 205, 'HV_vanttt_161112', 1, '1008', 'Triệu Thị Thúy Vân', 'Nam', '2012-11-15', NULL, 72.00, NULL, 72.00, NULL, 72.00, 73.00, 74.00, 76.00, 'Đạt', '2012', '2025-12-17 09:58:50.692499', '2025-12-17 09:58:50.692499'),
(200, NULL, 'CLB_00468', 206, 'HV_nhipy_011111', 1, '1009', 'Phạm Yến Nhi', 'Nam', '2011-10-31', NULL, 73.00, NULL, 73.00, NULL, 73.00, 73.00, 77.00, 72.00, 'Đạt', '2011', '2025-12-17 09:58:50.722616', '2025-12-17 09:58:50.722616'),
(201, NULL, 'CLB_00468', 207, 'HV_hueltt_060811', 1, '1010', 'Lư Thị Tuyết Huệ', 'Nam', '2011-08-05', NULL, 75.00, NULL, 74.00, NULL, 71.00, 74.00, 75.00, 76.00, 'Đạt', '2011', '2025-12-17 09:58:50.798127', '2025-12-17 09:58:50.798127'),
(202, NULL, 'CLB_00468', 208, 'HV_minhhnb_070111', 1, '1011', 'Hoàng Nữ Bình Minh', 'Nam', '2011-01-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Chưa có kết quả', '2011', '2025-12-17 09:58:50.822228', '2025-12-17 09:58:50.822228'),
(203, NULL, 'CLB_00468', 209, 'HV_chaulm_141011', 1, '1012', 'Lục Minh Châu', 'Nam', '2011-10-13', NULL, 72.00, NULL, 73.00, NULL, 73.00, 73.00, 78.00, 76.00, 'Đạt', '2011', '2025-12-17 09:58:50.852855', '2025-12-17 09:58:50.852855'),
(204, NULL, 'CLB_00468', 210, 'HV_chintd_200911', 1, '1013', 'Nguyễn Thị Diệp Chi', 'Nam', '2011-09-19', NULL, 72.00, NULL, 72.00, NULL, 73.00, 73.00, 75.00, 76.00, 'Đạt', '2011', '2025-12-17 09:58:50.871887', '2025-12-17 09:58:50.871887'),
(205, NULL, 'CLB_00468', 211, 'HV_nhusny_280211', 1, '1014', 'Sầm Ngọc Yến Như', 'Nam', '2011-02-27', NULL, 72.00, NULL, 72.00, NULL, 73.00, 72.00, 77.00, 76.00, 'Đạt', '2011', '2025-12-17 09:58:50.895961', '2025-12-17 09:58:50.895961'),
(206, NULL, 'CLB_00468', 212, 'HV_tuyenltt_041010', 1, '1015', 'Lê Thị Thanh Tuyền', 'Nam', '2010-10-03', NULL, 72.00, NULL, 73.00, NULL, 72.00, 73.00, 76.00, 76.00, 'Đạt', '2010', '2025-12-17 09:58:50.924022', '2025-12-17 09:58:50.924022'),
(207, NULL, 'CLB_00468', 213, 'HV_cuongdq_071117', 1, '1016', 'Đỗ Quốc Cường', 'Nam', '2017-11-06', NULL, 72.00, NULL, 73.00, NULL, 73.00, 73.00, 74.00, 76.00, 'Đạt', '2017', '2025-12-17 09:58:50.945474', '2025-12-17 09:58:50.945474'),
(208, NULL, 'CLB_00468', 214, 'HV_antt_180317', 1, '1017', 'Triệu Thiên Ân', 'Nam', '2017-03-17', NULL, 72.00, NULL, 73.00, NULL, 73.00, 72.00, 77.00, 76.00, 'Đạt', '2017', '2025-12-17 09:58:50.963641', '2025-12-17 09:58:50.963641'),
(209, NULL, 'CLB_00468', 215, 'HV_anhtt_090116', 1, '1018', 'Huỳnh Thái Tâm An', 'Nam', '2016-01-08', NULL, 73.00, NULL, 72.00, NULL, 73.00, 72.00, 77.00, 76.00, 'Đạt', '2016', '2025-12-17 09:58:50.985571', '2025-12-17 09:58:50.985571'),
(210, NULL, 'CLB_00468', 216, 'HV_nhatlm_020416', 1, '1019', 'Lê Minh Nhật', 'Nam', '2016-04-01', NULL, 72.00, NULL, 72.00, NULL, 73.00, 72.00, 74.00, 76.00, 'Đạt', '2016', '2025-12-17 09:58:51.055199', '2025-12-17 09:58:51.055199'),
(211, NULL, 'CLB_00468', 217, 'HV_thiennm_041215', 1, '1020', 'Nguyễn Minh Thiện', 'Nam', '2015-12-03', NULL, 72.00, NULL, 72.00, NULL, 72.00, 72.00, 74.00, 76.00, 'Đạt', '2015', '2025-12-17 09:58:51.092546', '2025-12-17 09:58:51.092546');
INSERT INTO `ket_qua_thi` (`id`, `test_id`, `ma_clb`, `user_id`, `ma_hoi_vien`, `cap_dai_du_thi_id`, `so_thi`, `ho_va_ten`, `gioi_tinh`, `ngay_thang_nam_sinh`, `ky_thuat_tan_can_ban`, `nguyen_tac_phat_luc`, `can_ban_tay`, `ky_thuat_chan`, `can_ban_tu_ve`, `bai_quyen`, `phan_the_bai_quyen`, `song_dau`, `the_luc`, `ket_qua`, `ghi_chu`, `created_at`, `updated_at`) VALUES
(212, NULL, 'CLB_00468', 218, 'HV_doandt_050115', 1, '1021', 'Đặng Trung Đoàn', 'Nam', '2015-01-04', NULL, 75.00, NULL, 74.00, NULL, 75.00, 75.00, 78.00, 75.00, 'Đạt', '2015', '2025-12-17 09:58:51.124942', '2025-12-17 09:58:51.124942'),
(213, NULL, 'CLB_00468', 219, 'HV_kietta_240615', 1, '1022', 'Triệu Anh Kiệt', 'Nam', '2015-06-23', NULL, 73.00, NULL, 73.00, NULL, 72.00, 72.00, 77.00, 76.00, 'Đạt', '2015', '2025-12-17 09:58:51.158686', '2025-12-17 09:58:51.158686'),
(214, NULL, 'CLB_00468', 220, 'HV_khoand_070114', 1, '1023', 'Nguyễn Đăng Khoa', 'Nam', '2014-01-06', NULL, 74.00, NULL, 75.00, NULL, 74.00, 74.00, 77.00, 76.00, 'Đạt', '2014', '2025-12-17 09:58:51.185661', '2025-12-17 09:58:51.185661'),
(215, NULL, 'CLB_00468', 221, 'HV_tripcm_150714', 1, '1024', 'Phan Công Minh Trí', 'Nam', '2014-07-14', NULL, 73.00, NULL, 73.00, NULL, 73.00, 73.00, 75.00, 76.00, 'Đạt', '2014', '2025-12-17 09:58:51.204956', '2025-12-17 09:58:51.204956'),
(216, NULL, 'CLB_00468', 222, 'HV_baong_150914', 1, '1025', 'Nguyễn Gia Bảo', 'Nam', '2014-09-14', NULL, 73.00, NULL, 73.00, NULL, 72.00, 72.00, 76.00, 76.00, 'Đạt', '2014', '2025-12-17 09:58:51.235644', '2025-12-17 09:58:51.235644'),
(217, NULL, 'CLB_00468', 223, 'HV_hungng_221113', 1, '1026', 'Nguyễn Gia Hưng', 'Nam', '2013-11-21', NULL, 73.00, NULL, 72.00, NULL, 72.00, 73.00, 75.00, 76.00, 'Đạt', '2013', '2025-12-17 09:58:51.278748', '2025-12-17 09:58:51.278748'),
(218, NULL, 'CLB_00468', 224, 'HV_nambq_290713', 1, '1027', 'Bùi Quốc Nam', 'Nam', '2013-07-28', NULL, 73.00, NULL, 74.00, NULL, 74.00, 73.00, 77.00, 76.00, 'Đạt', '2013', '2025-12-17 09:58:51.326212', '2025-12-17 09:58:51.326212'),
(219, NULL, 'CLB_00468', 225, 'HV_longlh_090712', 1, '1028', 'Lý Hoàng Long', 'Nam', '2012-07-08', NULL, 73.00, NULL, 72.00, NULL, 72.00, 73.00, 78.00, 76.00, 'Đạt', '2012', '2025-12-17 09:58:51.350161', '2025-12-17 09:58:51.350161'),
(220, NULL, 'CLB_00468', 226, 'HV_vinhtc_310712', 1, '1029', 'Triệu Công Vĩnh', 'Nam', '2012-07-30', NULL, 73.00, NULL, 73.00, NULL, 74.00, 74.00, 74.00, 76.00, 'Đạt', '2012', '2025-12-17 09:58:51.382757', '2025-12-17 09:58:51.382757'),
(221, NULL, 'CLB_00468', 227, 'HV_thiendm_190712', 1, '1030', 'Đỗ Minh Thiện', 'Nam', '2012-07-18', NULL, 73.00, NULL, 72.00, NULL, 73.00, 73.00, 75.00, 76.00, 'Đạt', '2012', '2025-12-17 09:58:51.411775', '2025-12-17 09:58:51.411775'),
(222, NULL, 'CLB_00468', 228, 'HV_phattm_251011', 1, '1031', 'Tạ Minh Phát', 'Nam', '2011-10-24', NULL, 72.00, NULL, 72.00, NULL, 72.00, 73.00, 74.00, 76.00, 'Đạt', '2011', '2025-12-17 09:58:51.434297', '2025-12-17 09:58:51.434297'),
(223, NULL, 'CLB_00468', 229, 'HV_datnk_290411', 1, '1032', 'Nông Khánh Đạt', 'Nam', '2011-04-28', NULL, 71.00, NULL, 73.00, NULL, 72.00, 73.00, 77.00, 76.00, 'Đạt', '2011', '2025-12-17 09:58:51.452251', '2025-12-17 09:58:51.452251'),
(224, NULL, 'CLB_00468', 230, 'HV_quanndm_111011', 1, '1033', 'Nguyễn Đồng Minh Quân', 'Nam', '2011-10-10', NULL, 74.00, NULL, 72.00, NULL, 73.00, 73.00, 76.00, 76.00, 'Đạt', '2011', '2025-12-17 09:58:51.474368', '2025-12-17 09:58:51.474368'),
(225, NULL, 'CLB_00468', 231, 'HV_phuonghm_100510', 1, '1034', 'Hoàng Minh Phương', 'Nam', '2010-05-09', NULL, 73.00, NULL, 73.00, NULL, 72.00, 72.00, 75.00, 76.00, 'Đạt', '2010', '2025-12-17 09:58:51.496825', '2025-12-17 09:58:51.496825');

-- --------------------------------------------------------

--
-- Table structure for table `khoa_hoc`
--

CREATE TABLE `khoa_hoc` (
  `id` int NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `level` enum('beginner','intermediate','advanced') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'beginner',
  `quarter` enum('Q1','Q2','Q3','Q4') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` int DEFAULT NULL,
  `coach_id` int DEFAULT NULL,
  `club_id` int DEFAULT NULL,
  `branch_id` int DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `current_students` int NOT NULL DEFAULT '0',
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ky_thi_thang_cap`
--

CREATE TABLE `ky_thi_thang_cap` (
  `id` int NOT NULL,
  `test_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `test_date` date DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `examiner_id` int DEFAULT NULL,
  `club_id` int DEFAULT NULL,
  `max_participants` int DEFAULT NULL,
  `registration_deadline` date DEFAULT NULL,
  `test_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('upcoming','ongoing','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'upcoming',
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ky_thi_thang_cap`
--

INSERT INTO `ky_thi_thang_cap` (`id`, `test_name`, `test_date`, `location`, `examiner_id`, `club_id`, `max_participants`, `registration_deadline`, `test_fee`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Kỳ thi Q3.2025', '2025-11-15', NULL, NULL, NULL, NULL, NULL, 0.00, 'completed', '2025-11-15 10:31:18.000000', '2025-11-15 10:31:18.000000');

-- --------------------------------------------------------

--
-- Table structure for table `lich_hoc`
--

CREATE TABLE `lich_hoc` (
  `id` int NOT NULL,
  `course_id` int DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `location` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  `day_of_week` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lich_su_thi_thang_cap_dai`
--

CREATE TABLE `lich_su_thi_thang_cap_dai` (
  `id` int NOT NULL,
  `vo_sinh_id` int NOT NULL,
  `bai_quyen_id` int NOT NULL,
  `cap_dai_id` int NOT NULL,
  `diem_so` decimal(5,2) DEFAULT NULL,
  `ket_qua` enum('dat','khong_dat','xuat_sac') COLLATE utf8mb4_unicode_ci DEFAULT 'khong_dat',
  `ngay_thi` date NOT NULL,
  `ghi_chu` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phan_hoi`
--

CREATE TABLE `phan_hoi` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `course_id` int DEFAULT NULL,
  `coach_id` int DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `feedback_type` enum('course','coach','facility','general') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `is_anonymous` tinyint NOT NULL DEFAULT '0',
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phu_huynh`
--

CREATE TABLE `phu_huynh` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `relationship` enum('father','mother','guardian','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact` tinyint NOT NULL DEFAULT '0',
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active_status` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quan_ly_chi_nhanh`
--

CREATE TABLE `quan_ly_chi_nhanh` (
  `id` int NOT NULL,
  `branch_id` int NOT NULL,
  `manager_id` int NOT NULL,
  `role` enum('main_manager','assistant_manager') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'main_manager',
  `is_active` tinyint NOT NULL DEFAULT '1',
  `assigned_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quan_ly_chi_nhanh`
--

INSERT INTO `quan_ly_chi_nhanh` (`id`, `branch_id`, `manager_id`, `role`, `is_active`, `assigned_at`) VALUES
(1, 1, 3, 'main_manager', 1, '2025-11-09 14:17:53.000000'),
(2, 2, 3, 'main_manager', 1, '2025-11-09 14:17:53.000000'),
(3, 3, 3, 'main_manager', 1, '2025-11-09 14:17:53.000000');

-- --------------------------------------------------------

--
-- Table structure for table `su_kien`
--

CREATE TABLE `su_kien` (
  `id` int NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `event_type` enum('tournament','seminar','graduation','social','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `club_id` int DEFAULT NULL,
  `status` enum('upcoming','ongoing','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'upcoming',
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `thang_cap_dai`
--

CREATE TABLE `thang_cap_dai` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `from_belt_id` int DEFAULT NULL,
  `to_belt_id` int DEFAULT NULL,
  `promotion_date` date DEFAULT NULL,
  `coach_id` int DEFAULT NULL,
  `test_score` decimal(5,2) DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `thanh_toan`
--

CREATE TABLE `thanh_toan` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` date DEFAULT NULL,
  `month` int DEFAULT NULL,
  `year` int DEFAULT NULL,
  `status` enum('paid','pending','late') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'paid',
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `thong_bao`
--

CREATE TABLE `thong_bao` (
  `id` int NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `type` enum('general','payment','event','course','promotion') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `target_audience` enum('all','students','coaches','admins','HLV') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `club_id` int DEFAULT NULL,
  `is_urgent` tinyint NOT NULL DEFAULT '0',
  `published_at` datetime DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `thu_vien`
--

CREATE TABLE `thu_vien` (
  `id` int NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `file_url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_type` enum('image','video') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'image',
  `mime_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` bigint DEFAULT NULL COMMENT 'File size in bytes',
  `club_id` int DEFAULT NULL,
  `branch_id` int DEFAULT NULL,
  `created_by` int DEFAULT NULL COMMENT 'User ID who uploaded the media',
  `is_active` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tien_trinh_hoc_tap`
--

CREATE TABLE `tien_trinh_hoc_tap` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `course_id` int DEFAULT NULL,
  `lesson_date` date DEFAULT NULL,
  `lesson_content` text COLLATE utf8mb4_unicode_ci,
  `skills_learned` text COLLATE utf8mb4_unicode_ci,
  `homework` text COLLATE utf8mb4_unicode_ci,
  `coach_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tin_nhan_lien_he`
--

CREATE TABLE `tin_nhan_lien_he` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('new','read','replied','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tin_nhan_lien_he`
--

INSERT INTO `tin_nhan_lien_he` (`id`, `name`, `email`, `phone`, `subject`, `message`, `status`, `created_at`) VALUES
(1, 'minhle', 'letuanminh2409@gmail.com', '0344712604', 'hỗ trợ', 'hi', 'new', '2025-12-20 09:46:58.097688');

-- --------------------------------------------------------

--
-- Table structure for table `tin_tuc`
--

CREATE TABLE `tin_tuc` (
  `id` int NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `author_id` int DEFAULT NULL,
  `featured_image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `images` text COLLATE utf8mb4_unicode_ci,
  `is_published` tinyint NOT NULL DEFAULT '0',
  `published_at` datetime DEFAULT NULL,
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tro_giang_chi_nhanh`
--

CREATE TABLE `tro_giang_chi_nhanh` (
  `id` int NOT NULL,
  `branch_id` int NOT NULL,
  `assistant_id` int NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT '1',
  `assigned_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tro_giang_chi_nhanh`
--

INSERT INTO `tro_giang_chi_nhanh` (`id`, `branch_id`, `assistant_id`, `is_active`, `assigned_at`) VALUES
(1, 1, 4, 1, '2025-11-09 14:17:53.000000'),
(3, 2, 4, 1, '2025-11-09 14:17:53.000000'),
(5, 3, 4, 1, '2025-11-09 14:17:53.000000');

-- --------------------------------------------------------

--
-- Table structure for table `vo_sinh`
--

CREATE TABLE `vo_sinh` (
  `id` int NOT NULL,
  `ho_va_ten` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Họ và tên đầy đủ',
  `ngay_thang_nam_sinh` date NOT NULL COMMENT 'Ngày tháng năm sinh',
  `ma_clb` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã câu lạc bộ',
  `ma_don_vi` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã đơn vị',
  `quyen_so` int NOT NULL COMMENT 'Quyền số',
  `cap_dai_id` int NOT NULL COMMENT 'Cấp đai hiện tại',
  `gioi_tinh` enum('Nam','Nữ') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Giới tính',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `emergency_contact_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active_status` tinyint NOT NULL DEFAULT '1',
  `profile_image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mật khẩu đăng nhập cho võ sinh',
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  `chi_nhanh_id` int DEFAULT NULL,
  `cau_lac_bo_id` int DEFAULT NULL,
  `ma_hoi_vien` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã hội viên',
  `ma_hv` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã HV'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vo_sinh`
--

INSERT INTO `vo_sinh` (`id`, `ho_va_ten`, `ngay_thang_nam_sinh`, `ma_clb`, `ma_don_vi`, `quyen_so`, `cap_dai_id`, `gioi_tinh`, `email`, `phone`, `address`, `emergency_contact_name`, `emergency_contact_phone`, `active_status`, `profile_image_url`, `password`, `created_at`, `updated_at`, `chi_nhanh_id`, `cau_lac_bo_id`, `ma_hoi_vien`, `ma_hv`) VALUES
(37, 'Nguyễn Như Thư Kỳ', '2000-01-01', 'CLB_00468', 'DNAI', 1, 9, 'Nam', 'hvkynnt070210@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:48.294777', '2025-12-17 10:25:19.033224', NULL, NULL, 'HV_kynnt_070210', NULL),
(38, 'Vũ Tường Vy', '2000-01-01', 'CLB_00468', 'DNAI', 1, 9, 'Nam', 'hvvyvt040209@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:48.317702', '2025-12-17 10:25:19.033224', NULL, NULL, 'HV_vyvt_040209', NULL),
(39, 'Võ Nhật Huy', '2000-01-01', 'CLB_00468', 'DNAI', 1, 9, 'Nam', 'hvhuyvn161219@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:48.357779', '2025-12-17 10:25:19.033224', NULL, NULL, 'HV_huyvn_161219', NULL),
(40, 'Cao Minh Khang', '2000-01-01', 'CLB_00468', 'DNAI', 1, 9, 'Nam', 'hvkhangcm150618@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:48.388958', '2025-12-17 10:25:19.033224', NULL, NULL, 'HV_khangcm_150618', NULL),
(41, 'Phí Anh Minh', '2000-01-01', 'CLB_00468', 'DNAI', 1, 9, 'Nam', 'hvminhpa060418@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:48.422688', '2025-12-17 10:25:19.033224', NULL, NULL, 'HV_minhpa_060418', NULL),
(42, 'Phạm Nhật Huy', '2000-01-01', 'CLB_00468', 'DNAI', 1, 9, 'Nam', 'hvhuypn180718@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:48.451248', '2025-12-17 10:25:19.033224', NULL, NULL, 'HV_huypn_180718', NULL),
(44, 'Nguyễn Phạm Khánh Nguyện', '2000-01-01', 'CLB_00468', 'DNAI', 1, 9, 'Nam', 'hvnguyennpk140918@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:48.506747', '2025-12-17 10:25:19.033224', NULL, NULL, 'HV_nguyennpk_140918', NULL),
(46, 'Trần Anh Minh', '2000-01-01', 'CLB_00468', 'DNAI', 1, 9, 'Nam', 'hvminhta260216@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:48.583449', '2025-12-17 10:25:19.033224', NULL, NULL, 'HV_minhta_260216', NULL),
(47, 'Hồ Thiên Khánh', '2000-01-01', 'CLB_00468', 'DNAI', 1, 9, 'Nam', 'hvkhanhht220616@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:48.604535', '2025-12-17 10:25:19.033224', NULL, NULL, 'HV_khanhht_220616', NULL),
(48, 'Nguyễn Phong', '2000-01-01', 'CLB_00468', 'DNAI', 1, 9, 'Nam', 'hvphongn020416@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:48.657198', '2025-12-17 10:25:19.033224', NULL, NULL, 'HV_phongn_020416', NULL),
(53, 'Vũ Nguyễn Thành Luân', '2000-01-01', 'CLB_00468', 'DNAI', 1, 9, 'Nam', 'hvluanvnt240715@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:48.829459', '2025-12-17 10:25:19.033224', NULL, NULL, 'HV_luanvnt_240715', NULL),
(56, 'Huỳnh Ngọc Minh', '2000-01-01', 'CLB_00468', 'DNAI', 1, 9, 'Nam', 'hvminhhn211014@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:48.898276', '2025-12-17 10:25:19.033224', NULL, NULL, 'HV_minhhn_211014', NULL),
(57, 'Vũ Tuấn Khang', '2000-01-01', 'CLB_00468', 'DNAI', 1, 9, 'Nam', 'hvkhangvt270614@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:48.916960', '2025-12-17 10:25:19.033224', NULL, NULL, 'HV_khangvt_270614', NULL),
(59, 'Phạm Gia Huy', '2000-01-01', 'CLB_00468', 'DNAI', 1, 9, 'Nam', 'hvhuypg120713@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:48.957059', '2025-12-17 10:25:19.033224', NULL, NULL, 'HV_huypg_120713', NULL),
(60, 'Vũ Tuấn Minh', '2000-01-01', 'CLB_00468', 'DNAI', 1, 9, 'Nam', 'hvminhvt050413@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:48.977002', '2025-12-17 10:25:19.033224', NULL, NULL, 'HV_minhvt_050413', NULL),
(61, 'Hoàng Hữu Phú', '2000-01-01', 'CLB_00468', 'DNAI', 1, 9, 'Nam', 'hvphuhh040213@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:48.995086', '2025-12-17 10:25:19.033224', NULL, NULL, 'HV_phuhh_040213', NULL),
(62, 'Đặng Mạnh Tường', '2000-01-01', 'CLB_00468', 'DNAI', 1, 9, 'Nam', 'hvtuongdm010313@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.013742', '2025-12-17 10:25:19.033224', NULL, NULL, 'HV_tuongdm_010313', NULL),
(63, 'Đặng Hoàng Long', '2000-01-01', 'CLB_00468', 'DNAI', 1, 9, 'Nam', 'hvlongdh040513@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.041719', '2025-12-17 10:25:19.033224', NULL, NULL, 'HV_longdh_040513', NULL),
(64, 'Huỳnh Thái Tâm Như', '2000-01-01', 'CLB_00468', 'DNAI', 5, 6, 'Nam', 'hvnhuhtt121118@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.062861', '2025-12-17 16:58:07.478039', NULL, NULL, 'HV_nhuhtt_121118', NULL),
(65, 'Trần Gia Nhi', '2000-01-01', 'CLB_00468', 'DNAI', 5, 6, 'Nam', 'hvnhitg250918@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.085860', '2025-12-17 16:58:08.054419', NULL, NULL, 'HV_nhitg_250918', NULL),
(66, 'Lương Thị Ánh Ngọc', '2000-01-01', 'CLB_00468', 'DNAI', 5, 6, 'Nam', 'hvngoclta100118@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.106521', '2025-12-17 16:58:08.061842', NULL, NULL, 'HV_ngoclta_100118', NULL),
(67, 'Nguyễn Như Ngọc', '2000-01-01', 'CLB_00468', 'DNAI', 1, 6, 'Nam', 'hvngocnn171015@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.135006', '2025-12-17 10:25:19.033224', NULL, NULL, 'HV_ngocnn_171015', NULL),
(68, 'Vũ Như Ngọc', '2000-01-01', 'CLB_00468', 'DNAI', 5, 6, 'Nam', 'hvngocvn180815@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.159626', '2025-12-17 16:58:07.850321', NULL, NULL, 'HV_ngocvn_180815', NULL),
(69, 'Bùi Nguyễn Hà Vy', '2000-01-01', 'CLB_00468', 'DNAI', 5, 6, 'Nam', 'hvvybnh201115@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.185714', '2025-12-17 16:58:08.155087', NULL, NULL, 'HV_vybnh_201115', NULL),
(70, 'Lương Bảo Yến', '2000-01-01', 'CLB_00468', 'DNAI', 5, 6, 'Nam', 'hvyenlb240415@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.211664', '2025-12-17 16:58:08.067958', NULL, NULL, 'HV_yenlb_240415', NULL),
(71, 'Tạ Phan Uyển Nhi', '2000-01-01', 'CLB_00468', 'DNAI', 5, 6, 'Nam', 'hvnhitpu300714@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.239839', '2025-12-17 16:58:07.857074', NULL, NULL, 'HV_nhitpu_300714', NULL),
(72, 'Nguyễn Phan Bảo Thy', '2000-01-01', 'CLB_00468', 'DNAI', 5, 6, 'Nam', 'hvthynpb110813@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.262210', '2025-12-17 16:58:08.187253', NULL, NULL, 'HV_thynpb_110813', NULL),
(75, 'Nguyễn Ngọc Bảo Trân', '2000-01-01', 'CLB_00468', 'DNAI', 5, 6, 'Nam', 'hvtrannnb221012@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.330794', '2025-12-17 16:58:08.074180', NULL, NULL, 'HV_trannnb_221012', NULL),
(77, 'Trần Công Thịnh', '2000-01-01', 'CLB_00468', 'DNAI', 5, 6, 'Nam', 'hvthinhtc010617@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.384102', '2025-12-17 16:58:07.863189', NULL, NULL, 'HV_thinhtc_010617', NULL),
(79, 'Trần Văn Gia Lạc', '2000-01-01', 'CLB_00468', 'DNAI', 5, 6, 'Nam', 'hvlactvg050115@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.438870', '2025-12-17 16:58:08.160982', NULL, NULL, 'HV_lactvg_050115', NULL),
(80, 'Nguyễn Bế Minh Trí', '2000-01-01', 'CLB_00468', 'DNAI', 5, 6, 'Nam', 'hvtrinbm010615@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.477403', '2025-12-17 16:58:08.079187', NULL, NULL, 'HV_trinbm_010615', NULL),
(81, 'Lục Văn Huy', '2000-01-01', 'CLB_00468', 'DNAI', 5, 6, 'Nam', 'hvhuylv220915@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.502720', '2025-12-17 16:58:08.192011', NULL, NULL, 'HV_huylv_220915', NULL),
(83, 'Bùi Đức Tài', '2000-01-01', 'CLB_00468', 'DNAI', 1, 6, 'Nam', 'hvtaibd070814@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.543994', '2025-12-17 10:25:19.033224', NULL, NULL, 'HV_taibd_070814', NULL),
(84, 'Nguyễn Bế Gia Huy', '2000-01-01', 'CLB_00468', 'DNAI', 5, 6, 'Nam', 'hvhuynbg090213@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.561437', '2025-12-17 16:58:08.084885', NULL, NULL, 'HV_huynbg_090213', NULL),
(86, 'Đoàn Chấn Nam', '2000-01-01', 'CLB_00468', 'DNAI', 5, 6, 'Nam', 'hvnamdc131112@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.604146', '2025-12-17 16:58:08.195838', NULL, NULL, 'HV_namdc_131112', NULL),
(87, 'Nguyễn Thanh Trúc', '2000-01-01', 'CLB_00468', 'DNAI', 5, 5, 'Nam', 'hvtrucnt210317@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.622431', '2025-12-17 16:58:07.869488', NULL, NULL, 'HV_trucnt_210317', NULL),
(89, 'Phạm Thị Minh Ngọc', '2000-01-01', 'CLB_00468', 'DNAI', 5, 5, 'Nam', 'hvngocptm111216@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.660968', '2025-12-17 16:58:08.092780', NULL, NULL, 'HV_ngocptm_111216', NULL),
(90, 'Tô Ngọc Giang Hương', '2000-01-01', 'CLB_00468', 'DNAI', 5, 5, 'Nam', 'hvhuongtng010614@vosinh.local', '0123456789', NULL, NULL, NULL, 1, 'client/images/users/1766026413504-570582.png', '123456@LV23', '2025-12-17 09:14:49.681555', '2025-12-18 02:53:49.000000', NULL, NULL, 'HV_huongtng_010614', NULL),
(93, 'Bùi Phạm Minh Trang', '2000-01-01', 'CLB_00468', 'DNAI', 5, 5, 'Nam', 'hvtrangbpm011112@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.733326', '2025-12-17 16:58:08.169699', NULL, NULL, 'HV_trangbpm_011112', NULL),
(94, 'Nguyễn Trần Quỳnh Như', '2000-01-01', 'CLB_00468', 'DNAI', 5, 5, 'Nam', 'hvnhuntq050812@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.750676', '2025-12-17 16:58:07.877482', NULL, NULL, 'HV_nhuntq_050812', NULL),
(95, 'Võ Ngọc Khánh Vân', '2000-01-01', 'CLB_00468', 'DNAI', 5, 5, 'Nam', 'hvvanvnk190412@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.769711', '2025-12-17 16:58:08.174572', NULL, NULL, 'HV_vanvnk_190412', NULL),
(99, 'Vũ Công Minh', '2000-01-01', 'CLB_00468', 'DNAI', 5, 5, 'Nam', 'hvminhvc250416@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.853141', '2025-12-17 16:58:07.885088', NULL, NULL, 'HV_minhvc_250416', NULL),
(101, 'Nguyễn Nhật Minh', '2000-01-01', 'CLB_00468', 'DNAI', 5, 5, 'Nam', 'hvminhnn290516@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.891986', '2025-12-17 16:58:07.890761', NULL, NULL, 'HV_minhnn_290516', NULL),
(102, 'Trần An Nguyên', '2000-01-01', 'CLB_00468', 'DNAI', 5, 5, 'Nam', 'hvnguyenta270516@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.909260', '2025-12-17 16:58:08.097883', NULL, NULL, 'HV_nguyenta_270516', NULL),
(103, 'Tăng Văn Trí', '2000-01-01', 'CLB_00468', 'DNAI', 5, 5, 'Nam', 'hvtritv120115@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.927756', '2025-12-17 16:58:08.101898', NULL, NULL, 'HV_tritv_120115', NULL),
(106, 'Nông Thanh Minh(1)', '2000-01-01', 'CLB_00468', 'DNAI', 5, 5, 'Nam', 'hvminh1nt150512@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:49.989565', '2025-12-17 16:58:08.107138', NULL, NULL, 'HV_minh(1)nt_150512', NULL),
(107, 'Nguyễn Hoàng Nam', '2000-01-01', 'CLB_00468', 'DNAI', 5, 5, 'Nam', 'hvnamnh290212@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.016076', '2025-12-17 16:58:08.178615', NULL, NULL, 'HV_namnh_290212', NULL),
(108, 'Huỳnh Đăng Khôi', '2000-01-01', 'CLB_00468', 'DNAI', 5, 5, 'Nam', 'hvkhoihd100412@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.050507', '2025-12-17 16:58:07.897918', NULL, NULL, 'HV_khoihd_100412', NULL),
(109, 'Trần Minh Nhân', '2000-01-01', 'CLB_00468', 'DNAI', 5, 5, 'Nam', 'hvnhantm010611@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.069087', '2025-12-17 16:58:08.113442', NULL, NULL, 'HV_nhantm_010611', NULL),
(110, 'Hà Minh Khuê', '2000-01-01', 'CLB_00468', 'DNAI', 9, 4, 'Nam', 'hvkhuehm060218@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.090518', '2025-12-17 16:58:07.483940', NULL, NULL, 'HV_khuehm_060218', NULL),
(111, 'Nguyễn Tâm Nhi', '2000-01-01', 'CLB_00468', 'DNAI', 9, 4, 'Nam', 'hvnhint060517@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.106843', '2025-12-17 16:58:07.490236', NULL, NULL, 'HV_nhint_060517', NULL),
(112, 'Phạm Trần Thảo Vy', '2000-01-01', 'CLB_00468', 'DNAI', 9, 4, 'Nam', 'hvvyptt141017@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.122018', '2025-12-17 16:58:07.498973', NULL, NULL, 'HV_vyptt_141017', NULL),
(113, 'Phạm Bảo Ngọc', '2000-01-01', 'CLB_00468', 'DNAI', 9, 4, 'Nam', 'hvngocpb210117@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.143513', '2025-12-17 16:58:07.419891', NULL, NULL, 'HV_ngocpb_210117', NULL),
(114, 'Trần Ngọc Bảo Nhi', '2000-01-01', 'CLB_00468', 'DNAI', 9, 4, 'Nam', 'hvnhitnb270717@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.159170', '2025-12-17 16:58:07.425969', NULL, NULL, 'HV_nhitnb_270717', NULL),
(115, 'Nguyễn Bảo Ngọc', '2000-01-01', 'CLB_00468', 'DNAI', 9, 4, 'Nam', 'hvngocnb020216@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.176377', '2025-12-17 16:58:07.430225', NULL, NULL, 'HV_ngocnb_020216', NULL),
(117, 'Ngô Minh Ngọc', '2000-01-01', 'CLB_00468', 'DNAI', 9, 4, 'Nam', 'hvngocnm160615@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.207935', '2025-12-17 16:58:07.514763', NULL, NULL, 'HV_ngocnm_160615', NULL),
(118, 'Mã Hoàng Anh Thư', '2000-01-01', 'CLB_00468', 'DNAI', 9, 4, 'Nam', 'hvthumha161013@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.387788', '2025-12-17 16:58:07.524943', NULL, NULL, 'HV_thumha_161013', NULL),
(119, 'Nông Thị Thùy Linh', '2000-01-01', 'CLB_00468', 'DNAI', 9, 4, 'Nam', 'hvlinhntt131112@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.403621', '2025-12-17 16:58:07.907368', NULL, NULL, 'HV_linhntt_131112', NULL),
(121, 'Nguyễn Đoàn Ngọc yến', '2000-01-01', 'CLB_00468', 'DNAI', 9, 4, 'Nam', 'hvyenndn151112@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.449583', '2025-12-17 16:58:07.534716', NULL, NULL, 'HV_yenndn_151112', NULL),
(122, 'Nguyễn Thị Bảo Ngọc', '2000-01-01', 'CLB_00468', 'DNAI', 9, 4, 'Nam', 'hvngocntb221212@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.480795', '2025-12-17 16:58:08.119850', NULL, NULL, 'HV_ngocntb_221212', NULL),
(123, 'Nguyễn Thị Hồng Thắm', '2000-01-01', 'CLB_00468', 'DNAI', 9, 4, 'Nam', 'hvthamnth051210@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.505179', '2025-12-17 16:58:07.546083', NULL, NULL, 'HV_thamnth_051210', NULL),
(124, 'Nguyễn Viết Thanh Sang(1)', '2000-01-01', 'CLB_00468', 'DNAI', 9, 4, 'Nam', 'hvsang1nvt121217@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.521614', '2025-12-17 16:58:07.555083', NULL, NULL, 'HV_sang(1)nvt_121217', NULL),
(125, 'Phan Phước Khải Lâm', '2000-01-01', 'CLB_00468', 'DNAI', 9, 4, 'Nam', 'hvlamppk040717@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.543784', '2025-12-17 16:58:07.435623', NULL, NULL, 'HV_lamppk_040717', NULL),
(127, 'Nguyễn Đại Công Thành', '2000-01-01', 'CLB_00468', 'DNAI', 9, 4, 'Nam', 'hvthanhndc021015@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.586521', '2025-12-17 16:58:07.913183', NULL, NULL, 'HV_thanhndc_021015', NULL),
(128, 'Nguyễn Hoàng Phát', '2000-01-01', 'CLB_00468', 'DNAI', 9, 4, 'Nam', 'hvphatnh261215@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.601931', '2025-12-17 16:58:07.441412', NULL, NULL, 'HV_phatnh_261215', NULL),
(129, 'Lê Quốc Huy', '2000-01-01', 'CLB_00468', 'DNAI', 9, 4, 'Nam', 'hvhuylq300815@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.617618', '2025-12-17 16:58:07.411547', NULL, NULL, 'HV_huylq_300815', NULL),
(133, 'Trần Duy Khánh', '2000-01-01', 'CLB_00468', 'DNAI', 9, 4, 'Nam', 'hvkhanhtd220614@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.701835', '2025-12-17 16:58:07.446062', NULL, NULL, 'HV_khanhtd_220614', NULL),
(134, 'Nguyễn Hữu Phước', '2000-01-01', 'CLB_00468', 'DNAI', 9, 4, 'Nam', 'hvphuocnh250314@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.730042', '2025-12-17 16:58:07.450032', NULL, NULL, 'HV_phuocnh_250314', NULL),
(135, 'Hà Phúc Lâm', '2000-01-01', 'CLB_00468', 'DNAI', 9, 4, 'Nam', 'hvlamhp010413@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.759856', '2025-12-17 16:58:07.454628', NULL, NULL, 'HV_lamhp_010413', NULL),
(139, 'Lê Ngọc Bảo Nam', '2000-01-01', 'CLB_00468', 'DNAI', 9, 4, 'Nam', 'hvnamlnb280713@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.881508', '2025-12-17 16:58:07.568325', NULL, NULL, 'HV_namlnb_280713', NULL),
(140, 'Phạm Thanh Phương', '2000-01-01', 'CLB_00468', 'DNAI', 9, 4, 'Nam', 'hvphuongpt250411@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.905017', '2025-12-17 16:58:07.459132', NULL, NULL, 'HV_phuongpt_250411', NULL),
(141, 'Vũ Minh Thiện', '2000-01-01', 'CLB_00468', 'DNAI', 9, 4, 'Nam', 'hvthienvm260811@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.921492', '2025-12-17 16:58:07.464063', NULL, NULL, 'HV_thienvm_260811', NULL),
(142, 'Đỗ Mai Ly', '2000-01-01', 'CLB_00468', 'DNAI', 9, 3, 'Nam', 'hvlydm130617@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.937938', '2025-12-17 16:58:07.576057', NULL, NULL, 'HV_lydm_130617', NULL),
(143, 'Nguyễn Bảo Nghi', '2000-01-01', 'CLB_00468', 'DNAI', 9, 3, 'Nam', 'hvnghinb170517@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.960470', '2025-12-17 16:58:08.182833', NULL, NULL, 'HV_nghinb_170517', NULL),
(144, 'Phan Thị Anh Thư', '2000-01-01', 'CLB_00468', 'DNAI', 9, 3, 'Nam', 'hvthupta120716@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:50.983642', '2025-12-17 16:58:07.919378', NULL, NULL, 'HV_thupta_120716', NULL),
(145, 'Phan Thị Kim Yến', '2000-01-01', 'CLB_00468', 'DNAI', 9, 3, 'Nam', 'hvyenptk180316@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.004128', '2025-12-17 16:58:07.927186', NULL, NULL, 'HV_yenptk_180316', NULL),
(146, 'Bùi Hoàng Trúc Ly', '2000-01-01', 'CLB_00468', 'DNAI', 9, 3, 'Nam', 'hvlybht040615@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.024749', '2025-12-17 16:58:07.592489', NULL, NULL, 'HV_lybht_040615', NULL),
(147, 'Nguyễn Thị Kim Ngân', '2000-01-01', 'CLB_00468', 'DNAI', 9, 3, 'Nam', 'hvnganntk041015@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.051544', '2025-12-17 16:58:07.599081', NULL, NULL, 'HV_nganntk_041015', NULL),
(148, 'Triệu Thị Trúc Linh', '2000-01-01', 'CLB_00468', 'DNAI', 9, 3, 'Nam', 'hvlinhttt050515@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.118437', '2025-12-17 16:58:07.934784', NULL, NULL, 'HV_linhttt_050515', NULL),
(149, 'Hồ Minh Tuệ', '2000-01-01', 'CLB_00468', 'DNAI', 9, 3, 'Nam', 'hvtuehm080115@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.172707', '2025-12-17 16:58:07.610711', NULL, NULL, 'HV_tuehm_080115', NULL),
(150, 'Lê Nguyễn Thanh Vy', '2000-01-01', 'CLB_00468', 'DNAI', 9, 3, 'Nam', 'hvvylnt280915@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.198294', '2025-12-17 16:58:07.621767', NULL, NULL, 'HV_vylnt_280915', NULL),
(153, 'Duyên Thị Tuyết Trang', '2000-01-01', 'CLB_00468', 'DNAI', 9, 3, 'Nam', 'hvtrangdtt250814@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.255689', '2025-12-17 16:58:08.130192', NULL, NULL, 'HV_trangdtt_250814', NULL),
(154, 'Trần Hoàng Như Ý', '2000-01-01', 'CLB_00468', 'DNAI', 9, 3, 'Nam', 'hvythn160913@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.271432', '2025-12-17 16:58:07.630355', NULL, NULL, 'HV_ythn_160913', NULL),
(155, 'Phan Thị Kim Oanh', '2000-01-01', 'CLB_00468', 'DNAI', 9, 3, 'Nam', 'hvoanhptk150712@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.295469', '2025-12-17 16:58:07.645470', NULL, NULL, 'HV_oanhptk_150712', NULL),
(156, 'Mai Phương Trinh', '2000-01-01', 'CLB_00468', 'DNAI', 9, 3, 'Nam', 'hvtrinhmp170212@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.331422', '2025-12-17 16:58:07.940540', NULL, NULL, 'HV_trinhmp_170212', NULL),
(157, 'Nguyễn Phượng Quỳnh', '2000-01-01', 'CLB_00468', 'DNAI', 9, 3, 'Nam', 'hvquynhnp240611@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.363033', '2025-12-17 16:58:07.946751', NULL, NULL, 'HV_quynhnp_240611', NULL),
(158, 'Võ Thị Quỳnh Như', '2000-01-01', 'CLB_00468', 'DNAI', 9, 3, 'Nam', 'hvnhuvtq210408@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.398571', '2025-12-17 16:58:07.671355', NULL, NULL, 'HV_nhuvtq_210408', NULL),
(159, 'Nguyễn Thị Cẩm Tân', '2000-01-01', 'CLB_00468', 'DNAI', 9, 3, 'Nam', 'hvtanntc200298@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.435681', '2025-12-17 16:58:07.956234', NULL, NULL, 'HV_tanntc_200298', NULL),
(161, 'Lê Chấn Khang', '2000-01-01', 'CLB_00468', 'DNAI', 9, 3, 'Nam', 'hvkhanglc100417@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.484760', '2025-12-17 16:58:07.693648', NULL, NULL, 'HV_khanglc_100417', NULL),
(163, 'Nguyễn Duy Minh', '2000-01-01', 'CLB_00468', 'DNAI', 9, 3, 'Nam', 'hvminhnd230317@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.544300', '2025-12-17 16:58:07.725226', NULL, NULL, 'HV_minhnd_230317', NULL),
(164, 'Hồ Nguyễn Hoàng Quân', '2000-01-01', 'CLB_00468', 'DNAI', 9, 3, 'Nam', 'hvquanhnh280917@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.575499', '2025-12-17 16:58:07.469226', NULL, NULL, 'HV_quanhnh_280917', NULL),
(165, 'Nông Hữu Phước', '2000-01-01', 'CLB_00468', 'DNAI', 9, 3, 'Nam', 'hvphuocnh040516@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.611440', '2025-12-17 16:58:07.732320', NULL, NULL, 'HV_phuocnh_040516', NULL),
(166, 'Trần Bảo Minh(1)', '2000-01-01', 'CLB_00468', 'DNAI', 9, 3, 'Nam', 'hvminh1tb120216@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.636183', '2025-12-17 16:58:07.741278', NULL, NULL, 'HV_minh(1)tb_120216', NULL),
(167, 'Dương Gia Phúc', '2000-01-01', 'CLB_00468', 'DNAI', 9, 3, 'Nam', 'hvphucdg210516@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.675043', '2025-12-17 16:58:07.747679', NULL, NULL, 'HV_phucdg_210516', NULL),
(169, 'Chu Thiện Nhân', '2000-01-01', 'CLB_00468', 'DNAI', 9, 3, 'Nam', 'hvnhanct140914@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.730844', '2025-12-17 16:58:07.962815', NULL, NULL, 'HV_nhanct_140914', NULL),
(171, 'Trần Minh Nhật', '2000-01-01', 'CLB_00468', 'DNAI', 9, 3, 'Nam', 'hvnhattm140813@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.762024', '2025-12-17 16:58:07.755281', NULL, NULL, 'HV_nhattm_140813', NULL),
(173, 'Nguyễn Mã Ngọc Phong', '2000-01-01', 'CLB_00468', 'DNAI', 9, 3, 'Nam', 'hvphongnmn311211@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.797454', '2025-12-17 16:58:07.972890', NULL, NULL, 'HV_phongnmn_311211', NULL),
(177, 'Nguyễn Thanh Vy', '2000-01-01', 'CLB_00468', 'DNAI', 9, 2, 'Nam', 'hvvynt280917@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.862598', '2025-12-17 16:58:08.134644', NULL, NULL, 'HV_vynt_280917', NULL),
(178, 'Triệu Thị Ngọc Trúc', '2000-01-01', 'CLB_00468', 'DNAI', 9, 2, 'Nam', 'hvtructtn030916@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.879340', '2025-12-17 16:58:07.979985', NULL, NULL, 'HV_tructtn_030916', NULL),
(180, 'Nguyễn Ngọc Kim Ngân', '2000-01-01', 'CLB_00468', 'DNAI', 9, 2, 'Nam', 'hvngannnk280315@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.910912', '2025-12-17 16:58:07.986756', NULL, NULL, 'HV_ngannnk_280315', NULL),
(181, 'Nguyễn Thanh Như', '2000-01-01', 'CLB_00468', 'DNAI', 9, 2, 'Nam', 'hvnhunt281215@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.927533', '2025-12-17 16:58:07.765026', NULL, NULL, 'HV_nhunt_281215', NULL),
(183, 'Dương Phương Nghi', '2000-01-01', 'CLB_00468', 'DNAI', 9, 2, 'Nam', 'hvnghidp090114@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.961574', '2025-12-17 16:58:07.992138', NULL, NULL, 'HV_nghidp_090114', NULL),
(184, 'Lục Minh Thư', '2000-01-01', 'CLB_00468', 'DNAI', 9, 2, 'Nam', 'hvthulm120414@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:51.982449', '2025-12-17 16:58:07.771853', NULL, NULL, 'HV_thulm_120414', NULL),
(188, 'Phùng Ngọc Minh', '2000-01-01', 'CLB_00468', 'DNAI', 9, 2, 'Nam', 'hvminhpn160217@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:52.086900', '2025-12-17 16:58:07.778078', NULL, NULL, 'HV_minhpn_160217', NULL),
(189, 'Nguyễn Thế Liêm', '2000-01-01', 'CLB_00468', 'DNAI', 9, 2, 'Nam', 'hvliemnt080516@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:52.102511', '2025-12-17 16:58:07.999645', NULL, NULL, 'HV_liemnt_080516', NULL),
(190, 'Hà Đức Huy', '2000-01-01', 'CLB_00468', 'DNAI', 9, 2, 'Nam', 'hvhuyhd081215@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:52.119600', '2025-12-17 16:58:07.784226', NULL, NULL, 'HV_huyhd_081215', NULL),
(192, 'Nguyễn Bảo Quân', '2000-01-01', 'CLB_00468', 'DNAI', 9, 2, 'Nam', 'hvquannb211114@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:52.152995', '2025-12-17 16:58:07.791847', NULL, NULL, 'HV_quannb_211114', NULL),
(193, 'Đào Tuấn Khang', '2000-01-01', 'CLB_00468', 'DNAI', 9, 2, 'Nam', 'hvkhangdt120714@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:52.171599', '2025-12-17 16:58:07.473106', NULL, NULL, 'HV_khangdt_120714', NULL),
(194, 'Nguyễn Chí Thanh', '2000-01-01', 'CLB_00468', 'DNAI', 9, 2, 'Nam', 'hvthanhnc070712@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:52.189003', '2025-12-17 16:58:08.004933', NULL, NULL, 'HV_thanhnc_070712', NULL),
(196, 'Trần Thanh Phước', '2000-01-01', 'CLB_00468', 'DNAI', 9, 2, 'Nam', 'hvphuoctt271012@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:52.233665', '2025-12-17 16:58:07.796665', NULL, NULL, 'HV_phuoctt_271012', NULL),
(197, 'Trần Thanh Tín', '2000-01-01', 'CLB_00468', 'DNAI', 9, 2, 'Nam', 'hvtintt081111@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:52.255470', '2025-12-17 16:58:08.137962', NULL, NULL, 'HV_tintt_081111', NULL),
(200, 'Nguyễn Thị Quỳnh Thy', '2000-01-01', 'CLB_00468', 'DNAI', 9, 1, 'Nam', 'hvthyntq150915@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:52.335934', '2025-12-17 16:58:07.801616', NULL, NULL, 'HV_thyntq_150915', NULL),
(204, 'Triệu Thị Ngọc Yến', '2000-01-01', 'CLB_00468', 'DNAI', 9, 1, 'Nam', 'hvyenttn121112@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:52.467581', '2025-12-17 16:58:08.012245', NULL, NULL, 'HV_yenttn_121112', NULL),
(205, 'Triệu Thị Thúy Vân', '2000-01-01', 'CLB_00468', 'DNAI', 9, 1, 'Nam', 'hvvanttt161112@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:52.503555', '2025-12-17 16:58:07.807417', NULL, NULL, 'HV_vanttt_161112', NULL),
(206, 'Phạm Yến Nhi', '2000-01-01', 'CLB_00468', 'DNAI', 9, 1, 'Nam', 'hvnhipy011111@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:52.556356', '2025-12-17 16:58:08.017810', NULL, NULL, 'HV_nhipy_011111', NULL),
(208, 'Hoàng Nữ Bình Minh', '2000-01-01', 'CLB_00468', 'DNAI', 1, 1, 'Nam', 'hvminhhnb070111@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:52.632694', '2025-12-17 10:25:19.033224', NULL, NULL, 'HV_minhhnb_070111', NULL),
(211, 'Sầm Ngọc Yến Như', '2000-01-01', 'CLB_00468', 'DNAI', 9, 1, 'Nam', 'hvnhusny280211@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:52.696004', '2025-12-17 16:58:08.022988', NULL, NULL, 'HV_nhusny_280211', NULL),
(212, 'Lê Thị Thanh Tuyền', '2000-01-01', 'CLB_00468', 'DNAI', 9, 1, 'Nam', 'hvtuyenltt041010@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:52.714532', '2025-12-17 16:58:07.814885', NULL, NULL, 'HV_tuyenltt_041010', NULL),
(216, 'Lê Minh Nhật', '2000-01-01', 'CLB_00468', 'DNAI', 9, 1, 'Nam', 'hvnhatlm020416@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:52.785088', '2025-12-17 16:58:08.031299', NULL, NULL, 'HV_nhatlm_020416', NULL),
(217, 'Nguyễn Minh Thiện', '2000-01-01', 'CLB_00468', 'DNAI', 9, 1, 'Nam', 'hvthiennm041215@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:52.805863', '2025-12-17 16:58:07.823683', NULL, NULL, 'HV_thiennm_041215', NULL),
(219, 'Triệu Anh Kiệt', '2000-01-01', 'CLB_00468', 'DNAI', 9, 1, 'Nam', 'hvkietta240615@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:52.843574', '2025-12-17 16:58:07.828611', NULL, NULL, 'HV_kietta_240615', NULL),
(220, 'Nguyễn Đăng Khoa', '2000-01-01', 'CLB_00468', 'DNAI', 9, 1, 'Nam', 'hvkhoand070114@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:52.866293', '2025-12-17 16:58:08.142838', NULL, NULL, 'HV_khoand_070114', NULL),
(221, 'Phan Công Minh Trí', '2000-01-01', 'CLB_00468', 'DNAI', 9, 1, 'Nam', 'hvtripcm150714@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:52.885839', '2025-12-17 16:58:08.037132', NULL, NULL, 'HV_tripcm_150714', NULL),
(224, 'Bùi Quốc Nam', '2000-01-01', 'CLB_00468', 'DNAI', 9, 1, 'Nam', 'hvnambq290713@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:52.941200', '2025-12-17 16:58:08.147152', NULL, NULL, 'HV_nambq_290713', NULL),
(225, 'Lý Hoàng Long', '2000-01-01', 'CLB_00468', 'DNAI', 9, 1, 'Nam', 'hvlonglh090712@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:52.957984', '2025-12-17 16:58:07.833527', NULL, NULL, 'HV_longlh_090712', NULL),
(226, 'Triệu Công Vĩnh', '2000-01-01', 'CLB_00468', 'DNAI', 9, 1, 'Nam', 'hvvinhtc310712@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:52.982734', '2025-12-17 16:58:08.151736', NULL, NULL, 'HV_vinhtc_310712', NULL),
(227, 'Đỗ Minh Thiện', '2000-01-01', 'CLB_00468', 'DNAI', 9, 1, 'Nam', 'hvthiendm190712@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:53.001265', '2025-12-17 16:58:08.042984', NULL, NULL, 'HV_thiendm_190712', NULL),
(228, 'Tạ Minh Phát', '2000-01-01', 'CLB_00468', 'DNAI', 9, 1, 'Nam', 'hvphattm251011@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:53.020931', '2025-12-17 16:58:07.840451', NULL, NULL, 'HV_phattm_251011', NULL),
(230, 'Nguyễn Đồng Minh Quân', '2000-01-01', 'CLB_00468', 'DNAI', 9, 1, 'Nam', 'hvquanndm111011@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:53.068253', '2025-12-17 16:58:08.048920', NULL, NULL, 'HV_quanndm_111011', NULL),
(231, 'Hoàng Minh Phương', '2000-01-01', 'CLB_00468', 'DNAI', 9, 1, 'Nam', 'hvphuonghm100510@vosinh.local', NULL, NULL, NULL, NULL, 1, NULL, '123456@LV23', '2025-12-17 09:14:53.089417', '2025-12-17 16:58:07.845273', NULL, NULL, 'HV_phuonghm_100510', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `IDX_de87485f6489f5d0995f584195` (`email`);

--
-- Indexes for table `bai_quyen`
--
ALTER TABLE `bai_quyen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cap_dai`
--
ALTER TABLE `cap_dai`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `IDX_4691bedf365a54f4cfd2ae7083` (`name`);

--
-- Indexes for table `cap_dai_bai_quyen`
--
ALTER TABLE `cap_dai_bai_quyen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_9f60e812a3cccd7d69fe37ce7c5` (`cap_dai_id`),
  ADD KEY `FK_82223ee8cc77ce937be730dbadf` (`bai_quyen_id`);

--
-- Indexes for table `cau_lac_bo`
--
ALTER TABLE `cau_lac_bo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `IDX_8c85f0457a26a71ec7378691c5` (`club_code`),
  ADD KEY `FK_dba6d02c71687c83da6112289e4` (`head_coach_id`);

--
-- Indexes for table `chi_nhanh`
--
ALTER TABLE `chi_nhanh`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `IDX_2dac264d209ab493266684971b` (`branch_code`),
  ADD KEY `FK_c5540dae5de2ae752d9d1177e33` (`club_id`);

--
-- Indexes for table `chi_tiet_thanh_toan`
--
ALTER TABLE `chi_tiet_thanh_toan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_c5c1d7b9f4710dd8b286b15ae43` (`payment_id`),
  ADD KEY `FK_bd2cb7333be812a68ee65d56e5f` (`tuition_package_id`);

--
-- Indexes for table `chung_chi`
--
ALTER TABLE `chung_chi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `IDX_4cbec73d922d16303b6c4ca068` (`certificate_number`),
  ADD KEY `FK_0317919cce8f3e99e48c88db106` (`user_id`),
  ADD KEY `FK_c7314027bb1725c325d7a5ae05a` (`belt_level_id`);

--
-- Indexes for table `dang_ky_hoc`
--
ALTER TABLE `dang_ky_hoc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_2cb5db43456884504c1d5c16c5d` (`user_id`),
  ADD KEY `FK_f891c59d3004571bc9f375bef75` (`course_id`);

--
-- Indexes for table `dang_ky_thi`
--
ALTER TABLE `dang_ky_thi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_75e9ae458edb84eb8c9ce47bc6a` (`test_id`),
  ADD KEY `FK_f47f2d9b20c17330d9958d826d1` (`user_id`),
  ADD KEY `FK_b13d03bfba5faa6bf71db9de3db` (`current_belt_id`),
  ADD KEY `FK_fdef3a8c5bf8332e43cefda7ad6` (`target_belt_id`);

--
-- Indexes for table `danh_gia_hoc_vien`
--
ALTER TABLE `danh_gia_hoc_vien`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_cef600a329bb278b966bbf5bcf4` (`user_id`),
  ADD KEY `FK_059790452b1553d676993271119` (`coach_id`),
  ADD KEY `FK_495b9e459df5c987e3845b7958f` (`course_id`);

--
-- Indexes for table `danh_gia_phan_hoi`
--
ALTER TABLE `danh_gia_phan_hoi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `coach_id` (`coach_id`);

--
-- Indexes for table `diem_danh`
--
ALTER TABLE `diem_danh`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_e9b24be1853077c6d0fdd7c0588` (`user_id`),
  ADD KEY `FK_730ff3735a261fc369f2e77f754` (`course_id`);

--
-- Indexes for table `goi_hoc_phi`
--
ALTER TABLE `goi_hoc_phi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_0597791a677798515f8be4a93e0` (`club_id`);

--
-- Indexes for table `hoc_vien_phu_huynh`
--
ALTER TABLE `hoc_vien_phu_huynh`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_f892c50786d457aace3e17343d6` (`student_id`),
  ADD KEY `FK_e905207f623036fc5554809e6e1` (`parent_id`);

--
-- Indexes for table `huan_luyen_vien`
--
ALTER TABLE `huan_luyen_vien`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `IDX_2462b9a13861a3af1c4df8a14f` (`ma_hoi_vien`),
  ADD UNIQUE KEY `IDX_edbacb1e73e3334cae785bad05` (`email`),
  ADD KEY `FK_8c65cd32b3ea2eefcb7c4d8f085` (`cap_dai_id`);

--
-- Indexes for table `ket_qua_thi`
--
ALTER TABLE `ket_qua_thi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_efd75ded789fdc6c7d98c2dcdd9` (`test_id`),
  ADD KEY `FK_80f140836a3cebe8ac305c848a4` (`user_id`),
  ADD KEY `FK_d6ba6c08c08a7901e2bc7fc86d3` (`cap_dai_du_thi_id`);

--
-- Indexes for table `khoa_hoc`
--
ALTER TABLE `khoa_hoc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_837aa1770ecd214705e44bf3709` (`club_id`),
  ADD KEY `FK_74e0a47b8123918537236368137` (`branch_id`),
  ADD KEY `FK_c8d5d2197fd86aeb7e2a24d9429` (`coach_id`);

--
-- Indexes for table `ky_thi_thang_cap`
--
ALTER TABLE `ky_thi_thang_cap`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_8552f1586c16b0651f782b63bfd` (`examiner_id`),
  ADD KEY `FK_216a070a1e21775b0dbea344d78` (`club_id`);

--
-- Indexes for table `lich_hoc`
--
ALTER TABLE `lich_hoc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_9626b0376bd156ec27788eb4e40` (`course_id`);

--
-- Indexes for table `lich_su_thi_thang_cap_dai`
--
ALTER TABLE `lich_su_thi_thang_cap_dai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lich_su_thi_thang_cap_dai_vo_sinh` (`vo_sinh_id`),
  ADD KEY `idx_lich_su_thi_thang_cap_dai_ngay_thi` (`ngay_thi`),
  ADD KEY `idx_lich_su_thi_thang_cap_dai_bai_quyen` (`bai_quyen_id`),
  ADD KEY `idx_lich_su_thi_thang_cap_dai_cap_dai` (`cap_dai_id`);

--
-- Indexes for table `phan_hoi`
--
ALTER TABLE `phan_hoi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_735afd6ac71cc5945f92e47dec3` (`user_id`),
  ADD KEY `FK_2c9bd4eaa0241e2f2fa49dc2d4e` (`course_id`),
  ADD KEY `FK_d3df732f8cdd43be90b7d6d6c8f` (`coach_id`);

--
-- Indexes for table `phu_huynh`
--
ALTER TABLE `phu_huynh`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quan_ly_chi_nhanh`
--
ALTER TABLE `quan_ly_chi_nhanh`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `IDX_cacc552b1fc123a7d4b1720e57` (`branch_id`,`manager_id`),
  ADD KEY `FK_f83f7492a88e4283e8c0e36a987` (`manager_id`);

--
-- Indexes for table `su_kien`
--
ALTER TABLE `su_kien`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_9e3ab2a35a45a0dc3cd5e097274` (`club_id`);

--
-- Indexes for table `thang_cap_dai`
--
ALTER TABLE `thang_cap_dai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_cf33eabc7545f7fa12469b43143` (`user_id`),
  ADD KEY `FK_eb1c996827d97f80b67c962f5d8` (`from_belt_id`),
  ADD KEY `FK_77c72632f0cd77be64ee7374f4c` (`to_belt_id`),
  ADD KEY `FK_cf3ff66a1bcaa4acfa76701e7df` (`coach_id`);

--
-- Indexes for table `thanh_toan`
--
ALTER TABLE `thanh_toan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_29447c1f43540ae4274e5b23751` (`user_id`);

--
-- Indexes for table `thong_bao`
--
ALTER TABLE `thong_bao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_810835e2d93399b51a97d4f1885` (`club_id`);

--
-- Indexes for table `thu_vien`
--
ALTER TABLE `thu_vien`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_9771b9493ee531dd1182933cf2f` (`club_id`),
  ADD KEY `FK_8deeb0eb2c0862e0691748e6f3b` (`branch_id`);

--
-- Indexes for table `tien_trinh_hoc_tap`
--
ALTER TABLE `tien_trinh_hoc_tap`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_94cf19cc432d987406805aa5b35` (`user_id`),
  ADD KEY `FK_ca0c5fdfc0e96c17aca93272ffb` (`course_id`);

--
-- Indexes for table `tin_nhan_lien_he`
--
ALTER TABLE `tin_nhan_lien_he`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tin_tuc`
--
ALTER TABLE `tin_tuc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `IDX_69aca933594a3caa25f14bfd97` (`slug`),
  ADD KEY `FK_272030c24574ecac94fe2890326` (`author_id`);

--
-- Indexes for table `tro_giang_chi_nhanh`
--
ALTER TABLE `tro_giang_chi_nhanh`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `IDX_915d2c9936d54e25df9c0ed227` (`branch_id`,`assistant_id`),
  ADD KEY `FK_ceec9dcf2dd0c5621fb8cb1065a` (`assistant_id`);

--
-- Indexes for table `vo_sinh`
--
ALTER TABLE `vo_sinh`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `IDX_b15a03afdff6866c673524972d` (`email`),
  ADD UNIQUE KEY `IDX_624be563ef47696d519ff536f9` (`ma_hoi_vien`),
  ADD KEY `FK_29722a8adde0e46aa0060ebf86f` (`cap_dai_id`),
  ADD KEY `FK_77e8608b7188ba820b444141ffc` (`chi_nhanh_id`),
  ADD KEY `FK_55fd290bd2366ae7d159f4350c9` (`cau_lac_bo_id`),
  ADD KEY `FK_29a394dfd1734d173b9d865eb1c` (`quyen_so`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bai_quyen`
--
ALTER TABLE `bai_quyen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `cap_dai`
--
ALTER TABLE `cap_dai`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `cap_dai_bai_quyen`
--
ALTER TABLE `cap_dai_bai_quyen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `cau_lac_bo`
--
ALTER TABLE `cau_lac_bo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `chi_nhanh`
--
ALTER TABLE `chi_nhanh`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `chi_tiet_thanh_toan`
--
ALTER TABLE `chi_tiet_thanh_toan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chung_chi`
--
ALTER TABLE `chung_chi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dang_ky_hoc`
--
ALTER TABLE `dang_ky_hoc`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dang_ky_thi`
--
ALTER TABLE `dang_ky_thi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `danh_gia_hoc_vien`
--
ALTER TABLE `danh_gia_hoc_vien`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `danh_gia_phan_hoi`
--
ALTER TABLE `danh_gia_phan_hoi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `diem_danh`
--
ALTER TABLE `diem_danh`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `goi_hoc_phi`
--
ALTER TABLE `goi_hoc_phi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hoc_vien_phu_huynh`
--
ALTER TABLE `hoc_vien_phu_huynh`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `huan_luyen_vien`
--
ALTER TABLE `huan_luyen_vien`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ket_qua_thi`
--
ALTER TABLE `ket_qua_thi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=226;

--
-- AUTO_INCREMENT for table `khoa_hoc`
--
ALTER TABLE `khoa_hoc`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ky_thi_thang_cap`
--
ALTER TABLE `ky_thi_thang_cap`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lich_hoc`
--
ALTER TABLE `lich_hoc`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lich_su_thi_thang_cap_dai`
--
ALTER TABLE `lich_su_thi_thang_cap_dai`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phan_hoi`
--
ALTER TABLE `phan_hoi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phu_huynh`
--
ALTER TABLE `phu_huynh`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quan_ly_chi_nhanh`
--
ALTER TABLE `quan_ly_chi_nhanh`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `su_kien`
--
ALTER TABLE `su_kien`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `thang_cap_dai`
--
ALTER TABLE `thang_cap_dai`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `thanh_toan`
--
ALTER TABLE `thanh_toan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `thong_bao`
--
ALTER TABLE `thong_bao`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `thu_vien`
--
ALTER TABLE `thu_vien`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tien_trinh_hoc_tap`
--
ALTER TABLE `tien_trinh_hoc_tap`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tin_nhan_lien_he`
--
ALTER TABLE `tin_nhan_lien_he`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tin_tuc`
--
ALTER TABLE `tin_tuc`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tro_giang_chi_nhanh`
--
ALTER TABLE `tro_giang_chi_nhanh`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vo_sinh`
--
ALTER TABLE `vo_sinh`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=232;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cap_dai_bai_quyen`
--
ALTER TABLE `cap_dai_bai_quyen`
  ADD CONSTRAINT `FK_82223ee8cc77ce937be730dbadf` FOREIGN KEY (`bai_quyen_id`) REFERENCES `bai_quyen` (`id`),
  ADD CONSTRAINT `FK_9f60e812a3cccd7d69fe37ce7c5` FOREIGN KEY (`cap_dai_id`) REFERENCES `cap_dai` (`id`);

--
-- Constraints for table `cau_lac_bo`
--
ALTER TABLE `cau_lac_bo`
  ADD CONSTRAINT `FK_dba6d02c71687c83da6112289e4` FOREIGN KEY (`head_coach_id`) REFERENCES `huan_luyen_vien` (`id`);

--
-- Constraints for table `chi_nhanh`
--
ALTER TABLE `chi_nhanh`
  ADD CONSTRAINT `FK_c5540dae5de2ae752d9d1177e33` FOREIGN KEY (`club_id`) REFERENCES `cau_lac_bo` (`id`);

--
-- Constraints for table `chi_tiet_thanh_toan`
--
ALTER TABLE `chi_tiet_thanh_toan`
  ADD CONSTRAINT `FK_bd2cb7333be812a68ee65d56e5f` FOREIGN KEY (`tuition_package_id`) REFERENCES `goi_hoc_phi` (`id`),
  ADD CONSTRAINT `FK_c5c1d7b9f4710dd8b286b15ae43` FOREIGN KEY (`payment_id`) REFERENCES `thanh_toan` (`id`);

--
-- Constraints for table `chung_chi`
--
ALTER TABLE `chung_chi`
  ADD CONSTRAINT `FK_0317919cce8f3e99e48c88db106` FOREIGN KEY (`user_id`) REFERENCES `vo_sinh` (`id`),
  ADD CONSTRAINT `FK_c7314027bb1725c325d7a5ae05a` FOREIGN KEY (`belt_level_id`) REFERENCES `cap_dai` (`id`);

--
-- Constraints for table `dang_ky_hoc`
--
ALTER TABLE `dang_ky_hoc`
  ADD CONSTRAINT `FK_2cb5db43456884504c1d5c16c5d` FOREIGN KEY (`user_id`) REFERENCES `vo_sinh` (`id`),
  ADD CONSTRAINT `FK_f891c59d3004571bc9f375bef75` FOREIGN KEY (`course_id`) REFERENCES `khoa_hoc` (`id`);

--
-- Constraints for table `dang_ky_thi`
--
ALTER TABLE `dang_ky_thi`
  ADD CONSTRAINT `FK_75e9ae458edb84eb8c9ce47bc6a` FOREIGN KEY (`test_id`) REFERENCES `ky_thi_thang_cap` (`id`),
  ADD CONSTRAINT `FK_b13d03bfba5faa6bf71db9de3db` FOREIGN KEY (`current_belt_id`) REFERENCES `cap_dai` (`id`),
  ADD CONSTRAINT `FK_f47f2d9b20c17330d9958d826d1` FOREIGN KEY (`user_id`) REFERENCES `vo_sinh` (`id`),
  ADD CONSTRAINT `FK_fdef3a8c5bf8332e43cefda7ad6` FOREIGN KEY (`target_belt_id`) REFERENCES `cap_dai` (`id`);

--
-- Constraints for table `danh_gia_hoc_vien`
--
ALTER TABLE `danh_gia_hoc_vien`
  ADD CONSTRAINT `FK_059790452b1553d676993271119` FOREIGN KEY (`coach_id`) REFERENCES `huan_luyen_vien` (`id`),
  ADD CONSTRAINT `FK_495b9e459df5c987e3845b7958f` FOREIGN KEY (`course_id`) REFERENCES `khoa_hoc` (`id`),
  ADD CONSTRAINT `FK_cef600a329bb278b966bbf5bcf4` FOREIGN KEY (`user_id`) REFERENCES `vo_sinh` (`id`);

--
-- Constraints for table `danh_gia_phan_hoi`
--
ALTER TABLE `danh_gia_phan_hoi`
  ADD CONSTRAINT `danh_gia_phan_hoi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `vo_sinh` (`id`),
  ADD CONSTRAINT `danh_gia_phan_hoi_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `khoa_hoc` (`id`),
  ADD CONSTRAINT `danh_gia_phan_hoi_ibfk_3` FOREIGN KEY (`coach_id`) REFERENCES `huan_luyen_vien` (`id`);

--
-- Constraints for table `diem_danh`
--
ALTER TABLE `diem_danh`
  ADD CONSTRAINT `FK_730ff3735a261fc369f2e77f754` FOREIGN KEY (`course_id`) REFERENCES `khoa_hoc` (`id`),
  ADD CONSTRAINT `FK_e9b24be1853077c6d0fdd7c0588` FOREIGN KEY (`user_id`) REFERENCES `vo_sinh` (`id`);

--
-- Constraints for table `goi_hoc_phi`
--
ALTER TABLE `goi_hoc_phi`
  ADD CONSTRAINT `FK_0597791a677798515f8be4a93e0` FOREIGN KEY (`club_id`) REFERENCES `cau_lac_bo` (`id`);

--
-- Constraints for table `hoc_vien_phu_huynh`
--
ALTER TABLE `hoc_vien_phu_huynh`
  ADD CONSTRAINT `FK_e905207f623036fc5554809e6e1` FOREIGN KEY (`parent_id`) REFERENCES `phu_huynh` (`id`),
  ADD CONSTRAINT `FK_f892c50786d457aace3e17343d6` FOREIGN KEY (`student_id`) REFERENCES `vo_sinh` (`id`);

--
-- Constraints for table `huan_luyen_vien`
--
ALTER TABLE `huan_luyen_vien`
  ADD CONSTRAINT `FK_8c65cd32b3ea2eefcb7c4d8f085` FOREIGN KEY (`cap_dai_id`) REFERENCES `cap_dai` (`id`);

--
-- Constraints for table `ket_qua_thi`
--
ALTER TABLE `ket_qua_thi`
  ADD CONSTRAINT `FK_80f140836a3cebe8ac305c848a4` FOREIGN KEY (`user_id`) REFERENCES `vo_sinh` (`id`),
  ADD CONSTRAINT `FK_d6ba6c08c08a7901e2bc7fc86d3` FOREIGN KEY (`cap_dai_du_thi_id`) REFERENCES `cap_dai` (`id`),
  ADD CONSTRAINT `FK_efd75ded789fdc6c7d98c2dcdd9` FOREIGN KEY (`test_id`) REFERENCES `ky_thi_thang_cap` (`id`);

--
-- Constraints for table `khoa_hoc`
--
ALTER TABLE `khoa_hoc`
  ADD CONSTRAINT `FK_74e0a47b8123918537236368137` FOREIGN KEY (`branch_id`) REFERENCES `chi_nhanh` (`id`),
  ADD CONSTRAINT `FK_837aa1770ecd214705e44bf3709` FOREIGN KEY (`club_id`) REFERENCES `cau_lac_bo` (`id`),
  ADD CONSTRAINT `FK_c8d5d2197fd86aeb7e2a24d9429` FOREIGN KEY (`coach_id`) REFERENCES `huan_luyen_vien` (`id`);

--
-- Constraints for table `ky_thi_thang_cap`
--
ALTER TABLE `ky_thi_thang_cap`
  ADD CONSTRAINT `FK_216a070a1e21775b0dbea344d78` FOREIGN KEY (`club_id`) REFERENCES `cau_lac_bo` (`id`),
  ADD CONSTRAINT `FK_8552f1586c16b0651f782b63bfd` FOREIGN KEY (`examiner_id`) REFERENCES `huan_luyen_vien` (`id`);

--
-- Constraints for table `lich_hoc`
--
ALTER TABLE `lich_hoc`
  ADD CONSTRAINT `FK_9626b0376bd156ec27788eb4e40` FOREIGN KEY (`course_id`) REFERENCES `khoa_hoc` (`id`);

--
-- Constraints for table `lich_su_thi_thang_cap_dai`
--
ALTER TABLE `lich_su_thi_thang_cap_dai`
  ADD CONSTRAINT `lich_su_thi_thang_cap_dai_ibfk_1` FOREIGN KEY (`vo_sinh_id`) REFERENCES `vo_sinh` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lich_su_thi_thang_cap_dai_ibfk_2` FOREIGN KEY (`bai_quyen_id`) REFERENCES `bai_quyen` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lich_su_thi_thang_cap_dai_ibfk_3` FOREIGN KEY (`cap_dai_id`) REFERENCES `cap_dai` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `phan_hoi`
--
ALTER TABLE `phan_hoi`
  ADD CONSTRAINT `FK_2c9bd4eaa0241e2f2fa49dc2d4e` FOREIGN KEY (`course_id`) REFERENCES `khoa_hoc` (`id`),
  ADD CONSTRAINT `FK_735afd6ac71cc5945f92e47dec3` FOREIGN KEY (`user_id`) REFERENCES `vo_sinh` (`id`),
  ADD CONSTRAINT `FK_d3df732f8cdd43be90b7d6d6c8f` FOREIGN KEY (`coach_id`) REFERENCES `huan_luyen_vien` (`id`);

--
-- Constraints for table `quan_ly_chi_nhanh`
--
ALTER TABLE `quan_ly_chi_nhanh`
  ADD CONSTRAINT `FK_a4ef7dce177a0ebe6288d459088` FOREIGN KEY (`branch_id`) REFERENCES `chi_nhanh` (`id`),
  ADD CONSTRAINT `FK_f83f7492a88e4283e8c0e36a987` FOREIGN KEY (`manager_id`) REFERENCES `huan_luyen_vien` (`id`);

--
-- Constraints for table `su_kien`
--
ALTER TABLE `su_kien`
  ADD CONSTRAINT `FK_9e3ab2a35a45a0dc3cd5e097274` FOREIGN KEY (`club_id`) REFERENCES `cau_lac_bo` (`id`);

--
-- Constraints for table `thang_cap_dai`
--
ALTER TABLE `thang_cap_dai`
  ADD CONSTRAINT `FK_77c72632f0cd77be64ee7374f4c` FOREIGN KEY (`to_belt_id`) REFERENCES `cap_dai` (`id`),
  ADD CONSTRAINT `FK_cf33eabc7545f7fa12469b43143` FOREIGN KEY (`user_id`) REFERENCES `vo_sinh` (`id`),
  ADD CONSTRAINT `FK_cf3ff66a1bcaa4acfa76701e7df` FOREIGN KEY (`coach_id`) REFERENCES `huan_luyen_vien` (`id`),
  ADD CONSTRAINT `FK_eb1c996827d97f80b67c962f5d8` FOREIGN KEY (`from_belt_id`) REFERENCES `cap_dai` (`id`);

--
-- Constraints for table `thanh_toan`
--
ALTER TABLE `thanh_toan`
  ADD CONSTRAINT `FK_29447c1f43540ae4274e5b23751` FOREIGN KEY (`user_id`) REFERENCES `vo_sinh` (`id`);

--
-- Constraints for table `thong_bao`
--
ALTER TABLE `thong_bao`
  ADD CONSTRAINT `FK_810835e2d93399b51a97d4f1885` FOREIGN KEY (`club_id`) REFERENCES `cau_lac_bo` (`id`);

--
-- Constraints for table `thu_vien`
--
ALTER TABLE `thu_vien`
  ADD CONSTRAINT `FK_8deeb0eb2c0862e0691748e6f3b` FOREIGN KEY (`branch_id`) REFERENCES `chi_nhanh` (`id`),
  ADD CONSTRAINT `FK_9771b9493ee531dd1182933cf2f` FOREIGN KEY (`club_id`) REFERENCES `cau_lac_bo` (`id`);

--
-- Constraints for table `tien_trinh_hoc_tap`
--
ALTER TABLE `tien_trinh_hoc_tap`
  ADD CONSTRAINT `FK_94cf19cc432d987406805aa5b35` FOREIGN KEY (`user_id`) REFERENCES `vo_sinh` (`id`),
  ADD CONSTRAINT `FK_ca0c5fdfc0e96c17aca93272ffb` FOREIGN KEY (`course_id`) REFERENCES `khoa_hoc` (`id`);

--
-- Constraints for table `tin_tuc`
--
ALTER TABLE `tin_tuc`
  ADD CONSTRAINT `FK_272030c24574ecac94fe2890326` FOREIGN KEY (`author_id`) REFERENCES `vo_sinh` (`id`);

--
-- Constraints for table `tro_giang_chi_nhanh`
--
ALTER TABLE `tro_giang_chi_nhanh`
  ADD CONSTRAINT `FK_ceec9dcf2dd0c5621fb8cb1065a` FOREIGN KEY (`assistant_id`) REFERENCES `huan_luyen_vien` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_f72aa5050a9a3bbe73f68b2bef5` FOREIGN KEY (`branch_id`) REFERENCES `chi_nhanh` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vo_sinh`
--
ALTER TABLE `vo_sinh`
  ADD CONSTRAINT `FK_29722a8adde0e46aa0060ebf86f` FOREIGN KEY (`cap_dai_id`) REFERENCES `cap_dai` (`id`),
  ADD CONSTRAINT `FK_29a394dfd1734d173b9d865eb1c` FOREIGN KEY (`quyen_so`) REFERENCES `bai_quyen` (`id`),
  ADD CONSTRAINT `FK_55fd290bd2366ae7d159f4350c9` FOREIGN KEY (`cau_lac_bo_id`) REFERENCES `cau_lac_bo` (`id`),
  ADD CONSTRAINT `FK_77e8608b7188ba820b444141ffc` FOREIGN KEY (`chi_nhanh_id`) REFERENCES `chi_nhanh` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
