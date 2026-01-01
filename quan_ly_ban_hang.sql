-- phpMyAdmin SQL Dump - Cấu trúc hoàn chỉnh và tối ưu cho quản lý quán ăn/nhà hàng
-- Loại bỏ các bảng bị trùng lặp/thừa (SANPHAM, Nhasanxuat) và tối ưu hóa khóa ngoại.

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
-- BẢNG 1: users (Người dùng/Nhân viên)
--
CREATE TABLE `users` (
  `ma_user` varchar(50) NOT NULL,
  `ten_user` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `phan_quyen` enum('admin','nhan_vien') DEFAULT 'nhan_vien',
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`ma_user`, `ten_user`, `password`, `email`, `phan_quyen`, `ngay_tao`) VALUES
('U01', 'Admin', '123', 'admin@gmail.com', 'admin', '2025-12-11 00:29:07'),
('U02', 'vinh', '123', 'a@gmail.com', 'nhan_vien', '2025-12-28 00:29:07'),
('U06', 'quanly', '123', 'vinh@gmail.com', 'admin', '2025-12-28 21:23:03');

-- --------------------------------------------------------

--
-- BẢNG 2: danh_muc (Danh mục Món ăn)
--
CREATE TABLE `danh_muc` (
  `ma_danh_muc` varchar(50) NOT NULL,
  `ten_danh_muc` varchar(100) NOT NULL UNIQUE,
  `image` text DEFAULT NULL,
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `danh_muc` (`ma_danh_muc`, `ten_danh_muc`, `image`, `ngay_tao`) VALUES
('DM01', 'Cà phê', 'https://maccakimlien.com/...', '2025-12-28 00:29:07'),
('DM02', 'Trà', 'https://vienhuyethoc.vn/...', '2025-12-28 00:29:07'),
('DM05', 'Bánh ngọt', '/qlsp/Public/Pictures/danhmuc/dm_DM05_1766897204.jpeg', '2025-12-28 00:29:07'),
('DM06', 'Nước lọc', 'https://www.sonha.com.vn/...', '2025-12-28 09:53:25');

-- --------------------------------------------------------

--
-- BẢNG 3: thuc_don (Món ăn/Sản phẩm bán)
--
CREATE TABLE `thuc_don` (
  `ma_thuc_don` varchar(50) NOT NULL,
  `ten_mon` varchar(150) NOT NULL,
  `gia` decimal(12,2) NOT NULL,
  `so_luong` int(100) DEFAULT NULL,
  `ma_danh_muc` varchar(50) DEFAULT NULL,
  `img_thuc_don` text DEFAULT NULL,
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `thuc_don` (`ma_thuc_don`, `ten_mon`, `gia`, `so_luong`, `ma_danh_muc`, `img_thuc_don`, `ngay_tao`) VALUES
('TD01', 'Cà phê sữa', '25000.00', 30, 'DM01', 'https://sahaco.vn/...', '2025-12-28 00:29:07'),
('TD02', 'Cà phê đen', '20000.00', 15, 'DM01', 'https://cafefcdn.com/...', '2025-12-28 00:29:07'),
('TD03', 'Trà đào', '30000.00', 10, 'DM02', 'https://horecavn.com/...', '2025-12-28 00:29:07'),
('TD06', 'Dasani', '5000.00', 11, 'DM06', 'https://www.lottemart.vn/...', '2025-12-31 00:18:31');

-- --------------------------------------------------------

--
-- BẢNG 4: ban_uong (Bàn)
--
CREATE TABLE `ban_uong` (
  `ma_ban` varchar(50) NOT NULL,
  `ten_ban` varchar(100) NOT NULL UNIQUE,
  `so_cho_ngoi` int(11) NOT NULL,
  `trang_thai_ban` enum('trong','dang_su_dung') DEFAULT 'trong',
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `ban_uong` (`ma_ban`, `ten_ban`, `so_cho_ngoi`, `trang_thai_ban`, `ngay_tao`) VALUES
('B01', 'Bàn 1', 4, 'dang_su_dung', '2025-12-28 00:29:07'),
('B02', 'Bàn 2', 2, 'trong', '2025-12-28 00:29:07'),
('B05', 'Trong nhà kính', 8, 'dang_su_dung', '2025-12-28 00:29:07');

-- --------------------------------------------------------

--
-- BẢNG 5: don_hang (Đơn hàng/Hóa đơn)
--
CREATE TABLE `don_hang` (
  `ma_don_hang` varchar(50) NOT NULL,
  `ma_ban` varchar(50) DEFAULT NULL,
  `ma_user` varchar(50) DEFAULT NULL,
  `tong_tien` decimal(12,2) DEFAULT 0.00,
  `trang_thai_thanh_toan` enum('chua_thanh_toan','da_thanh_toan') DEFAULT 'chua_thanh_toan',
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `don_hang` (`ma_don_hang`, `ma_ban`, `ma_user`, `tong_tien`, `trang_thai_thanh_toan`, `ngay_tao`) VALUES
('DH1', 'B01', 'U02', '30000.00', 'da_thanh_toan', '2025-12-30 17:50:28'),
('DH2', 'B02', 'U02', '20000.00', 'da_thanh_toan', '2025-12-30 17:50:54'),
('DH3', 'B01', 'U01', '60000.00', 'da_thanh_toan', '2025-12-30 18:55:00');

-- --------------------------------------------------------

--
-- BẢNG 6: chi_tiet_don_hang (Chi tiết Món ăn trong Đơn hàng)
--
CREATE TABLE `chi_tiet_don_hang` (
  `ma_ctdh` varchar(50) NOT NULL,
  `ma_don_hang` varchar(50) NOT NULL, -- KHÔNG cho phép NULL
  `ma_thuc_don` varchar(50) NOT NULL, -- KHÔNG cho phép NULL
  `so_luong` int(11) NOT NULL,
  `gia_tai_thoi_diem_dat` decimal(12,2) NOT NULL,
  `ghi_chu` varchar(255) DEFAULT NULL,
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP(),
  -- Thêm khóa tổ hợp để đảm bảo mỗi món chỉ xuất hiện 1 lần trong 1 đơn hàng
  UNIQUE KEY `uk_ctdh` (`ma_don_hang`,`ma_thuc_don`) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `chi_tiet_don_hang` VALUES
('CT01','DH1','TD01',1,25000.00,'Ít đá','2026-01-01'),
('CT02','DH1','TD03',1,30000.00,NULL,'2026-01-01'),
('CT03','DH2','TD02',1,20000.00,NULL,'2026-01-01');

-- --------------------------------------------------------

--
-- BẢNG 7: khuyen_mai (Chương trình Khuyến mãi)
--
CREATE TABLE `khuyen_mai` (
  `ma_khuyen_mai` varchar(30) NOT NULL,
  `ten_khuyen_mai` varchar(50) NOT NULL,
  `gia_tri_giam` decimal(10,2) NOT NULL, -- Đổi tên cột từ tien_khuyen_mai
  `loai_khuyen_mai` ENUM('phan_tram', 'tien_mat') NOT NULL, -- Thêm loại khuyến mãi
  `ngay_bat_dau` DATE NOT NULL,
  `ngay_ket_thuc` DATE NOT NULL,
  `ghi_chu` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- BẢNG 8: nhacungcap (Nhà cung cấp Nguyên vật liệu)
--
CREATE TABLE `nhacungcap` (
  `mancc` varchar(20) NOT NULL,
  `tenncc` varchar(100) NOT NULL,
  `diachi` varchar(255) DEFAULT NULL,
  `dienthoai` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `nhacungcap` (`mancc`, `tenncc`, `diachi`, `dienthoai`) VALUES
('NCC1', 'Trung Nguyên', 'Tây Nguyên', '0123456789'),
('NCC2', 'Lavie', 'Hà Nội', '0123456789'),
('NCC3', 'Lam Sơn (Trứng)', 'Quảng Ngãi', '0123456789');

-- --------------------------------------------------------

--
-- BẢNG 9: nguyen_lieu (Nguyên vật liệu nhập kho) - Thay thế cho sanpham2
--
CREATE TABLE `nguyen_lieu` (
  `ma_nl` varchar(20) NOT NULL, -- Đổi tên cột từ masp
  `ten_nl` varchar(100) NOT NULL, -- Đổi tên cột từ tensp
  `gia_nhap` decimal(12,2) NOT NULL, -- Đổi tên cột từ gia
  `so_luong_ton` int(11) NOT NULL, -- Đổi tên cột từ soluong
  `don_vi_tinh` varchar(20) NOT NULL, -- Thêm đơn vị tính (kg, lít, gói,...)
  `mancc` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `nguyen_lieu` (`ma_nl`, `ten_nl`, `gia_nhap`, `so_luong_ton`, `don_vi_tinh`, `mancc`) VALUES
('NL01', 'Bột Cà Phê', '56000.00', 10, 'kg', 'NCC1'),
('NL02', 'Nước lọc (thùng)', '3000.00', 24, 'thùng', 'NCC2'),
('NL03', 'Trứng gà', '4000.00', 100, 'quả', 'NCC3');

-- --------------------------------------------------------
--
-- CHỈ MỤC (INDEXES)
--
--

--
-- Chỉ mục cho bảng `ban_uong`
--
ALTER TABLE `ban_uong`
  ADD PRIMARY KEY (`ma_ban`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ma_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `danh_muc`
--
ALTER TABLE `danh_muc`
  ADD PRIMARY KEY (`ma_danh_muc`);

--
-- Chỉ mục cho bảng `thuc_don`
--
ALTER TABLE `thuc_don`
  ADD PRIMARY KEY (`ma_thuc_don`),
  ADD KEY `fk_thucdon_danhmuc` (`ma_danh_muc`);

--
-- Chỉ mục cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD PRIMARY KEY (`ma_don_hang`),
  ADD KEY `idx_ma_ban` (`ma_ban`), -- Thêm index cho khóa ngoại
  ADD KEY `idx_ma_user` (`ma_user`); -- Thêm index cho khóa ngoại

--
-- Chỉ mục cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD PRIMARY KEY (`ma_ctdh`),
  ADD KEY `idx_ma_don_hang` (`ma_don_hang`), -- Thêm index cho khóa ngoại
  ADD KEY `idx_ma_thuc_don` (`ma_thuc_don`); -- Thêm index cho khóa ngoại

--
-- Chỉ mục cho bảng `khuyen_mai`
--
ALTER TABLE `khuyen_mai`
  ADD PRIMARY KEY (`ma_khuyen_mai`);

--
-- Chỉ mục cho bảng `nhacungcap`
--
ALTER TABLE `nhacungcap`
  ADD PRIMARY KEY (`mancc`);

--
-- Chỉ mục cho bảng `nguyen_lieu`
--
ALTER TABLE `nguyen_lieu`
  ADD PRIMARY KEY (`ma_nl`),
  ADD KEY `fk_nguyenlieu_nhacungcap` (`mancc`);


-- --------------------------------------------------------
--
-- CÁC RÀNG BUỘC (FOREIGN KEYS)
--
--

--
-- Các ràng buộc cho bảng `thuc_don`
--
ALTER TABLE `thuc_don`
  ADD CONSTRAINT `fk_thucdon_danhmuc` FOREIGN KEY (`ma_danh_muc`) REFERENCES `danh_muc` (`ma_danh_muc`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD CONSTRAINT `fk_donhang_ban` FOREIGN KEY (`ma_ban`) REFERENCES `ban_uong` (`ma_ban`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_donhang_user` FOREIGN KEY (`ma_user`) REFERENCES `users` (`ma_user`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD CONSTRAINT `fk_ctdh_donhang` FOREIGN KEY (`ma_don_hang`) REFERENCES `don_hang` (`ma_don_hang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ctdh_thucdon` FOREIGN KEY (`ma_thuc_don`) REFERENCES `thuc_don` (`ma_thuc_don`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `nguyen_lieu`
--
ALTER TABLE `nguyen_lieu`
  ADD CONSTRAINT `fk_nguyenlieu_nhacungcap` FOREIGN KEY (`mancc`) REFERENCES `nhacungcap` (`mancc`) ON UPDATE CASCADE;


COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;