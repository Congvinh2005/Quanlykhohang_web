-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th1 01, 2026 lúc 05:45 PM
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
('B08', 'Vip 1', 6, 'trong', '2025-12-29 12:22:43');

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
('CT1', 'DH1', 'TD02', 1, '20000.00', '', '2026-01-01 23:33:35'),
('CT2', 'DH1', 'TD03', 1, '30000.00', '', '2026-01-01 23:33:35'),
('CT3', 'DH2', 'TD03', 1, '30000.00', '', '2026-01-01 23:44:15');

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
('DM01', 'Cà phê', 'https://maccakimlien.com/vnt_upload/news/07_2025/ca_phe_3.jpg', '2025-12-28 00:29:07'),
('DM02', 'Trà', 'https://vienhuyethoc.vn/wp-content/uploads/2023/01/tra-xanh-117.jpg', '2025-12-28 00:29:07'),
('DM03', 'Sinh tố', 'https://elmich.vn/wp-content/uploads/2024/01/sinh-to-bo-xoai-5.jpg', '2025-12-28 00:29:07'),
('DM04', 'Nước ép', 'https://congnghenhat.com/wp-content/uploads/2024/07/nuoc-ep-trai-cay-va-thuoc-6.jpg', '2025-12-28 00:29:07'),
('DM05', 'Bánh ngọt ', '/qlsp/Public/Pictures/danhmuc/dm_DM05_1766897204.jpeg', '2025-12-28 00:29:07'),
('DM06', 'Nước lọc', 'https://www.sonha.com.vn/wp-content/uploads/2020/12/nuoc-500.jpg', '2025-12-28 09:53:25');

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
  `ghi_chu` varchar(255) DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `don_hang`
--

INSERT INTO `don_hang` (`ma_don_hang`, `ma_ban`, `ma_user`, `ma_khuyen_mai`, `tien_khuyen_mai`, `tong_tien`, `trang_thai_thanh_toan`, `ngay_tao`) VALUES
('DH1', 'B01', 'U02', NULL, '20000.00', '50000.00', 'da_thanh_toan', '2026-01-01 17:33:35'),
('DH2', 'B02', 'U02', NULL, '20000.00', '30000.00', 'da_thanh_toan', '2026-01-01 17:44:15');

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
('KM1', 'Giảm giá tết', '111111.00', 'lễ tết'),
('KM2', 'xuân', '12000.00', 'vanvinh'),
('KM3', 'Giảm 20%', '20000.00', 'Chương trình khuyến mãi tháng 1'),
('KM4', 'Mua 1 tặng 1', '50000.00', 'Khuyến mãi đặc biệt cuối tuần');

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
('NCC8', 'Đào Lạng Sơn', 'Lạng Sơn', '0369783618'),
('NCC9', 'Tân Cương', 'Thái Nguyên', '0389783612');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Nhasanxuat`
--

CREATE TABLE `Nhasanxuat` (
  `Masx` varchar(30) NOT NULL,
  `Tensx` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Nhasanxuat`
--

INSERT INTO `Nhasanxuat` (`Masx`, `Tensx`) VALUES
('SX01', 'Nhà xuất bản Giáo Dục'),
('SX02', 'Nhà xuất bản Trẻ'),
('SX03', 'Nhà xuất bản Kim Đồng'),
('SX04', 'Nhà xuất bản Lao Động'),
('SX05', 'Nhà xuất bản Thống Kê');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `SANPHAM`
--

CREATE TABLE `SANPHAM` (
  `Masp` varchar(10) NOT NULL,
  `Tensp` varchar(150) NOT NULL,
  `Gia` varchar(30) NOT NULL,
  `Soluong` varchar(30) NOT NULL,
  `Tgbaohanh` varchar(30) DEFAULT NULL,
  `Masx` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `SANPHAM`
--

INSERT INTO `SANPHAM` (`Masp`, `Tensp`, `Gia`, `Soluong`, `Tgbaohanh`, `Masx`) VALUES
('12', '12', '12.00', '12', '2025-12-07', 'SX03'),
('123', '123', '123.00', '123', '2025-12-05', 'SX03'),
('SP01', 'Kerm', '12.00', '1', '2025-12-07', 'SX02'),
('SP02', 'Bún', '4.00', '3', '2025-12-07', 'SX01'),
('SP03', 'Kerm', '12.00', '1', '2025-12-07', 'SX01'),
('SP04', 'Bún', '4.00', '3', '2025-12-07', 'SX01'),
('SP06', 'Sản phẩm 6', '1000000.00', '5', '2025-12-07', 'SX02'),
('SP07', 'Sản phẩm 7', '2000000.00', '3', '2025-12-07', 'SX02');

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
('TD01', 'Cà phê sữa', '25000.00', 0, 'DM01', 'https://sahaco.vn/wp-content/uploads/2024/12/top-nhung-hinh-anh-ly-ca-phe-sua-da-dep-nhat-6.webp', '2025-12-28 00:29:07'),
('TD02', 'Cà phê đen', '20000.00', 1, 'DM01', 'https://cafefcdn.com/2017/photo-0-1498280500677.jpg', '2025-12-28 00:29:07'),
('TD03', 'Trà đào', '30000.00', 1, 'DM02', 'https://horecavn.com/wp-content/uploads/2024/05/huong-dan-cong-thuc-tra-dao-cam-sa-hut-khach-ngon-kho-cuong_20240526180626.jpg', '2025-12-28 00:29:07'),
('TD04', 'Sinh tố bơ', '35000.00', 2, 'DM03', 'https://png.pngtree.com/png-vector/20240731/ourmid/pngtree-avocado-smoothie-with-isolated-on-transparent-background-png-image_13317561.png', '2025-12-28 00:29:07'),
('TD05', 'Bánh tiramisu', '40000.00', 1, 'DM05', 'https://chonchon.vn/wp-content/uploads/2020/11/013-01.jpg', '2025-12-28 00:29:07'),
('TD06', 'Dasani', '5000.00', 1, 'DM06', 'https://www.lottemart.vn/media/catalog/product/cache/0x0/8/9/8935049510864.jpg.webp', '2025-12-31 00:18:31'),
('TD07', 'Nước tạm', '0.00', 1, 'DM06', 'https://www.sonha.com.vn/wp-content/uploads/2020/12/nuoc-500.jpg', '2025-12-31 00:38:24');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `ma_user` varchar(50) NOT NULL,
  `ten_user` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phan_quyen` enum('admin','nhan_vien') DEFAULT 'nhan_vien',
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
('U05', 'vanh', '123', 'd@gmail.com', 'nhan_vien', '2025-12-28 00:29:07'),
('U06', 'quanly', '123', 'vinh@gmail.com', 'admin', '2025-12-28 21:23:03'),
('U08', 'xuan', '123', 'daovinhgm2005@gmail.com', 'nhan_vien', '2025-12-28 10:09:04');

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
-- Chỉ mục cho bảng `Nhasanxuat`
--
ALTER TABLE `Nhasanxuat`
  ADD PRIMARY KEY (`Masx`);

--
-- Chỉ mục cho bảng `SANPHAM`
--
ALTER TABLE `SANPHAM`
  ADD PRIMARY KEY (`Masp`),
  ADD KEY `fk_sanpham_nhasx` (`Masx`);

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
-- Các ràng buộc cho bảng `SANPHAM`
--
ALTER TABLE `SANPHAM`
  ADD CONSTRAINT `fk_sanpham_nhasx` FOREIGN KEY (`Masx`) REFERENCES `NHASANXUAT` (`Masx`) ON UPDATE CASCADE;

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
