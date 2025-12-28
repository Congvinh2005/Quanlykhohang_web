<?php 
    class Nhacungcap extends controller{
        private $ncc;
        
        function __construct()
        {
            $this->ncc = $this->model("Nhacungcap_m");
        }
        
        // Hàm mặc định, hiển thị trang danh sách (chứa tìm kiếm, xuất/nhập)
        function index(){
            $this->danhsach();
        }

        // Thêm phương thức Get_data để xử lý URL ?url=Nhacungcap/Get_data
        function Get_data(){
            $this->danhsach();
        }
        
        function danhsach(){
            // Lấy toàn bộ dữ liệu nhà cung cấp
            $result = $this->ncc->Nhacungcap_find('', '');

            $this->view('Master',[
                'page' => 'Danhsachnhacungcap_v', // View danh sách mới
                'mancc' => '',
                'tenncc' => '',
                'diachi' => '',
                'dienthoai' => '',
                'dulieu' => $result
            ]);
        }
        function themmoi(){
             $this->view('Master',[
                'page' => 'Nhacungcap_v', // View thêm mới
                'mancc' => '',
                'tenncc' => '',
                'diachi' => '',
                'dienthoai' => ''
            ]);
        }

        function ins(){
            if(isset($_POST['btnLuu'])){
                $mancc = $_POST['txtMancc'];
                $tenncc = $_POST['txtTenncc'];
                $diachi = $_POST['txtDiachi'];
                $dienthoai = $_POST['txtDienthoai'];

                // Kiểm tra dữ liệu rỗng
                if($mancc == ''){
                    echo "<script>alert('Mã nhà cung cấp không được rỗng!')</script>";
                } else {
                    // Kiểm tra số điện thoại có đúng 10 chữ số không nếu không để trống
                    $dienthoai = trim($dienthoai); // Loại bỏ khoảng trắng
                    if($dienthoai != '' && !preg_match('/^\d{10}$/', $dienthoai)){
                        echo "<script>alert('Số điện thoại phải có đúng 10 chữ số!')</script>";
                        $this->form_them();
                        return;
                    }

                    // Kiểm tra trùng mã nhà cung cấp
                    $kq1 = $this->ncc->checktrungMaNCC($mancc);
                    if($kq1){
                        echo "<script>alert('Mã nhà cung cấp đã tồn tại! Vui lòng nhập mã khác.')</script>";
                         $this->view('Master', [
                                'page' => 'Nhacungcap_v',
                                'mancc' => $mancc,
                                'tenncc' => $tenncc,
                                'diachi' => $diachi,
                                'dienthoai' => $dienthoai

                            ]);
                    } else {
                        $kq = $this->ncc->nhacungcap_ins($mancc, $tenncc, $diachi, $dienthoai);
                        if($kq) {
                            echo "<script>alert('Thêm mới thành công!');</script>";
                            $this->danhsach();

                        } else {
                            echo "<script>alert('Thêm mới thất bại!');</script>";
                            $this->view('Master', [
                                'page' => 'Nhacungcap_v',
                                'mancc' => $mancc,
                                'tenncc' => $tenncc,
                                'diachi' => $diachi,
                                'dienthoai' => $dienthoai
                            ]);
                        }
                    }
                }

            }
        }
        
        function tim(){
            if(isset($_POST['btnTim'])){
                $mancc = $_POST['txtMancc'];
                $tenncc = $_POST['txtTenncc'];

                $result = $this->ncc->Nhacungcap_find($mancc, $tenncc);

                $this->view('Master',[
                    'page' => 'Danhsachnhacungcap_v',
                    'mancc' => $mancc,
                    'tenncc' => $tenncc,
                    'diachi' => '',
                    'dienthoai' => '',
                    'dulieu' => $result
                ]);
            }
        }

        // AJAX search (JSON) - Giữ nguyên
        function tim_ajax(){
            header('Content-Type: application/json; charset=utf-8');
            $mancc = isset($_POST['q_mancc']) ? $_POST['q_mancc'] : '';
            $tenncc = isset($_POST['q_tenncc']) ? $_POST['q_tenncc'] : '';
            $result = $this->ncc->Nhacungcap_find($mancc, $tenncc);
            $rows = [];
            if($result){
                while($r = mysqli_fetch_assoc($result)){
                    $rows[] = [
                        'mancc' => $r['mancc'],
                        'tenncc' => $r['tenncc'],
                        'diachi' => $r['diachi'],
                        'dienthoai' => $r['dienthoai']
                    ];
                }
            }
            echo json_encode(['data' => $rows]);
            exit;
        }
        
        function sua($mancc){
            $result = $this->ncc->Nhacungcap_find($mancc, '');
            $row = mysqli_fetch_array($result);

            $this->view('Master',[
                'page' => 'Nhacungcap_sua', // View sửa mới
                'mancc' => $row['mancc'],
                'tenncc' => $row['tenncc'],
                'diachi' => $row['diachi'],
                'dienthoai' => $row['dienthoai']
            ]);
        }
        
        function update(){
            if(isset($_POST['btnCapnhat'])){
                $mancc = $_POST['txtMancc'];
                $tenncc = $_POST['txtTenncc'];
                $diachi = $_POST['txtDiachi'];
                $dienthoai = $_POST['txtDienthoai'];

                // Kiểm tra số điện thoại có đúng 10 chữ số không nếu không để trống
                $dienthoai = trim($dienthoai); // Loại bỏ khoảng trắng
                if($dienthoai != '' && !preg_match('/^\d{10}$/', $dienthoai)){
                    echo "<script>alert('Số điện thoại phải có đúng 10 chữ số!')</script>";
                    $this->sua($mancc);
                    return;
                }

                $kq = $this->ncc->Nhacungcap_update($mancc, $tenncc, $diachi, $dienthoai);
                if($kq)
                    echo "<script>alert('Cập nhật thành công!'); window.location='http://localhost/QLSP/Nhacungcap/danhsach';</script>";
                else
                    echo "<script>alert('Cập nhật thất bại!');</script>";

                // Nếu cập nhật thất bại, gọi lại view sửa để người dùng thử lại
                if(!$kq){
                    $this->view('Master',[
                        'page' => 'Nhacungcap_sua',
                        'mancc' => $mancc,
                        'tenncc' => $tenncc,
                        'diachi' => $diachi,
                        'dienthoai' => $dienthoai
                    ]);
                }
            }
        }
        
        function xoa($mancc){
            $kq = $this->ncc->Nhacungcap_delete($mancc);
            if($kq)
                echo "<script>alert('Xóa thành công!'); window.location='http://localhost/QLSP/Nhacungcap/danhsach';</script>"; // Chuyển về trang danh sách
            else
                echo "<script>alert('Xóa thất bại!'); window.location='http://localhost/QLSP/Nhacungcap/danhsach';</script>"; // Quay lại trang danh sách
        }

        // Xuất Excel danh sách nhà cung cấp (theo tìm kiếm nếu có)
        function export(){
            // Get search parameters from URL or POST
            $mancc = $_GET['mancc'] ?? '';
            $tenncc = $_GET['tenncc'] ?? '';

            // Find data based on search parameters (if provided) or all records (if not)
            $data = $this->ncc->Nhacungcap_find($mancc, $tenncc);
            $excel = new PHPExcel();
            $excel->getProperties()->setCreator("QLSP")->setTitle("Danh sách nhà cung cấp");
            $sheet = $excel->setActiveSheetIndex(0);
            $sheet->setTitle('Nhacungcap');
            // Header
            $sheet->setCellValue('A1','Mã NCC');
            $sheet->setCellValue('B1','Tên NCC');
            $sheet->setCellValue('C1','Địa chỉ');
            $sheet->setCellValue('D1','Điện thoại');
            // Rows
            $rowIndex = 2;
            while($r = mysqli_fetch_array($data)){
                $sheet->setCellValue('A'.$rowIndex,$r['mancc']);
                $sheet->setCellValue('B'.$rowIndex,$r['tenncc']);
                $sheet->setCellValue('C'.$rowIndex,$r['diachi']);
                $sheet->setCellValue('D'.$rowIndex,$r['dienthoai']);
                $rowIndex++;
            }
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="nhacungcap.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }

        // Hiển thị form nhập Excel - Giữ nguyên
        function import_form(){
            $this->view('Master',[
                'page' => 'Nhacungcap_up_v'
            ]);
        }

        // Xử lý nhập Excel - Sửa redirect về danhsach
        //     function up_l(){
        //     if(!isset($_FILES['txtfile']) || $_FILES['txtfile']['error'] != 0){
        //         echo "<script>alert('Upload file lỗi')</script>";
        //         return;
        //     }

        //     $file = $_FILES['txtfile']['tmp_name'];

        //     $objReader = PHPExcel_IOFactory::createReaderForFile($file);
        //     $objExcel  = $objReader->load($file);

        //     $sheet     = $objExcel->getSheet(0);
        //     $sheetData = $sheet->toArray(null,true,true,true);

        //     for($i = 2; $i <= count($sheetData); $i++){

        //         $mancc   = trim((string)$sheetData[$i]['A']);
        //         $tenncc = trim((string)$sheetData[$i]['B']);
        //         $diachi     = trim((string)$sheetData[$i]['C']);
        //         $dienthoai  = trim((string)$sheetData[$i]['D']);

        //         // Kiểm tra số điện thoại có đúng 10 chữ số không nếu không để trống
        //         if($dienthoai != '' && !preg_match('/^\d{10}$/', $dienthoai)){
        //             echo "<script>alert('Số điện thoại ở dòng $i không đúng định dạng 10 chữ số. Dữ liệu không được nhập.')</script>";
        //             return;
        //         }

        //         if($mancc == '') continue;
        //         if(!$this->ncc->Nhacungcap_ins($mancc,$tenncc,$diachi,$dienthoai)){
        //             die(mysqli_error($this->ncc->con));
        //         }
        //     }

        //     echo "<script>alert('Upload nhà cung cấp thành công!')</script>";
        //     $this->view('Master',['page'=>'Nhacungcap_up_v']);
        // }


                function up_l(){
            if(!isset($_FILES['txtfile']) || $_FILES['txtfile']['error'] != 0){
                echo "<script>alert('Upload file lỗi')</script>";
                $this->view('Master',['page'=>'Nhacungcap_up_v']);
                return;
            }

            $file = $_FILES['txtfile']['tmp_name'];

            $objReader = PHPExcel_IOFactory::createReaderForFile($file);
            $objExcel  = $objReader->load($file);

            $sheet     = $objExcel->getSheet(0);
            $sheetData = $sheet->toArray(null,true,true,true);

            $errors = [];
            $rows   = [];

            // 1️⃣ VALIDATE TRƯỚC
            for($i = 2; $i <= count($sheetData); $i++){
                $mancc     = trim((string)$sheetData[$i]['A']);
                $tenncc    = trim((string)$sheetData[$i]['B']);
                $diachi    = trim((string)$sheetData[$i]['C']);
                $dienthoai = trim((string)$sheetData[$i]['D']);

                if($mancc == '') continue;

                if($dienthoai != '' && !preg_match('/^\d{10}$/', $dienthoai)){
                    $errors[] = "Dòng $i: Số điện thoại phải đúng 10 chữ số";
                }

                if($this->ncc->checktrungMaNCC($mancc)){
                    $errors[] = "Dòng $i: Mã NCC [$mancc] đã tồn tại";
                }

                $rows[] = [$mancc, $tenncc, $diachi, $dienthoai];
            }

            // ❌ Nếu có lỗi → dừng import
            if(!empty($errors)){
                echo "<script>alert('LỖI dữ liệu bảng không phù hợp:\\n" . implode("\\n", $errors) . "')</script>";
                $this->view('Master',['page'=>'Nhacungcap_up_v']);
                return;
            }

            // 2️⃣ INSERT SAU KHI HỢP LỆ
            foreach($rows as $r){
                if(!$this->ncc->Nhacungcap_ins($r[0], $r[1], $r[2], $r[3])){
                    echo "<script>alert('Lỗi khi lưu dữ liệu!')</script>";
                    $this->view('Master',['page'=>'Nhacungcap_up_v']);
                    return;
                }
            }

            echo "<script>alert('Upload nhà cung cấp thành công!')</script>";
            $this->view('Master',['page'=>'Nhacungcap_up_v']);
        }   

        // Tải mẫu Excel (chỉ header) - Giữ nguyên
        function template(){
            $excel = new PHPExcel();
            $sheet = $excel->setActiveSheetIndex(0);
            $sheet->setTitle('Nhacungcap');
            $sheet->setCellValue('A1','Mã NCC');
            $sheet->setCellValue('B1','Tên NCC');
            $sheet->setCellValue('C1','Địa chỉ');
            $sheet->setCellValue('D1','Điện thoại');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="mau_nhacungcap.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }
    }
?>