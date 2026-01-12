-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th1 12, 2026 lúc 06:31 PM
-- Phiên bản máy phục vụ: 10.4.27-MariaDB
-- Phiên bản PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `Web`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ban_uong`
--

CREATE TABLE `ban_uong` (
  `ma_ban` varchar(50) NOT NULL,
  `ten_ban` varchar(100) NOT NULL,
  `so_cho_ngoi` int(11) NOT NULL,
  `trang_thai_ban` enum('trong','dang_su_dung') DEFAULT 'trong',
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ban_uong`
--

INSERT INTO `ban_uong` (`ma_ban`, `ten_ban`, `so_cho_ngoi`, `trang_thai_ban`, `ngay_tao`) VALUES
('B01', 'Bàn 1', 4, 'trong', '2025-12-28 00:29:07'),
('B02', 'Bàn 2', 2, 'trong', '2025-12-28 00:29:07'),
('B03', 'Bàn 3', 6, 'trong', '2025-12-28 00:29:07'),
('B04', 'Bàn 4', 4, 'trong', '2025-12-28 00:29:07'),
('B05', 'Trong nhà kính', 8, 'trong', '2025-12-28 00:29:07'),
('B07', 'Ngoài trời vip', 4, 'trong', '2025-12-29 17:27:24'),
('B08', 'Vip 1', 6, 'trong', '2025-12-29 12:22:43'),
('KHACH_HANG', 'Khách hàng', 1, 'trong', '2026-01-03 18:22:53');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_tiet_don_hang`
--

CREATE TABLE `chi_tiet_don_hang` (
  `ma_ctdh` varchar(50) NOT NULL,
  `ma_don_hang` varchar(50) DEFAULT NULL,
  `ma_thuc_don` varchar(50) DEFAULT NULL,
  `so_luong` int(11) NOT NULL,
  `gia_tai_thoi_diem_dat` decimal(12,2) NOT NULL,
  `ghi_chu` varchar(255) DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chi_tiet_don_hang`
--

INSERT INTO `chi_tiet_don_hang` (`ma_ctdh`, `ma_don_hang`, `ma_thuc_don`, `so_luong`, `gia_tai_thoi_diem_dat`, `ghi_chu`, `ngay_tao`) VALUES
('CT1', 'DH1', 'TD01', 1, '25000.00', '', '2026-01-12 18:10:24'),
('CT10', 'DH5', 'TD03', 1, '30000.00', '', '2026-01-12 19:48:52'),
('CT11', 'DH6', 'TD03', 1, '30000.00', '', '2026-01-12 19:49:59'),
('CT12', 'DH6', 'TD06', 1, '5000.00', '', '2026-01-12 19:49:59'),
('CT13', 'DH7', 'TD06', 1, '5000.00', '', '2026-01-12 23:14:55'),
('CT14', 'DH7', 'TD03', 1, '30000.00', '', '2026-01-12 23:14:55'),
('CT15', 'DH7', 'TD02', 1, '20000.00', '', '2026-01-12 23:14:55'),
('CT16', 'DH7', 'TD05', 1, '40000.00', '', '2026-01-12 23:14:55'),
('CT17', 'DH7', 'TD04', 1, '35000.00', '', '2026-01-12 23:14:55'),
('CT18', 'DH7', 'TD09', 1, '10000.00', '', '2026-01-12 23:14:55'),
('CT19', 'DH7', 'TD13', 2, '30000.00', '', '2026-01-12 23:14:55'),
('CT2', 'DH2', 'TD02', 1, '20000.00', '', '2026-01-12 18:22:05'),
('CT20', 'DH7', 'TD10', 1, '15000.00', '', '2026-01-12 23:14:55'),
('CT21', 'DH7', 'TD11', 1, '35000.00', '', '2026-01-12 23:14:55'),
('CT22', 'DH7', 'TD01', 2, '25000.00', '', '2026-01-12 23:14:55'),
('CT23', 'DH8', 'TD17', 1, '10000.00', '', '2026-01-13 00:08:11'),
('CT24', 'DH8', 'TD18', 1, '4000.00', '', '2026-01-13 00:08:11'),
('CT25', 'DH9', 'TD18', 1, '4000.00', '', '2026-01-13 00:13:09'),
('CT26', 'DH10', 'TD01', 1, '25000.00', '', '2026-01-13 00:13:38'),
('CT27', 'DH10', 'TD08', 1, '10000.00', '', '2026-01-13 00:13:38'),
('CT28', 'DH11', 'TD17', 1, '10000.00', '', '2026-01-13 00:22:26'),
('CT29', 'DH11', 'TD18', 1, '4000.00', '', '2026-01-13 00:22:26'),
('CT3', 'DH3', 'TD01', 1, '25000.00', '', '2026-01-12 19:30:05'),
('CT30', 'DH11', 'TD19', 1, '30000.00', '', '2026-01-13 00:22:26'),
('CT31', 'DH12', 'TD01', 1, '25000.00', '', '2026-01-13 00:27:29'),
('CT32', 'DH12', 'TD02', 1, '20000.00', '', '2026-01-13 00:27:29'),
('CT33', 'DH12', 'TD03', 1, '30000.00', '', '2026-01-13 00:27:29'),
('CT34', 'DH12', 'TD04', 1, '35000.00', '', '2026-01-13 00:27:29'),
('CT35', 'DH12', 'TD05', 1, '40000.00', '', '2026-01-13 00:27:29'),
('CT36', 'DH12', 'TD06', 1, '5000.00', '', '2026-01-13 00:27:29'),
('CT37', 'DH12', 'TD07', 1, '35000.00', '', '2026-01-13 00:27:29'),
('CT38', 'DH12', 'TD08', 1, '10000.00', '', '2026-01-13 00:27:29'),
('CT39', 'DH12', 'TD09', 1, '10000.00', '', '2026-01-13 00:27:29'),
('CT4', 'DH3', 'TD02', 1, '20000.00', '', '2026-01-12 19:30:05'),
('CT40', 'DH12', 'TD10', 1, '15000.00', '', '2026-01-13 00:27:29'),
('CT41', 'DH12', 'TD11', 1, '35000.00', '', '2026-01-13 00:27:29'),
('CT42', 'DH12', 'TD12', 1, '25000.00', '', '2026-01-13 00:27:29'),
('CT43', 'DH12', 'TD13', 1, '30000.00', '', '2026-01-13 00:27:29'),
('CT44', 'DH12', 'TD14', 1, '30000.00', '', '2026-01-13 00:27:29'),
('CT45', 'DH12', 'TD15', 1, '30000.00', '', '2026-01-13 00:27:29'),
('CT46', 'DH12', 'TD16', 1, '30000.00', '', '2026-01-13 00:27:29'),
('CT47', 'DH12', 'TD17', 1, '10000.00', '', '2026-01-13 00:27:29'),
('CT48', 'DH12', 'TD18', 1, '4000.00', '', '2026-01-13 00:27:29'),
('CT49', 'DH12', 'TD19', 1, '30000.00', '', '2026-01-13 00:27:29'),
('CT5', 'DH3', 'TD03', 1, '30000.00', '', '2026-01-12 19:30:05'),
('CT50', 'DH13', 'TD01', 1, '25000.00', '', '2026-01-13 00:28:35'),
('CT51', 'DH13', 'TD02', 1, '20000.00', '', '2026-01-13 00:28:35'),
('CT52', 'DH13', 'TD03', 1, '30000.00', '', '2026-01-13 00:28:35'),
('CT53', 'DH13', 'TD04', 1, '35000.00', '', '2026-01-13 00:28:35'),
('CT54', 'DH13', 'TD05', 1, '40000.00', '', '2026-01-13 00:28:35'),
('CT55', 'DH13', 'TD06', 1, '5000.00', '', '2026-01-13 00:28:35'),
('CT56', 'DH13', 'TD07', 1, '35000.00', '', '2026-01-13 00:28:35'),
('CT57', 'DH13', 'TD08', 1, '10000.00', '', '2026-01-13 00:28:35'),
('CT58', 'DH13', 'TD09', 1, '10000.00', '', '2026-01-13 00:28:35'),
('CT59', 'DH13', 'TD10', 1, '15000.00', '', '2026-01-13 00:28:35'),
('CT6', 'DH3', 'TD04', 1, '35000.00', '', '2026-01-12 19:30:05'),
('CT60', 'DH13', 'TD11', 1, '35000.00', '', '2026-01-13 00:28:35'),
('CT61', 'DH13', 'TD12', 1, '25000.00', '', '2026-01-13 00:28:35'),
('CT62', 'DH13', 'TD13', 1, '30000.00', '', '2026-01-13 00:28:35'),
('CT63', 'DH13', 'TD14', 1, '30000.00', '', '2026-01-13 00:28:35'),
('CT64', 'DH13', 'TD15', 1, '30000.00', '', '2026-01-13 00:28:35'),
('CT65', 'DH13', 'TD16', 1, '30000.00', '', '2026-01-13 00:28:35'),
('CT66', 'DH13', 'TD17', 1, '10000.00', '', '2026-01-13 00:28:35'),
('CT67', 'DH13', 'TD18', 1, '4000.00', '', '2026-01-13 00:28:35'),
('CT68', 'DH13', 'TD19', 1, '30000.00', '', '2026-01-13 00:28:35'),
('CT7', 'DH3', 'TD05', 1, '40000.00', '', '2026-01-12 19:30:05'),
('CT8', 'DH3', 'TD06', 1, '5000.00', '', '2026-01-12 19:30:05'),
('CT9', 'DH4', 'TD02', 1, '20000.00', '', '2026-01-12 19:36:45');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_muc`
--

CREATE TABLE `danh_muc` (
  `ma_danh_muc` varchar(50) NOT NULL,
  `ten_danh_muc` varchar(100) NOT NULL,
  `image` text DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_muc`
--

INSERT INTO `danh_muc` (`ma_danh_muc`, `ten_danh_muc`, `image`, `ngay_tao`) VALUES
('DM01', 'Cà phê', 'capheden.jpg', '2025-12-28 00:29:07'),
('DM02', 'Trà', 'tra_chanh.jpg', '2025-12-28 00:29:07'),
('DM03', 'Sinh tố', 'sinhtoduahau.jpg', '2025-12-28 00:29:07'),
('DM04', 'Nước ép', 'nuoc_cam.jpg', '2025-12-28 00:29:07'),
('DM05', 'Bánh ngọt ', 'Sachertorte.jpg', '2025-12-28 00:29:07'),
('DM06', 'Nước lọc', 'nuoc_lavie.jpg', '2025-12-28 09:53:25'),
('DM07', 'Nước ngọt', 'coca.jpg', '2026-01-12 22:51:51');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `don_hang`
--

CREATE TABLE `don_hang` (
  `ma_don_hang` varchar(50) NOT NULL,
  `ma_ban` varchar(50) DEFAULT NULL,
  `ma_user` varchar(50) DEFAULT NULL,
  `ma_khuyen_mai` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tien_khuyen_mai` decimal(10,2) DEFAULT 0.00,
  `tong_tien` decimal(12,2) DEFAULT 0.00,
  `trang_thai_thanh_toan` enum('chua_thanh_toan','da_thanh_toan') DEFAULT 'chua_thanh_toan',
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `don_hang`
--

INSERT INTO `don_hang` (`ma_don_hang`, `ma_ban`, `ma_user`, `ma_khuyen_mai`, `tien_khuyen_mai`, `tong_tien`, `trang_thai_thanh_toan`, `ngay_tao`) VALUES
('DH1', 'KHACH_HANG', 'U05', NULL, '12000.00', '25000.00', 'da_thanh_toan', '2026-01-11 18:10:24'),
('DH10', 'B01', 'U02', NULL, '0.00', '35000.00', 'da_thanh_toan', '2026-01-13 00:13:38'),
('DH11', 'B01', 'U02', NULL, '5000.00', '44000.00', 'da_thanh_toan', '2026-01-13 00:22:26'),
('DH12', 'KHACH_HANG', 'U05', NULL, '5000.00', '449000.00', 'da_thanh_toan', '2026-01-13 00:27:29'),
('DH13', 'B02', 'U02', NULL, '20000.00', '449000.00', 'da_thanh_toan', '2026-01-13 00:28:35'),
('DH2', 'KHACH_HANG', 'U05', NULL, '12000.00', '20000.00', 'da_thanh_toan', '2026-01-12 18:22:05'),
('DH3', 'KHACH_HANG', 'U05', NULL, '12000.00', '155000.00', 'da_thanh_toan', '2026-01-12 19:30:05'),
('DH4', 'KHACH_HANG', 'U05', NULL, '0.00', '20000.00', 'da_thanh_toan', '2026-01-12 19:36:45'),
('DH5', 'B04', 'U02', NULL, '12000.00', '30000.00', 'da_thanh_toan', '2026-01-12 19:48:52'),
('DH6', 'B01', 'U02', NULL, '0.00', '35000.00', 'da_thanh_toan', '2026-01-12 19:49:59'),
('DH7', 'KHACH_HANG', 'U05', NULL, '0.00', '300000.00', 'chua_thanh_toan', '2026-01-12 23:14:55'),
('DH8', 'B01', 'U02', NULL, '0.00', '14000.00', 'da_thanh_toan', '2026-01-13 00:08:11'),
('DH9', 'KHACH_HANG', 'U05', NULL, '0.00', '4000.00', 'da_thanh_toan', '2026-01-13 00:13:09');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khuyen_mai`
--

CREATE TABLE `khuyen_mai` (
  `ma_khuyen_mai` varchar(30) NOT NULL,
  `ten_khuyen_mai` varchar(50) NOT NULL,
  `tien_khuyen_mai` decimal(10,2) NOT NULL,
  `ghi_chu` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `khuyen_mai`
--

INSERT INTO `khuyen_mai` (`ma_khuyen_mai`, `ten_khuyen_mai`, `tien_khuyen_mai`, `ghi_chu`) VALUES
('KM1', 'Giảm giá Tết', '10000.00', 'Áp dụng dịp lễ Tết'),
('KM2', 'Ưu đãi mùa xuân', '12000.00', 'Chương trình mùa xuân'),
('KM3', 'Giảm tháng 1', '20000.00', 'Khuyến mãi tháng 1'),
('KM4', 'Ưu đãi cuối tuần', '5000.00', 'Áp dụng Thứ 7 – CN'),
('KM5', 'Giảm sinh viên', '8000.00', 'Áp dụng cho sinh viên'),
('KM6', 'Giảm khách thân thiết', '15000.00', 'Khách hàng VIP'),
('KM7', 'Giờ vàng buổi sáng', '5000.00', 'Áp dụng 7h – 9h sáng'),
('KM8', 'Giảm cuối tháng', '10000.00', 'Tổng kết cuối tháng'),
('KM9', 'Khuyến mãi khai trương', '20000.00', 'Áp dụng chi nhánh mới');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Nhacungcap`
--

CREATE TABLE `Nhacungcap` (
  `mancc` varchar(20) NOT NULL,
  `tenncc` varchar(100) NOT NULL,
  `diachi` varchar(255) DEFAULT NULL,
  `dienthoai` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Nhacungcap`
--

INSERT INTO `Nhacungcap` (`mancc`, `tenncc`, `diachi`, `dienthoai`) VALUES
('NCC1', 'Trung Nguyên', 'Tây Nguyên', '0123456789'),
('NCC10', 'Sushi Nhật Bản', 'Nhật Bản', '01232134124'),
('NCC11', 'Bánh KingDo', 'Hà Nội', '01231216473'),
('NCC111', 'Bấm móng tay', 'Hà nội', '0232519438'),
('NCC12', 'Cốm Xuyên', 'Hà nội', '0232519438'),
('NCC2', ' Lavie', 'Hà Nội', '0123456789'),
('NCC3', 'Lam Sơn', 'Quảng Ngãi', '0123456789'),
('NCC4', 'Nước Ngọt', 'Hà Nội', '0123456789'),
('NCC5', 'ThaiLanfood', 'Hà Nội', '0123456789'),
('NCC7', 'Blaofood', 'Tây Nguyên', '0389783611'),
('NCC712', 'Bút Bi Thiên Long', '12', '12'),
('NCC72', '12', '121', '0123456789'),
('NCC8', 'Đào Lạng Sơn', 'Lạng Sơn', '0369783618'),
('NCC9', 'Tân Cương', 'Thái Nguyên', '0389783612');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sanpham2`
--

CREATE TABLE `sanpham2` (
  `masp` varchar(20) NOT NULL,
  `tensp` varchar(100) NOT NULL,
  `gia` varchar(20) NOT NULL,
  `soluong` varchar(20) NOT NULL,
  `mancc` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sanpham2`
--

INSERT INTO `sanpham2` (`masp`, `tensp`, `gia`, `soluong`, `mancc`) VALUES
('1', 'Bột Thái Xanh', '150000', '6', 'NCC5'),
('10', 'Bơ nguyên trái', '23000', '5', 'NCC7'),
('12', 'Bột Cà Phê', '56000', '10', 'NCC1'),
('12123', '1', '1', '1', 'NCC3'),
('2', 'Nước lọc', '3000', '24', 'NCC2'),
('3', 'Trứng gà ', '4000', '100', 'NCC3'),
('4', 'Cốt đường mía', '45000', '3', 'NCC4'),
('5', 'Bột trà thái xanh', '185000', '5', 'NCC5'),
('SP0213', 'Kem', '8000', '1', 'NCC1');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thuc_don`
--

CREATE TABLE `thuc_don` (
  `ma_thuc_don` varchar(50) NOT NULL,
  `ten_mon` varchar(150) NOT NULL,
  `gia` decimal(12,2) NOT NULL,
  `so_luong` int(100) DEFAULT NULL,
  `ma_danh_muc` varchar(50) DEFAULT NULL,
  `img_thuc_don` text DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `thuc_don`
--

INSERT INTO `thuc_don` (`ma_thuc_don`, `ten_mon`, `gia`, `so_luong`, `ma_danh_muc`, `img_thuc_don`, `ngay_tao`) VALUES
('TD01', 'Cà phê sữa', '25000.00', 77, 'DM01', 'caphesua.jpg', '2025-12-28 00:29:07'),
('TD02', 'Cà phê đen', '20000.00', 87, 'DM01', 'capheden.jpg', '2025-12-28 00:29:07'),
('TD03', 'Trà đào', '30000.00', 45, 'DM02', 'tradao.jpg', '2025-12-28 00:29:07'),
('TD04', 'Sinh tố bơ', '35000.00', 97, 'DM03', 'sinhtobo.jpg', '2025-12-28 00:29:07'),
('TD05', 'Bánh tiramisu', '40000.00', 96, 'DM05', 'tiramisu.jpg', '2025-12-28 00:29:07'),
('TD06', 'Dasani', '5000.00', 92, 'DM06', 'Danasi.jpg', '2025-12-31 00:18:31'),
('TD07', 'Capuchino', '35000.00', 98, 'DM01', 'capuchino.jpg', '2025-12-31 00:38:24'),
('TD08', 'Pessi', '10000.00', 97, 'DM07', 'pessi.jpg', '2026-01-03 00:32:57'),
('TD09', 'Coca_cola', '10000.00', 98, 'DM07', 'coca.jpg', '2026-01-12 22:53:35'),
('TD10', 'Trà chanh', '15000.00', 98, 'DM02', 'tra_chanh.jpg', '2026-01-12 22:59:26'),
('TD11', 'Bạc Xỉu', '35000.00', 98, 'DM01', 'bac_xiu.jpg', '2026-01-12 23:00:27'),
('TD12', 'Trà Vải', '25000.00', 98, 'DM02', 'tra_vai.jpg', '2026-01-12 23:01:53'),
('TD13', 'Nước ép dưa hấu', '30000.00', 98, 'DM04', 'sinhtoduahau.jpg', '2026-01-12 23:03:30'),
('TD14', 'Nước cam', '30000.00', 98, 'DM04', 'nuoc_cam.jpg', '2026-01-12 23:04:44'),
('TD15', 'Swedish Princess', '30000.00', 98, 'DM05', 'Swedish_Princess.jpg', '2026-01-12 23:06:44'),
('TD16', 'Sachertorte', '30000.00', 98, 'DM05', 'Sachertorte.jpg', '2026-01-12 23:07:43'),
('TD17', 'Fanta', '10000.00', 96, 'DM07', 'fanta.jpg', '2026-01-12 23:08:35'),
('TD18', 'Lavie', '4000.00', 95, 'DM06', 'nuoc_lavie.jpg', '2026-01-12 23:09:51'),
('TD19', 'Sinh tố dâu tây', '30000.00', 97, 'DM03', 'sinhtodau.jpg', '2026-01-12 23:13:55');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `ma_user` varchar(50) NOT NULL,
  `ten_user` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phan_quyen` enum('admin','nhan_vien','khach_hang') DEFAULT 'nhan_vien',
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`ma_user`, `ten_user`, `password`, `email`, `phan_quyen`, `ngay_tao`) VALUES
('U01', 'Admin', '123', 'admin@gmail.com', 'admin', '2025-12-11 00:29:07'),
('U02', 'vinh', '123', 'a@gmail.com', 'nhan_vien', '2025-12-28 00:29:07'),
('U03', 'qa', '123', 'b@gmail.com', 'nhan_vien', '2025-12-28 00:29:07'),
('U04', 'thanh', '123', 'c@gmail.com', 'nhan_vien', '2025-12-28 00:29:07'),
('U05', 'vanh', '123', 'd@gmail.com', 'khach_hang', '2025-12-28 00:29:07'),
('U06', 'quanly', '123', 'vinh@gmail.com', 'admin', '2025-12-28 21:23:03'),
('U07', 'chuong', '123', 'daovinhgm12005@gmail.com', 'khach_hang', '2026-01-12 19:47:11'),
('U08', 'xuan', '123', 'daovinhgm2005@gmail.com', 'khach_hang', '2026-01-12 18:03:33');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `ban_uong`
--
ALTER TABLE `ban_uong`
  ADD PRIMARY KEY (`ma_ban`);

--
-- Chỉ mục cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD PRIMARY KEY (`ma_ctdh`),
  ADD KEY `fk_ctdh_donhang` (`ma_don_hang`),
  ADD KEY `fk_ctdh_thucdon` (`ma_thuc_don`);

--
-- Chỉ mục cho bảng `danh_muc`
--
ALTER TABLE `danh_muc`
  ADD PRIMARY KEY (`ma_danh_muc`);

--
-- Chỉ mục cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD PRIMARY KEY (`ma_don_hang`),
  ADD KEY `fk_donhang_ban` (`ma_ban`),
  ADD KEY `fk_donhang_user` (`ma_user`),
  ADD KEY `fk_donhang_khuyenmai` (`ma_khuyen_mai`);

--
-- Chỉ mục cho bảng `khuyen_mai`
--
ALTER TABLE `khuyen_mai`
  ADD PRIMARY KEY (`ma_khuyen_mai`);

--
-- Chỉ mục cho bảng `Nhacungcap`
--
ALTER TABLE `Nhacungcap`
  ADD PRIMARY KEY (`mancc`);

--
-- Chỉ mục cho bảng `sanpham2`
--
ALTER TABLE `sanpham2`
  ADD PRIMARY KEY (`masp`),
  ADD KEY `fk_sanpham_nhacungcap` (`mancc`);

--
-- Chỉ mục cho bảng `thuc_don`
--
ALTER TABLE `thuc_don`
  ADD PRIMARY KEY (`ma_thuc_don`),
  ADD KEY `fk_thucdon_danhmuc` (`ma_danh_muc`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ma_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD CONSTRAINT `fk_ctdh_donhang` FOREIGN KEY (`ma_don_hang`) REFERENCES `don_hang` (`ma_don_hang`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ctdh_thucdon` FOREIGN KEY (`ma_thuc_don`) REFERENCES `thuc_don` (`ma_thuc_don`);

--
-- Các ràng buộc cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD CONSTRAINT `fk_donhang_ban` FOREIGN KEY (`ma_ban`) REFERENCES `ban_uong` (`ma_ban`),
  ADD CONSTRAINT `fk_donhang_khuyenmai` FOREIGN KEY (`ma_khuyen_mai`) REFERENCES `khuyen_mai` (`ma_khuyen_mai`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_donhang_user` FOREIGN KEY (`ma_user`) REFERENCES `users` (`ma_user`);

--
-- Các ràng buộc cho bảng `sanpham2`
--
ALTER TABLE `sanpham2`
  ADD CONSTRAINT `fk_sanpham_nhacungcap` FOREIGN KEY (`mancc`) REFERENCES `nhacungcap` (`mancc`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `thuc_don`
--
ALTER TABLE `thuc_don`
  ADD CONSTRAINT `fk_thucdon_danhmuc` FOREIGN KEY (`ma_danh_muc`) REFERENCES `danh_muc` (`ma_danh_muc`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
