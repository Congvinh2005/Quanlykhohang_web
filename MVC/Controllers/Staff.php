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


         // Chi tiết đơn hàng cho nhân viên
        function order_detail($ma_don_hang){
            $order = $this->dh->Donhang_getById($ma_don_hang);
            $order_details = $this->ctdh->Chitietdonhang_getByOrderId($ma_don_hang);

            $this->view('StaffMaster', [
                'page' => 'Staff/order_detail_v',
                'order' => $order,
                'order_details' => $order_details
            ]);
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

        // Helper method to clear cart for a table
        private function clearCartForTable($ma_ban) {
            $session_key = 'cart_' . $ma_ban;
            unset($_SESSION[$session_key]);
        }

        // Helper method to update table status
        private function updateTableStatus($ma_ban, $status) {
            $sql = "UPDATE ban_uong SET trang_thai_ban = '$status' WHERE ma_ban = '$ma_ban'";
            return mysqli_query($this->bu->con, $sql);
        }

    }
?>