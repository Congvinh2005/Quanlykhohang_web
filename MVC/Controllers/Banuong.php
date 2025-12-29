<?php
    class Banuong extends controller{
        private $bu;

        function __construct()
        {
            $this->bu = $this->model("Banuong_m");
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

        // Tải mẫu Excel (chỉ header)
        function template(){
            $excel = new PHPExcel();
            $sheet = $excel->setActiveSheetIndex(0);
            $sheet->setTitle('BanUong');
            $sheet->setCellValue('A1','Mã Bàn');
            $sheet->setCellValue('B1','Tên Bàn');
            $sheet->setCellValue('C1','Số Chỗ Ngồi');
            $sheet->setCellValue('D1','Trạng Thái');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="mau_banuong.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }
    }
?>