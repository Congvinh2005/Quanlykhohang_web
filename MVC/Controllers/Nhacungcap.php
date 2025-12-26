<?php 
    class Nhacungcap extends controller{
        private $ncc;
        function __construct()
        {
            $this->ncc = $this->model("Nhacungcap_m");
        }
        
        function Get_data(){
            // Lấy toàn bộ dữ liệu nhà cung cấp
            $result = $this->ncc->Nhacungcap_find('', '');
            
            $this->view('Master',[
                'page' => 'Nhacungcap_v',
                'mancc' => '',
                'tenncc' => '', 
                'diachi' => '',
                'dienthoai' => '',
                'dulieu' => $result
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
                    // Kiểm tra trùng mã nhà cung cấp
                    $kq1 = $this->ncc->checktrungMaNCC($mancc);
                    if($kq1){
                        echo "<script>alert('Mã nhà cung cấp đã tồn tại! Vui lòng nhập mã khác.')</script>";
                    } else {
                        $kq = $this->ncc->nhacungcap_ins($mancc, $tenncc, $diachi, $dienthoai);
                        if($kq)
                            echo "<script>alert('Thêm mới thành công!')</script>";
                        else
                            echo "<script>alert('Thêm mới thất bại!')</script>";
                    }
                }

                // Gọi lại giao diện và load lại dữ liệu
                $result = $this->ncc->Nhacungcap_find('', '');
                $this->view('Master',[
                    'page' => 'Nhacungcap_v',
                    'mancc' => '',
                    'tenncc' => '',
                    'diachi' => '',
                    'dienthoai' => '',
                    'dulieu' => $result
                ]);
            }
        }
        
        function tim(){
            if(isset($_POST['btnTim'])){
                $mancc = $_POST['txtMancc'];
                $tenncc = $_POST['txtTenncc'];
                
                $result = $this->ncc->Nhacungcap_find($mancc, $tenncc);
                
                $this->view('Master',[
                    'page' => 'Nhacungcap_v',
                    'mancc' => $mancc,
                    'tenncc' => $tenncc,
                    'diachi' => '',
                    'dienthoai' => '',
                    'dulieu' => $result
                ]);
            }
        }

        // AJAX search (JSON)
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
                'page' => 'Nhacungcap_sua',
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
                
                $kq = $this->ncc->Nhacungcap_update($mancc, $tenncc, $diachi, $dienthoai);
                if($kq)
                    echo "<script>alert('Cập nhật thành công!')</script>";
                else
                    echo "<script>alert('Cập nhật thất bại!')</script>";
                    
                $this->Get_data();
            }
        }
        
        function xoa($mancc){
            $kq = $this->ncc->Nhacungcap_delete($mancc);
            if($kq)
                echo "<script>alert('Xóa thành công!'); window.location='?url=Nhacungcap/Get_data';</script>";
            else
                echo "<script>alert('Xóa thất bại!');</script>";
        }

        // Xuất Excel danh sách nhà cung cấp
        function export(){
            $data = $this->ncc->Nhacungcap_find('', '');
            $excel = new PHPExcel();
            $excel->getProperties()->setCreator("QLSP")->setTitle("Danh sách nhà cung cấp");
            $sheet = $excel->setActiveSheetIndex(0);
            $sheet->setTitle('NCC');
            // Header
            $sheet->setCellValue('A1','Mã NCC');
            $sheet->setCellValue('B1','Tên nhà cung cấp');
            $sheet->setCellValue('C1','Địa chỉ');
            $sheet->setCellValue('D1','Điện thoại');
            // Rows
            $row = 2;
            while($r = mysqli_fetch_array($data)){
                $sheet->setCellValue('A'.$row,$r['mancc']);
                $sheet->setCellValue('B'.$row,$r['tenncc']);
                $sheet->setCellValue('C'.$row,$r['diachi']);
                $sheet->setCellValue('D'.$row,$r['dienthoai']);
                $row++;
            }
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="nhacungcap.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }

        // Hiển thị form nhập Excel
        function import_form(){
            $this->view('Master',[
                'page' => 'Nhacungcap_import_v'
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
                        $mancc = trim($sheet->getCell('A'.$row)->getValue());
                        $tenncc = trim($sheet->getCell('B'.$row)->getValue());
                        $diachi = trim($sheet->getCell('C'.$row)->getValue());
                        $dienthoai = trim($sheet->getCell('D'.$row)->getValue());
                        if($mancc !== ''){
                            if(!$this->ncc->checktrungMaNCC($mancc)){
                                if($this->ncc->nhacungcap_ins($mancc,$tenncc,$diachi,$dienthoai)) $imported++; else $skipped++;
                            }
                            else { $skipped++; }
                        }
                    }
                    echo "<script>alert('Nhập thành công: ".$imported." dòng, bỏ qua: ".$skipped." dòng.'); window.location='?url=Nhacungcap/Get_data';</script>";
                }catch(Exception $e){
                    echo "<script>alert('Lỗi đọc file Excel!'); window.location='?url=Nhacungcap/import_form';</script>";
                }
            } else {
                echo "<script>alert('Vui lòng chọn file Excel!'); window.location='?url=Nhacungcap/import_form';</script>";
            }
        }

        // Tải mẫu Excel (chỉ header)
        function template(){
            $excel = new PHPExcel();
            $sheet = $excel->setActiveSheetIndex(0);
            $sheet->setTitle('NCC');
            $sheet->setCellValue('A1','Mã NCC');
            $sheet->setCellValue('B1','Tên nhà cung cấp');
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
