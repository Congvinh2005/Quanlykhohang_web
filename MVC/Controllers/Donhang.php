<?php
    class Donhang extends controller{
        private $dh;
        private $bu;
        private $us;
        private $ctdh;
        private $td;

        function __construct()
        {
            $this->dh = $this->model("Donhang_m");
            $this->bu = $this->model("Banuong_m");
            $this->us = $this->model("Users_m");
            $this->ctdh = $this->model("Chitietdonhang_m");
            $this->td = $this->model("Thucdon_m");
        }

        function Get_data(){
            // Hàm mặc định - hiển thị danh sách đơn hàng
            $this->danhsach();
        }
        
        function danhsach(){
            $result = $this->dh->Donhang_getAll();

            $this->view('Master',[
                'page' => 'Danhsachdonhang_v',
                'dulieu' => $result
            ]);
        }

        // Function to get order details for a specific order
        function get_order_details($ma_don_hang){
            $order_details = $this->ctdh->Chitietdonhang_getByOrderId($ma_don_hang);
            
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'order_details' => $order_details
            ]);
            exit;
        }

        function Timkiem()
    {
        // Get the search parameters from the form
        $ma_don_hang = $_POST['txtMadonhang'] ?? '';
        $ma_ban = $_POST['txtMaban'] ?? '';

        // 👉 LẤY DỮ LIỆU THEO MÃ ĐƠN HÀNG + MÃ BÀN
        $result = $this->dh->Donhang_find($ma_don_hang, $ma_ban);

        // ====== XUẤT EXCEL ======
        if (isset($_POST['btnXuatexcel'])) {

            $objExcel = new PHPExcel();
            $objExcel->setActiveSheetIndex(0);
            $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachDonHang');

            // Header tương ứng với ảnh CSDL
            $sheet->setCellValue('A1', 'Mã Đơn Hàng');
            $sheet->setCellValue('B1', 'Mã Bàn');
            $sheet->setCellValue('C1', 'Mã User');
            $sheet->setCellValue('D1', 'Tổng Tiền');
            $sheet->setCellValue('E1', 'Trạng Thái Thanh Toán');
            $sheet->setCellValue('F1', 'Ngày Tạo');

            $rowCount = 2; // Starting from row 2 since row 1 is headers
            mysqli_data_seek($result, 0); // Reset result pointer to beginning
            while ($row = mysqli_fetch_assoc($result)) {
                // Mapping field according to database table
                $sheet->setCellValue('A'.$rowCount, $row['ma_don_hang']);
                $sheet->setCellValue('B'.$rowCount, $row['ma_ban']);
                $sheet->setCellValue('C'.$rowCount, $row['ma_user']);
                $sheet->setCellValue('D'.$rowCount, $row['tong_tien']);
                $sheet->setCellValue('E'.$rowCount, $row['trang_thai_thanh_toan']);
                $sheet->setCellValue('F'.$rowCount, $row['ngay_tao']);
                $rowCount++;
            }

            foreach (range('A','F') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            if (ob_get_length()) ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="DanhSachDonHang.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }

        // ====== DISPLAY VIEW ======
        $this->view('Master', [
            'page' => 'Danhsachdonhang_v',
            'ma_don_hang' => $ma_don_hang, // Consistent with view variable name
            'ma_ban' => $ma_ban, // Consistent with view variable name
            'dulieu' => $result
        ]);
    }

        // AJAX search (JSON)
        function tim_ajax(){
            header('Content-Type: application/json; charset=utf-8');
            $ma_don_hang = isset($_POST['q_madh']) ? $_POST['q_madh'] : '';
            $ma_ban = isset($_POST['q_maban']) ? $_POST['q_maban'] : '';
            $result = $this->dh->Donhang_find($ma_don_hang, $ma_ban);
            $rows = [];
            if($result){
                while($r = mysqli_fetch_assoc($result)){
                    $rows[] = [
                        'ma_don_hang' => $r['ma_don_hang'],
                        'ma_ban' => $r['ma_ban'],
                        'ma_user' => $r['ma_user'],
                        'tong_tien' => $r['tong_tien'],
                        'trang_thai_thanh_toan' => $r['trang_thai_thanh_toan'],
                        'ngay_tao' => $r['ngay_tao']
                    ];
                }
            }
            echo json_encode(['data' => $rows]);
            exit;
        }

        // Hiển thị form nhập Excel
        function import_form(){
            $this->view('Master',[
                'page' => 'Donhang_up_v'
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

            $ma_don_hang = trim($sheetData[$i]['A']);
            $ma_ban      = trim($sheetData[$i]['B']);
            $ma_user     = trim($sheetData[$i]['C']);
            $tong_tien   = trim($sheetData[$i]['D']);
            $trang_thai_thanh_toan = trim($sheetData[$i]['E']);
            $ngay_tao    = trim($sheetData[$i]['F']);
            
            if($ma_don_hang == '') continue;

            // ✅ CHECK TRÙNG MÃ
            if($this->dh->checktrungMaDonhang($ma_don_hang)){
                echo "<script>
                    alert('Mã đơn hàng $ma_don_hang đã tồn tại! Vui lòng kiểm tra lại file.');
                    window.location.href='http://localhost/QLSP/Donhang/import_form';
                </script>";
                return;
            }

            // Insert
            if(!$this->dh->Donhang_ins($ma_don_hang,$ma_ban,$ma_user,$tong_tien,$trang_thai_thanh_toan,$ngay_tao)){
                die(mysqli_error($this->dh->con));
            }
    }

    echo "<script>alert('Upload đơn hàng thành công!')</script>";
    $this->view('Master',['page'=>'Donhang_up_v']);
}

        function xoa($ma_don_hang){
            $kq = $this->dh->Donhang_delete($ma_don_hang);
            if($kq)
                echo "<script>alert('Xóa thành công!'); window.location='http://localhost/QLSP/Donhang/danhsach';</script>"; // Chuyển về trang danh sách
            else
                echo "<script>alert('Xóa thất bại!'); window.location='http://localhost/QLSP/Donhang/danhsach';</script>"; // Quay lại trang danh sách
        }

        // Method to export current search results or all orders
        function export(){
            // Check if coming from search context - get parameters from URL if available
            $ma_don_hang = $_GET['ma_don_hang'] ?? '';
            $ma_ban = $_GET['ma_ban'] ?? '';

            // Get the filtered data based on search parameters, or all if none provided
            $result = $this->dh->Donhang_find($ma_don_hang, $ma_ban);

            $objExcel = new PHPExcel();
            $objExcel->setActiveSheetIndex(0);
            $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachDonHang');

            // Header tương ứng với ảnh CSDL
            $sheet->setCellValue('A1', 'Mã Đơn Hàng');
            $sheet->setCellValue('B1', 'Mã Bàn');
            $sheet->setCellValue('C1', 'Mã User');
            $sheet->setCellValue('D1', 'Tổng Tiền');
            $sheet->setCellValue('E1', 'Trạng Thái Thanh Toán');
            $sheet->setCellValue('F1', 'Ngày Tạo');

            $rowCount = 2; // Starting from row 2 since row 1 is headers
            mysqli_data_seek($result, 0); // Reset result pointer to beginning
            while ($row = mysqli_fetch_assoc($result)) {
                $sheet->setCellValue('A'.$rowCount, $row['ma_don_hang']);
                $sheet->setCellValue('B'.$rowCount, $row['ma_ban']);
                $sheet->setCellValue('C'.$rowCount, $row['ma_user']);
                $sheet->setCellValue('D'.$rowCount, $row['tong_tien']);
                $sheet->setCellValue('E'.$rowCount, $row['trang_thai_thanh_toan']);
                $sheet->setCellValue('F'.$rowCount, $row['ngay_tao']);
                $rowCount++;
            }

            foreach (range('A','F') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            if (ob_get_length()) ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="DanhSachDonHang.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }
    }
?>