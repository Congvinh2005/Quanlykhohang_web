<?php
class Khuyenmai extends controller
{
    private $km;

    function __construct()
    {
        $this->km = $this->model("Khuyenmai_m");
    }

    function Get_data()
    {
        $this->danhsach();
    }

    function danhsach()
    {
        $result = $this->km->Khuyenmai_getAll();

        $this->view('Master', [
            'page' => 'Danhsachkhuyenmai_v',
            'dulieu' => $result
        ]);
    }

    function themmoi()
    {
        $this->view('Master', [
            'page' => 'Khuyenmai_v',
            'ma_khuyen_mai' => '',
            'ten_khuyen_mai' => '',
            'tien_khuyen_mai' => '',
            'ghi_chu' => ''
        ]);
    }

    function ins()
    {
        if (isset($_POST['btnLuu'])) {
            $ma_khuyen_mai = $_POST['txtMakhuyenmai'];
            $ten_khuyen_mai = $_POST['txtTenkhuyenmai'];
            $tien_khuyen_mai = $_POST['txtTienkhuyenmai'];
            $ghi_chu = $_POST['txtGhichu'];

            // Kiểm tra dữ liệu rỗng
            if ($ma_khuyen_mai == '') {
                echo "<script>alert('Mã khuyến mãi không được rỗng!')</script>";
                $this->themmoi();
            } else {
                // Kiểm tra trùng mã khuyến mãi
                $kq1 = $this->km->checktrungMaKhuyenMai($ma_khuyen_mai);
                if ($kq1) {
                    echo "<script>alert('Mã khuyến mãi đã tồn tại! Vui lòng nhập mã khác.')</script>";
                    echo "<script>alert('Mã user đã tồn tại!')</script>";
                    $this->view('Master', [
                        'page' => 'Khuyenmai_v',
                        'ma_khuyen_mai' => $ma_khuyen_mai,
                        'ten_khuyen_mai' => $ten_khuyen_mai,
                        'tien_khuyen_mai' => $tien_khuyen_mai,
                        'ghi_chu' => $ghi_chu
                    ]);
                } else {
                    $kq = $this->km->khuyenmai_ins($ma_khuyen_mai, $ten_khuyen_mai, $tien_khuyen_mai, $ghi_chu);
                    if ($kq) {
                        echo "<script>alert('Thêm mới thành công!');</script>";
                        $this->danhsach(); // Quay về danh sách sau khi thêm thành công
                    } else {
                        echo "<script>alert('Thêm mới thất bại!');</script>";
                        $this->view('Master', [
                            'page' => 'Khuyenmai_v',
                            'ma_khuyen_mai' => $ma_khuyen_mai,
                            'ten_khuyen_mai' => $ten_khuyen_mai,
                            'tien_khuyen_mai' => $tien_khuyen_mai,
                            'ghi_chu' => $ghi_chu
                        ]);
                    }
                }
            }
        }
    }

    function Timkiem()
    {
        // Lấy các tham số tìm kiếm từ biểu mẫu
        $ma_khuyen_mai = $_POST['txtMakhuyenmai'] ?? '';
        $ten_khuyen_mai = $_POST['txtTenkhuyenmai'] ?? '';

        // 👉 LẤY DỮ LIỆU THEO MÃ KHUYẾN MÃI + TÊN KHUYẾN MÃI
        $result = $this->km->Khuyenmai_find($ma_khuyen_mai, $ten_khuyen_mai);

        // ====== XUẤT EXCEL ======
        if (isset($_POST['btnXuatexcel'])) {

            $objExcel = new PHPExcel();
            $objExcel->setActiveSheetIndex(0);
            $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachKhuyenMai');

            // Header tương ứng với ảnh CSDL
            $sheet->setCellValue('A1', 'Mã Khuyến Mãi');
            $sheet->setCellValue('B1', 'Tên Khuyến Mãi');
            $sheet->setCellValue('C1', 'Tiền Khuyến Mãi');
            $sheet->setCellValue('D1', 'Ghi Chú');

            $rowCount = 2; // Starting from row 2 since row 1 is headers
            mysqli_data_seek($result, 0); // Reset result pointer to beginning
            while ($row = mysqli_fetch_assoc($result)) {
                // Mapping field according to database table
                $sheet->setCellValue('A' . $rowCount, $row['ma_khuyen_mai']);
                $sheet->setCellValue('B' . $rowCount, $row['ten_khuyen_mai']);
                $sheet->setCellValue('C' . $rowCount, $row['tien_khuyen_mai']);
                $sheet->setCellValue('D' . $rowCount, $row['ghi_chu']);
                $rowCount++;
            }

            foreach (range('A', 'D') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            if (ob_get_length()) ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="DanhSachKhuyenMai.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }

        // ====== DISPLAY VIEW ======
        $this->view('Master', [
            'page' => 'Danhsachkhuyenmai_v',
            'ma_khuyen_mai' => $ma_khuyen_mai, // Consistent with view variable name
            'ten_khuyen_mai' => $ten_khuyen_mai, // Consistent with view variable name
            'dulieu' => $result
        ]);
    }



    function sua($ma_khuyen_mai)
    {
        $result = $this->km->Khuyenmai_find($ma_khuyen_mai, '');
        $row = mysqli_fetch_array($result);

        $this->view('Master', [
            'page' => 'Khuyenmai_sua',
            'ma_khuyen_mai' => $row['ma_khuyen_mai'],
            'ten_khuyen_mai' => $row['ten_khuyen_mai'],
            'tien_khuyen_mai' => $row['tien_khuyen_mai'],
            'ghi_chu' => $row['ghi_chu']
        ]);
    }

    function update()
    {
        if (isset($_POST['btnCapnhat'])) {
            $ma_khuyen_mai = $_POST['txtMakhuyenmai'];
            $ten_khuyen_mai = $_POST['txtTenkhuyenmai'];
            $tien_khuyen_mai = $_POST['txtTienkhuyenmai'];
            $ghi_chu = $_POST['txtGhichu'];

            $kq = $this->km->Khuyenmai_update($ma_khuyen_mai, $ten_khuyen_mai, $tien_khuyen_mai, $ghi_chu);
            if ($kq)
                echo "<script>alert('Cập nhật thành công!'); window.location='" . $this->url('Khuyenmai/danhsach') . "';</script>";
            else
                echo "<script>alert('Cập nhật thất bại!');</script>";

            // Nếu cập nhật thất bại, gọi lại view sửa để người dùng thử lại
            if (!$kq) {
                $this->sua($ma_khuyen_mai);
            }
        }
    }

    function xoa($ma_khuyen_mai)
    {
        $kq = $this->km->Khuyenmai_delete($ma_khuyen_mai);
        if ($kq)
            echo "<script>alert('Xóa thành công!'); window.location='" . $this->url('Khuyenmai/danhsach') . "';</script>"; // Chuyển về trang danh sách
        else
            echo "<script>alert('Xóa thất bại!'); window.location='" . $this->url('Khuyenmai/danhsach') . "';</script>"; // Quay lại trang danh sách
    }



    // Hiển thị form nhập Excel
    function import_form()
    {
        $this->view('Master', [
            'page' => 'Khuyenmai_up_v'
        ]);
    }

    function up_l()
    {
        if (!isset($_FILES['txtfile']) || $_FILES['txtfile']['error'] != 0) {
            echo "<script>alert('Upload file lỗi')</script>";
            return;
        }

        $file = $_FILES['txtfile']['tmp_name'];

        $objReader = PHPExcel_IOFactory::createReaderForFile($file);
        $objExcel  = $objReader->load($file);

        $sheet     = $objExcel->getSheet(0);
        $sheetData = $sheet->toArray(null, true, true, true);

        for ($i = 2; $i <= count($sheetData); $i++) {

            $ma_khuyen_mai = trim($sheetData[$i]['A']);
            $ten_khuyen_mai = trim($sheetData[$i]['B']);
            $tien_khuyen_mai = trim($sheetData[$i]['C']);
            $ghi_chu = trim($sheetData[$i]['D']);

            if ($ma_khuyen_mai == '') continue;

            // ✅ CHECK TRÙNG MÃ
            if ($this->km->checktrungMaKhuyenMai($ma_khuyen_mai)) {
                echo "<script>
                    alert('Mã khuyến mãi $ma_khuyen_mai đã tồn tại! Vui lòng kiểm tra lại file.');
                    window.location.href='" . $this->url('Khuyenmai/import_form') . "';
                </script>";
                return;
            }

            // Insert
            if (!$this->km->khuyenmai_ins($ma_khuyen_mai, $ten_khuyen_mai, $tien_khuyen_mai, $ghi_chu)) {
                die(mysqli_error($this->km->con));
            }
        }

        echo "<script>alert('Upload khuyến mãi thành công!')</script>";
        $this->view('Master', ['page' => 'Khuyenmai_up_v']);
    }
}
