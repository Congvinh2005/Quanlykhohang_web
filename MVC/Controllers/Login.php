<?php
    class Login extends controller{
        private $user;

        function __construct()
        {
            $this->user = $this->model("Users_m");
        }

        function Get_data(){
            $this->index();
        }

        function index(){
            // Kiểm tra xem người dùng đã đăng nhập chưa
            if(isset($_SESSION['user_id'])){
                // Nếu đã đăng nhập, chuyển hướng theo vai trò
                if($_SESSION['user_role'] === 'admin'){
                    header('Location: ' . $this->url('Home'));
                } elseif($_SESSION['user_role'] === 'nhan_vien'){
                    header('Location: ' . $this->url('Staff'));
                } else {
                    // For customer role or any other role
                    header('Location: ' . $this->url('Khachhang'));
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
                    // Thiết lập thông tin người dùng vào session
                    $_SESSION['user_id'] = $user['ma_user'];
                    $_SESSION['user_name'] = $user['ten_user'];
                    $_SESSION['user_role'] = $user['phan_quyen'];

                    // Chuyển hướng theo vai trò
                    if($user['phan_quyen'] == 'admin'){
                        header('Location: ' . $this->url('Home'));
                    } elseif($user['phan_quyen'] == 'nhan_vien'){
                        header('Location: ' . $this->url('Staff'));
                    } else {
                        // For customer role or any other role
                        header('Location: ' . $this->url('Khachhang'));
                    }
                    exit;
                }else {
                    $_SESSION['error'] = 'Tên đăng nhập hoặc mật khẩu không đúng!';
                    header('Location: ' . $this->url('Login'));
                    exit;
                }
            } else {
                // Nếu không có dữ liệu POST, chuyển hướng đến trang đăng nhập
                header('Location: ' . $this->url('Login'));
                exit;
            }
        }
        function register(){
            // Hiển thị trang đăng ký độc lập (không có layout Master)
            include_once __DIR__.'/../Views/Pages/Register_v.php';
        }

        function process_register(){
            if(isset($_POST['username']) && isset($_POST['password'])){
                $username = $_POST['username'];
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];
                $email = $_POST['email'] ?? ''; // Get email from form, default to empty string if not provided

                // Kiểm tra xác nhận mật khẩu
                if($password !== $confirm_password) {
                    $_SESSION['error'] = 'Mật khẩu xác nhận không khớp!';
                    header('Location: ' . $this->url('Login/register'));
                    exit;
                }

                // Kiểm tra độ dài mật khẩu
                if(strlen($password) < 6) {
                    $_SESSION['error'] = 'Mật khẩu phải có ít nhất 6 ký tự!';
                    header('Location: ' . $this->url('Login/register'));
                    exit;
                }

                // Kiểm tra xem username đã tồn tại chưa
                $existing_user = $this->user->getUserByUsername($username);

                if($existing_user) {
                    $_SESSION['error'] = 'Tên đăng nhập đã tồn tại!';
                    header('Location: ' . $this->url('Login/register'));
                    exit;
                }

                // Kiểm tra xem email đã tồn tại chưa (nếu có email được cung cấp)
                if(!empty($email)) {
                    $existing_email = $this->user->checktrungEmail($email, null);
                    if(mysqli_num_rows($existing_email) > 0) {
                        $_SESSION['error'] = 'Email đã được sử dụng!';
                        header('Location: ' . $this->url('Login/register'));
                        exit;
                    }
                } else {
                    // Nếu không có email được cung cấp, tạo email giả định để tránh lỗi NOT NULL
                    $email = $username . '@example.com';
                }

                // Không mã hóa mật khẩu
                // Tạo người dùng mới với vai trò khách hàng
                $result = $this->user->createUser($username, $email, $password, 'khach_hang');

                if($result) {
                    $_SESSION['success'] = 'Đăng ký thành công! Bạn có thể đăng nhập ngay bây giờ.';
                    header('Location: ' . $this->url('Login'));
                    exit;
                } else {
                    // Lấy lỗi cụ thể từ database để debug
                    $error_msg = mysqli_error($this->user->con);
                    $_SESSION['error'] = 'Đăng ký thất bại! Lỗi: ' . $error_msg;
                    header('Location: ' . $this->url('Login/register'));
                    exit;
                }
            } else {
                // Nếu không có dữ liệu POST, chuyển hướng đến trang đăng ký
                header('Location: ' . $this->url('Login/register'));
                exit;
            }
        }
    }
?>