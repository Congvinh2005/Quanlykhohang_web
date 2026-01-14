<?php
class Banuong extends controller
{
    private $bu;
    private $dh;
    private $ctdh;

    function __construct()
    {
        $this->bu = $this->model("Banuong_m");
        $this->dh = $this->model("Donhang_m");
        $this->ctdh = $this->model("Chitietdonhang_m");
    }
    function Get_data()
    {
        $this->danhsach();
    }

    function danhsach()
    {
        $result = $this->bu->Banuong_find('', '', '');

        $this->view('Master', [
            'page' => 'Danhsachbanuong_v',
            'ma_ban' => '',
            'ten_ban' => '',
            'so_cho_ngoi' => '',
            'dulieu' => $result
        ]);
    }


    function themmoi()
    {
        $this->view('Master', [
            'page' => 'Banuong_v',
            'ma_ban' => '',
            'ten_ban' => '',
            'so_cho_ngoi' => '',
            'trang_thai_ban' => ''
        ]);
    }

    function ins()
    {
        if (isset($_POST['btnLuu'])) {
            $ma_ban = $_POST['txtMaban'];
            $ten_ban = $_POST['txtTenban'];
            $so_cho_ngoi = $_POST['txtSochongoi'];
            $trang_thai_ban = $_POST['txtTrangthai'] ?? 'trong';

            // Kiểm tra dữ liệu rỗng
            if ($ma_ban == '') {
                echo "<script>alert('Mã bàn không được rỗng!')</script>";
                $this->themmoi();
            } else {
                // Kiểm tra trùng mã bàn
                $kq1 = $this->bu->checktrungMaBan($ma_ban);
                if ($kq1) {
                    echo "<script>alert('Mã bàn đã tồn tại! Vui lòng nhập mã khác.')</script>";
                    $this->themmoi();
                } else {
                    $kq = $this->bu->banuong_ins($ma_ban, $ten_ban, $so_cho_ngoi, $trang_thai_ban);
                    if ($kq) {
                        echo "<script>alert('Thêm mới thành công!');</script>";
                        $this->danhsach(); // Quay về danh sách sau khi thêm thành công
                    } else {
                        echo "<script>alert('Thêm mới thất bại!');</script>";
                        $this->themmoi();
                    }
                }
            }
        }
    }

    function Timkiem()
    {
        // Lấy các tham số tìm kiếm từ biểu mẫu
        $ma_ban = $_POST['txtMaban'] ?? '';
        $ten_ban = $_POST['txtTenban'] ?? '';
        $so_cho_ngoi = $_POST['txtSochongoi'] ?? '';

        $result = $this->bu->Banuong_find($ma_ban, $ten_ban, $so_cho_ngoi);
        // ====== XUẤT EXCEL ======
        if (isset($_POST['btnXuatexcel'])) {

            $objExcel = new PHPExcel();
            $objExcel->setActiveSheetIndex(0);
            $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachBanUong');

            $sheet->setCellValue('A1', 'Mã Bàn');
            $sheet->setCellValue('B1', 'Tên Bàn');
            $sheet->setCellValue('C1', 'Số Chỗ Ngồi');
            $sheet->setCellValue('D1', 'Trạng Thái Bàn');
            $sheet->setCellValue('E1', 'Ngày Tạo');


            $rowCount = 2; // Bắt đầu từ hàng 2 vì hàng 1 là tiêu đề
            mysqli_data_seek($result, 0); // Đặt lại con trỏ kết quả về đầu
            while ($row = mysqli_fetch_assoc($result)) {
                // Ánh xạ trường theo bảng cơ sở dữ liệu
                $sheet->setCellValue('A' . $rowCount, $row['ma_ban']);
                $sheet->setCellValue('B' . $rowCount, $row['ten_ban']);
                $sheet->setCellValue('C' . $rowCount, $row['so_cho_ngoi']);
                $sheet->setCellValue('D' . $rowCount, $row['trang_thai_ban']);
                $sheet->setCellValue('E' . $rowCount, $row['ngay_tao']);
                $rowCount++;
            }

            foreach (range('A', 'E') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            if (ob_get_length()) ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="DanhSachBanUong.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }

        $this->view('Master', [
            'page' => 'Danhsachbanuong_v',
            'ma_ban' => $ma_ban, // Nhất quán với tên biến trong view
            'ten_ban' => $ten_ban, // Nhất quán với tên biến trong view
            'so_cho_ngoi' => $so_cho_ngoi, // Nhất quán với tên biến trong view
            'dulieu' => $result
        ]);
    }



    function sua($ma_ban)
    {
        $result = $this->bu->Banuong_find($ma_ban, '', '');
        $row = mysqli_fetch_array($result);

        $this->view('Master', [
            'page' => 'Banuong_sua',
            'ma_ban' => $row['ma_ban'],
            'ten_ban' => $row['ten_ban'],
            'so_cho_ngoi' => $row['so_cho_ngoi'],
            'trang_thai_ban' => $row['trang_thai_ban']
        ]);
    }

    function update()
    {
        if (isset($_POST['btnCapnhat'])) {
            $ma_ban = $_POST['txtMaban'];
            $ten_ban = $_POST['txtTenban'];
            $so_cho_ngoi = $_POST['txtSochongoi'];
            $trang_thai_ban = $_POST['txtTrangthai'] ?? 'trong';

            $kq = $this->bu->Banuong_update($ma_ban, $ten_ban, $so_cho_ngoi, $trang_thai_ban);
            if ($kq)
                echo "<script>alert('Cập nhật thành công!'); window.location='" . $this->url('Banuong/danhsach') . "';</script>";
            else
                echo "<script>alert('Cập nhật thất bại!');</script>";

            // Nếu cập nhật thất bại, gọi lại view sửa để người dùng thử lại
            if (!$kq) {
                $this->sua($ma_ban);
            }
        }
    }

    function xoa($ma_ban)
    {
        $kq = $this->bu->Banuong_delete($ma_ban);
        if ($kq)
            echo "<script>alert('Xóa thành công!'); window.location='" . $this->url('Banuong/danhsach') . "';</script>"; // Chuyển về trang danh sách
        else
            echo "<script>alert('Xóa thất bại!'); window.location='" . $this->url('Banuong/danhsach') . "';</script>"; // Quay lại trang danh sách
    }



    // Hiển thị form nhập Excel
    function import_form()
    {
        $this->view('Master', [
            'page' => 'Banuong_up_v'
        ]);
    }

    function up_l()
    {
        if (!isset($_FILES['txtfile']) || $_FILES['txtfile']['error'] != 0) {
            echo "<script>alert('Upload file lỗi')</script>";
            return;
        }

        $file = $_FILES['txtfile']['tmp_name'];

        $objReader = PHPExcel_IOFactory::createReaderForFile($file);
        $objExcel  = $objReader->load($file);

        $sheet     = $objExcel->getSheet(0);
        $sheetData = $sheet->toArray(null, true, true, true);

        for ($i = 2; $i <= count($sheetData); $i++) {

            $ma_ban       = trim($sheetData[$i]['A']);
            $ten_ban      = trim($sheetData[$i]['B']);
            $so_cho_ngoi  = trim($sheetData[$i]['C']);
            $trang_thai_ban = trim($sheetData[$i]['D']);

            if ($ma_ban == '') continue;

            // ✅ CHECK TRÙNG MÃ BÀN
            if ($this->bu->checktrungMaBan($ma_ban)) {
                echo "<script>
                alert('Mã bàn $ma_ban đã tồn tại! Vui lòng kiểm tra lại file.');
                window.location.href='" . $this->url('Banuong/import_form') . "';
            </script>";
                return;
            }

            // Insert
            if (!$this->bu->Banuong_ins($ma_ban, $ten_ban, $so_cho_ngoi, $trang_thai_ban)) {
                die(mysqli_error($this->bu->con));
            }
        }

        echo "<script>alert('Upload bàn uống thành công!')</script>";
        $this->view('Master', ['page' => 'Banuong_up_v']);
    }




    function order($ma_ban)
    {
        // Lấy thông tin bàn
        $table = $this->bu->Banuong_getById($ma_ban);
        $table_info = mysqli_fetch_array($table);

        // Check if table is currently in use
        if ($table_info['trang_thai_ban'] == 'dang_su_dung') {
            // Find the unpaid order for this table and redirect to order detail view
            $sql = "SELECT * FROM don_hang WHERE ma_ban = '$ma_ban' AND trang_thai_thanh_toan = 'chua_thanh_toan' LIMIT 1";
            $result = mysqli_query($this->dh->con, $sql);
            $unpaid_order = $result ? mysqli_fetch_assoc($result) : null;

            if ($unpaid_order) {
                // Redirect to order detail view for the existing order
                header('Location: ' . $this->url('Banuong/order_detail/' . $unpaid_order['ma_don_hang']));
                exit;
            }
        }

        $danhmuc = $this->model("Danhmuc_m");
        $categories = $danhmuc->Danhmuc_getAll();

        // Lấy chỉ các mục menu có sẵn (không hết hàng)
        $thucdon = $this->model("Thucdon_m");
        $menu_items = $thucdon->Thucdon_getAvailable();

        // Lấy giỏ hàng hiện tại từ phiên cho bàn này nếu tồn tại
        $current_cart = $this->getCartForTable($ma_ban);

        $this->view('StaffMaster', [
            'page' => 'Staff/Table_order_v',
            'table_info' => $table_info,
            'categories' => $categories,
            'menu_items' => $menu_items,
            'current_cart' => $current_cart
        ]);
    }
    function orderkhachhang($ma_ban)
    {
        // Lấy thông tin bàn
        $table = $this->bu->Banuong_getById($ma_ban);
        $table_info = mysqli_fetch_array($table);

        // Check if table is currently in use
        if ($table_info['trang_thai_ban'] == 'dang_su_dung') {
            // Find the unpaid order for this table and redirect to order detail view
            $sql = "SELECT * FROM don_hang WHERE ma_ban = '$ma_ban' AND trang_thai_thanh_toan = 'chua_thanh_toan' LIMIT 1";
            $result = mysqli_query($this->dh->con, $sql);
            $unpaid_order = $result ? mysqli_fetch_assoc($result) : null;

            if ($unpaid_order) {
                // Redirect to order detail view for the existing order
                header('Location: ' . $this->url('Banuong/order_detail/' . $unpaid_order['ma_don_hang']));
                exit;
            }
        }

        $danhmuc = $this->model("Danhmuc_m");
        $categories = $danhmuc->Danhmuc_getAll();

        // Lấy chỉ các mục menu có sẵn (không hết hàng)
        $thucdon = $this->model("Thucdon_m");
        $menu_items = $thucdon->Thucdon_getAvailable();

        // Lấy giỏ hàng hiện tại từ phiên cho bàn này nếu tồn tại
        $current_cart = $this->getCartForTable($ma_ban);

        $this->view('KhachhangMaster', [
            'page' => 'Khachhang/Table_order_v',
            'table_info' => $table_info,
            'categories' => $categories,
            'menu_items' => $menu_items,
            'current_cart' => $current_cart
        ]);
    }


    // Phương thức trợ giúp để lấy giỏ hàng từ phiên cho một bàn cụ thể
    private function getCartForTable($ma_ban)
    {
        $session_key = 'cart_' . $ma_ban;
        return isset($_SESSION[$session_key]) ? $_SESSION[$session_key] : [];
    }

    // Phương thức trợ giúp để lưu giỏ hàng vào phiên cho một bàn cụ thể
    private function setCartForTable($ma_ban, $cart)
    {
        $session_key = 'cart_' . $ma_ban;
        $_SESSION[$session_key] = $cart;
    }

    // Phương thức trợ giúp để xóa giỏ hàng khỏi phiên cho một bàn cụ thể
    private function clearCartForTable($ma_ban)
    {
        $session_key = 'cart_' . $ma_ban;
        unset($_SESSION[$session_key]);
    }

    // Phương thức để cập nhật giỏ hàng qua AJAX
    function update_cart()
    {
        // Only allow POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            echo json_encode(['success' => false, 'message' => 'Phương thức không được phép']);
            exit;
        }

        // Lấy dữ liệu JSON từ yêu cầu
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!$data || !isset($data['ma_ban']) || !isset($data['cart'])) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            exit;
        }

        $ma_ban = $data['ma_ban'];
        $cart = $data['cart'];

        // For customer orders, use a special session key
        if ($ma_ban === 'KHACH_HANG') {
            $this->setCartForTable('KHACH_HANG', $cart);
        } else {
            // Lưu giỏ hàng vào phiên cho đơn hàng bàn thường
            $this->setCartForTable($ma_ban, $cart);
        }

        echo json_encode(['success' => true, 'message' => 'Cập nhật giỏ hàng thành công!']);
        exit;
    }

    // Phương thức để tạo đơn hàng qua AJAX
    function create_order()
    {
        // Verify user is authenticated before creating order
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'khach_hang' && $_SESSION['user_role'] !== 'nhan_vien')) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(['success' => false, 'message' => 'Bạn không có quyền tạo đơn hàng']);
            exit;
        }

        // Only allow POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            echo json_encode(['success' => false, 'message' => 'Phương thức không được phép']);
            exit;
        }

        // Lấy dữ liệu JSON từ yêu cầu
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!$data || !isset($data['ma_ban']) || !isset($data['cart'])) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            exit;
        }

        $ma_ban = $data['ma_ban'];
        $cart = $data['cart'];

        // Check if this is a customer direct order (no table needed)
        $is_customer_order = ($ma_ban === 'KHACH_HANG');

        if (!$is_customer_order) {
            // Validate table exists for staff orders
            $table = $this->bu->Banuong_getById($ma_ban);
            if (!$table || mysqli_num_rows($table) == 0) {
                echo json_encode(['success' => false, 'message' => 'Bàn không tồn tại']);
                exit;
            }
        } else {
            // For customer orders, assign a default table or generate one if needed
            // We'll use a special table ID for customer orders
            $ma_ban = $this->getOrCreateCustomerTable();
        }

        // Generate unique order ID (always create a new one)
        $ma_don_hang = $this->generateUniqueOrderId();

        $tong_tien = 0;

        // Calculate total amount
        foreach ($cart as $item) {
            $tong_tien += ($item['price'] * $item['quantity']);
        }

        // Check if the order is being placed by a customer (no specific table) or by staff
        if ($is_customer_order) {
            // For customer orders, use the current logged-in user's ID
            // The Khachhang controller ensures only authenticated users can place orders
            $ma_user = $_SESSION['user_id'] ?? 'U01';
        } else {
            // For staff orders, use the logged-in user ID
            $ma_user = $_SESSION['user_id'] ?? 'U01'; // Default user if not set
        }

        // Get order notes from the request
        $ghi_chu = $data['ghi_chu'] ?? null;

        // Create new order using the Donhang model's insert method
        $result = $this->dh->donhang_ins($ma_don_hang, $ma_ban, $ma_user, $tong_tien, 'chua_thanh_toan', date('Y-m-d H:i:s'), $ghi_chu);
        if (!$result) {
            echo json_encode(['success' => false, 'message' => 'Không thể tạo đơn hàng']);
            exit;
        }

        // Add order items to chi_tiet_don_hang table
        foreach ($cart as $item) {
            $ma_thuc_don = $item['id'];
            $so_luong = $item['quantity'];
            $gia_tai_thoi_diem_dat = $item['price'];
            $ghi_chu = ''; // You can add notes functionality later

            // Add new order item
            $this->addOrderDetail($ma_don_hang, $ma_thuc_don, $so_luong, $gia_tai_thoi_diem_dat, $ghi_chu);
        }

        // Cập nhật trạng thái bàn thành đã chiếm chỗ chỉ cho đơn hàng bàn thường
        if (!$is_customer_order) {
            $this->updateTableStatus($ma_ban, 'dang_su_dung');
        }

        echo json_encode(['success' => true, 'message' => 'Tạo đơn hàng thành công!', 'order_id' => $ma_don_hang]);
        exit;
    }

    // Phương thức trợ giúp để lấy đơn hàng chưa thanh toán theo bàn
    private function getUnpaidOrderByTable($ma_ban)
    {
        $sql = "SELECT * FROM don_hang WHERE ma_ban = '$ma_ban' AND trang_thai_thanh_toan = 'chua_thanh_toan' LIMIT 1";
        $result = mysqli_query($this->dh->con, $sql);
        return $result ? mysqli_fetch_assoc($result) : null;
    }

    // Phương thức trợ giúp để cập nhật tổng tiền đơn hàng
    private function updateOrder($ma_don_hang, $tong_tien)
    {
        $sql = "UPDATE don_hang SET tong_tien = '$tong_tien', ngay_tao = NOW() WHERE ma_don_hang = '$ma_don_hang'";
        return mysqli_query($this->dh->con, $sql);
    }

    // Phương thức trợ giúp để kiểm tra chi tiết đơn hàng đã tồn tại
    private function checkExistingOrderDetail($ma_don_hang, $ma_thuc_don)
    {
        $sql = "SELECT * FROM chi_tiet_don_hang WHERE ma_don_hang = '$ma_don_hang' AND ma_thuc_don = '$ma_thuc_don' LIMIT 1";
        $result = mysqli_query($this->ctdh->con, $sql);
        return $result ? mysqli_fetch_assoc($result) : null;
    }

    // Phương thức trợ giúp để cập nhật số lượng chi tiết đơn hàng
    private function updateOrderDetailQuantity($ma_ctdh, $so_luong, $gia)
    {
        $sql = "UPDATE chi_tiet_don_hang SET so_luong = '$so_luong', gia_tai_thoi_diem_dat = '$gia' WHERE ma_ctdh = '$ma_ctdh'";
        return mysqli_query($this->ctdh->con, $sql);
    }

    // Phương thức trợ giúp để thêm chi tiết đơn hàng
    private function addOrderDetail($ma_don_hang, $ma_thuc_don, $so_luong, $gia, $ghi_chu)
    {
        $result = $this->ctdh->Chitietdonhang_ins($ma_don_hang, $ma_thuc_don, $so_luong, $gia, $ghi_chu);

        // Log để kiểm tra lỗi nếu có
        if (!$result) {
            error_log("Lỗi khi thêm chi tiết đơn hàng: " . mysqli_error($this->ctdh->con));
        } else {
            error_log("Thêm chi tiết đơn hàng thành công: $ma_don_hang, $ma_thuc_don, $so_luong");
        }

        return $result;
    }

    // Phương thức trợ giúp để lấy hoặc tạo bàn mặc định cho đơn hàng khách hàng
    private function getOrCreateCustomerTable()
    {
        // Check if a default customer table exists
        $sql = "SELECT ma_ban FROM ban_uong WHERE ma_ban = 'KHACH_HANG' LIMIT 1";
        $result = mysqli_query($this->bu->con, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            // Default customer table already exists
            $row = mysqli_fetch_assoc($result);
            return $row['ma_ban'];
        } else {
            // Create a default customer table
            $sql = "INSERT INTO ban_uong (ma_ban, ten_ban, so_cho_ngoi, trang_thai_ban, ngay_tao)
                        VALUES ('KHACH_HANG', 'Khách hàng', 1, 'trong', NOW())";
            $result = mysqli_query($this->bu->con, $sql);

            if ($result) {
                return 'KHACH_HANG';
            } else {
                // If creation fails, use a temporary ID
                error_log("Failed to create default customer table: " . mysqli_error($this->bu->con));
                return 'KHACH_HANG';
            }
        }
    }

    // Phương thức trợ giúp để cập nhật trạng thái bàn
    private function updateTableStatus($ma_ban, $status)
    {
        $sql = "UPDATE ban_uong SET trang_thai_ban = '$status' WHERE ma_ban = '$ma_ban'";
        return mysqli_query($this->bu->con, $sql);
    }

    // Phương thức trợ giúp để tạo mã đơn hàng duy nhất
    private function generateUniqueOrderId()
    {
        // Lấy ID đơn hàng cao nhất hiện có để tạo ID tiếp theo
        $sql = "SELECT ma_don_hang FROM don_hang ORDER BY CAST(SUBSTRING(ma_don_hang, 3) AS UNSIGNED) DESC LIMIT 1";
        $result = mysqli_query($this->dh->con, $sql);

        $new_id = 'DH1'; // Default starting ID

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $last_id = $row['ma_don_hang'];

            // Extract the numeric part from the last ID
            preg_match('/^DH(\d+)$/', $last_id, $matches);

            if (isset($matches[1])) {
                $number = intval($matches[1]);
                $number++; // Increment the number
                $new_id = 'DH' . $number; // Format the ID correctly
            } else {
                // If the last ID doesn't match the expected format, start from DH1
                $new_id = 'DH1';
            }
        }

        return $new_id;
    }

    // Phương thức để xem chi tiết đơn hàng cho thanh toán (có chức năng thanh toán)
    function order_detail($ma_don_hang)
    {
        // Lấy thông tin đơn hàng
        $order_result = $this->dh->Donhang_getByIdWithDiscount($ma_don_hang);

        // Kiểm tra nếu đơn hàng tồn tại trong cơ sở dữ liệu
        if ($order_result && mysqli_num_rows($order_result) > 0) {
            $order_row = mysqli_fetch_array($order_result);
            // Đặt lại con trỏ về đầu để view có thể lấy lại dữ liệu
            mysqli_data_seek($order_result, 0);

            // Lấy chi tiết đơn hàng từ cơ sở dữ liệu
            $order_details = $this->ctdh->Chitietdonhang_getByOrderId($ma_don_hang);

            // Gỡ lỗi: Ghi log trạng thái đơn hàng và số lượng chi tiết
            error_log("Order $ma_don_hang status: " . $order_row['trang_thai_thanh_toan'] . ", details count: " . count($order_details));

            // Đối với đơn hàng đã thanh toán, chúng ta luôn phải có chi tiết đơn hàng trong cơ sở dữ liệu
            // Việc sử dụng giỏ hàng từ session chỉ nên áp dụng cho các đơn hàng tạm thời chưa được lưu
            // Nếu đơn hàng đã thanh toán nhưng không tìm thấy chi tiết trong cơ sở dữ liệu, điều đó cho thấy sự không nhất quán dữ liệu
            if ($order_row['trang_thai_thanh_toan'] === 'da_thanh_toan') {
                // Đối với đơn hàng đã thanh toán, luôn sử dụng chi tiết từ cơ sở dữ liệu và bỏ qua giỏ hàng từ session
                // Điều này đảm bảo rằng sau khi thanh toán, chỉ những chi tiết đơn hàng đã lưu mới được hiển thị
                // Nếu không tìm thấy chi tiết trong cơ sở dữ liệu cho đơn hàng đã thanh toán, đó là trạng thái lỗi
                if (empty($order_details)) {
                    // Ghi log trạng thái lỗi - đơn hàng đã thanh toán nên phải có chi tiết trong DB
                    error_log("ERROR: Paid order $ma_don_hang has no details in database");
                    // Vẫn hiển thị đơn hàng nhưng với chi tiết trống
                }
            } else if (empty($order_details)) {
                // Chỉ thử lấy từ giỏ hàng trong session cho các đơn hàng chưa thanh toán có thể chưa được lưu vào DB
                $ma_ban = $order_row['ma_ban'];

                // Lấy giỏ hàng từ session cho bàn này
                $session_cart = $this->getCartForTable($ma_ban);

                // Chuyển đổi giỏ hàng từ session sang định dạng giống với chi tiết đơn hàng trong cơ sở dữ liệu
                if (!empty($session_cart)) {
                    $order_details = [];
                    foreach ($session_cart as $item) {
                        // Lấy chi tiết mặt hàng từ cơ sở dữ liệu để lấy tên và hình ảnh
                        $thucdon = $this->model("Thucdon_m");
                        $item_details = $thucdon->Thucdon_getById($item['id']);

                        if ($item_details && mysqli_num_rows($item_details) > 0) {
                            $item_db = mysqli_fetch_array($item_details);
                            $order_details[] = [
                                'ma_thuc_don' => $item['id'],
                                'so_luong' => $item['quantity'],
                                'gia_tai_thoi_diem_dat' => $item['price'],
                                'ten_mon' => $item_db['ten_mon'],
                                'img_thuc_don' => $item_db['img_thuc_don']
                            ];
                        }
                    }
                }
            }
        } else {
            // Đơn hàng không tồn tại trong cơ sở dữ liệu
            echo '<p>Không tìm thấy đơn hàng.</p>';
            return;
        }

        // Lấy phiếu giảm giá
        $discount_vouchers = $this->dh->getDiscountVouchers();

        // Kiểm tra nếu đơn hàng thuộc về khách hàng (ma_ban = 'KHACH_HANG')
        // Nếu đúng, sử dụng giao diện khách hàng thay vì giao diện nhân viên
        $order_row = mysqli_fetch_array($order_result);
        mysqli_data_seek($order_result, 0); // Đặt lại con trỏ một lần nữa cho view

        if ($order_row['ma_ban'] === 'KHACH_HANG') {
            $this->view('KhachhangMaster', [
                'page' => 'Khachhang/Chi_tiet_don_hang_v',
                'order' => $order_result, // Truyền mysqli_result như view mong đợi
                'order_details' => $order_details,
                'discount_vouchers' => $discount_vouchers
            ]);
        } else {
            $this->view('StaffMaster', [
                'page' => 'Staff/Chi_tiet_don_hang_v',
                'order' => $order_result, // Truyền mysqli_result như view mong đợi
                'order_details' => $order_details,
                'discount_vouchers' => $discount_vouchers
            ]);
        }
    }
}