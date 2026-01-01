<?php
    class Banuong extends controller{
        private $bu; // ban_uong
        private $dh; // don_hang
        private $ctdh; // chi_tiet_don_hang

        function __construct()
        {
            $this->bu = $this->model("Banuong_m");
            $this->dh = $this->model("Donhang_m");
            $this->ctdh = $this->model("Chitietdonhang_m");
        }

        function index(){
            $this->danhsach();
        }

        function Get_data(){
            $this->danhsach();
        }

        function danhsach(){
            $result = $this->bu->Banuong_find('', '', '');

            $this->view('Master',[
                'page' => 'Danhsachbanuong_v',
                'ma_ban' => '',
                'ten_ban' => '',
                'so_cho_ngoi' => '',
                'dulieu' => $result
            ]);
        }


        function themmoi(){
             $this->view('Master',[
                'page' => 'Banuong_v',
                'ma_ban' => '',
                'ten_ban' => '',
                'so_cho_ngoi' => '',
                'trang_thai_ban' => ''
            ]);
        }

        function ins(){
            if(isset($_POST['btnLuu'])){
                $ma_ban = $_POST['txtMaban'];
                $ten_ban = $_POST['txtTenban'];
                $so_cho_ngoi = $_POST['txtSochongoi'];
                $trang_thai_ban = $_POST['txtTrangthai'] ?? 'trong';

                // Kiểm tra dữ liệu rỗng
                if($ma_ban == ''){
                    echo "<script>alert('Mã bàn không được rỗng!')</script>";
                    $this->themmoi();
                } else {
                    // Kiểm tra trùng mã bàn
                    $kq1 = $this->bu->checktrungMaBan($ma_ban);
                    if($kq1){
                        echo "<script>alert('Mã bàn đã tồn tại! Vui lòng nhập mã khác.')</script>";
                        $this->themmoi();
                    } else {
                        $kq = $this->bu->banuong_ins($ma_ban, $ten_ban, $so_cho_ngoi, $trang_thai_ban);
                        if($kq) {
                            echo "<script>alert('Thêm mới thành công!');</script>";
                            $this->danhsach(); // Quay về danh sách sau khi thêm thành công
                        } else {
                            echo "<script>alert('Thêm mới thất bại!');</script>";
                            $this->themmoi();
                        }
                    }
                }
            }
        }

function Timkiem()
    {
        // Get the search parameters from the form
        $ma_ban = $_POST['txtMaban'] ?? '';
        $ten_ban = $_POST['txtTenban'] ?? '';
        $so_cho_ngoi = $_POST['txtSochongoi'] ?? '';

        // 👉 LẤY DỮ LIỆU THEO MÃ BÀN + TÊN BÀN + SỐ CHỖ NGỒI
        $result = $this->bu->Banuong_find($ma_ban, $ten_ban, $so_cho_ngoi);
        // ====== XUẤT EXCEL ======
        if (isset($_POST['btnXuatexcel'])) {

            $objExcel = new PHPExcel();
            $objExcel->setActiveSheetIndex(0);
            $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachBanUong');

            // Header tương ứng với ảnh CSDL
            $sheet->setCellValue('A1', 'Mã Bàn');
            $sheet->setCellValue('B1', 'Tên Bàn');
            $sheet->setCellValue('C1', 'Số Chỗ Ngồi');
            $sheet->setCellValue('D1', 'Trạng Thái Bàn');
            $sheet->setCellValue('E1', 'Ngày Tạo');


            $rowCount = 2; // Starting from row 2 since row 1 is headers
            mysqli_data_seek($result, 0); // Reset result pointer to beginning
            while ($row = mysqli_fetch_assoc($result)) {
                // Mapping field according to database table
                $sheet->setCellValue('A'.$rowCount, $row['ma_ban']);
                $sheet->setCellValue('B'.$rowCount, $row['ten_ban']);
                $sheet->setCellValue('C'.$rowCount, $row['so_cho_ngoi']);
                $sheet->setCellValue('D'.$rowCount, $row['trang_thai_ban']);
                $sheet->setCellValue('E'.$rowCount, $row['ngay_tao']);
                $rowCount++;
            }

            foreach (range('A','E') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            if (ob_get_length()) ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="DanhSachBanUong.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }

        // ====== DISPLAY VIEW ======
        $this->view('Master', [
            'page' => 'Danhsachbanuong_v',
            'ma_ban' => $ma_ban, // Consistent with view variable name
            'ten_ban' => $ten_ban, // Consistent with view variable name
            'so_cho_ngoi' => $so_cho_ngoi, // Consistent with view variable name
            'dulieu' => $result
        ]);
    }

        // AJAX search (JSON)
        function tim_ajax(){
            header('Content-Type: application/json; charset=utf-8');
            $ma_ban = isset($_POST['q_maban']) ? $_POST['q_maban'] : '';
            $ten_ban = isset($_POST['q_tenban']) ? $_POST['q_tenban'] : '';
            $so_cho_ngoi = isset($_POST['q_sochongoi']) ? $_POST['q_sochongoi'] : '';
            $result = $this->bu->Banuong_find($ma_ban, $ten_ban, $so_cho_ngoi);
            $rows = [];
            if($result){
                while($r = mysqli_fetch_assoc($result)){
                    $rows[] = [
                        'ma_ban' => $r['ma_ban'],
                        'ten_ban' => $r['ten_ban'],
                        'so_cho_ngoi' => $r['so_cho_ngoi'],
                        'trang_thai_ban' => $r['trang_thai_ban']
                    ];
                }
            }
            echo json_encode(['data' => $rows]);
            exit;
        }

        function sua($ma_ban){
            $result = $this->bu->Banuong_find($ma_ban, '', '');
            $row = mysqli_fetch_array($result);

            $this->view('Master',[
                'page' => 'Banuong_sua',
                'ma_ban' => $row['ma_ban'],
                'ten_ban' => $row['ten_ban'],
                'so_cho_ngoi' => $row['so_cho_ngoi'],
                'trang_thai_ban' => $row['trang_thai_ban']
            ]);
        }

        function update(){
            if(isset($_POST['btnCapnhat'])){
                $ma_ban = $_POST['txtMaban'];
                $ten_ban = $_POST['txtTenban'];
                $so_cho_ngoi = $_POST['txtSochongoi'];
                $trang_thai_ban = $_POST['txtTrangthai'] ?? 'trong';

                $kq = $this->bu->Banuong_update($ma_ban, $ten_ban, $so_cho_ngoi, $trang_thai_ban);
                if($kq)
                    echo "<script>alert('Cập nhật thành công!'); window.location='http://localhost/QLSP/Banuong/danhsach';</script>";
                else
                    echo "<script>alert('Cập nhật thất bại!');</script>";

                // Nếu cập nhật thất bại, gọi lại view sửa để người dùng thử lại
                if(!$kq){
                    $this->sua($ma_ban);
                }
            }
        }

        function xoa($ma_ban){
            $kq = $this->bu->Banuong_delete($ma_ban);
            if($kq)
                echo "<script>alert('Xóa thành công!'); window.location='http://localhost/QLSP/Banuong/danhsach';</script>"; // Chuyển về trang danh sách
            else
                echo "<script>alert('Xóa thất bại!'); window.location='http://localhost/QLSP/Banuong/danhsach';</script>"; // Quay lại trang danh sách
        }

        // Xuất Excel danh sách bàn uống (theo tìm kiếm nếu có)
        function export(){
            // Get search parameters from URL or POST
            $ma_ban = $_GET['ma_ban'] ?? '';
            $ten_ban = $_GET['ten_ban'] ?? '';
            $so_cho_ngoi = $_GET['so_cho_ngoi'] ?? '';

            // Find data based on search parameters (if provided) or all records (if not)
            $data = $this->bu->Banuong_find($ma_ban, $ten_ban, $so_cho_ngoi);
            $excel = new PHPExcel();
            $excel->getProperties()->setCreator("QLSP")->setTitle("Danh sách bàn uống");
            $sheet = $excel->setActiveSheetIndex(0);
            $sheet->setTitle('BanUong');
            // Header
            $sheet->setCellValue('A1','Mã Bàn');
            $sheet->setCellValue('B1','Tên Bàn');
            $sheet->setCellValue('C1','Số Chỗ Ngồi');
            $sheet->setCellValue('D1','Trạng Thái');
            // Rows
            $rowIndex = 2;
            while($r = mysqli_fetch_array($data)){
                $sheet->setCellValue('A'.$rowIndex,$r['ma_ban']);
                $sheet->setCellValue('B'.$rowIndex,$r['ten_ban']);
                $sheet->setCellValue('C'.$rowIndex,$r['so_cho_ngoi']);
                $sheet->setCellValue('D'.$rowIndex,$r['trang_thai_ban']);
                $rowIndex++;
            }
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="banuong.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }

        // Hiển thị form nhập Excel
        function import_form(){
            $this->view('Master',[
                'page' => 'Banuong_up_v'
            ]);
        }

        function up_l(){
    if(!isset($_FILES['txtfile']) || $_FILES['txtfile']['error'] != 0){
        echo "<script>alert('Upload file lỗi')</script>";
        return;
    }

    $file = $_FILES['txtfile']['tmp_name'];

    $objReader = PHPExcel_IOFactory::createReaderForFile($file);
    $objExcel  = $objReader->load($file);

    $sheet     = $objExcel->getSheet(0);
    $sheetData = $sheet->toArray(null,true,true,true);

    for($i = 2; $i <= count($sheetData); $i++){

        $ma_ban       = trim($sheetData[$i]['A']);
        $ten_ban      = trim($sheetData[$i]['B']);
        $so_cho_ngoi  = trim($sheetData[$i]['C']);
        $trang_thai_ban = trim($sheetData[$i]['D']);

        if($ma_ban == '') continue;

        // ✅ CHECK TRÙNG MÃ BÀN
        if($this->bu->checktrungMaBan($ma_ban)){
            echo "<script>
                alert('Mã bàn $ma_ban đã tồn tại! Vui lòng kiểm tra lại file.');
                window.location.href='http://localhost/QLSP/Banuong/import_form';
            </script>";
            return;
        }

        // Insert
        if(!$this->bu->Banuong_ins($ma_ban,$ten_ban,$so_cho_ngoi,$trang_thai_ban)){
            die(mysqli_error($this->bu->con));
        }
    }

    echo "<script>alert('Upload bàn uống thành công!')</script>";
    $this->view('Master',['page'=>'Banuong_up_v']);
}




        function order($ma_ban){
            // Get table info
            $table = $this->bu->Banuong_getById($ma_ban);
            $table_info = mysqli_fetch_array($table);

            // Check if table is currently in use
            if ($table_info['trang_thai_ban'] == 'dang_su_dung') {
                // Find the unpaid order for this table and redirect to order detail view
                $sql = "SELECT * FROM don_hang WHERE ma_ban = '$ma_ban' AND trang_thai_thanh_toan = 'chua_thanh_toan' LIMIT 1";
                $result = mysqli_query($this->dh->con, $sql);
                $unpaid_order = $result ? mysqli_fetch_assoc($result) : null;

                if ($unpaid_order) {
                    // Redirect to order detail view for the existing order
                    header('Location: http://localhost/QLSP/Banuong/order_detail/' . $unpaid_order['ma_don_hang']);
                    exit;
                }
            }

            $danhmuc = $this->model("Danhmuc_m");
            $categories = $danhmuc->Danhmuc_getAll();

            // Get only available menu items (not out of stock)
            $thucdon = $this->model("Thucdon_m");
            $menu_items = $thucdon->Thucdon_getAvailable();

            // Get current cart from session for this table if exists
            $current_cart = $this->getCartForTable($ma_ban);

            $this->view('StaffMaster', [
                'page' => 'Staff/Table_order_v',
                'table_info' => $table_info,
                'categories' => $categories,
                'menu_items' => $menu_items,
                'current_cart' => $current_cart
            ]);
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

        // Method to update cart via AJAX
        function update_cart(){
            // Only allow POST requests
            if($_SERVER['REQUEST_METHOD'] !== 'POST'){
                header('HTTP/1.1 405 Method Not Allowed');
                echo json_encode(['success' => false, 'message' => 'Phương thức không được phép']);
                exit;
            }

            // Get JSON data from request
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if(!$data || !isset($data['ma_ban']) || !isset($data['cart'])){
                echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
                exit;
            }

            $ma_ban = $data['ma_ban'];
            $cart = $data['cart'];

            // Save cart to session
            $this->setCartForTable($ma_ban, $cart);

            echo json_encode(['success' => true, 'message' => 'Cập nhật giỏ hàng thành công!']);
            exit;
        }

        // Method to create an order via AJAX
        function create_order(){
            // Only allow POST requests
            if($_SERVER['REQUEST_METHOD'] !== 'POST'){
                header('HTTP/1.1 405 Method Not Allowed');
                echo json_encode(['success' => false, 'message' => 'Phương thức không được phép']);
                exit;
            }

            // Get JSON data from request
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if(!$data || !isset($data['ma_ban']) || !isset($data['cart'])){
                echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
                exit;
            }

            $ma_ban = $data['ma_ban'];
            $cart = $data['cart'];

            // Validate table exists
            $table = $this->bu->Banuong_getById($ma_ban);
            if(!$table || mysqli_num_rows($table) == 0){
                echo json_encode(['success' => false, 'message' => 'Bàn không tồn tại']);
                exit;
            }

            // Generate unique order ID (always create a new one)
            $ma_don_hang = $this->generateUniqueOrderId();

            $tong_tien = 0;

            // Calculate total amount
            foreach($cart as $item) {
                $tong_tien += ($item['price'] * $item['quantity']);
            }

            // Assuming current user is admin or staff (you may want to get from session)
            $ma_user = $_SESSION['user_id'] ?? 'U01'; // Default user if not set

            // Create new order using the Donhang model's insert method
            $result = $this->dh->donhang_ins($ma_don_hang, $ma_ban, $ma_user, $tong_tien, 'chua_thanh_toan', date('Y-m-d H:i:s'));
            if(!$result) {
                echo json_encode(['success' => false, 'message' => 'Không thể tạo đơn hàng']);
                exit;
            }

            // Add order items to chi_tiet_don_hang table
            foreach($cart as $item) {
                $ma_thuc_don = $item['id'];
                $so_luong = $item['quantity'];
                $gia_tai_thoi_diem_dat = $item['price'];
                $ghi_chu = ''; // You can add notes functionality later

                // Add new order item
                $this->addOrderDetail($ma_don_hang, $ma_thuc_don, $so_luong, $gia_tai_thoi_diem_dat, $ghi_chu);
            }

            // Update table status to occupied
            $this->updateTableStatus($ma_ban, 'dang_su_dung');

            echo json_encode(['success' => true, 'message' => 'Tạo đơn hàng thành công!', 'order_id' => $ma_don_hang]);
            exit;
        }

        // Helper method to get unpaid order by table
        private function getUnpaidOrderByTable($ma_ban) {
            $sql = "SELECT * FROM don_hang WHERE ma_ban = '$ma_ban' AND trang_thai_thanh_toan = 'chua_thanh_toan' LIMIT 1";
            $result = mysqli_query($this->dh->con, $sql);
            return $result ? mysqli_fetch_assoc($result) : null;
        }

        // Helper method to update order amount
        private function updateOrder($ma_don_hang, $tong_tien) {
            $sql = "UPDATE don_hang SET tong_tien = '$tong_tien', ngay_tao = NOW() WHERE ma_don_hang = '$ma_don_hang'";
            return mysqli_query($this->dh->con, $sql);
        }

        // Helper method to check existing order detail
        private function checkExistingOrderDetail($ma_don_hang, $ma_thuc_don) {
            $sql = "SELECT * FROM chi_tiet_don_hang WHERE ma_don_hang = '$ma_don_hang' AND ma_thuc_don = '$ma_thuc_don' LIMIT 1";
            $result = mysqli_query($this->ctdh->con, $sql);
            return $result ? mysqli_fetch_assoc($result) : null;
        }

        // Helper method to update order detail quantity
        private function updateOrderDetailQuantity($ma_ctdh, $so_luong, $gia) {
            $sql = "UPDATE chi_tiet_don_hang SET so_luong = '$so_luong', gia_tai_thoi_diem_dat = '$gia' WHERE ma_ctdh = '$ma_ctdh'";
            return mysqli_query($this->ctdh->con, $sql);
        }

        // Helper method to add order detail
        private function addOrderDetail($ma_don_hang, $ma_thuc_don, $so_luong, $gia, $ghi_chu) {
            $result = $this->ctdh->Chitietdonhang_ins($ma_don_hang, $ma_thuc_don, $so_luong, $gia, $ghi_chu);

            // Log để kiểm tra lỗi nếu có
            if (!$result) {
                error_log("Lỗi khi thêm chi tiết đơn hàng: " . mysqli_error($this->ctdh->con));
            } else {
                error_log("Thêm chi tiết đơn hàng thành công: $ma_don_hang, $ma_thuc_don, $so_luong");
            }

            return $result;
        }

        // Helper method to update table status
        private function updateTableStatus($ma_ban, $status) {
            $sql = "UPDATE ban_uong SET trang_thai_ban = '$status' WHERE ma_ban = '$ma_ban'";
            return mysqli_query($this->bu->con, $sql);
        }

        // Helper method to generate a unique order ID
        private function generateUniqueOrderId() {
            // Get the highest existing order ID to generate the next one
            $sql = "SELECT ma_don_hang FROM don_hang ORDER BY CAST(SUBSTRING(ma_don_hang, 3) AS UNSIGNED) DESC LIMIT 1";
            $result = mysqli_query($this->dh->con, $sql);

            $new_id = 'DH1'; // Default starting ID

            if($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $last_id = $row['ma_don_hang'];

                // Extract the numeric part from the last ID
                preg_match('/^DH(\d+)$/', $last_id, $matches);

                if(isset($matches[1])) {
                    $number = intval($matches[1]);
                    $number++; // Increment the number
                    $new_id = 'DH' . $number; // Format the ID correctly
                } else {
                    // If the last ID doesn't match the expected format, start from DH1
                    $new_id = 'DH1';
                }
            }

            return $new_id;
        }

        // Method to view order details
        function order_detail($ma_don_hang) {
            // Get order information
            $order_result = $this->dh->Donhang_getByIdWithDiscount($ma_don_hang);

            // Check if order exists in database
            if ($order_result && mysqli_num_rows($order_result) > 0) {
                $order_row = mysqli_fetch_array($order_result);
                // Reset pointer to beginning so the view can fetch it again
                mysqli_data_seek($order_result, 0);

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
                'page' => 'Staff/Chi_tiet_don_hang_v',
                'order' => $order_result, // Pass the mysqli_result as expected by the view
                'order_details' => $order_details,
                'discount_vouchers' => $discount_vouchers
            ]);

        }
    }
?>