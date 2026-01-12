<?php
    class Thucdon extends controller{
        private $td;
        private $dm;

        function __construct()
        {
            $this->td = $this->model("Thucdon_m");
            $this->dm = $this->model("Danhmuc_m");
        }

        function Get_data(){
            // Hàm mặc định - hiển thị danh sách thực đơn
            $this->danhsach();
        }

        function index($ma_ban = null){
            // Kiểm tra xem người dùng đã đăng nhập chưa
            if(!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'nhan_vien' && $_SESSION['user_role'] !== 'khach_hang')){
                header('Location: ' . $this->url('Users/login'));
                exit;
            }

            $result = $this->td->Thucdon_getAll();

            // Pass the table ID to the view if provided
            $this->view('Master',[
                'page' => 'Danhsachthucdon_v',
                'dulieu' => $result,
                'ma_ban' => $ma_ban
            ]);
        }
        function danhsach(){
            $result = $this->td->Thucdon_getAll();

            $this->view('Master',[
                'page' => 'Danhsachthucdon_v',
                'dulieu' => $result
            ]);
        }

          function themmoi(){
             // Lấy danh sách danh mục cho dropdown
            $dsdm = $this->dm->Danhmuc_find('', '');
            // Lấy toàn bộ thực đơn
            $result = $this->td->Thucdon_find('', '');

            $this->view('Master',[
                'page' => 'Thucdon_v', // View thêm mới
                'ma_thuc_don' => '',
                'ten_mon' => '',
                'gia' => '',
                'so_luong' => '0',
                'ma_danh_muc' => '',
                'img_thuc_don' => '',
                'dsdm' => $dsdm,
                'dulieu' => $result
            ]);
        }
        function ins(){
            if(isset($_POST['btnLuu'])){
                $ma_thuc_don = $_POST['txtMathucdon'];
                $ten_mon = $_POST['txtTenmon'];
                $gia = $_POST['txtGia'];
                $so_luong = $_POST['txtSoluong'] ?? '0';
                $ma_danh_muc = $_POST['ddlDanhmuc'];
                $dsdm = $this->dm->Danhmuc_find('', '');

                // Xử lý upload hình ảnh
                $img_thuc_don = '';
                if(isset($_FILES['txtImage']) && $_FILES['txtImage']['error'] == 0) {
                    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $filename = $_FILES['txtImage']['name'];
                    $filetmp = $_FILES['txtImage']['tmp_name'];
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                    if(in_array($ext, $allowed)) {
                        $new_filename = 'td_' . $ma_thuc_don . '_' . time() . '.' . $ext;
                        // Sử dụng đường dẫn tuyệt đối đến thư mục Public/Pictures/thucdon
                        $upload_path = $_SERVER['DOCUMENT_ROOT'] . '/qlsp/Public/Pictures/thucdon/' . $new_filename;

                        // Tạo thư mục nếu chưa tồn tại
                        $upload_dir = dirname($upload_path);
                        if (!is_dir($upload_dir)) {
                            // Tạo thư mục với quyền cao hơn và đảm bảo thư mục cha tồn tại
                            mkdir($upload_dir, 0777, true);
                        }

                        if(move_uploaded_file($filetmp, $upload_path)) {
                            $img_thuc_don = $new_filename; // Chỉ lưu tên tệp vào DB
                        } else {
                            echo "<script>alert('Upload hình ảnh thất bại!');</script>";
                            $this->view('Master',[
                                'page' => 'Thucdon_v',
                                'ma_thuc_don' => $ma_thuc_don,
                                'ten_mon' => $ten_mon,
                                'gia' => $gia,
                                'so_luong' => $so_luong,
                                'ma_danh_muc' => $ma_danh_muc,
                                'img_thuc_don' => $img_thuc_don,
                                'dsdm' => $dsdm
                            ]);
                            return;
                        }
                    } else {
                        echo "<script>alert('Định dạng hình ảnh không hợp lệ!');</script>";
                        $this->view('Master',[
                            'page' => 'Thucdon_v',
                            'ma_thuc_don' => $ma_thuc_don,
                            'ten_mon' => $ten_mon,
                            'gia' => $gia,
                            'so_luong' => $so_luong,
                            'ma_danh_muc' => $ma_danh_muc,
                            'img_thuc_don' => $img_thuc_don,
                            'dsdm' => $dsdm
                        ]);
                        return;
                    }
                } else {
                    // Nếu không có file upload mới, sử dụng giá trị từ form (trường text)
                    $img_thuc_don = $_POST['txtImage'];
                }

                // Kiểm tra dữ liệu rỗng
                if($ma_thuc_don == ''){
                    echo "<script>alert('Mã thực đơn không được rỗng!')</script>";
                    $this->themmoi();
                } else if($ten_mon == ''){
                    echo "<script>alert('Tên món không được rỗng!')</script>";
                    $this->themmoi();
                } else if($ma_danh_muc == ''){
                    echo "<script>alert('Vui lòng chọn danh mục cho món ăn!')</script>";
                    $this->themmoi();
                } else {
                    $kq1 = $this->td->checktrungMaThucdon($ma_thuc_don);
                    if($kq1){
                        echo "<script>alert('Mã thực đơn đã tồn tại! Vui lòng nhập mã khác.')</script>";
                        $this->view('Master',[
                                'page' => 'Thucdon_v',
                                'ma_thuc_don' => $ma_thuc_don,
                                'ten_mon' => $ten_mon,
                                'gia' => $gia,
                                'so_luong' => $so_luong,
                                'ma_danh_muc' => $ma_danh_muc,
                                'img_thuc_don' => $img_thuc_don,
                                'dsdm' => $dsdm
                            ]);

                    } else {
                        $kq = $this->td->thucdon_ins($ma_thuc_don, $ten_mon, $gia, $so_luong, $ma_danh_muc, $img_thuc_don);
                        if($kq) {
                            echo "<script>alert('Thêm mới thành công!')</script>";
                            $this->danhsach();
                        } else {
                            echo "<script>alert('Thêm mới thất bại!')</script>";
                            $this->view('Master',[
                                'page' => 'Thucdon_v',
                                'ma_thuc_don' => $ma_thuc_don,
                                'ten_mon' => $ten_mon,
                                'gia' => $gia,
                                'so_luong' => $so_luong,
                                'ma_danh_muc' => $ma_danh_muc,
                                'img_thuc_don' => $img_thuc_don,
                                'dsdm' => $dsdm
                            ]);
                        }
                    }
                }
            }
        }


        function Timkiem()
    {
        // Get the search parameters from the form
        $ma_thuc_don = $_POST['txtMathucdon'] ?? '';
        $ten_mon = $_POST['txtTenmon'] ?? '';

        // 👉 LẤY DỮ LIỆU THEO MÃ TD + TÊN MÓN
        $result = $this->td->Thucdon_find($ma_thuc_don, $ten_mon);

        // ====== XUẤT EXCEL ======
        if (isset($_POST['btnXuatexcel'])) {

            $objExcel = new PHPExcel();
            $objExcel->setActiveSheetIndex(0);
            $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachThucDon');

            // Header tương ứng với ảnh CSDL
            $sheet->setCellValue('A1', 'Mã TD');
            $sheet->setCellValue('B1', 'Tên Món');
             $sheet->setCellValue('C1', 'Hình Ảnh');
            $sheet->setCellValue('D1', 'Giá');
            $sheet->setCellValue('E1', 'Số Lượng');
            $sheet->setCellValue('F1', 'Tên Danh Mục');
           

            $rowCount = 2; // Starting from row 2 since row 1 is headers
            mysqli_data_seek($result, 0); // Reset result pointer to beginning
            while ($row = mysqli_fetch_assoc($result)) {
                // Mapping field according to database table
                $sheet->setCellValue('A'.$rowCount, $row['ma_thuc_don']);
                $sheet->setCellValue('B'.$rowCount, $row['ten_mon']);
                $sheet->setCellValue('C'.$rowCount, $row['img_thuc_don']);
                $sheet->setCellValue('D'.$rowCount, $row['gia']);
                $sheet->setCellValue('E'.$rowCount, $row['so_luong']);
                $sheet->setCellValue('F'.$rowCount, $row['ten_danh_muc']);

                $rowCount++;
            }

            foreach (range('A','F') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            if (ob_get_length()) ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="DanhSachThucDon.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }

        // ====== DISPLAY VIEW ======
        $this->view('Master', [
            'page' => 'Danhsachthucdon_v',
            'ma_thuc_don' => $ma_thuc_don, // Consistent with view variable name
            'ten_mon' => $ten_mon, // Consistent with view variable name
            'dulieu' => $result
        ]);
    }


       

        function sua($ma_thuc_don){
            $result = $this->td->Thucdon_getById($ma_thuc_don);
            $row = mysqli_fetch_array($result);
            $dsdm = $this->dm->Danhmuc_find('', '');

            $this->view('Master',[
                'page' => 'Thucdon_sua',
                'ma_thuc_don' => $row['ma_thuc_don'],
                'ten_mon' => $row['ten_mon'],
                'gia' => $row['gia'],
                'so_luong' => $row['so_luong'],
                'ma_danh_muc' => $row['ma_danh_muc'],
                'img_thuc_don' => $row['img_thuc_don'],
                'dsdm' => $dsdm
            ]);
        }




        function update(){
            if(isset($_POST['btnCapnhat'])){
                $ma_thuc_don = $_POST['txtMathucdon'];
                $ten_mon = $_POST['txtTenmon'];
                $gia = $_POST['txtGia'];
                $so_luong = $_POST['txtSoluong'] ?? '0';
                $ma_danh_muc = $_POST['ddlDanhmuc'];

                // Lấy hình ảnh hiện tại từ database trước
                $current_record = $this->td->Thucdon_getById($ma_thuc_don);
                $current_row = mysqli_fetch_array($current_record);
                $img_thuc_don = $current_row['img_thuc_don']; // Giữ hình ảnh hiện tại mặc định

                // Xử lý upload hình ảnh mới (nếu có)
                if(isset($_FILES['txtImage']) && $_FILES['txtImage']['error'] == 0) {
                    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $filename = $_FILES['txtImage']['name'];
                    $filetmp = $_FILES['txtImage']['tmp_name'];
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                    if(in_array($ext, $allowed)) {
                        $new_filename = 'td_' . $ma_thuc_don . '_' . time() . '.' . $ext;
                        // Sử dụng đường dẫn tuyệt đối đến thư mục Public/Pictures/thucdon
                        $upload_path = $_SERVER['DOCUMENT_ROOT'] . '/qlsp/Public/Pictures/thucdon/' . $new_filename;

                        // Tạo thư mục nếu chưa tồn tại
                        $upload_dir = dirname($upload_path);
                        if (!is_dir($upload_dir)) {
                            // Tạo thư mục với quyền cao hơn
                            mkdir($upload_dir, 0777, true);
                        }

                        if(move_uploaded_file($filetmp, $upload_path)) {
                            // Xóa hình ảnh cũ nếu tồn tại
                            $old_image_path = $_SERVER['DOCUMENT_ROOT'] . '/qlsp/Public/Pictures/thucdon/' . $current_row['img_thuc_don'];
                            if(!empty($current_row['img_thuc_don']) && file_exists($old_image_path) && strpos($old_image_path, '/Public/Pictures/thucdon/') !== false) {
                                unlink($old_image_path);
                            }

                            $img_thuc_don = $new_filename; // Chỉ lưu tên tệp vào DB
                        } else {
                            echo "<script>alert('Upload hình ảnh thất bại!');</script>";
                            $this->sua($ma_thuc_don);
                            return;
                        }
                    } else {
                        echo "<script>alert('Định dạng hình ảnh không hợp lệ!');</script>";
                        $this->sua($ma_thuc_don);
                        return;
                    }
                }
                // Nếu không có file upload mới, giữ nguyên hình ảnh hiện tại (đã được lấy ở trên)

                $kq = $this->td->Thucdon_update($ma_thuc_don, $ten_mon, $gia, $so_luong, $ma_danh_muc, $img_thuc_don);
                if($kq)
                    echo "<script>alert('Cập nhật thành công!')</script>";
                else
                    echo "<script>alert('Cập nhật thất bại!')</script>";

                $this->Get_data();
            }
        }

        // Hiển thị form nhập Excel
        function import_form(){
            $this->view('Master',[
                'page' => 'Thucdon_up_v'
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

            $ma_thuc_don = trim($sheetData[$i]['A']);
            $ten_mon     = trim($sheetData[$i]['B']);
            $gia         = trim($sheetData[$i]['C']);
            $so_luong    = trim($sheetData[$i]['D']);
            $ma_danh_muc = trim($sheetData[$i]['E']);
            $img_thuc_don = trim($sheetData[$i]['F']);
            if($ma_thuc_don == '') continue;

            // ✅ CHECK TRÙNG MÃ
            if($this->td->checktrungMaThucdon($ma_thuc_don)){
                echo "<script>
                    alert('Mã thực đơn $ma_thuc_don đã tồn tại! Vui lòng kiểm tra lại file.');
                    window.location.href='" . $this->url('Thucdon/import_form') . "';
                </script>";
                return;
            }

            // Insert
            if(!$this->td->Thucdon_ins($ma_thuc_don,$ten_mon,$gia,$so_luong,$ma_danh_muc,$img_thuc_don)){
                die(mysqli_error($this->td->con));
            }
    }

    echo "<script>alert('Upload thực đơn thành công!')</script>";
    $this->view('Master',['page'=>'Thucdon_up_v']);
}
        function xoa($ma_thuc_don){
            $kq = $this->td->Thucdon_delete($ma_thuc_don);
            if($kq)
                echo "<script>alert('Xóa thành công!'); window.location='" . $this->url('Thucdon/danhsach') . "';</script>"; 
            else
                echo "<script>alert('Xóa thất bại!'); window.location='" . $this->url('Thucdon/danhsach') . "';</script>"; 
        }

        // Method to add item to cart for customers
        function addToCart() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Check if user is logged in as customer
                if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'khach_hang') {
                    echo json_encode(['status' => 'error', 'message' => 'Bạn cần đăng nhập với vai trò khách hàng để thêm món.']);
                    exit;
                }

                $product_id = $_POST['product_id'] ?? '';
                $table_id = $_POST['table_id'] ?? '';
                $price = $_POST['price'] ?? 0;

                if (empty($product_id)) {
                    echo json_encode(['status' => 'error', 'message' => 'Thiếu thông tin sản phẩm.']);
                    exit;
                }

                // Use a default table ID for customer if not provided
                if (empty($table_id)) {
                    $table_id = 'KHACHHANG_' . $_SESSION['user_id'];
                }

                // Get product details to ensure it exists and has sufficient quantity
                $product = $this->td->Thucdon_getById($product_id);
                if (!$product || mysqli_num_rows($product) === 0) {
                    echo json_encode(['status' => 'error', 'message' => 'Sản phẩm không tồn tại.']);
                    exit;
                }

                $product_data = mysqli_fetch_array($product);
                if ($product_data['so_luong'] <= 0) {
                    echo json_encode(['status' => 'error', 'message' => 'Sản phẩm đã hết hàng.']);
                    exit;
                }

                // Add to session cart
                $session_key = 'cart_' . $table_id;
                $cart = isset($_SESSION[$session_key]) ? $_SESSION[$session_key] : [];

                // Check if product already exists in cart
                $item_exists = false;
                foreach ($cart as $key => $item) {
                    if ($item['id'] == $product_id) {
                        $cart[$key]['quantity']++;
                        $item_exists = true;
                        break;
                    }
                }

                // If item doesn't exist in cart, add it
                if (!$item_exists) {
                    $cart[] = [
                        'id' => $product_id,
                        'quantity' => 1,
                        'price' => $price
                    ];
                }

                $_SESSION[$session_key] = $cart;

                echo json_encode(['status' => 'success', 'message' => 'Đã thêm món vào giỏ hàng.']);
                exit;
            }

            // If not POST request, redirect to menu
            header('Location: ' . $this->url('Thucdon'));
            exit;
        }

        

        

       
    }
?>