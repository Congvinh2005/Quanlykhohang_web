<?php
class Khachhang extends controller
{
    private $bu; // ban_uong
    private $dh; // don_hang
    private $ctdh; // chi_tiet_don_hang

    function __construct()
    {
        // Kiểm tra xem người dùng đã đăng nhập và có vai trò nhân viên không
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'khach_hang' && $_SESSION['user_role'] !== 'nhan_vien')) {
            header('Location: ' . $this->url('Users/login'));
            exit;
        }

        $this->bu = $this->model("Banuong_m");
        $this->dh = $this->model("Donhang_m");
        $this->ctdh = $this->model("Chitietdonhang_m");
    }

    function Get_data()
    {
        // Đây là phương thức mặc định được gọi bởi hệ thống định tuyến
        $this->index();
    }

    function index()
    {
        $this->direct_menu();
    }

    function dashboard()
    {
        // Lấy thống kê cơ bản cho bảng điều khiển nhân viên
        $active_tables_result = $this->bu->getActiveTables();
        $active_tables = [
            'total_tables' => mysqli_num_rows($active_tables_result)
        ];

        $todays_orders = $this->dh->getTodaysOrders();
        $todays_revenue = $this->dh->getTodaysRevenue();

        $this->view('KhachhangMaster', [
            'page' => 'Khachhang/dashboard_v',
            'active_tables' => $active_tables,
            'todays_orders' => $todays_orders,
            'todays_revenue' => $todays_revenue
        ]);
    }



    // Direct menu selection for customers without table selection
    function direct_menu()
    {
        $danhmuc = $this->model("Danhmuc_m");
        $categories = $danhmuc->Danhmuc_getAll();

        // Lấy chỉ các mục menu có sẵn (không hết hàng)
        $thucdon = $this->model("Thucdon_m");
        $menu_items = $thucdon->Thucdon_getAvailable();

        // Lấy giỏ hàng hiện tại từ phiên cho đơn hàng khách hàng
        $current_cart = $this->getCartForTable('KHACH_HANG');

        $this->view('KhachhangMaster', [
            'page' => 'Khachhang/Direct_menu_v',
            'categories' => $categories,
            'menu_items' => $menu_items,
            'current_cart' => $current_cart
        ]);
    }

    // // Create new order for payment (redirects to payment page)
    // function tao_don_hang_de_thanh_toan(){
    //     // Generate a temporary order to show on payment page
    //     $danhmuc = $this->model("Danhmuc_m");
    //     $categories = $danhmuc->Danhmuc_getAll();

    //     // Get only available menu items (not out of stock)
    //     $thucdon = $this->model("Thucdon_m");
    //     $menu_items = $thucdon->Thucdon_getAvailable();

    //     // Get current cart from session for customer orders
    //     $current_cart = $this->getCartForTable('KHACH_HANG');

    //     // Calculate total for the temporary order
    //     $tong_tien = 0;
    //     if (!empty($current_cart)) {
    //         foreach($current_cart as $item) {
    //             $tong_tien += ($item['price'] * $item['quantity']);
    //         }
    //     }

    //     // Create a temporary order object for the view
    //     $order = [
    //         'ma_don_hang' => 'TEMP_' . time(), // Temporary ID
    //         'ma_ban' => 'KHACH_HANG',
    //         'tong_tien' => $tong_tien,
    //         'trang_thai_thanh_toan' => 'chua_thanh_toan',
    //         'tien_khuyen_mai' => 0,
    //         'ngay_tao' => date('Y-m-d H:i:s')
    //     ];

    //     // Get discount vouchers
    //     $discount_vouchers = $this->dh->getDiscountVouchers();

    //     $this->view('KhachhangMaster', [
    //         'page' => 'Khachhang/Chi_tiet_don_hang_v',
    //         'order' => [$order], // Pass as array to match expected format
    //         'order_details' => $current_cart,
    //         'discount_vouchers' => $discount_vouchers
    //     ]);
    // }

    // Quản lý đơn hàng cho khách hàng
    function orders($page = 1)
    {
        $limit = 10; // Number of orders per page
        $offset = ($page - 1) * $limit;

        $total_orders = $this->dh->getTotalKhachhangOrdersCount();
        $total_pages = ceil($total_orders / $limit);

        $orders = $this->dh->getOrdersForKhachhangWithPagination($limit, $offset);

        $this->view('KhachhangMaster', [
            'page' => 'Khachhang/orders_v',
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

        // Initialize variables
        $order_details = [];

        // Check if order exists in database
        if ($order && mysqli_num_rows($order) > 0) {
            // Order exists in database - get the order row for processing
            $order_row = mysqli_fetch_array($order);

            // Reset the result pointer so the view can fetch it again
            mysqli_data_seek($order, 0);

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

        $this->view('KhachhangMaster', [
            'page' => 'Khachhang/order_detail_v',
            'order' => $order, // Pass the mysqli_result object as expected by the view
            'order_details' => $order_details,
            'discount_vouchers' => $discount_vouchers
        ]);
    }


    // Cập nhật giảm giá cho đơn hàng
    function update_order_discount($ma_don_hang)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $ma_khuyen_mai = $data['ma_khuyen_mai'];

            // Lấy số tiền giảm giá từ cơ sở dữ liệu
            $sql = "SELECT tien_khuyen_mai FROM khuyen_mai WHERE ma_khuyen_mai = '$ma_khuyen_mai'";
            $result = mysqli_query($this->dh->con, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                $discount = mysqli_fetch_assoc($result);
                $tien_khuyen_mai = $discount['tien_khuyen_mai'];

                // Cập nhật đơn hàng với giảm giá
                $result = $this->dh->updateOrderWithDiscount($ma_don_hang, $tien_khuyen_mai);

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

    // lấy mã bàn để cập nhật trạng thái thanh toán nữa
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
        $this->view('KhachhangMaster', [
            'page' => 'Khachhang/Chi_tiet_don_hang_v',
            'order' => $order_result, // Pass the mysqli_result as expected by the view
            'order_details' => $order_details,
            'discount_vouchers' => $discount_vouchers
        ]);
    }
}