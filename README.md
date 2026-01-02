# Quản Lý Quán Ăn - Restaurant Management System

## Mô tả dự án

Đây là một hệ thống quản lý quán ăn hoàn chỉnh được xây dựng bằng PHP và MySQL, sử dụng mô hình MVC (Model-View-Controller). Hệ thống hỗ trợ quản lý thực đơn, đơn hàng, bàn ăn, người dùng và các chức năng khác của một quán ăn hiện đại.

## Tính năng nổi bật

### Quản lý thực đơn
- Thêm, sửa, xóa món ăn
- Phân loại món ăn theo danh mục
- Quản lý số lượng tồn kho
- Upload hình ảnh món ăn
- Xuất/nhập dữ liệu Excel

### Quản lý đơn hàng
- Tạo đơn hàng theo bàn
- Theo dõi trạng thái đơn hàng
- Thanh toán đa phương thức (tiền mặt, thẻ, QR code)
- Áp dụng mã giảm giá
- In hóa đơn

### Quản lý bàn ăn
- Hiển thị trạng thái bàn (trống, đang sử dụng)
- Giao diện trực quan dễ sử dụng
- Quản lý số lượng khách tối đa mỗi bàn

### Quản lý người dùng
- Đăng nhập/đăng xuất
- Phân quyền admin và nhân viên
- Quản lý thông tin người dùng
- Nhập dữ liệu từ Excel

### Quản lý danh mục
- Tạo, sửa, xóa danh mục món ăn
- Tổ chức thực đơn theo nhóm

## Công nghệ sử dụng

- **Backend**: PHP
- **Database**: MySQL
- **Frontend**: HTML, CSS, JavaScript
- **Framework**: Tự xây dựng theo mô hình MVC
- **UI Framework**: Bootstrap
- **Icons**: Font Awesome
- **Excel Export**: PHPExcel

## Cấu trúc thư mục

```
qlsp/
├── MVC/
│   ├── Controllers/     # Xử lý logic điều khiển
│   ├── Models/          # Xử lý logic dữ liệu
│   └── Views/           # Giao diện người dùng
├── Public/
│   ├── Classes/         # Thư viện hỗ trợ
│   ├── Css/             # File CSS
│   ├── Js/              # File JavaScript
│   └── Pictures/        # Hình ảnh
├── index.php            # Entry point
└── README.md
```

## Cài đặt

### Yêu cầu hệ thống
- PHP 7.0 trở lên
- MySQL 5.7 trở lên
- Apache hoặc Nginx
- Composer (tùy chọn)

### Bước 1: Clone hoặc tải dự án
```bash
git clone https://github.com/yourusername/qlsp.git
```

### Bước 2: Cấu hình cơ sở dữ liệu
1. Tạo cơ sở dữ liệu MySQL mới
2. Import file `quan_ly_ban_hang.sql` vào cơ sở dữ liệu
3. Cấu hình thông tin kết nối trong `MVC/Models/connectDB.php`

### Bước 3: Cấu hình đường dẫn
1. Cập nhật đường dẫn trong file `.htaccess` nếu cần
2. Đảm bảo thư mục `Public/Pictures/thucdon/` có quyền ghi

### Bước 4: Chạy ứng dụng
1. Khởi động máy chủ Apache và MySQL
2. Truy cập `http://localhost/qlsp` trong trình duyệt

## Cấu trúc cơ sở dữ liệu

### Bảng chính
- `ban_uong` - Quản lý bàn ăn
- `don_hang` - Quản lý đơn hàng
- `chi_tiet_don_hang` - Chi tiết đơn hàng
- `thuc_don` - Danh sách món ăn
- `danh_muc` - Danh mục món ăn
- `users` - Người dùng hệ thống
- `khuyen_mai` - Mã giảm giá

## Tính năng chi tiết

### Quản lý thực đơn
- **Thêm món ăn**: Tên món, giá, số lượng, danh mục, hình ảnh
- **Sửa thông tin**: Cập nhật giá, số lượng, hình ảnh
- **Xóa món**: Xóa khỏi hệ thống
- **Tìm kiếm**: Tìm kiếm theo tên hoặc mã món
- **Xuất Excel**: Xuất danh sách thực đơn ra file Excel
- **Import Excel**: Nhập dữ liệu từ file Excel

### Quản lý đơn hàng
- **Tạo đơn hàng**: Chọn bàn, thêm món ăn
- **Cập nhật đơn hàng**: Thêm/bớt món, thay đổi số lượng
- **Thanh toán**: Hỗ trợ nhiều phương thức (tiền mặt, thẻ, QR)
- **Trạng thái**: Theo dõi trạng thái thanh toán
- **Giảm giá**: Áp dụng mã giảm giá cho đơn hàng

### Quản lý bàn ăn
- **Hiển thị trạng thái**: Trạng thái trực quan (trống, đang sử dụng)
- **Chọn bàn**: Giao diện chọn bàn thân thiện
- **Quản lý số lượng**: Giới hạn số lượng khách mỗi bàn

### Quản lý người dùng
- **Phân quyền**: Admin và nhân viên
- **Đăng nhập**: Xác thực người dùng
- **Thông tin cá nhân**: Quản lý thông tin người dùng

## API và AJAX

Hệ thống sử dụng AJAX cho các tính năng:
- Tìm kiếm không tải lại trang
- Cập nhật trạng thái thời gian thực
- Thêm/xóa món trong giỏ hàng
- Xác nhận thanh toán

## Bảo mật

- Xác thực người dùng
- Phân quyền truy cập
- Lọc dữ liệu đầu vào
- SQL injection protection

## Giao diện người dùng

### Nhân viên
- Giao diện thân thiện cho nhân viên phục vụ
- Quản lý bàn ăn dễ dàng
- Tạo đơn hàng nhanh chóng
- Theo dõi đơn hàng đang xử lý

### Quản trị viên
- Toàn quyền quản lý hệ thống
- Quản lý người dùng
- Thống kê doanh thu
- Quản lý thực đơn và danh mục

## API endpoints

### Đơn hàng
- `/Banuong/order/{ma_ban}` - Tạo đơn hàng cho bàn
- `/Banuong/create_order` - Tạo đơn hàng mới
- `/Banuong/update_payment_status/{ma_don_hang}` - Cập nhật trạng thái thanh toán

### Thực đơn
- `/Thucdon/danhsach` - Danh sách thực đơn
- `/Thucdon/themmoi` - Thêm mới thực đơn
- `/Thucdon/sua/{ma_thuc_don}` - Sửa thực đơn

### Người dùng
- `/Users/danhsach` - Danh sách người dùng
- `/Users/login` - Đăng nhập
- `/Users/logout` - Đăng xuất

## Luồng hoạt động hệ thống

### Tổng quan luồng dữ liệu

Hệ thống quản lý quán ăn hoạt động theo luồng sau từ khi chọn bàn đến khi xem chi tiết đơn hàng:

#### 1. Chọn bàn (Staff/table hoặc Banuong/order)
- Nhân viên chọn một bàn để phục vụ khách
- Nếu bàn đang trống, nhân viên có thể bắt đầu tạo đơn hàng mới
- Nếu bàn đang có đơn hàng chưa thanh toán, hệ thống sẽ chuyển đến trang chi tiết đơn hàng đó

#### 2. Chọn món (StaffMaster với Staff/Table_order_v)
- Giao diện hiển thị các danh mục món và danh sách món
- Nhân viên có thể thêm món vào giỏ hàng cho bàn đó
- Giỏ hàng được lưu vào session theo mã bàn (dưới dạng `$_SESSION['cart_' . $ma_ban]`)
- Có thể cập nhật số lượng hoặc xóa món trong giỏ hàng

#### 3. Tạo đơn hàng (Banuong/create_order)
- Khi nhân viên nhấn nút tạo đơn hàng, hệ thống thực hiện:
  - Tạo đơn hàng mới trong bảng `don_hang` với trạng thái `chua_thanh_toan` (chưa thanh toán)
  - Tạo mã đơn hàng duy nhất
  - Lưu chi tiết các món vào bảng `chi_tiet_don_hang`
  - Cập nhật trạng thái bàn thành `dang_su_dung` (đang sử dụng)
  - Xóa giỏ hàng trong session sau khi lưu vào database

#### 4. Thanh toán (Staff/update_payment_status)
- Nhân viên nhấn nút "Thanh toán" trên trang chi tiết đơn hàng
- Gửi yêu cầu AJAX đến `Staff/update_payment_status`
- Hệ thống cập nhật trạng thái đơn hàng thành `da_thanh_toan` (đã thanh toán)
- Cập nhật trạng thái bàn thành `trong` (trống)
- Xóa giỏ hàng trong session nếu còn tồn tại

#### 5. Xem danh sách đơn hàng (Staff/orders)
- Hiển thị danh sách các đơn hàng trong hệ thống
- Có phân trang để quản lý nhiều đơn hàng
- Có thể lọc theo trạng thái (đã thanh toán/chưa thanh toán)

#### 6. Xem chi tiết đơn hàng (Staff/order_detail hoặc Banuong/order_detail)
- Hiển thị thông tin chi tiết của một đơn hàng cụ thể
- Nếu đơn hàng đã thanh toán (`da_thanh_toan`), hệ thống chỉ lấy dữ liệu từ database
- Nếu đơn hàng chưa thanh toán và không có trong database, hệ thống có thể lấy từ session cart
- Hiển thị danh sách món, số lượng, giá và tổng tiền

### Luồng dữ liệu quan trọng:
- **Trước thanh toán**: Dữ liệu có thể từ session cart hoặc database
- **Sau thanh toán**: Dữ liệu luôn từ database, không còn phụ thuộc vào session
- **Session cart**: Chỉ dùng cho đơn hàng chưa lưu vào database
- **Database**: Lưu trữ chính thức sau khi tạo đơn hàng

### Các bảng liên quan:
- `ban_uong`: Thông tin các bàn
- `don_hang`: Thông tin đơn hàng (mã đơn, mã bàn, trạng thái thanh toán, tổng tiền)
- `chi_tiet_don_hang`: Chi tiết các món trong đơn hàng
- `thuc_don`: Thông tin món ăn (tên, giá, hình ảnh)
- `danh_muc`: Danh mục món ăn

### Lưu ý:
- Sau khi thanh toán, đơn hàng vẫn được lưu trong database để xem lại
- Trạng thái bàn được cập nhật phù hợp (trống/đang sử dụng)
- Dữ liệu không bị mất sau khi thanh toán, mà được lưu vào database để truy xuất sau này

## Tùy chỉnh

### Thêm tính năng mới
1. Tạo controller mới trong `MVC/Controllers/`
2. Tạo model trong `MVC/Models/`
3. Tạo view trong `MVC/Views/Pages/`
4. Cập nhật routing trong `.htaccess`

### Thay đổi giao diện
- Sửa file CSS trong `Public/Css/`
- Cập nhật template trong `MVC/Views/`

## Triển khai

### Trên môi trường production
1. Cấu hình máy chủ web (Apache/Nginx)
2. Cài đặt SSL certificate
3. Cấu hình môi trường (production)
4. Tối ưu hiệu suất

### Backup dữ liệu
- Sao lưu cơ sở dữ liệu định kỳ
- Sao lưu file hình ảnh
- Lưu trữ backup an toàn

## Troubleshooting

### Lỗi thường gặp
- **404 Error**: Kiểm tra cấu hình `.htaccess` và mod_rewrite
- **Database Connection**: Kiểm tra thông tin kết nối trong `connectDB.php`
- **Permission Error**: Kiểm tra quyền ghi cho thư mục upload

### Debug
- Bật chế độ debug trong file cấu hình
- Kiểm tra log lỗi trong hệ thống
- Sử dụng công cụ developer tools

## Đóng góp

1. Fork dự án
2. Tạo branch tính năng (`git checkout -b feature/AmazingFeature`)
3. Commit thay đổi (`git commit -m 'Add some AmazingFeature'`)
4. Push lên branch (`git push origin feature/AmazingFeature`)
5. Tạo Pull Request

## Tác giả

- **Developer**: [Tên bạn]
- **Email**: [Email bạn]
- **GitHub**: [Link GitHub]

## Giấy phép

Dự án này được cấp phép theo giấy phép MIT - xem file [LICENSE](LICENSE) để biết thêm chi tiết.

## Liên hệ

- Email: [email liên hệ]
- Website: [website nếu có]

---

**Version**: 1.0.0
**Ngày phát hành**: [Ngày tháng năm]
**Ngôn ngữ lập trình**: PHP, MySQL, HTML, CSS, JavaScript