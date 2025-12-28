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
                  $dsNCC = $this->ncc->Nhacungcap_find('', '');

                // Kiểm tra dữ liệu rỗng
                if($masp == ''){
                    echo "<script>alert('Mã sản phẩm không được rỗng!')</script>";
                    $this-> themmoi();
                } else if($tensp == ''){
                    echo "<script>alert('Tên sản phẩm không được rỗng!')</script>";
                    $this->themmoi();
                } else {
                    $kq1 = $this->sp->checktrungMaSP($masp);
                    if($kq1){
                        echo "<script>alert('Mã sản phẩm đã tồn tại! Vui lòng nhập mã khác.')</script>";
                        $this->view('Master',[
                                'page' => 'Sanpham_v',
                                'Masanpham' => $masp,
                                'Tensanpham' => $tensp,
                                'Gia' => $gia,
                                'Soluong' => $soluong,
                                'mancc' => $mancc,
                                'dsncc' => $dsNCC
                            ]);
                      
                    } else {
                        $kq = $this->sp->sanpham_ins($masp, $tensp, $gia, $soluong, $mancc);
                        if($kq) {
                            echo "<script>alert('Thêm mới thành công!')</script>";
                            $this->danhsach();
                        } else {
                            echo "<script>alert('Thêm mới thất bại!')</script>";
                            $this->view('Master',[
                                'page' => 'Sanpham_v',
                                'Masanpham' => $masp,
                                'Tensanpham' => $tensp,
                                'Gia' => $gia,
                                'Soluong' => $soluong,
                                'mancc' => $mancc
                            ]);
                        }
                    }
                }
            } 
        }
        
        // function tim(){
        //     if(isset($_POST['btnTim'])){
        //         $masp = $_POST['txtMasanpham'];
        //         $tensp = $_POST['txtTensanpham'];
                
        //         $result = $this->sp->Sanpham_find($masp, $tensp);
                
        //         $this->view('Master',[
        //             'page' => 'Danhsachsanpham_v',
        //             'Masanpham' => $masp,
        //             'Tensanpham' => $tensp,
        //             'dulieu' => $result
        //         ]);
        //     }
        //      // Xuất Excel danh sách sản phẩm
        // function export(){
        //     $data = $this->sp->Sanpham_getAll();
        //     $excel = new PHPExcel();
        //     $excel->getProperties()->setCreator("QLSP")->setTitle("Danh sách sản phẩm");
        //     $sheet = $excel->setActiveSheetIndex(0);
        //     $sheet->setTitle('Sanpham');
        //     // Header
        //     $sheet->setCellValue('A1','Mã SP');
        //     $sheet->setCellValue('B1','Tên sản phẩm');
        //     $sheet->setCellValue('C1','Giá');
        //     $sheet->setCellValue('D1','Số lượng');
        //     $sheet->setCellValue('E1','Mã NCC');
        //     // Rows
        //     $rowIndex = 2;
        //     while($r = mysqli_fetch_array($data)){
        //         $sheet->setCellValue('A'.$rowIndex,$r['masp']);
        //         $sheet->setCellValue('B'.$rowIndex,$r['tensp']);
        //         $sheet->setCellValue('C'.$rowIndex,$r['gia']);
        //         $sheet->setCellValue('D'.$rowIndex,$r['soluong']);
        //         $sheet->setCellValue('E'.$rowIndex,$r['mancc']);
        //         $rowIndex++;
        //     }
        //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        //     header('Content-Disposition: attachment;filename="sanpham.xlsx"');
        //     header('Cache-Control: max-age=0');
        //     $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        //     $writer->save('php://output');
        //     exit;
        // }

        // }

        function Timkiem()
    {
        // Get the search parameters from the form
        $masp = $_POST['txtMasanpham'] ?? '';
        $tensp = $_POST['txtTensanpham'] ?? '';

        // 👉 LẤY DỮ LIỆU THEO MÃ SP + TÊN SP
        $result = $this->sp->Sanpham_find($masp, $tensp);

        // ====== XUẤT EXCEL ======
        if (isset($_POST['btnXuatexcel'])) {

            $objExcel = new PHPExcel();
            $objExcel->setActiveSheetIndex(0);
            $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachSanPham');

            // Header tương ứng với ảnh CSDL
            $sheet->setCellValue('A1', 'Mã SP');
            $sheet->setCellValue('B1', 'Tên SP');
            $sheet->setCellValue('C1', 'Giá');
            $sheet->setCellValue('D1', 'SL');
            $sheet->setCellValue('E1', 'Mã NCC');

            $rowCount = 2; // Starting from row 2 since row 1 is headers
            mysqli_data_seek($result, 0); // Reset result pointer to beginning
            while ($row = mysqli_fetch_assoc($result)) {
                // Mapping field according to database table
                $sheet->setCellValue('A'.$rowCount, $row['masp']);
                $sheet->setCellValue('B'.$rowCount, $row['tensp']);
                $sheet->setCellValue('C'.$rowCount, $row['gia']);
                $sheet->setCellValue('D'.$rowCount, $row['soluong']);
                $sheet->setCellValue('E'.$rowCount, $row['mancc']);
                $rowCount++;
            }

            foreach (range('A','E') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            if (ob_get_length()) ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="DanhSachSanPham.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }

        // ====== DISPLAY VIEW ======
        $this->view('Master', [
            'page' => 'Danhsachsanpham_v',
            'Masanpham' => $masp, // Consistent with view variable name
            'Tensanpham' => $tensp, // Consistent with view variable name
            'dulieu' => $result
        ]);
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

       
        // Hiển thị form nhập Excel
        function import_form(){
            $this->view('Master',[
                'page' => 'Sanpham_up_v'
            ]);
        }

        // // Xử lý nhập Excel
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

        //         $masp   = trim((string)$sheetData[$i]['A']);
        //         $tensp = trim((string)$sheetData[$i]['B']);
        //         $gia     = trim((string)$sheetData[$i]['C']);
        //         $soluong  = trim((string)$sheetData[$i]['D']);
        //         $mancc    = trim((string)$sheetData[$i]['E']);

        //         if($masp == '') continue;
        //         if(!$this->sp->Sanpham_ins($masp,$tensp,$gia,$soluong,$mancc)){
        //             die(mysqli_error($this->sp->con));
        //         }
        //     }

        //     echo "<script>alert('Upload sản phẩm thành công!')</script>";
        //     $this->view('Master',['page'=>'Sanpham_up_v']);
        // }

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

            $masp   = trim($sheetData[$i]['A']);
            $tensp  = trim($sheetData[$i]['B']);
            $gia    = trim($sheetData[$i]['C']);
            $soluong= trim($sheetData[$i]['D']);
            $mancc  = trim($sheetData[$i]['E']);
            if($masp == '') continue;

            // ✅ CHECK TRÙNG MÃ
            if($this->sp->checktrungMaSP($masp)){
                echo "<script>
                    alert('Mã sản phẩm $masp đã tồn tại! Vui lòng kiểm tra lại file.');
                    window.location.href='http://localhost/QLSP/Sanpham/import_form';
                </script>";
                return;
            }

            // Insert
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

        // Method to export current search results or all products
        function export(){
            // Check if coming from search context - get parameters from URL if available
            $masp = $_GET['masp'] ?? '';
            $masx = $_GET['masx'] ?? '';

            // Get the filtered data based on search parameters, or all if none provided
            $result = $this->sanpham->Sanpham_find($masp, $masx);

            $objExcel = new PHPExcel();
            $objExcel->setActiveSheetIndex(0);
            $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachSanPham');

            // Header tương ứng với ảnh CSDL
            $sheet->setCellValue('A1', 'Mã sản phẩm');
            $sheet->setCellValue('B1', 'Tên sản phẩm');
            $sheet->setCellValue('C1', 'Giá');
            $sheet->setCellValue('D1', 'Số lượng');
            $sheet->setCellValue('E1', 'Thời gian bảo hành'); // Thêm cột E
            $sheet->setCellValue('F1', 'Nhà sản xuất');       // Đẩy cột Nhà SX sang F

            $rowCount = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                $rowCount++;
                // Mapping field theo bảng SANPHAM và NHASANXUAT
                $sheet->setCellValue('A'.$rowCount, $row['Masp']);
                $sheet->setCellValue('B'.$rowCount, $row['Tensp']);
                $sheet->setCellValue('C'.$rowCount, $row['Gia']);
                $sheet->setCellValue('D'.$rowCount, $row['Soluong']); // Sửa: Solg → Soluong
                $sheet->setCellValue('E'.$rowCount, date('d/m/Y', strtotime($row['Tgbaohanh']))); // Format ngày tương ứng với view
                $sheet->setCellValue('F'.$rowCount, $row['Tensx']);
            }

            foreach (range('A','F') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            if (ob_get_length()) ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="DanhSachSanPham.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }
    }
?>