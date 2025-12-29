<?php
    class Login extends controller{
        private $user;

        function __construct()
        {
            $this->user = $this->model("Users_m");
        }

        function Get_data(){
            // This is the default method that gets called if no specific action is provided
            $this->index();
        }

        function index(){
            // Check if user is already logged in
            if(isset($_SESSION['user_id'])){
                // If already logged in, redirect based on role
                if($_SESSION['user_role'] === 'admin'){
                    header('Location: http://localhost/QLSP/Home');
                } else {
                    header('Location: http://localhost/QLSP/Staff');
                }
                exit;
            } else {
               
                include_once __DIR__.'/../Views/Pages/Login_v.php';
            }
        }

        function process(){
            if(isset($_POST['username']) && isset($_POST['password'])){
                $username = $_POST['username'];
                $password = $_POST['password'];

                // Validate credentials
                $result = $this->user->validateUser($username, $password);

                if($result && mysqli_num_rows($result) > 0){
                    $user = mysqli_fetch_assoc($result);
                    // Set session variables
                    $_SESSION['user_id'] = $user['ma_user'];
                    $_SESSION['user_name'] = $user['ten_user'];
                    $_SESSION['user_role'] = $user['phan_quyen'];

                    // Redirect based on role
                    if($user['phan_quyen'] == 'admin'){
                        header('Location: http://localhost/QLSP/Home');
                    } else {
                        header('Location: http://localhost/QLSP/Staff');
                    }
                    exit;
                } else {
                    // Invalid credentials - redirect to login with error
                    header('Location: http://localhost/QLSP/Login?error=' . urlencode('Tên đăng nhập hoặc mật khẩu không đúng!'));
                    exit;
                }
            } else {
                // If no POST data, redirect to login page
                header('Location: http://localhost/QLSP/Login');
                exit;
            }
        }
    }
?>