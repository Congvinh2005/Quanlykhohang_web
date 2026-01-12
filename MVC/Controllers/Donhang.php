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

        // Hàm để lấy chi tiết đơn hàng cho một đơn hàng cụ thể
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
        // Lấy các tham số tìm kiếm từ form
        $ma_don_hang = $_POST['txtMadonhang'] ?? '';
        $ten_ban = $_POST['txtTenban'] ?? '';
        $ten_user = $_POST['txtTenuser'] ?? '';

        // 👉 LẤY DỮ LIỆU THEO MÃ ĐƠN HÀNG + MÃ BÀN
        $result = $this->dh->Donhang_find($ma_don_hang, $ten_ban, $ten_user);

        // ====== XUẤT EXCEL ======
        if (isset($_POST['btnXuatexcel'])) {

            $objExcel = new PHPExcel();
            $objExcel->setActiveSheetIndex(0);
            $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachDonHang');

            // Header tương ứng với ảnh CSDL
            $sheet->setCellValue('A1', 'Mã Đơn Hàng');
            $sheet->setCellValue('B1', 'Tên Bàn');
            $sheet->setCellValue('C1', 'Tên User');
            $sheet->setCellValue('D1', 'Tổng Tiền');
            $sheet->setCellValue('E1', 'Tiền Khuyến Mãi');
            $sheet->setCellValue('F1', 'Số Tiền Cần Thanh Toán');
            $sheet->setCellValue('G1', 'Trạng Thái Thanh Toán');
            $sheet->setCellValue('H1', 'Ngày Tạo');

            $rowCount = 2; // Bắt đầu từ hàng 2 vì hàng 1 là tiêu đề
            mysqli_data_seek($result, 0); // Đặt lại con trỏ kết quả về đầu
            while ($row = mysqli_fetch_assoc($result)) {
                // Ánh xạ trường theo bảng cơ sở dữ liệu
                $sheet->setCellValue('A'.$rowCount, $row['ma_don_hang']);
                $sheet->setCellValue('B'.$rowCount, $row['ten_ban']);
                $sheet->setCellValue('C'.$rowCount, $row['ten_user']);
                $sheet->setCellValue('D'.$rowCount, $row['tong_tien']);
                $sheet->setCellValue('E'.$rowCount, $row['tien_khuyen_mai']);
                $sheet->setCellValue('F'.$rowCount, $row['tong_tien'] - $row['tien_khuyen_mai']);
                $sheet->setCellValue('G'.$rowCount, $row['trang_thai_thanh_toan']);
                $sheet->setCellValue('H'.$rowCount, $row['ngay_tao']);
                $rowCount++;
            }

            foreach (range('A','H') as $col) {
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
            'ten_ban' => $ten_ban,
            'ten_user' => $ten_user, // Consistent with view variable name
            'dulieu' => $result
        ]);
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
                    window.location.href='" . $this->url('Donhang/import_form') . "';
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
                echo "<script>alert('Xóa thành công!'); window.location='" . $this->url('Donhang/danhsach') . "';</script>"; // Chuyển về trang danh sách
            else
                echo "<script>alert('Xóa thất bại!'); window.location='" . $this->url('Donhang/danhsach') . "';</script>"; // Quay lại trang danh sách
        }

       
        // Cập nhật trạng thái thanh toán cho đơn hàng (dành cho admin)
        function update_payment_status($ma_don_hang){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $result = $this->dh->update_order_status($ma_don_hang, 'da_thanh_toan');

                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Cập nhật trạng thái thanh toán thành công']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Cập nhật thất bại']);
                }
                exit;
            }
        }
    }
?>