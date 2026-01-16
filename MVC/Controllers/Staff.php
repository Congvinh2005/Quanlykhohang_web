<?php
class Staff extends controller
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
        $this->dashboard();
    }
    function dashboard()
    {
        // Lấy thống kê cơ bản cho bảng điều khiển nhân viên
        $active_tables_result = $this->bu->getActiveTables();
        $active_tables = [
            'total_tables' => mysqli_num_rows($active_tables_result)
        ];

        // Lấy ID của nhân viên hiện tại từ session
        $current_staff_id = $_SESSION['user_id'];

        // Lấy số lượng đơn hàng và doanh thu trong ngày hôm nay cho nhân viên hiện tại
        $todays_orders = $this->dh->getTodaysOrdersByStaff($current_staff_id);
        $todays_revenue = $this->dh->getTodaysRevenueByStaff($current_staff_id);

        $this->view('StaffMaster', [
            'page' => 'Staff/dashboard_v',
            'active_tables' => $active_tables,
            'todays_orders' => $todays_orders,
            'todays_revenue' => $todays_revenue
        ]);
    }

    // Chọn bàn cho nhân viên
    function table()
    {
        $tables = $this->bu->Banuong_getAll();

        $this->view('StaffMaster', [
            'page' => 'Staff/Chon_ban_v',
            'tables' => $tables
        ]);
    }

    // Quản lý đơn hàng cho nhân viên
    function orders($page = 1)
    {
        $limit = 10; // Number of orders per page
        $offset = ($page - 1) * $limit;

        $user_id = $_SESSION['user_id'];
        $total_orders = $this->dh->getTotalEmployeeOrdersCount($user_id);
        $total_pages = ceil($total_orders / $limit);

        $orders = $this->dh->getOrdersForEmployeeWithPagination($user_id, $limit, $offset);

        $this->view('StaffMaster', [
            'page' => 'Staff/orders_v',
            'orders' => $orders,
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total_orders' => $total_orders
        ]);
    }




    function order_detail($ma_don_hang)
    {
        // Lấy thông tin đơn hàng
        $order = $this->dh->Donhang_getByIdWithDiscount($ma_don_hang);

        // Khởi tạo biến
        $order_details = [];

        // Kiểm tra nếu đơn hàng tồn tại trong cơ sở dữ liệu
        if ($order && mysqli_num_rows($order) > 0) {
            // Đơn hàng tồn tại trong cơ sở dữ liệu - lấy hàng đơn hàng để xử lý
            $order_row = mysqli_fetch_array($order);

            // Kiểm tra quyền truy cập - nhân viên (admin/nhan_vien) nên có thể truy cập bất kỳ đơn hàng nào
            // Chỉ hạn chế truy cập nếu người dùng hiện tại là khách hàng và đơn hàng không phải của họ
            if ($_SESSION['user_role'] === 'khach_hang') {
                // Với vai trò khách hàng, kiểm tra nếu đơn hàng thuộc về họ
                if ($order_row['ma_ban'] !== 'Online' && $order_row['ma_user'] !== $_SESSION['user_id']) {
                    echo '<p>Bạn không có quyền truy cập đơn hàng này.</p>';
                    return;
                }
            }

            // Đặt lại con trỏ kết quả để view có thể lấy lại
            mysqli_data_seek($order, 0);

            // Lấy chi tiết đơn hàng từ cơ sở dữ liệu
            $order_details = $this->ctdh->Chitietdonhang_getByOrderId($ma_don_hang);

            // Debug: Log the order status and details count
            error_log("Order $ma_don_hang status: " . $order_row['trang_thai_thanh_toan'] . ", details count: " . count($order_details));

            // Đối với các đơn hàng đã thanh toán, chúng ta luôn nên có chi tiết đơn hàng trong cơ sở dữ liệu
            // Việc quay trở lại giỏ hàng phiên chỉ nên dành cho các đơn hàng tạm thời chưa được lưu
            // Nếu đơn hàng đã thanh toán và không tìm thấy chi tiết trong cơ sở dữ liệu, điều đó cho thấy sự không nhất quán dữ liệu
            if ($order_row['trang_thai_thanh_toan'] === 'da_thanh_toan') {
                // Đối với các đơn hàng đã thanh toán, luôn sử dụng chi tiết từ cơ sở dữ liệu và bỏ qua giỏ hàng phiên
                // Điều này đảm bảo rằng sau khi thanh toán, chỉ những chi tiết đơn hàng đã lưu mới được hiển thị
                // Nếu không tìm thấy chi tiết trong cơ sở dữ liệu cho một đơn hàng đã thanh toán, đó là trạng thái lỗi
                if (empty($order_details)) {
                    // Ghi nhật ký điều này như là trạng thái lỗi - đơn hàng đã thanh toán nên có chi tiết trong cơ sở dữ liệu
                    error_log("ERROR: Paid order $ma_don_hang has no details in database");
                    // Vẫn hiển thị đơn hàng nhưng với chi tiết trống
                }
            } else if (empty($order_details)) {
                // Chỉ thử lấy từ giỏ hàng phiên cho các đơn hàng chưa thanh toán mà có thể chưa được lưu vào cơ sở dữ liệu
                $ma_ban = $order_row['ma_ban'];

                // Lấy giỏ hàng từ phiên cho bàn này
                $session_cart = $this->getCartForTable($ma_ban);

                // Chuyển đổi giỏ hàng phiên sang cùng định dạng với chi tiết đơn hàng cơ sở dữ liệu
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
            // Order doesn't exist in database
            echo '<p>Không tìm thấy đơn hàng.</p>';
            return;
        }

        // Lấy phiếu giảm giá
        $discount_vouchers = $this->dh->getDiscountVouchers();

        $this->view('StaffMaster', [
            'page' => 'Staff/order_detail_v',
            'order' => $order, // Pass the mysqli_result object as expected by the view
            'order_details' => $order_details,
            'discount_vouchers' => $discount_vouchers
        ]);
    }


    // Cập nhật giảm giá cho đơn hàng
    function update_order_discount($ma_don_hang)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy thông tin đơn hàng để kiểm tra quyền truy cập
            $order_data = $this->dh->Donhang_getById($ma_don_hang);

            if (!$order_data || mysqli_num_rows($order_data) == 0) {
                echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy đơn hàng']);
                exit;
            }

            $order_row = mysqli_fetch_array($order_data);

            // Kiểm tra nếu đơn hàng thuộc về khách hàng (ma_ban = 'KHACH_HANG')
            // Nếu không phải đơn hàng khách, chỉ cho phép quản trị viên/nhân viên cập nhật giảm giá
            if ($order_row['ma_ban'] !== 'Online' && $_SESSION['user_role'] === 'khach_hang') {
                echo json_encode(['status' => 'error', 'message' => 'Bạn không có quyền truy cập đơn hàng này']);
                exit;
            }

            $data = json_decode(file_get_contents('php://input'), true);
            $ma_khuyen_mai = $data['ma_khuyen_mai'];

            // Lấy số tiền giảm giá từ cơ sở dữ liệu
            $sql = "SELECT tien_khuyen_mai FROM khuyen_mai WHERE ma_khuyen_mai = '$ma_khuyen_mai'";
            $result = mysqli_query($this->dh->con, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                $discount = mysqli_fetch_assoc($result);
                $tien_khuyen_mai = $discount['tien_khuyen_mai'];

                // Cập nhật đơn hàng với giảm giá
                $result = $this->dh->updateOrderWithDiscount($ma_don_hang, $ma_khuyen_mai, $tien_khuyen_mai);

                if ($result) {
                    // Lấy dữ liệu đơn hàng đã cập nhật để bao gồm tổng số tiền
                    $order_sql = "SELECT tong_tien FROM don_hang WHERE ma_don_hang = '$ma_don_hang'";
                    $order_result = mysqli_query($this->dh->con, $order_sql);
                    $order_data = mysqli_fetch_assoc($order_result);
                    $tong_tien = $order_data['tong_tien'];

                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Cập nhật giảm giá thành công',
                        'tien_khuyen_mai' => $tien_khuyen_mai,
                        'tong_tien' => $tong_tien
                    ]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Cập nhật giảm giá thất bại']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy phiếu giảm giá']);
            }
            exit;
        }
    }
    // Cập nhật trạng thái thanh toán cho đơn hàng
    function update_payment_status($ma_don_hang)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy thông tin đơn hàng để kiểm tra quyền truy cập
            $order_data = $this->dh->Donhang_getById($ma_don_hang);

            if (!$order_data || mysqli_num_rows($order_data) == 0) {
                echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy đơn hàng']);
                exit;
            }

            $order_row = mysqli_fetch_array($order_data);

            // Kiểm tra nếu đơn hàng thuộc về khách hàng (ma_ban = 'Online')
            // Nếu không phải đơn hàng khách, chỉ cho phép quản trị viên/nhân viên cập nhật trạng thái thanh toán
            if ($order_row['ma_ban'] !== 'Online' && $_SESSION['user_role'] === 'khach_hang') {
                echo json_encode(['status' => 'error', 'message' => 'Bạn không có quyền truy cập đơn hàng này']);
                exit;
            }

            $result = $this->dh->update_order_status($ma_don_hang, 'da_thanh_toan');

            if ($result) {
                // Lấy đơn hàng để tìm ID bàn
                $order = $this->dh->Donhang_getById($ma_don_hang);
                if ($order && mysqli_num_rows($order) > 0) {
                    $order_row = mysqli_fetch_array($order);
                    $ma_ban = $order_row['ma_ban'];

                    // Lấy chi tiết đơn hàng để giảm hàng tồn kho
                    $order_details = $this->ctdh->Chitietdonhang_getByOrderId($ma_don_hang);

                    if ($order_details && !empty($order_details)) {
                        // Giảm hàng tồn kho cho mỗi mặt hàng trong đơn hàng
                        foreach ($order_details as $detail) {
                            $ma_thuc_don = $detail['ma_thuc_don'];
                            $so_luong_dat = $detail['so_luong'];

                            // Lấy số lượng hiện tại từ cơ sở dữ liệu
                            $thucdon_model = $this->model("Thucdon_m");
                            $thucdon = $thucdon_model->Thucdon_getById($ma_thuc_don);

                            if ($thucdon && mysqli_num_rows($thucdon) > 0) {
                                $thucdon_row = mysqli_fetch_array($thucdon);
                                $current_quantity = $thucdon_row['so_luong'];

                                // Tính toán số lượng mới (giảm theo số lượng đã đặt)
                                $new_quantity = $current_quantity - $so_luong_dat;

                                // Đảm bảo số lượng không nhỏ hơn 0
                                if ($new_quantity < 0) {
                                    $new_quantity = 0;
                                }

                                // Cập nhật số lượng trong cơ sở dữ liệu
                                $update_sql = "UPDATE thuc_don SET so_luong = $new_quantity WHERE ma_thuc_don = '$ma_thuc_don'";
                                mysqli_query($thucdon_model->con, $update_sql);
                            }
                        }
                    }

                    // Xóa giỏ hàng cho bàn này sau khi thanh toán
                    $this->clearCartForTable($ma_ban);

                    // Cập nhật trạng thái bàn thành trống sau khi thanh toán
                    $this->updateTableStatus($ma_ban, 'trong');
                }

                echo json_encode(['status' => 'success', 'message' => 'Cập nhật trạng thái thanh toán thành công']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Cập nhật thất bại']);
            }
            exit;
        }
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

    // Phương thức trợ giúp để cập nhật trạng thái bàn
    private function updateTableStatus($ma_ban, $status)
    {
        $sql = "UPDATE ban_uong SET trang_thai_ban = '$status' WHERE ma_ban = '$ma_ban'";
        return mysqli_query($this->bu->con, $sql);
    }

    // Phương thức để xem chi tiết đơn hàng để thanh toán (đặc biệt cho các đơn hàng đã tồn tại)
    function order_detail_payment($ma_don_hang)
    {
        // Lấy thông tin đơn hàng
        $order_result = $this->dh->Donhang_getByIdWithDiscount($ma_don_hang);

        // Check if order exists in database
        if ($order_result && mysqli_num_rows($order_result) > 0) {
            $order_row = mysqli_fetch_array($order_result);
            // Reset pointer to beginning so the view can fetch it again
            mysqli_data_seek($order_result, 0);

            // Lấy chi tiết đơn hàng từ cơ sở dữ liệu
            $order_details = $this->ctdh->Chitietdonhang_getByOrderId($ma_don_hang);

            // Debug: Log the order status and details count
            error_log("Order $ma_don_hang status: " . $order_row['trang_thai_thanh_toan'] . ", details count: " . count($order_details));

            // For paid orders, we should always have order details in the database
            // The fallback to session cart should only be for temporary orders that haven't been saved yet
            // If order is paid and no details found in database, it indicates a data inconsistency
            if ($order_row['trang_thai_thanh_toan'] === 'da_thanh_toan') {
                // For paid orders, always use database details and ignore session cart
                // This ensures that after payment, only the saved order details are shown
                // If no details found in database for a paid order, it's an error state
                if (empty($order_details)) {
                    // Log this as an error state - paid order should have details in DB
                    error_log("ERROR: Paid order $ma_don_hang has no details in database");
                    // Still show the order but with empty details
                }
            } else if (empty($order_details)) {
                // Only try to get from session cart for unpaid orders that might not have been saved to DB yet
                $ma_ban = $order_row['ma_ban'];

                // Get cart from session for this table
                $session_cart = $this->getCartForTable($ma_ban);

                // Convert session cart to the same format as database order details
                if (!empty($session_cart)) {
                    $order_details = [];
                    foreach ($session_cart as $item) {
                        // Get item details from database to get name and image
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
            // Order doesn't exist in database
            echo '<p>Không tìm thấy đơn hàng.</p>';
            return;
        }

        // Lấy phiếu giảm giá
        $discount_vouchers = $this->dh->getDiscountVouchers();

        // Load the payment view (Chi_tiet_don_hang_v) instead of the view-only page
        $this->view('StaffMaster', [
            'page' => 'StaffMaster/Chi_tiet_don_hang_v',
            'order' => $order_result, // Pass the mysqli_result as expected by the view
            'order_details' => $order_details,
            'discount_vouchers' => $discount_vouchers
        ]);
    }

    // Generate invoice PDF
    public function generateInvoice($ma_don_hang)
    {
        // Bắt đầu bộ đệm đầu ra để ngăn chặn bất kỳ đầu ra nào trước tiêu đề PDF
        ob_start();

        // Bao gồm lớp tạo PDF
        require_once __DIR__ . '/../../Public/Classes/PDF/InvoicePDF.php';

        // Lấy thông tin đơn hàng
        $order = $this->dh->Donhang_getByIdWithDiscount($ma_don_hang);

        // Khởi tạo biến
        $order_data = [];
        $order_details = [];

        // Kiểm tra nếu đơn hàng tồn tại trong cơ sở dữ liệu
        if ($order && mysqli_num_rows($order) > 0) {
            // Order exists in database - format as array for consistency
            $order_row = mysqli_fetch_array($order);
            $order_data = $order_row; // Pass the order row directly

            // Check if order is paid before allowing invoice generation
            if ($order_row['trang_thai_thanh_toan'] !== 'da_thanh_toan') {
                // Làm sạch bộ đệm đầu ra và hiển thị lỗi
                ob_clean();
                echo '<p>Chỉ được in hóa đơn khi đơn hàng đã được thanh toán.</p>';
                return;
            }

            // Lấy chi tiết đơn hàng từ cơ sở dữ liệu
            $order_details = $this->ctdh->Chitietdonhang_getByOrderId($ma_don_hang);

            // Đối với các đơn hàng đã thanh toán, chúng ta luôn nên có chi tiết đơn hàng trong cơ sở dữ liệu
            if ($order_row['trang_thai_thanh_toan'] === 'da_thanh_toan') {
                // Đối với các đơn hàng đã thanh toán, luôn sử dụng chi tiết từ cơ sở dữ liệu và bỏ qua giỏ hàng phiên
                if (empty($order_details)) {
                    // Ghi nhật ký điều này như là trạng thái lỗi - đơn hàng đã thanh toán nên có chi tiết trong cơ sở dữ liệu
                    error_log("ERROR: Paid order $ma_don_hang has no details in database");
                    // Vẫn hiển thị đơn hàng nhưng với chi tiết trống
                }
            } else if (empty($order_details)) {
                // Chỉ thử lấy từ giỏ hàng phiên cho các đơn hàng chưa thanh toán mà có thể chưa được lưu vào cơ sở dữ liệu
                $ma_ban = $order_row['ma_ban'];

                // Lấy giỏ hàng từ phiên cho bàn này
                $session_cart = $this->getCartForTable($ma_ban);

                // Chuyển đổi giỏ hàng phiên sang cùng định dạng với chi tiết đơn hàng cơ sở dữ liệu
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
            // Order doesn't exist in database
            // Clean the output buffer and show error
            ob_clean();
            echo '<p>Không tìm thấy đơn hàng.</p>';
            return;
        }

        // Làm sạch bộ đệm đầu ra trước khi tạo PDF
        ob_clean();

        // Tạo PDF
        $discount_amount = $order_data['tien_khuyen_mai'] ?? 0;
        $pdf = new InvoicePDF($order_data, $order_details, $order_data['tong_tien'] ?? 0, $discount_amount);
        $pdf->generateInvoice();

        // Xuất PDF
        $pdf->Output('HoaDon_' . $ma_don_hang . '.pdf', 'I'); // 'I' để hiển thị nội tuyến, 'D' để tải xuống
    }

    public function generateInvoice_admin($ma_don_hang)
    {
        // Bắt đầu bộ đệm đầu ra để ngăn chặn bất kỳ đầu ra nào trước tiêu đề PDF
        ob_start();

        // Bao gồm lớp tạo PDF
        require_once __DIR__ . '/../../Public/Classes/PDF/in_don_hang.php';

        // Lấy thông tin đơn hàng
        $order = $this->dh->Donhang_getByIdWithDiscount($ma_don_hang);

        // Khởi tạo biến
        $order_data = [];
        $order_details = [];

        // Kiểm tra nếu đơn hàng tồn tại trong cơ sở dữ liệu
        if ($order && mysqli_num_rows($order) > 0) {
            // Order exists in database - format as array for consistency
            $order_row = mysqli_fetch_array($order);
            $order_data = $order_row; // Pass the order row directly

            // Lấy chi tiết đơn hàng từ cơ sở dữ liệu
            $order_details = $this->ctdh->Chitietdonhang_getByOrderId($ma_don_hang);

            // If no details found in database, try to get from session cart
            if (empty($order_details)) {
                $ma_ban = $order_row['ma_ban'];

                // Lấy giỏ hàng từ phiên cho bàn này
                $session_cart = $this->getCartForTable($ma_ban);

                // Chuyển đổi giỏ hàng phiên sang cùng định dạng với chi tiết đơn hàng cơ sở dữ liệu
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
            // Order doesn't exist in database
            // Clean the output buffer and show error
            ob_clean();
            echo '<p>Không tìm thấy đơn hàng.</p>';
            return;
        }

        // Làm sạch bộ đệm đầu ra trước khi tạo PDF
        ob_clean();

        // Tạo PDF
        $discount_amount = $order_data['tien_khuyen_mai'] ?? 0;
        $pdf = new InvoicePDF($order_data, $order_details, $order_data['tong_tien'] ?? 0, $discount_amount);
        $pdf->generateInvoice();

        // Xuất PDF
        $pdf->Output('HoaDon_' . $ma_don_hang . '.pdf', 'I'); // 'I' để hiển thị nội tuyến, 'D' để tải xuống
    }

    // Hủy đơn hàng chưa thanh toán
    function cancel_order($ma_don_hang)
    {
        // Kiểm tra xem đơn hàng có tồn tại và chưa thanh toán không
        $order = $this->dh->Donhang_getById($ma_don_hang);

        if ($order && mysqli_num_rows($order) > 0) {
            $order_row = mysqli_fetch_array($order);

            // Chỉ cho phép hủy đơn chưa thanh toán
            if ($order_row['trang_thai_thanh_toan'] === 'chua_thanh_toan') {
                // Khôi phục số lượng tồn kho
                $thucdon_model = $this->model("Thucdon_m");
                $thucdon_model->restoreInventory($ma_don_hang);

                // Xóa đơn hàng
                $delete_result = $this->dh->Donhang_delete($ma_don_hang);

                if ($delete_result) {
                    // Xóa giỏ hàng liên quan nếu có
                    $ma_ban = $order_row['ma_ban'];
                    $this->clearCartForTable($ma_ban);

                    // Cập nhật trạng thái bàn nếu cần
                    if ($ma_ban !== 'Online') {
                        $this->updateTableStatus($ma_ban, 'trong');
                    }

                    echo "<script>alert('Hủy đơn hàng thành công!'); window.location.href='http://localhost/QLSP/Staff/orders';</script>";
                } else {
                    echo "<script>alert('Hủy đơn hàng thất bại!'); window.location.href='http://localhost/QLSP/Staff/orders';</script>";
                }
            } else {
                echo "<script>alert('Chỉ có thể hủy đơn hàng chưa thanh toán!'); window.location.href='http://localhost/QLSP/Staff/orders';</script>";
            }
        } else {
            echo "<script>alert('Đơn hàng không tồn tại!'); window.location.href='http://localhost/QLSP/Staff/orders';</script>";
        }
    }
}
