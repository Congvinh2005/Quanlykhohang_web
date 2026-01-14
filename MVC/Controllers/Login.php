<?php
class Login extends controller
{
    private $user;

    function __construct()
    {
        $this->user = $this->model("Users_m");
    }

    function Get_data()
    {
        $this->phan_quyen();
    }

    function phan_quyen()
    {
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['user_role'] === 'admin') {
                header('Location: ' . $this->url('Home'));
            } elseif ($_SESSION['user_role'] === 'nhan_vien') {
                header('Location: ' . $this->url('Staff'));
            } else {
                header('Location: ' . $this->url('Khachhang'));
            }
            exit;
        } else {
            include_once __DIR__ . '/../Views/Pages/Login_v.php';
        }
    }

    function process()
    {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = $this->user->authenticateUser($username, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['ma_user'];
                $_SESSION['user_name'] = $user['ten_user'];
                $_SESSION['user_role'] = $user['phan_quyen'];

                if ($user['phan_quyen'] == 'admin') {
                    header('Location: ' . $this->url('Home'));
                } elseif ($user['phan_quyen'] == 'nhan_vien') {
                    header('Location: ' . $this->url('Staff'));
                } else {
                    header('Location: ' . $this->url('Khachhang'));
                }
                exit;
            } else {
                $_SESSION['error'] = 'Tên đăng nhập hoặc mật khẩu không đúng!';
                header('Location: ' . $this->url('Login'));
                exit;
            }
        } else {
            header('Location: ' . $this->url('Login'));
            exit;
        }
    }

    function register()
    {
        include_once __DIR__ . '/../Views/Pages/Register_v.php';
    }

    function process_register()
    {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $email = $_POST['email'] ?? '';


            if ($password !== $confirm_password) {
                $_SESSION['error'] = 'Mật khẩu xác nhận không khớp!';
                header('Location: ' . $this->url('Login/register'));
                exit;
            }

            if (strlen($password) < 6) {
                $_SESSION['error'] = 'Mật khẩu phải có ít nhất 6 ký tự!';
                header('Location: ' . $this->url('Login/register'));
                exit;
            }

            $existing_user = $this->user->getUserByUsername($username);
            if ($existing_user) {
                $_SESSION['error'] = 'Tên đăng nhập đã tồn tại!';
                header('Location: ' . $this->url('Login/register'));
                exit;
            }

            if (!empty($email)) {
                $existing_email = $this->user->checktrungEmail($email, null);
                if (mysqli_num_rows($existing_email) > 0) {
                    $_SESSION['error'] = 'Email đã được sử dụng!';
                    header('Location: ' . $this->url('Login/register'));
                    exit;
                }
            } else {
                $email = $username . '@gmail.com';
            }


            $result = $this->user->createUser($username, $email, $password, 'khach_hang');

            if ($result) {
                echo '<script>alert("Đăng ký thành công! Bạn có thể đăng nhập ngay bây giờ."); window.location.href = "' . $this->url('Login') . '";</script>';
                exit;
            } else {
                $error_msg = mysqli_error($this->user->con);
                $_SESSION['error'] = 'Đăng ký thất bại! Lỗi: ' . $error_msg;
                header('Location: ' . $this->url('Login/register'));
                exit;
            }
        } else {

            header('Location: ' . $this->url('Login/register'));
            exit;
        }
    }
    function logout()
    {
        session_destroy();
        header('Location: ' . $this->url('Login'));
        exit;
    }
}