<?php
class Donhang extends controller
{
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

    function Get_data()
    {
        $this->danhsach();
    }

    function danhsach()
    {
        $result = $this->dh->Donhang_getAll();

        $this->view('Master', [
            'page' => 'Danhsachdonhang_v',
            'dulieu' => $result
        ]);
    }

    // Hàm để lấy chi tiết đơn hàng cho một đơn hàng cụ thể
    function get_order_details($ma_don_hang)
    {
        $order_details = $this->ctdh->Chitietdonhang_getByOrderId($ma_don_hang);

        // Lấy thông tin đơn hàng để lấy ghi chú
        $order_info = $this->dh->Donhang_getById($ma_don_hang);
        $order_data = mysqli_fetch_array($order_info);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'order_details' => $order_details,
            'order_notes' => $order_data['ghi_chu'] ?? ''
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
                $sheet->setCellValue('A' . $rowCount, $row['ma_don_hang']);
                $sheet->setCellValue('B' . $rowCount, $row['ten_ban']);
                $sheet->setCellValue('C' . $rowCount, $row['ten_user']);
                $sheet->setCellValue('D' . $rowCount, $row['tong_tien']);
                $sheet->setCellValue('E' . $rowCount, $row['tien_khuyen_mai']);
                $sheet->setCellValue('F' . $rowCount, $row['tong_tien'] - $row['tien_khuyen_mai']);
                $sheet->setCellValue('G' . $rowCount, $row['trang_thai_thanh_toan']);
                $sheet->setCellValue('H' . $rowCount, $row['ngay_tao']);
                $rowCount++;
            }

            foreach (range('A', 'H') as $col) {
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

        // ====== HIỂN THỊ GIAO DIỆN ======
        $this->view('Master', [
            'page' => 'Danhsachdonhang_v',
            'ma_don_hang' => $ma_don_hang, // Consistent with view variable name
            'ten_ban' => $ten_ban,
            'ten_user' => $ten_user, // Consistent with view variable name
            'dulieu' => $result
        ]);
    }





    function xoa($ma_don_hang)
    {
        $kq = $this->dh->Donhang_delete($ma_don_hang);
        if ($kq)
            echo "<script>alert('Xóa thành công!'); window.location='" . $this->url('Donhang/danhsach') . "';</script>"; // Chuyển về trang danh sách
        else
            echo "<script>alert('Xóa thất bại!'); window.location='" . $this->url('Donhang/danhsach') . "';</script>"; // Quay lại trang danh sách
    }
}
