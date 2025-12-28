-- Cleaned SQL dump (removed `SANPHAM` and `Nhasanxuat` tables)
-- Generated: 2025-12-28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- Table structure for `ban_uong`
-- --------------------------------------------------------
CREATE TABLE `ban_uong` (
  `ma_ban` varchar(50) NOT NULL,
  `ten_ban` varchar(100) NOT NULL,
  `so_cho_ngoi` int(11) NOT NULL,
  `trang_thai_ban` enum('trong','dang_su_dung') DEFAULT 'trong',
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `ban_uong` (`ma_ban`, `ten_ban`, `so_cho_ngoi`, `trang_thai_ban`, `ngay_tao`) VALUES
('B01', 'Bàn 1', 4, 'trong', '2025-12-28 00:29:07'),
('B02', 'Bàn 2', 2, 'trong', '2025-12-28 00:29:07'),
('B03', 'Bàn 3', 6, 'trong', '2025-12-28 00:29:07'),
('B04', 'Bàn 4', 4, 'dang_su_dung', '2025-12-28 00:29:07'),
('B05', 'Bàn 5', 8, 'trong', '2025-12-28 00:29:07');

-- --------------------------------------------------------
-- Table structure for `chi_tiet_don_hang`
-- --------------------------------------------------------
CREATE TABLE `chi_tiet_don_hang` (
  `ma_ctdh` varchar(50) NOT NULL,
  `ma_don_hang` varchar(50) DEFAULT NULL,
  `ma_thuc_don` varchar(50) DEFAULT NULL,
  `so_luong` int(11) NOT NULL,
  `gia_tai_thoi_diem_dat` decimal(12,2) NOT NULL,
  `ghi_chu` varchar(255) DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `chi_tiet_don_hang` (`ma_ctdh`, `ma_don_hang`, `ma_thuc_don`, `so_luong`, `gia_tai_thoi_diem_dat`, `ghi_chu`, `ngay_tao`) VALUES
('CT01', 'DH01', 'TD01', 2, '25000.00', 'Ít đá', '2025-12-28 00:29:07'),
('CT02', 'DH01', 'TD02', 1, '20000.00', '', '2025-12-28 00:29:07'),
('CT03', 'DH02', 'TD03', 2, '30000.00', '', '2025-12-28 00:29:07'),
('CT04', 'DH03', 'TD04', 1, '35000.00', 'Không đường', '2025-12-28 00:29:07'),
('CT05', 'DH04', 'TD05', 3, '40000.00', 'Mang về', '2025-12-28 00:29:07');

-- --------------------------------------------------------
-- Table structure for `danh_muc`
-- --------------------------------------------------------
CREATE TABLE `danh_muc` (
  `ma_danh_muc` varchar(50) NOT NULL,
  `ten_danh_muc` varchar(100) NOT NULL,
  `image` text DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `danh_muc` (`ma_danh_muc`, `ten_danh_muc`, `image`, `ngay_tao`) VALUES
('DM01', 'Cà phê', 'https://maccakimlien.com/vnt_upload/news/07_2025/ca_phe_3.jpg', '2025-12-28 00:29:07'),
('DM02', 'Trà', 'https://vienhuyethoc.vn/wp-content/uploads/2023/01/tra-xanh-117.jpg', '2025-12-28 00:29:07'),
('DM03', 'Sinh tố', 'https://elmich.vn/wp-content/uploads/2024/01/sinh-to-bo-xoai-5.jpg', '2025-12-28 00:29:07'),
('DM04', 'Nước ép', 'https://congnghenhat.com/wp-content/uploads/2024/07/nuoc-ep-trai-cay-va-thuoc-6.jpg', '2025-12-28 00:29:07'),
('DM05', 'Bánh ngọt', 'https://cdn2.fptshop.com.vn/unsafe/1920x0/filters:format(webp):quality(75)/tat_ca_cac_loai_banh_ngot_0_1_1_ce126f5313.jpg', '2025-12-28 00:29:07');

-- --------------------------------------------------------
-- Table structure for `don_hang`
-- --------------------------------------------------------
CREATE TABLE `don_hang` (
  `ma_don_hang` varchar(50) NOT NULL,
  `ma_ban` varchar(50) DEFAULT NULL,
  `ma_user` varchar(50) DEFAULT NULL,
  `tong_tien` decimal(12,2) DEFAULT 0.00,
  `trang_thai_thanh_toan` enum('chua_thanh_toan','da_thanh_toan') DEFAULT 'chua_thanh_toan',
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `don_hang` (`ma_don_hang`, `ma_ban`, `ma_user`, `tong_tien`, `trang_thai_thanh_toan`, `ngay_tao`) VALUES
('DH01', 'B01', 'U02', '0.00', 'chua_thanh_toan', '2025-12-28 00:29:07'),
('DH02', 'B02', 'U03', '0.00', 'chua_thanh_toan', '2025-12-28 00:29:07'),
('DH03', 'B03', 'U04', '0.00', 'chua_thanh_toan', '2025-12-28 00:29:07'),
('DH04', 'B04', 'U05', '0.00', 'chua_thanh_toan', '2025-12-28 00:29:07'),
('DH05', 'B05', 'U02', '0.00', 'chua_thanh_toan', '2025-12-28 00:29:07');

-- --------------------------------------------------------
-- Table structure for `Nhacungcap`
-- --------------------------------------------------------
CREATE TABLE `Nhacungcap` (
  `mancc` varchar(20) NOT NULL,
  `tenncc` varchar(100) NOT NULL,
  `diachi` varchar(255) DEFAULT NULL,
  `dienthoai` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `Nhacungcap` (`mancc`, `tenncc`, `diachi`, `dienthoai`) VALUES
('NCC1', 'Hải sản top 1', 'Hà Nội', '0123456789'),
('NCC10', 'Sushi Nhật Bản', 'Nhật Bản', '01232134124'),
('NCC11', 'Bánh KingDo', 'Hà Nội', '01231216473'),
('NCC12', 'Bấm móng tay', 'Hà nội', '232519438'),
('NCC2', 'Thuốc lá Thăng Long', 'Hà Nội', '0123456789'),
('NCC3', 'Bia Hà Nội', 'Hà Nội', '0123456789'),
('NCC4', 'Nước Ngọt', 'Hà Nội', '0123456789'),
('NCC5', 'Đèn bàn', 'Hà Nội', '0123456789'),
('NCC7', 'Gốm bát tràng', 'Gia Lâm', '0389783611'),
('NCC8', 'Kinh anna', 'Hải phòng', '0369783618'),
('NCC9', 'Bephouse Hàn Quốc', 'Hàn Quốc', '0389783612');

-- --------------------------------------------------------
-- Table structure for `sanpham2`
-- --------------------------------------------------------
CREATE TABLE `sanpham2` (
  `masp` varchar(20) NOT NULL,
  `tensp` varchar(100) NOT NULL,
  `gia` varchar(20) NOT NULL,
  `soluong` varchar(20) NOT NULL,
  `mancc` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `sanpham2` (`masp`, `tensp`, `gia`, `soluong`, `mancc`) VALUES
('1', 'Bàn học sinh', '1500000.00', '11', 'NCC5'),
('10', '12', '12', '12', 'NCC4'),
('1012', '12', '12', '12', 'NCC5'),
('12', 'Kềm', '12.00', '12', 'NCC1'),
('12123', '1', '1', '1', 'NCC1'),
('15', '1', '1', '1', 'NCC7'),
('2', 'Ghế văn phòng', '120000.00', '5', 'NCC2'),
('3', 'Tủ tài liệu', '3200000.00', '3', 'NCC3'),
('4', 'Máy in HP', '4500000.00', '2', 'NCC4'),
('5', 'Laptop Dell', '18500000.00', '4', 'NCC5'),
('6', 'Thịt chó', '1800000.00', '2', 'NCC2'),
('SP016', 'Kèm', '12.00', '1', 'NCC1'),
('SP017', 'Bún', '4.00', '13', 'NCC10'),
('SP03', 'Kem', '12', '1', 'NCC1'),
('SP04', 'Bún', '4', '12', 'NCC2');

-- --------------------------------------------------------
-- Table structure for `thuc_don`
-- --------------------------------------------------------
CREATE TABLE `thuc_don` (
  `ma_thuc_don` varchar(50) NOT NULL,
  `ten_mon` varchar(150) NOT NULL,
  `gia` decimal(12,2) NOT NULL,
  `trang_thai` enum('con_ban','het_ban') DEFAULT 'con_ban',
  `ma_danh_muc` varchar(50) DEFAULT NULL,
  `img_thuc_don` text DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `thuc_don` (`ma_thuc_don`, `ten_mon`, `gia`, `trang_thai`, `ma_danh_muc`, `img_thuc_don`, `ngay_tao`) VALUES
('TD01', 'Cà phê sữa', '25000.00', 'con_ban', 'DM01', 'https://sahaco.vn/wp-content/uploads/2024/12/top-nhung-hinh-anh-ly-ca-phe-sua-da-dep-nhat-6.webp', '2025-12-28 00:29:07'),
('TD02', 'Cà phê đen', '20000.00', 'con_ban', 'DM01', 'https://cafefcdn.com/2017/photo-0-1498280500677.jpg', '2025-12-28 00:29:07'),
('TD03', 'Trà đào', '30000.00', 'con_ban', 'DM02', 'https://horecavn.com/wp-content/uploads/2024/05/huong-dan-cong-thuc-tra-dao-cam-sa-hut-khach-ngon-kho-cuong_20240526180626.jpg', '2025-12-28 00:29:07'),
('TD04', 'Sinh tố bơ', '35000.00', 'con_ban', 'DM03', 'https://png.pngtree.com/png-vector/20240731/ourmid/pngtree-avocado-smoothie-with-isolated-on-transparent-background-png-image_13317561.png', '2025-12-28 00:29:07'),
('TD05', 'Bánh tiramisu', '40000.00', 'con_ban', 'DM05', 'https://chonchon.vn/wp-content/uploads/2020/11/013-01.jpg', '2025-12-28 00:29:07');

-- --------------------------------------------------------
-- Table structure for `users`
-- --------------------------------------------------------
CREATE TABLE `users` (
  `ma_user` varchar(50) NOT NULL,
  `ten_user` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phan_quyen` enum('admin','nhan_vien') DEFAULT 'nhan_vien',
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`ma_user`, `ten_user`, `password`, `email`, `phan_quyen`, `ngay_tao`) VALUES
('U01', 'Admin', '123456', 'admin@gmail.com', 'admin', '2025-12-28 00:29:07'),
('U02', 'Nguyễn Văn A', '123456', 'a@gmail.com', 'nhan_vien', '2025-12-28 00:29:07'),
('U03', 'Trần Văn B', '123456', 'b@gmail.com', 'nhan_vien', '2025-12-28 00:29:07'),
('U04', 'Lê Văn C', '123456', 'c@gmail.com', 'nhan_vien', '2025-12-28 00:29:07'),
('U05', 'Phạm Văn D', '123456', 'd@gmail.com', 'nhan_vien', '2025-12-28 00:29:07');

-- --------------------------------------------------------
-- Indexes and constraints (kept for remaining tables)
-- --------------------------------------------------------
ALTER TABLE `ban_uong`
  ADD PRIMARY KEY (`ma_ban`);

ALTER TABLE `chi_tiet_don_hang`
  ADD PRIMARY KEY (`ma_ctdh`),
  ADD KEY `fk_ctdh_donhang` (`ma_don_hang`),
  ADD KEY `fk_ctdh_thucdon` (`ma_thuc_don`);

ALTER TABLE `danh_muc`
  ADD PRIMARY KEY (`ma_danh_muc`);

ALTER TABLE `don_hang`
  ADD PRIMARY KEY (`ma_don_hang`),
  ADD KEY `fk_donhang_ban` (`ma_ban`),
  ADD KEY `fk_donhang_user` (`ma_user`);

ALTER TABLE `Nhacungcap`
  ADD PRIMARY KEY (`mancc`);

ALTER TABLE `Nhasanxuat` -- NOTE: table removed, skip
  ;

ALTER TABLE `sanpham2`
  ADD PRIMARY KEY (`masp`),
  ADD KEY `fk_sanpham_nhacungcap` (`mancc`);

ALTER TABLE `thuc_don`
  ADD PRIMARY KEY (`ma_thuc_don`),
  ADD KEY `fk_thucdon_danhmuc` (`ma_danh_muc`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`ma_user`),
  ADD UNIQUE KEY `email` (`email`);

-- Foreign key constraints
ALTER TABLE `chi_tiet_don_hang`
  ADD CONSTRAINT `fk_ctdh_donhang` FOREIGN KEY (`ma_don_hang`) REFERENCES `don_hang` (`ma_don_hang`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ctdh_thucdon` FOREIGN KEY (`ma_thuc_don`) REFERENCES `thuc_don` (`ma_thuc_don`);

ALTER TABLE `don_hang`
  ADD CONSTRAINT `fk_donhang_ban` FOREIGN KEY (`ma_ban`) REFERENCES `ban_uong` (`ma_ban`),
  ADD CONSTRAINT `fk_donhang_user` FOREIGN KEY (`ma_user`) REFERENCES `users` (`ma_user`);

ALTER TABLE `thuc_don`
  ADD CONSTRAINT `fk_thucdon_danhmuc` FOREIGN KEY (`ma_danh_muc`) REFERENCES `danh_muc` (`ma_danh_muc`) ON DELETE SET NULL;

ALTER TABLE `sanpham2`
  ADD CONSTRAINT `fk_sanpham_nhacungcap` FOREIGN KEY (`mancc`) REFERENCES `Nhacungcap` (`mancc`) ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
