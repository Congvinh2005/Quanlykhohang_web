<?php
    class Staff extends controller{
        private $bu; // ban_uong
        private $dh; // don_hang
        private $ctdh; // chi_tiet_don_hang

        function __construct()
        {
            // Kiểm tra xem người dùng đã đăng nhập và có vai trò nhân viên không
            if(!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'nhan_vien')){
                header('Location: http://localhost/QLSP/Users/login');
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
            $active_tables = $this->bu->getActiveTables();
            $todays_orders = $this->dh->getTodaysOrders();

            $this->view('StaffMaster', [
                'page' => 'Staff/dashboard_v',
                'active_tables' => $active_tables,
                'todays_orders' => $todays_orders
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

            $total_orders = $this->dh->getTotalOrdersCount();
            $total_pages = ceil($total_orders / $limit);

            $orders = $this->dh->getOrdersForStaffWithPagination($limit, $offset);

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
                'order' => $order_data, // Pass the formatted order data
                'order_details' => $order_details,
                'discount_vouchers' => $discount_vouchers
            ]);
        }

        // Cập nhật giảm giá cho đơn hàng
        function update_order_discount($ma_don_hang) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                        echo json_encode(['status' => 'success', 'message' => 'Cập nhật giảm giá thành công', 'tien_khuyen_mai' => $tien_khuyen_mai]);
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
                $result = $this->dh->update_order_status($ma_don_hang, 'da_thanh_toan');

                if ($result) {
                    // Get the order to find the table ID
                    $order = $this->dh->Donhang_getById($ma_don_hang);
                    if ($order && mysqli_num_rows($order) > 0) {
                        $order_row = mysqli_fetch_array($order);
                        $ma_ban = $order_row['ma_ban'];

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
            $pdf = new InvoicePDF($order_data, $order_details, $order_data['tong_tien'] ?? 0);
            $pdf->generateInvoice();

            // Output PDF
            $pdf->Output('HoaDon_' . $ma_don_hang . '.pdf', 'I'); // 'I' for inline display, 'D' for download
        }

    }
?>