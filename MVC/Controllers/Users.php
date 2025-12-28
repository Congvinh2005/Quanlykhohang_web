<?php
    class Users extends controller{
        private $user;

        function __construct()
        {
            $this->user = $this->model("Users_m");
        }

        function index(){
            $this->danhsach();
        }

        function Get_data(){
            $this->danhsach();
        }

        function danhsach(){
            $result = $this->user->Users_getAll();
            $this->view('Master',[
                'page' => 'Danhsachusers_v',
                'ma_user' => '',
                'ten_user' => '',
                'dulieu' => $result
            ]);
        }


        function themmoi(){
            $this->view('Master',[
                'page' => 'Users_v',
                'ma_user' => '',
                'ten_user' => '',
                'password' => '',
                'email' => '',
                'phan_quyen' => 'nhan_vien'
            ]);
        }

        function ins()
    {
        if (isset($_POST['btnLuu'])) {
            $ma_user = $_POST['txtMauser'];
            $ten_user = $_POST['txtTenuser'];
            $password = $_POST['txtPassword'];
            $email = $_POST['txtEmail'];
            $phan_quyen = $_POST['ddlPhanquyen'];

            if ($ma_user == '') {
                echo "<script>alert('Mã user không được rỗng!')</script>";
            } else {
                $kq1 = $this->user->checktrungMaUser($ma_user);
                $checkEmail = $this->user->checktrungEmail($email, null);
                if ($kq1) {
                    echo "<script>alert('Mã user đã tồn tại!')</script>";
                    $this->view('Master', [
                        'page' => 'Users_v',
                        'ma_user' => $ma_user,
                        'ten_user' => $ten_user,
                        'password' => $password,
                        'email' => $email,
                        'phan_quyen' => $phan_quyen
                    ]);
                } else if (mysqli_num_rows($checkEmail) > 0) {
                    echo "<script>alert('Email đã được sử dụng!')</script>";
                    $this->view('Master', [
                        'page' => 'Users_v',
                        'ma_user' => $ma_user,
                        'ten_user' => $ten_user,
                        'password' => $password,
                        'email' => '',
                        'phan_quyen' => $phan_quyen
                    ]);
                    return;
                } else {
                    $kq = $this->user->users_ins($ma_user, $ten_user, $password, $email, $phan_quyen);
                    if ($kq) {
                        echo "<script>alert('Thêm mới thành công!')</script>";
                        $this->danhsach();
                    } else {
                        echo "<script>alert('Thêm mới thất bại!')</script>";
                        $this->view('Master', [
                            'page' => 'Users_v',
                            'ma_user' => $ma_user,
                            'ten_user' => $ten_user,
                            'password' => $password,
                            'email' => $email,
                            'phan_quyen' => $phan_quyen
                        ]);
                    }
                }
            }
        }
    }

        function tim(){
            if(isset($_POST['btnTim'])){
                $ma_user = $_POST['txtMauser'];
                $ten_user = $_POST['txtTenuser'];
                $result = $this->user->Users_find($ma_user, $ten_user);
                $this->view('Master',[
                    'page' => 'Danhsachusers_v',
                    'ma_user' => $ma_user,
                    'ten_user' => $ten_user,
                    'dulieu' => $result
                ]);
            }
        }

        function tim_ajax(){
            header('Content-Type: application/json; charset=utf-8');
            $ma_user = isset($_POST['q_mauser']) ? $_POST['q_mauser'] : '';
            $ten_user = isset($_POST['q_tenuser']) ? $_POST['q_tenuser'] : '';
            $result = $this->user->Users_find($ma_user, $ten_user);
            $rows = [];
            if($result){
                while($r = mysqli_fetch_assoc($result)){
                    $rows[] = [
                        'ma_user' => $r['ma_user'],
                        'ten_user' => $r['ten_user'],
                        'email' => $r['email'],
                        'phan_quyen' => $r['phan_quyen']
                    ];
                }
            }
            echo json_encode(['data' => $rows]);
            exit;
        }

        function sua($ma_user){
            $result = $this->user->Users_getById($ma_user);
            $row = mysqli_fetch_array($result);
            $this->view('Master',[
                'page' => 'Users_sua',
                'ma_user' => $row['ma_user'],
                'ten_user' => $row['ten_user'],
                'password' => $row['password'],
                'email' => $row['email'],
                'phan_quyen' => $row['phan_quyen']
            ]);
        }

       function update()
    {
        if (isset($_POST['btnCapnhat'])) {
            $ma_user = $_POST['txtMauser'];
            $ten_user = $_POST['txtTenuser'];
            $password = $_POST['txtPassword'];
            $email = $_POST['txtEmail'];
            $phan_quyen = $_POST['ddlPhanquyen'];

            $check = $this->user->checktrungEmail($email, $ma_user);
            if (mysqli_num_rows($check) > 0) {
                echo "<script>alert('Email đã được sử dụng bởi tài khoản khác!');history.back();</script>";
                return;
            }

            $kq = $this->user->Users_update($ma_user, $ten_user, $password, $email, $phan_quyen);
            if ($kq)
                echo "<script>alert('Cập nhật thành công!'); window.location='http://localhost/QLSP/Users/danhsach';</script>";
            else
                echo "<script>alert('Cập nhật thất bại!')</script>";
        }
    }

        function xoa($ma_user){
            $kq = $this->user->Users_delete($ma_user);
            if($kq)
                echo "<script>alert('Xóa thành công!'); window.location='http://localhost/QLSP/Users/danhsach';</script>";
            else
                echo "<script>alert('Xóa thất bại!'); window.location='http://localhost/QLSP/Users/danhsach';</script>";
        }

        // Xuất Excel danh sách users
        function export(){
            $data = $this->user->Users_getAll();
            $excel = new PHPExcel();
            $excel->getProperties()->setCreator("QLSP")->setTitle("Danh sách người dùng");
            $sheet = $excel->setActiveSheetIndex(0);
            $sheet->setTitle('Users');
            // Header
            $sheet->setCellValue('A1','Mã User');
            $sheet->setCellValue('B1','Tên');
            $sheet->setCellValue('C1','Email');
            $sheet->setCellValue('D1','Quyền');
            // Rows
            $rowIndex = 2;
            while($r = mysqli_fetch_array($data)){
                $sheet->setCellValue('A'.$rowIndex,$r['ma_user']);
                $sheet->setCellValue('B'.$rowIndex,$r['ten_user']);
                $sheet->setCellValue('C'.$rowIndex,$r['email']);
                $sheet->setCellValue('D'.$rowIndex,$r['phan_quyen']);
                $rowIndex++;
            }
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="users.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }

        // Hiển thị form nhập Excel
        function import_form(){
            $this->view('Master',[
                'page' => 'Users_up_v'
            ]);
        }

        //     // Xử lý nhập Excel
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

        //         $ma_user    = trim($sheetData[$i]['A']);
        //         $ten_user   = trim($sheetData[$i]['B']);
        //         $password   = trim($sheetData[$i]['C']);
        //         $email      = trim($sheetData[$i]['D']);
        //         $phan_quyen = trim($sheetData[$i]['E']);
        //         // $ngaytao    = trim($sheetData[$i]['F']);

        //         if($ma_user == '') continue;

        //         // ✅ CHECK TRÙNG MÃ USER
        //         if($this->user->checktrungMaUser($ma_user)){
        //             echo "<script>
        //                 alert('Mã user $ma_user đã tồn tại! Vui lòng kiểm tra lại file.');
        //                 window.location.href='http://localhost/QLSP/Users/import_form';
        //             </script>";
        //             return;
        //         }

        //         // Insert
        //         if(!$this->user->users_ins($ma_user,$ten_user,$password,$email,$phan_quyen)){
        //             die(mysqli_error($this->user->con));
        //         }
        //     }

        //     echo "<script>alert('Upload người dùng thành công!')</script>";
        //     $this->view('Master',['page'=>'Users_up_v']);
        // }

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

        $ma_user    = trim($sheetData[$i]['A']);
        $ten_user   = trim($sheetData[$i]['B']);
        $password   = trim($sheetData[$i]['C']);
        $email      = trim($sheetData[$i]['D']);
        $phan_quyen = trim($sheetData[$i]['E']);

        if($ma_user == '') continue;

        // ✅ CHECK GIÁ TRỊ PHÂN QUYỀN
        if($phan_quyen != 'admin' && $phan_quyen != 'nhan_vien'){
            echo "<script>
                alert('Phân quyền không hợp lệ cho user $ma_user! Chỉ cho phép admin hoặc nhan_vien.');
                window.location.href='http://localhost/QLSP/Users/import_form';
            </script>";
            return;
        }

        // ✅ CHECK TRÙNG MÃ USER
        if($this->user->checktrungMaUser($ma_user)){
            echo "<script>
                alert('Mã user $ma_user đã tồn tại! Vui lòng kiểm tra lại file.');
                window.location.href='http://localhost/QLSP/Users/import_form';
            </script>";
            return;
        }

        // Insert
        if(!$this->user->users_ins($ma_user,$ten_user,$password,$email,$phan_quyen)){
            die(mysqli_error($this->user->con));
        }
    }

    echo "<script>alert('Upload người dùng thành công!')</script>";
    $this->view('Master',['page'=>'Users_up_v']);
}


        // Tải mẫu Excel (chỉ header)
        function template(){
            $excel = new PHPExcel();
            $sheet = $excel->setActiveSheetIndex(0);
            $sheet->setTitle('Users');
            $sheet->setCellValue('A1','Mã User');
            $sheet->setCellValue('B1','Tên');
            $sheet->setCellValue('C1','Email');
            $sheet->setCellValue('D1','Quyền');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="mau_users.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }
    }
?>