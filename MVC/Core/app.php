<?php
    class app{
        protected $controller = "Login";
        protected $action = "Get_data";
        protected $param = [];
        function __construct()
        {
            // Check if user is logged in
            $this->checkAuth();
            
            $arr = $this->processURL();
            // print_r($arr);
            //Xử lý controller
            if($arr!=null){
                if(file_exists(__DIR__.'/../Controllers/'.$arr[0].'.php')){
                    $this->controller=$arr[0];
                    unset($arr[0]);
                }
            }
            include_once __DIR__.'/../Controllers/'.$this->controller.'.php';
            $this->controller=new $this->controller;
            //Xử lý action
            if(isset($arr[1])){
                if(method_exists($this->controller,$arr[1])){
                    $this->action=$arr[1];
                    unset($arr[1]);
                }
            }
            //Xử lý param
            $this->param=$arr?array_values($arr):[];
            //Tạo biến có 3 tham số
            call_user_func_array([$this->controller,$this->action],$this->param);
        }
        
        // Method to check if user is authenticated
        function checkAuth(){
            // Define public routes that don't require authentication
            $public_routes = ['Users/login', 'Users/logout', 'Login', 'Login/process'];

            // Get current route
            $current_route = '';
            if(isset($_GET['url'])){
                $current_route = $_GET['url'];
            } else {
                $current_route = '';  // This means we're on the home page (index.php)
            }

            // If user is not logged in and accessing the home page directly (root URL), redirect to login
            if(!isset($_SESSION['user_id']) && empty($current_route)){
                header('Location: http://localhost/QLSP/Login');
                exit;
            }

            // If user is not logged in and trying to access protected route (not login/logout)
            if(!isset($_SESSION['user_id']) && !in_array($current_route, $public_routes)){
                header('Location: http://localhost/QLSP/Login');
                exit;
            }

            // If user is logged in but trying to access login page, redirect based on role
            if(isset($_SESSION['user_id']) && in_array($current_route, ['Users/login', 'Login'])){
                if($_SESSION['user_role'] === 'admin'){
                    header('Location: http://localhost/QLSP/Home');
                } else {
                    header('Location: http://localhost/QLSP/Staff');
                }
                exit;
            }

            // If user is logged in and accessing home page directly, redirect based on role
            if(isset($_SESSION['user_id']) && empty($current_route)){
                if($_SESSION['user_role'] === 'admin'){
                    header('Location: http://localhost/QLSP/Home');
                } else {
                    header('Location: http://localhost/QLSP/Staff');
                }
                exit;
            }

            // Ensure that only authorized users can access staff routes
            if (!isset($_SESSION['user_id']) && strpos($current_route, 'Staff') === 0) {
                header('Location: http://localhost/QLSP/Login');
                exit;
            }
        }
        
        //Method to check if user is logged in
        function isLoggedIn(){
            return isset($_SESSION['user_id']);
        }
        
        //Kiểm tra xem có tồn tại url hay không?
        //trim có tác dụng loại bỏ khoảng trắng ở cuối show
        function processURL(){
        if(isset($_GET['url'])){
            return explode('/',filter_var(trim($_GET['url']),FILTER_DEFAULT));
        }
    }
    }
?>