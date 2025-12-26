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
                } else if($tensp == ''){
                    echo "<script>alert('Tên sản phẩm không được rỗng!')</script>";
                } else {
                    // Kiểm tra trùng mã sản phẩm
                    $kq1 = $this->sp->checktrungMaSP($masp);
                    if($kq1){
                        echo "<script>alert('Mã sản phẩm đã tồn tại! Vui lòng nhập mã khác.')</script>";
                    } else {
                        $kq = $this->sp->sanpham_ins($masp, $tensp, $gia, $soluong, $mancc);
                        if($kq)
                            echo "<script>alert('Thêm mới thành công!')</script>";
                        else
                            echo "<script>alert('Thêm mới thất bại!')</script>";
                    }
                }

                // Gọi lại giao diện
                $dsncc = $this->ncc->Nhacungcap_find('', '');
                $result = $this->sp->Sanpham_find('', '');
                $this->view('Master',[
                    'page' => 'Sanpham_v',
                    'Masanpham' => $masp,
                    'Tensanpham' => $tensp,
                    'Gia' => $gia,
                    'Soluong' => $soluong,
                    'mancc' => $mancc,
                    'dsncc' => $dsncc,
                    'dulieu' => $result
                ]);
            }
        }
        
        function tim(){
            if(isset($_POST['btnTim'])){
                $masp = $_POST['txtMasanpham'];
                $tensp = $_POST['txtTensanpham'];
                
                $result = $this->sp->Sanpham_find($masp, $tensp);
                $dsncc = $this->ncc->Nhacungcap_find('', '');
                
                $this->view('Master',[
                    'page' => 'Sanpham_v',
                    'Masanpham' => $masp,
                    'Tensanpham' => $tensp,
                    'Gia' => '',
                    'Soluong' => '',
                    'mancc' => '',
                    'dsncc' => $dsncc,
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
                'page' => 'Sanpham_import_v'
            ]);
        }

        // Xử lý nhập Excel
        function import(){
            if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK){
                $tmp = $_FILES['file']['tmp_name'];
                try{
                    $excel = PHPExcel_IOFactory::load($tmp);
                    $sheet = $excel->getActiveSheet();
                    $highestRow = $sheet->getHighestRow();
                    $imported = 0; $skipped = 0;
                    for($row = 2; $row <= $highestRow; $row++){
                        $masp = trim($sheet->getCell('A'.$row)->getValue());
                        $tensp = trim($sheet->getCell('B'.$row)->getValue());
                        $gia = trim($sheet->getCell('C'.$row)->getValue());
                        $soluong = trim($sheet->getCell('D'.$row)->getValue());
                        $mancc = trim($sheet->getCell('E'.$row)->getValue());
                        if($masp !== ''){
                            if(!$this->sp->checktrungMaSP($masp) && $this->ncc->checktrungMaNCC($mancc)){
                                if($this->sp->sanpham_ins($masp,$tensp,$gia,$soluong,$mancc)) $imported++; else $skipped++;
                            } else { $skipped++; }
                        }
                    }
                    echo "<script>alert('Nhập thành công: ".$imported." dòng, bỏ qua: ".$skipped." dòng.'); window.location='?url=Sanpham/Get_data';</script>";
                }catch(Exception $e){
                    echo "<script>alert('Lỗi đọc file Excel!'); window.location='?url=Sanpham/import_form';</script>";
                }
            } else {
                echo "<script>alert('Vui lòng chọn file Excel!'); window.location='?url=Sanpham/import_form';</script>";
            }
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
                echo "<script>alert('Xóa thành công!'); window.location='?url=Sanpham/Get_data';</script>";
            else
                echo "<script>alert('Xóa thất bại!');</script>";
        }
        
        function danhsach(){
            $result = $this->sp->Sanpham_getAll();
            
            $this->view('Master',[
                'page' => 'Danhsachsanpham_v',
                'dulieu' => $result
            ]);
        }
    }
?>