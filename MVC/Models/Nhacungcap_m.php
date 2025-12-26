<?php 
    class Nhacungcap_m extends connectDB{
        // Hàm thêm nhà cung cấp
        function nhacungcap_ins($mancc, $tenncc, $diachi, $dienthoai){
            $sql = "INSERT INTO nhacungcap VALUES ('$mancc', '$tenncc', '$diachi', '$dienthoai')";
            return mysqli_query($this->con, $sql);
        }
        
        // Hàm kiểm tra trùng mã nhà cung cấp
        function checktrungMaNCC($mancc){
            $sql = "SELECT * FROM nhacungcap WHERE mancc = '$mancc'";
            $result = mysqli_query($this->con, $sql);
            if(mysqli_num_rows($result) > 0)
                return true; // Trùng mã nhà cung cấp
            else
                return false; // Không trùng mã nhà cung cấp
        }
        
        // Hàm tìm kiếm nhà cung cấp
        function Nhacungcap_find($mancc, $tenncc){
            $sql = "SELECT * FROM nhacungcap WHERE mancc LIKE '%$mancc%' AND tenncc LIKE '%$tenncc%'";
            return mysqli_query($this->con, $sql);
        }
        
        // Hàm sửa nhà cung cấp
        function Nhacungcap_update($mancc, $tenncc, $diachi, $dienthoai){
            $sql = "UPDATE nhacungcap SET tenncc = '$tenncc', diachi = '$diachi', 
            dienthoai = '$dienthoai' WHERE mancc = '$mancc'";
            return mysqli_query($this->con, $sql);
        }
        
        // Hàm xóa nhà cung cấp
        function Nhacungcap_delete($mancc){
            $sql = "DELETE FROM nhacungcap WHERE mancc = '$mancc'";
            return mysqli_query($this->con, $sql);
        }
        
        // Hàm lấy tất cả nhà cung cấp
        function Nhacungcap_getAll(){
            $sql = "SELECT * FROM nhacungcap";
            return mysqli_query($this->con, $sql);
        }
    }
?>
