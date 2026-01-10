<?php
    class Staff extends controller{
        private $bu; // ban_uong
        private $dh; // don_hang
        private $ctdh; // chi_tiet_don_hang

        function __construct()
        {
            // Kiểm tra xem người dùng đã đăng nhập và có vai trò nhân viên không
            if(!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'nhan_vien')){
                header('Location: ' . $this->url('Users/login'));
                exit;
            }

            $this->bu = $this->model("Banuong_m");
            $this->dh = $this->model("Donhang_m");
            $this->ctdh = $this->model("Chitietdonhang_m");
        }

        function Get_data(){
            // Đây là phương thức mặc định được gọi bởi hệ thống định tuyến
            $this->index();
        }

        function index(){
            $this->dashboard();
        }

        function dashboard(){
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
        function table(){
            $tables = $this->bu->Banuong_getAll();

            $this->view('StaffMaster', [
                'page' => 'Staff/Chon_ban_v',
                'tables' => $tables
            ]);
        }

        // Quản lý đơn hàng cho nhân viên
        function orders($page = 1){
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




         function order_detail($ma_don_hang) {
            // Get order information
            $order = $this->dh->Donhang_getByIdWithDiscount($ma_don_hang);

            // Initialize variables
            $order_details = [];

            // Check if order exists in database
            if ($order && mysqli_num_rows($order) > 0) {
                // Order exists in database - get the order row for processing
                $order_row = mysqli_fetch_array($order);

                // Check access rights - staff (admin/nhan_vien) should be able to access any order
                // Only restrict access if the current user is a customer and the order is not theirs
                if ($_SESSION['user_role'] === 'khach_hang') {
                    // For customer role, check if the order belongs to them
                    if ($order_row['ma_ban'] !== 'KHACH_HANG' && $order_row['ma_user'] !== $_SESSION['user_id']) {
                        echo '<p>Bạn không có quyền truy cập đơn hàng này.</p>';
                        return;
                    }
                }

                // Reset the result pointer so the view can fetch it again
                mysqli_data_seek($order, 0);

                // Get order details from database
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

            // Get discount vouchers
            $discount_vouchers = $this->dh->getDiscountVouchers();

            $this->view('StaffMaster', [
                'page' => 'Staff/order_detail_v',
                'order' => $order, // Pass the mysqli_result object as expected by the view
                'order_details' => $order_details,
                'discount_vouchers' => $discount_vouchers
            ]);
        }

        // Method to view order details for viewing only (without payment functionality)
        function view_order_detail($ma_don_hang) {
            // Get order information
            $order = $this->dh->Donhang_getByIdWithDiscount($ma_don_hang);

            // Initialize variables
            $order_data = [];
            $order_details = [];

            // Check if order exists in database
            if ($order && mysqli_num_rows($order) > 0) {
                // Order exists in database - format as array for consistency
                $order_row = mysqli_fetch_array($order);
                $order_data = [$order_row]; // Wrap in array to match the view's expected format

                // Get order details from database
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

            // Get discount vouchers
            $discount_vouchers = $this->dh->getDiscountVouchers();

            $this->view('StaffMaster', [
                'page' => 'Staff/order_detail_v',
                'order' => $order, // Pass the formatted order data
                'order_details' => $order_details,
                'discount_vouchers' => $discount_vouchers
            ]);
        }

        // Cập nhật giảm giá cho đơn hàng
        function update_order_discount($ma_don_hang) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Get order information to check access rights
                $order_data = $this->dh->Donhang_getById($ma_don_hang);

                if (!$order_data || mysqli_num_rows($order_data) == 0) {
                    echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy đơn hàng']);
                    exit;
                }

                $order_row = mysqli_fetch_array($order_data);

                // Check if the order belongs to a guest customer (ma_ban = 'KHACH_HANG')
                // If not a guest order, only allow admin/staff to update discount
                if ($order_row['ma_ban'] !== 'KHACH_HANG' && $_SESSION['user_role'] === 'khach_hang') {
                    echo json_encode(['status' => 'error', 'message' => 'Bạn không có quyền truy cập đơn hàng này']);
                    exit;
                }

                $data = json_decode(file_get_contents('php://input'), true);
                $ma_khuyen_mai = $data['ma_khuyen_mai'];

                // Get discount amount from database
                $sql = "SELECT tien_khuyen_mai FROM khuyen_mai WHERE ma_khuyen_mai = '$ma_khuyen_mai'";
                $result = mysqli_query($this->dh->con, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    $discount = mysqli_fetch_assoc($result);
                    $tien_khuyen_mai = $discount['tien_khuyen_mai'];

                    // Update order with discount
                    $result = $this->dh->updateOrderWithDiscount($ma_don_hang, $tien_khuyen_mai);

                    if ($result) {
                        // Get updated order data to include total amount
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
        function update_payment_status($ma_don_hang){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Get order information to check access rights
                $order_data = $this->dh->Donhang_getById($ma_don_hang);

                if (!$order_data || mysqli_num_rows($order_data) == 0) {
                    echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy đơn hàng']);
                    exit;
                }

                $order_row = mysqli_fetch_array($order_data);

                // Check if the order belongs to a guest customer (ma_ban = 'KHACH_HANG')
                // If not a guest order, only allow admin/staff to update payment status
                if ($order_row['ma_ban'] !== 'KHACH_HANG' && $_SESSION['user_role'] === 'khach_hang') {
                    echo json_encode(['status' => 'error', 'message' => 'Bạn không có quyền truy cập đơn hàng này']);
                    exit;
                }

                $result = $this->dh->update_order_status($ma_don_hang, 'da_thanh_toan');

                if ($result) {
                    // Get the order to find the table ID
                    $order = $this->dh->Donhang_getById($ma_don_hang);
                    if ($order && mysqli_num_rows($order) > 0) {
                        $order_row = mysqli_fetch_array($order);
                        $ma_ban = $order_row['ma_ban'];

                        // Get order details to reduce inventory
                        $order_details = $this->ctdh->Chitietdonhang_getByOrderId($ma_don_hang);

                        if ($order_details && !empty($order_details)) {
                            // Reduce inventory for each item in the order
                            foreach ($order_details as $detail) {
                                $ma_thuc_don = $detail['ma_thuc_don'];
                                $so_luong_dat = $detail['so_luong'];

                                // Get current quantity from database
                                $thucdon_model = $this->model("Thucdon_m");
                                $thucdon = $thucdon_model->Thucdon_getById($ma_thuc_don);

                                if ($thucdon && mysqli_num_rows($thucdon) > 0) {
                                    $thucdon_row = mysqli_fetch_array($thucdon);
                                    $current_quantity = $thucdon_row['so_luong'];

                                    // Calculate new quantity (reduce by ordered amount)
                                    $new_quantity = $current_quantity - $so_luong_dat;

                                    // Ensure quantity doesn't go below 0
                                    if ($new_quantity < 0) {
                                        $new_quantity = 0;
                                    }

                                    // Update the quantity in the database
                                    $update_sql = "UPDATE thuc_don SET so_luong = $new_quantity WHERE ma_thuc_don = '$ma_thuc_don'";
                                    mysqli_query($thucdon_model->con, $update_sql);
                                }
                            }
                        }

                        // Clear the cart for this table after payment
                        $this->clearCartForTable($ma_ban);

                        // Update table status to empty after payment
                        $this->updateTableStatus($ma_ban, 'trong');
                    }

                    echo json_encode(['status' => 'success', 'message' => 'Cập nhật trạng thái thanh toán thành công']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Cập nhật thất bại']);
                }
                exit;
            }
        }

        // Helper method to get cart from session for a specific table
        private function getCartForTable($ma_ban) {
            $session_key = 'cart_' . $ma_ban;
            return isset($_SESSION[$session_key]) ? $_SESSION[$session_key] : [];
        }

        // Helper method to set cart to session for a specific table
        private function setCartForTable($ma_ban, $cart) {
            $session_key = 'cart_' . $ma_ban;
            $_SESSION[$session_key] = $cart;
        }

        // Helper method to clear cart from session for a specific table
        private function clearCartForTable($ma_ban) {
            $session_key = 'cart_' . $ma_ban;
            unset($_SESSION[$session_key]);
        }

        // Helper method to update table status
        private function updateTableStatus($ma_ban, $status) {
            $sql = "UPDATE ban_uong SET trang_thai_ban = '$status' WHERE ma_ban = '$ma_ban'";
            return mysqli_query($this->bu->con, $sql);
        }

        // Generate invoice PDF
        public function generateInvoice($ma_don_hang) {
            // Start output buffering to prevent any output before PDF headers
            ob_start();

            // Include the PDF generation class
            require_once __DIR__ . '/../../Public/Classes/PDF/InvoicePDF.php';

            // Get order information
            $order = $this->dh->Donhang_getByIdWithDiscount($ma_don_hang);

            // Initialize variables
            $order_data = [];
            $order_details = [];

            // Check if order exists in database
            if ($order && mysqli_num_rows($order) > 0) {
                // Order exists in database - format as array for consistency
                $order_row = mysqli_fetch_array($order);
                $order_data = $order_row; // Pass the order row directly

                // Check if order is paid before allowing invoice generation
                if ($order_row['trang_thai_thanh_toan'] !== 'da_thanh_toan') {
                    // Clean the output buffer and show error
                    ob_clean();
                    echo '<p>Chỉ được in hóa đơn khi đơn hàng đã được thanh toán.</p>';
                    return;
                }

                // Get order details from database
                $order_details = $this->ctdh->Chitietdonhang_getByOrderId($ma_don_hang);

                // For paid orders, we should always have order details in the database
                if ($order_row['trang_thai_thanh_toan'] === 'da_thanh_toan') {
                    // For paid orders, always use database details and ignore session cart
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
                // Clean the output buffer and show error
                ob_clean();
                echo '<p>Không tìm thấy đơn hàng.</p>';
                return;
            }

            // Clean the output buffer before generating PDF
            ob_clean();

            // Create PDF
            $discount_amount = $order_data['tien_khuyen_mai'] ?? 0;
            $pdf = new InvoicePDF($order_data, $order_details, $order_data['tong_tien'] ?? 0, $discount_amount);
            $pdf->generateInvoice();

            // Output PDF
            $pdf->Output('HoaDon_' . $ma_don_hang . '.pdf', 'I'); // 'I' for inline display, 'D' for download
        }

    }
?>