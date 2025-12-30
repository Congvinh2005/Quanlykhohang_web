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

            $danhmuc = $this->model("Danhmuc_m");
            $categories = $danhmuc->Danhmuc_getAll();

            // Get all menu items
            $thucdon = $this->model("Thucdon_m");
            $menu_items = $thucdon->Thucdon_getAll();

            $this->view('StaffMaster', [
                'page' => 'Staff/Table_order_v',
                'table_info' => $table_info,
                'categories' => $categories,
                'menu_items' => $menu_items
            ]);
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

            // Create new order in don_hang table
            $ma_don_hang = 'DH' . time(); // Generate unique order ID
            $tong_tien = 0;

            // Calculate total amount
            foreach($cart as $item) {
                $tong_tien += ($item['price'] * $item['quantity']);
            }

            // Assuming current user is admin or staff (you may want to get from session)
            $ma_user = $_SESSION['user_id'] ?? 'U01'; // Default user if not set

            // Check if there's already an unpaid order for this table
            $existing_order = $this->getUnpaidOrderByTable($ma_ban);

            if($existing_order) {
                // Update existing order
                $ma_don_hang = $existing_order['ma_don_hang'];
                $this->updateOrder($ma_don_hang, $tong_tien);
            } else {
                // Create new order using the Donhang model's insert method
                $result = $this->dh->donhang_ins($ma_don_hang, $ma_ban, $ma_user, $tong_tien, 'chua_thanh_toan', date('Y-m-d H:i:s'));
                if(!$result) {
                    echo json_encode(['success' => false, 'message' => 'Không thể tạo đơn hàng']);
                    exit;
                }
            }

            // Add order items to chi_tiet_don_hang table
            foreach($cart as $item) {
                $ma_thuc_don = $item['id'];
                $so_luong = $item['quantity'];
                $gia_tai_thoi_diem_dat = $item['price'];
                $ghi_chu = ''; // You can add notes functionality later

                // Check if this item already exists in the current order
                $existing_detail = $this->checkExistingOrderDetail($ma_don_hang, $ma_thuc_don);

                if($existing_detail) {
                    // Update quantity if item exists
                    $this->updateOrderDetailQuantity($existing_detail['ma_ctdh'], $so_luong, $gia_tai_thoi_diem_dat);
                } else {
                    // Add new order item
                    $this->addOrderDetail($ma_don_hang, $ma_thuc_don, $so_luong, $gia_tai_thoi_diem_dat, $ghi_chu);
                }
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
            $sql = "INSERT INTO chi_tiet_don_hang (ma_don_hang, ma_thuc_don, so_luong, gia_tai_thoi_diem_dat, ghi_chu)
                    VALUES ('$ma_don_hang', '$ma_thuc_don', '$so_luong', '$gia', '$ghi_chu')";
            return mysqli_query($this->ctdh->con, $sql);
        }

        // Helper method to update table status
        private function updateTableStatus($ma_ban, $status) {
            $sql = "UPDATE ban_uong SET trang_thai_ban = '$status' WHERE ma_ban = '$ma_ban'";
            return mysqli_query($this->bu->con, $sql);
        }
    }
?>