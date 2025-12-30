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
        function orders(){
            $orders = $this->dh->getOrdersForStaff();

            $this->view('StaffMaster', [
                'page' => 'Staff/orders_v',
                'orders' => $orders
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

    }
?>