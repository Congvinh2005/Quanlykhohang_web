<?php
    class Login extends controller{
        private $user;

        function __construct()
        {
            $this->user = $this->model("Users_m");
        }

        function Get_data(){
            // Đây là phương thức mặc định được gọi nếu không có hành động cụ thể nào được cung cấp
            $this->index();
        }

        function index(){
            // Kiểm tra xem người dùng đã đăng nhập chưa
            if(isset($_SESSION['user_id'])){
                // Nếu đã đăng nhập, chuyển hướng theo vai trò
                if($_SESSION['user_role'] === 'admin'){
                    header('Location: http://localhost/QLSP/Home');
                } else {
                    header('Location: http://localhost/QLSP/Staff');
                }
                exit;
            } else {
                // Hiển thị trang đăng nhập độc lập (không có layout Master)
                include_once __DIR__.'/../Views/Pages/Login_v.php';
            }
        }

        function process(){
            if(isset($_POST['username']) && isset($_POST['password'])){
                $username = $_POST['username'];
                $password = $_POST['password'];

                // Xác thực người dùng (thiết lập biến phiên nếu thành công)
                $user = $this->user->authenticateUser($username, $password);

                if($user) {
                    // Chuyển hướng theo vai trò
                    if($user['phan_quyen'] == 'admin'){
                        header('Location: http://localhost/QLSP/Home');
                    } else {
                        header('Location: http://localhost/QLSP/Staff');
                    }
                    exit;
                } else {
                    // Thông tin đăng nhập không hợp lệ - chuyển hướng đến đăng nhập với lỗi
                    header('Location: http://localhost/QLSP/Login?error=' . urlencode('Tên đăng nhập hoặc mật khẩu không đúng!'));
                    exit;
                }
            } else {
                // Nếu không có dữ liệu POST, chuyển hướng đến trang đăng nhập
                header('Location: http://localhost/QLSP/Login');
                exit;
            }
        }
    }
?>