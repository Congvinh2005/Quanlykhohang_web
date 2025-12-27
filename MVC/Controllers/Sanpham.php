<?php 
    class Sanpham extends controller{
        private $sp;
        private $ncc;
        
        function __construct()
        {
            $this->sp = $this->model("Sanpham_m");
            $this->ncc = $this->model("Nhacungcap_m");
        }
        
        function Get_data(){
            // Hàm mặc định - hiển thị danh sách sản phẩm
            $this->danhsach();
        }
        function danhsach(){
            $result = $this->sp->Sanpham_getAll();
            
            $this->view('Master',[
                'page' => 'Danhsachsanpham_v',
                'dulieu' => $result
            ]);
        }
        
        function form_them(){
            // Lấy danh sách nhà cung cấp cho dropdown
            $dsncc = $this->ncc->Nhacungcap_find('', '');
            // Lấy toàn bộ sản phẩm
            $result = $this->sp->Sanpham_find('', '');
            
            $this->view('Master',[
                'page' => 'Sanpham_v',
                'Masanpham' => '',
                'Tensanpham' => '',
                'Gia' => '',
                'Soluong' => '',
                'mancc' => '',
                'dsncc' => $dsncc,
                'dulieu' => $result
            ]);
        }

          function themmoi(){
             // Lấy danh sách nhà cung cấp cho dropdown
            $dsncc = $this->ncc->Nhacungcap_find('', '');
            // Lấy toàn bộ sản phẩm
            $result = $this->sp->Sanpham_find('', '');
            
            $this->view('Master',[
                'page' => 'Sanpham_v', // View thêm mới
                'Masanpham' => '',
                'Tensanpham' => '',
                'Gia' => '',
                'Soluong' => '',
                'mancc' => '',
                'dsncc' => $dsncc,
                'dulieu' => $result
            ]);
        }
        
        function ins(){
            if(isset($_POST['btnLuu'])){
                $masp = $_POST['txtMasanpham'];
                $tensp = $_POST['txtTensanpham'];
                $gia = $_POST['txtGia'];
                $soluong = $_POST['txtSoluong'];
                $mancc = $_POST['ddlNhacungcap'];

                // Kiểm tra dữ liệu rỗng
                if($masp == ''){
                    echo "<script>alert('Mã sản phẩm không được rỗng!')</script>";
                    $this->form_them();
                } else if($tensp == ''){
                    echo "<script>alert('Tên sản phẩm không được rỗng!')</script>";
                    $this->form_them();
                } else {
                    // Kiểm tra trùng mã sản phẩm
                    $kq1 = $this->sp->checktrungMaSP($masp);
                    if($kq1){
                        echo "<script>alert('Mã sản phẩm đã tồn tại! Vui lòng nhập mã khác.')</script>";
                        $this->form_them();
                    } else {
                        $kq = $this->sp->sanpham_ins($masp, $tensp, $gia, $soluong, $mancc);
                        if($kq) {
                            echo "<script>alert('Thêm mới thành công!')</script>";
                            // Quay về danh sách sau khi thêm thành công
                            $this->danhsach();
                        } else {
                            echo "<script>alert('Thêm mới thất bại!')</script>";
                            $this->form_them();
                        }
                    }
                }
            } else {
                // Hiển thị form thêm mới
                $this->form_them();
            }
        }
        
        function tim(){
            if(isset($_POST['btnTim'])){
                $masp = $_POST['txtMasanpham'];
                $tensp = $_POST['txtTensanpham'];
                
                $result = $this->sp->Sanpham_find($masp, $tensp);
                
                $this->view('Master',[
                    'page' => 'Danhsachsanpham_v',
                    'Masanpham' => $masp,
                    'Tensanpham' => $tensp,
                    'dulieu' => $result
                ]);
            }
        }

        // AJAX search (JSON)
        function tim_ajax(){
            header('Content-Type: application/json; charset=utf-8');
            $masp = isset($_POST['q_masp']) ? $_POST['q_masp'] : '';
            $tensp = isset($_POST['q_tensp']) ? $_POST['q_tensp'] : '';
            $result = $this->sp->Sanpham_find($masp, $tensp);
            $rows = [];
            if($result){
                while($r = mysqli_fetch_assoc($result)){
                    $rows[] = [
                        'masp' => $r['masp'],
                        'tensp' => $r['tensp'],
                        'gia' => $r['gia'],
                        'soluong' => $r['soluong'],
                        'mancc' => $r['mancc'],
                        'tenncc' => isset($r['tenncc']) ? $r['tenncc'] : ''
                    ];
                }
            }
            echo json_encode(['data' => $rows]);
            exit;
        }
        
        function sua($masp){
            $result = $this->sp->Sanpham_getById($masp);
            $row = mysqli_fetch_array($result);
            $dsncc = $this->ncc->Nhacungcap_find('', '');
            
            $this->view('Master',[
                'page' => 'Sanpham_sua',
                'masp' => $row['masp'],
                'tensp' => $row['tensp'],
                'gia' => $row['gia'],
                'soluong' => $row['soluong'],
                'mancc' => $row['mancc'],
                'dsncc' => $dsncc
            ]);
        }

        // Xuất Excel danh sách sản phẩm
        function export(){
            $data = $this->sp->Sanpham_getAll();
            $excel = new PHPExcel();
            $excel->getProperties()->setCreator("QLSP")->setTitle("Danh sách sản phẩm");
            $sheet = $excel->setActiveSheetIndex(0);
            $sheet->setTitle('Sanpham');
            // Header
            $sheet->setCellValue('A1','Mã SP');
            $sheet->setCellValue('B1','Tên sản phẩm');
            $sheet->setCellValue('C1','Giá');
            $sheet->setCellValue('D1','Số lượng');
            $sheet->setCellValue('E1','Mã NCC');
            // Rows
            $rowIndex = 2;
            while($r = mysqli_fetch_array($data)){
                $sheet->setCellValue('A'.$rowIndex,$r['masp']);
                $sheet->setCellValue('B'.$rowIndex,$r['tensp']);
                $sheet->setCellValue('C'.$rowIndex,$r['gia']);
                $sheet->setCellValue('D'.$rowIndex,$r['soluong']);
                $sheet->setCellValue('E'.$rowIndex,$r['mancc']);
                $rowIndex++;
            }
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="sanpham.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }

        // Hiển thị form nhập Excel
        function import_form(){
            $this->view('Master',[
                'page' => 'Sanpham_up_v'
            ]);
        }

        // Xử lý nhập Excel
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

                $masp   = trim((string)$sheetData[$i]['A']);
                $tensp = trim((string)$sheetData[$i]['B']);
                $gia     = trim((string)$sheetData[$i]['C']);
                $soluong  = trim((string)$sheetData[$i]['D']);
                $mancc    = trim((string)$sheetData[$i]['E']);

                if($masp == '') continue;
                if(!$this->sp->Sanpham_ins($masp,$tensp,$gia,$soluong,$mancc)){
                    die(mysqli_error($this->sp->con));
                }
            }

            echo "<script>alert('Upload sản phẩm thành công!')</script>";
            $this->view('Master',['page'=>'Sanpham_up_v']);
        }
        // Tải mẫu Excel (chỉ header)
        function template(){
            $excel = new PHPExcel();
            $sheet = $excel->setActiveSheetIndex(0);
            $sheet->setTitle('Sanpham');
            $sheet->setCellValue('A1','Mã SP');
            $sheet->setCellValue('B1','Tên sản phẩm');
            $sheet->setCellValue('C1','Giá');
            $sheet->setCellValue('D1','Số lượng');
            $sheet->setCellValue('E1','Mã NCC');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="mau_sanpham.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }
        
        function update(){
            if(isset($_POST['btnCapnhat'])){
                $masp = $_POST['txtMasanpham'];
                $tensp = $_POST['txtTensanpham'];
                $gia = $_POST['txtGia'];
                $soluong = $_POST['txtSoluong'];
                $mancc = $_POST['ddlNhacungcap'];
                
                $kq = $this->sp->Sanpham_update($masp, $tensp, $gia, $soluong, $mancc);
                if($kq)
                    echo "<script>alert('Cập nhật thành công!')</script>";
                else
                    echo "<script>alert('Cập nhật thất bại!')</script>";
                    
                $this->Get_data();
            }
        }
        
        function xoa($masp){
            $kq = $this->sp->Sanpham_delete($masp);
            if($kq)
                echo "<script>alert('Xóa thành công!'); window.location='http://localhost/QLSP/Sanpham/danhsach';</script>"; // Chuyển về trang danh sách
            else
                echo "<script>alert('Xóa thất bại!'); window.location='http://localhost/QLSP/Sanpham/danhsach';</script>"; // Quay lại trang danh sách
        }
        
        
    }
?>