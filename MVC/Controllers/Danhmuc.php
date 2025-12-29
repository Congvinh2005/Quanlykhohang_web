<?php
    class Danhmuc extends controller{
        private $dm;

        function __construct()
        {
            $this->dm = $this->model("Danhmuc_m");
        }

        function index(){
            $this->danhsach();
        }

        function Get_data(){
            $this->danhsach();
        }

        function danhsach(){
            $result = $this->dm->Danhmuc_find('', '');

            $this->view('Master',[
                'page' => 'Danhsachdanhmuc_v',
                'ma_danh_muc' => '',
                'ten_danh_muc' => '',
                'dulieu' => $result
            ]);
        }


        function themmoi(){
             $this->view('Master',[
                'page' => 'Danhmuc_v',
                'ma_danh_muc' => '',
                'ten_danh_muc' => '',
                'image' => ''
            ]);
        }

        function ins(){
            if(isset($_POST['btnLuu'])){
                $ma_danh_muc = $_POST['txtMadanhmuc'];
                $ten_danh_muc = $_POST['txtTendanhmuc'];

                // Xử lý upload hình ảnh
                $image = '';
                if(isset($_FILES['txtImage']) && $_FILES['txtImage']['error'] == 0) {
                    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $filename = $_FILES['txtImage']['name'];
                    $filetmp = $_FILES['txtImage']['tmp_name'];
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                    if(in_array($ext, $allowed)) {
                        $new_filename = 'dm_' . $ma_danh_muc . '_' . time() . '.' . $ext;
                        $upload_path = $_SERVER['DOCUMENT_ROOT'] . '/qlsp/Public/Pictures/danhmuc/' . $new_filename;

                        if(move_uploaded_file($filetmp, $upload_path)) {
                            $image = '/qlsp/Public/Pictures/danhmuc/' . $new_filename;
                        } else {
                            echo "<script>alert('Upload hình ảnh thất bại!');</script>";
                            $this->view('Master',[
                                'page' => 'Danhmuc_v',
                                'ma_danh_muc' => $ma_danh_muc,
                                'ten_danh_muc' => $ten_danh_muc,
                                'image' => ''
                            ]);
                            return;
                        }
                    } else {
                        echo "<script>alert('Định dạng hình ảnh không hợp lệ!');</script>";
                        $this->view('Master',[
                            'page' => 'Danhmuc_v',
                            'ma_danh_muc' => $ma_danh_muc,
                            'ten_danh_muc' => $ten_danh_muc,
                            'image' => ''
                        ]);
                        return;
                    }
                } else {
                    // Nếu không có file upload mới, sử dụng giá trị từ form (trường text)
                    $image = $_POST['txtImage'];
                }

                // Kiểm tra dữ liệu rỗng
                if($ma_danh_muc == ''){
                    echo "<script>alert('Mã danh mục không được rỗng!')</script>";
                    $this->themmoi();
                } else {
                    // Kiểm tra trùng mã danh mục
                    $kq1 = $this->dm->checktrungMaDanhmuc($ma_danh_muc);
                    if($kq1){
                        echo "<script>alert('Mã danh mục đã tồn tại! Vui lòng nhập mã khác.')</script>";
                         $this->view('Master',[
                            'page' => 'Danhmuc_v',
                            'ma_danh_muc' => $ma_danh_muc,
                            'ten_danh_muc' => $ten_danh_muc,
                            'image' => ''
                        ]);
                    } else {
                        $kq = $this->dm->danhmuc_ins($ma_danh_muc, $ten_danh_muc, $image);
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
        $ma_danh_muc = $_POST['txtMadanhmuc'] ?? '';
        $ten_danh_muc = $_POST['txtTendanhmuc'] ?? '';

        // 👉 LẤY DỮ LIỆU THEO MÃ DANH MỤC + TÊN DANH MỤC
        $result = $this->dm->Danhmuc_find($ma_danh_muc, $ten_danh_muc);
        // ====== XUẤT EXCEL ======
        if (isset($_POST['btnXuatexcel'])) {

            $objExcel = new PHPExcel();
            $objExcel->setActiveSheetIndex(0);
            $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachDanhmuc');

            // Header tương ứng với ảnh CSDL
            $sheet->setCellValue('A1', 'Mã Danh Mục');
            $sheet->setCellValue('B1', 'Tên Danh Mục');
            $sheet->setCellValue('C1', 'Hình Ảnh');
            $sheet->setCellValue('D1', 'Ngày Tạo');


            $rowCount = 2; // Starting from row 2 since row 1 is headers
            mysqli_data_seek($result, 0); // Reset result pointer to beginning
            while ($row = mysqli_fetch_assoc($result)) {
                // Mapping field according to database table
                $sheet->setCellValue('A'.$rowCount, $row['ma_danh_muc']);
                $sheet->setCellValue('B'.$rowCount, $row['ten_danh_muc']);
                $sheet->setCellValue('C'.$rowCount, $row['image']);
                $sheet->setCellValue('D'.$rowCount, $row['ngay_tao']);
                $rowCount++;
            }

            foreach (range('A','D') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            if (ob_get_length()) ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="DanhSachDanhmuc.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }

        // ====== DISPLAY VIEW ======
        $this->view('Master', [
            'page' => 'Danhsachdanhmuc_v',
            'ma_danh_muc' => $ma_danh_muc, // Consistent with view variable name
            'ten_danh_muc' => $ten_danh_muc, // Consistent with view variable name
            'dulieu' => $result
        ]);
    }

        // AJAX search (JSON)
        function tim_ajax(){
            header('Content-Type: application/json; charset=utf-8');
            $ma_danh_muc = isset($_POST['q_madanhmuc']) ? $_POST['q_madanhmuc'] : '';
            $ten_danh_muc = isset($_POST['q_tendanhmuc']) ? $_POST['q_tendanhmuc'] : '';
            $result = $this->dm->Danhmuc_find($ma_danh_muc, $ten_danh_muc);
            $rows = [];
            if($result){
                while($r = mysqli_fetch_assoc($result)){
                    $rows[] = [
                        'ma_danh_muc' => $r['ma_danh_muc'],
                        'ten_danh_muc' => $r['ten_danh_muc'],
                        'image' => $r['image']
                    ];
                }
            }
            echo json_encode(['data' => $rows]);
            exit;
        }

        function sua($ma_danh_muc){
            $result = $this->dm->Danhmuc_find($ma_danh_muc, '');
            $row = mysqli_fetch_array($result);

            $this->view('Master',[
                'page' => 'Danhmuc_sua',
                'ma_danh_muc' => $row['ma_danh_muc'],
                'ten_danh_muc' => $row['ten_danh_muc'],
                'image' => $row['image']
            ]);
        }

        function update(){
            if(isset($_POST['btnCapnhat'])){
                $ma_danh_muc = $_POST['txtMadanhmuc'];
                $ten_danh_muc = $_POST['txtTendanhmuc'];

                // Lấy hình ảnh hiện tại từ database trước
                $current_record = $this->dm->Danhmuc_getById($ma_danh_muc);
                $current_row = mysqli_fetch_array($current_record);
                $image = $current_row['image']; // Giữ hình ảnh hiện tại mặc định

                // Xử lý upload hình ảnh mới (nếu có)
                if(isset($_FILES['txtImage']) && $_FILES['txtImage']['error'] == 0) {
                    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $filename = $_FILES['txtImage']['name'];
                    $filetmp = $_FILES['txtImage']['tmp_name'];
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                    if(in_array($ext, $allowed)) {
                        $new_filename = 'dm_' . $ma_danh_muc . '_' . time() . '.' . $ext;
                        $upload_path = $_SERVER['DOCUMENT_ROOT'] . '/qlsp/Public/Pictures/danhmuc/' . $new_filename;

                        if(move_uploaded_file($filetmp, $upload_path)) {
                            // Xóa hình ảnh cũ nếu tồn tại
                            $old_image_path = $_SERVER['DOCUMENT_ROOT'] . $current_row['image'];
                            if(!empty($current_row['image']) && file_exists($old_image_path) && strpos($old_image_path, '/Public/Pictures/danhmuc/') !== false) {
                                unlink($old_image_path);
                            }

                            $image = '/qlsp/Public/Pictures/danhmuc/' . $new_filename;
                        } else {
                            echo "<script>alert('Upload hình ảnh thất bại!');</script>";
                            $this->sua($ma_danh_muc);
                            return;
                        }
                    } else {
                        echo "<script>alert('Định dạng hình ảnh không hợp lệ!');</script>";
                        $this->sua($ma_danh_muc);
                        return;
                    }
                }
                // Nếu không có file upload mới, giữ nguyên hình ảnh hiện tại (đã được lấy ở trên)

                $kq = $this->dm->Danhmuc_update($ma_danh_muc, $ten_danh_muc, $image);
                if($kq)
                    echo "<script>alert('Cập nhật thành công!'); window.location='http://localhost/QLSP/Danhmuc/danhsach';</script>";
                else
                    echo "<script>alert('Cập nhật thất bại!');</script>";

                // Nếu cập nhật thất bại, gọi lại view sửa để người dùng thử lại
                if(!$kq){
                    $this->sua($ma_danh_muc);
                }
            }
        }

        function xoa($ma_danh_muc){
            $kq = $this->dm->Danhmuc_delete($ma_danh_muc);
            if($kq)
                echo "<script>alert('Xóa thành công!'); window.location='http://localhost/QLSP/Danhmuc/danhsach';</script>"; // Chuyển về trang danh sách
            else
                echo "<script>alert('Xóa thất bại!'); window.location='http://localhost/QLSP/Danhmuc/danhsach';</script>"; // Quay lại trang danh sách
        }

        // Xuất Excel danh sách danh mục (theo tìm kiếm nếu có)
        function export(){
            // Get search parameters from URL or POST
            $ma_danh_muc = $_GET['ma_danh_muc'] ?? '';
            $ten_danh_muc = $_GET['ten_danh_muc'] ?? '';

            // Find data based on search parameters (if provided) or all records (if not)
            $data = $this->dm->Danhmuc_find($ma_danh_muc, $ten_danh_muc);
            $excel = new PHPExcel();
            $excel->getProperties()->setCreator("QLSP")->setTitle("Danh sách danh mục");
            $sheet = $excel->setActiveSheetIndex(0);
            $sheet->setTitle('Danhmuc');
            // Header
            $sheet->setCellValue('A1','Mã DM');
            $sheet->setCellValue('B1','Tên DM');
            $sheet->setCellValue('C1','Hình ảnh');
            // Rows
            $rowIndex = 2;
            while($r = mysqli_fetch_array($data)){
                $sheet->setCellValue('A'.$rowIndex,$r['ma_danh_muc']);
                $sheet->setCellValue('B'.$rowIndex,$r['ten_danh_muc']);
                $sheet->setCellValue('C'.$rowIndex,$r['image']);
                $rowIndex++;
            }
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="danhmuc.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }

        // Hiển thị form nhập Excel
        function import_form(){
            $this->view('Master',[
                'page' => 'Danhmuc_up_v'
            ]);
        }

        // Xử lý nhập Excel
        // function up_l(){
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

        //         $ma_danh_muc   = trim((string)$sheetData[$i]['A']);
        //         $ten_danh_muc = trim((string)$sheetData[$i]['B']);
        //         $image     = trim((string)$sheetData[$i]['C']);

        //         if($ma_danh_muc == '') continue;
        //         if(!$this->dm->Danhmuc_ins($ma_danh_muc,$ten_danh_muc,$image)){
        //             die(mysqli_error($this->dm->con));
        //         }
        //     }

        //     echo "<script>alert('Upload danh mục thành công!')</script>";
        //     $this->view('Master',['page'=>'Danhmuc_up_v']);
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

        $ma_danh_muc  = trim($sheetData[$i]['A']);
        $ten_danh_muc = trim($sheetData[$i]['B']);
        $image        = trim($sheetData[$i]['C']);

        if($ma_danh_muc == '') continue;

        // ✅ CHECK TRÙNG MÃ DANH MỤC
        if($this->dm->checktrungMaDanhmuc($ma_danh_muc)){
            echo "<script>
                alert('Mã danh mục $ma_danh_muc đã tồn tại! Vui lòng kiểm tra lại file.');
                window.location.href='http://localhost/QLSP/Danhmuc/import_form';
            </script>";
            return;
        }

        // Insert
        if(!$this->dm->Danhmuc_ins($ma_danh_muc,$ten_danh_muc,$image)){
            die(mysqli_error($this->dm->con));
        }
    }

    echo "<script>alert('Upload danh mục thành công!')</script>";
    $this->view('Master',['page'=>'Danhmuc_up_v']);
}

        // Tải mẫu Excel (chỉ header)
        function template(){
            $excel = new PHPExcel();
            $sheet = $excel->setActiveSheetIndex(0);
            $sheet->setTitle('Danhmuc');
            $sheet->setCellValue('A1','Mã DM');
            $sheet->setCellValue('B1','Tên DM');
            $sheet->setCellValue('C1','Hình ảnh');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="mau_danhmuc.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }
    }
?>