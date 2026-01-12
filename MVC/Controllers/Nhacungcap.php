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
                        $this->themmoi();
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
        
        function Timkiem()
    {
        // Get the search parameters from the form
        $mancc = $_POST['txtMancc'] ?? '';
        $tenncc = $_POST['txtTenncc'] ?? '';

        // 👉 LẤY DỮ LIỆU THEO MÃ NHÀ CUNG CẤP + TÊN NHÀ CUNG CẤP
        $result = $this->ncc->Nhacungcap_find($mancc, $tenncc);
        // ====== XUẤT EXCEL ======
        if (isset($_POST['btnXuatexcel'])) {

            $objExcel = new PHPExcel();
            $objExcel->setActiveSheetIndex(0);
            $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachNhacungcap');

            // Header tương ứng với ảnh CSDL
            $sheet->setCellValue('A1', 'Mã Nhà Cung Cấp');
            $sheet->setCellValue('B1', 'Tên Nhà Cung Cấp');
            $sheet->setCellValue('C1', 'Địa Chỉ');
            $sheet->setCellValue('D1', 'Điện Thoại');
           


            $rowCount = 2; // Starting from row 2 since row 1 is headers
            mysqli_data_seek($result, 0); // Reset result pointer to beginning
            while ($row = mysqli_fetch_assoc($result)) {
                // Mapping field according to database table
                $sheet->setCellValue('A'.$rowCount, $row['mancc']);
                $sheet->setCellValue('B'.$rowCount, $row['tenncc']);
                $sheet->setCellValue('C'.$rowCount, $row['diachi']);
                $sheet->setCellValue('D'.$rowCount, $row['dienthoai']);
                $rowCount++;
            }

            foreach (range('A','D') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            if (ob_get_length()) ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="DanhSachNhacungcap.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }

        // ====== DISPLAY VIEW ======
        $this->view('Master', [
            'page' => 'Danhsachnhacungcap_v',
            'mancc' => $mancc, // Consistent with view variable name
            'tenncc' => $tenncc, // Consistent with view variable name
            'dulieu' => $result
        ]);
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
                    echo "<script>alert('Cập nhật thành công!'); window.location='" . $this->url('Nhacungcap/danhsach') . "';</script>";
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
                echo "<script>alert('Xóa thành công!'); window.location='" . $this->url('Nhacungcap/danhsach') . "';</script>"; // Chuyển về trang danh sách
            else
                echo "<script>alert('Xóa thất bại!'); window.location='" . $this->url('Nhacungcap/danhsach') . "';</script>"; // Quay lại trang danh sách
        }

       

        // Hiển thị form nhập Excel - Giữ nguyên
        function import_form(){
            $this->view('Master',[
                'page' => 'Nhacungcap_up_v'
            ]);
        }


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

       
    }
?>