<?php 
    class Sanpham_m extends connectDB{
        // Hàm thêm sản phẩm
        function sanpham_ins($masp, $tensp, $gia, $soluong, $mancc){
            $sql = "INSERT INTO sanpham2 VALUES ('$masp', '$tensp', '$gia', '$soluong', '$mancc')";
            return mysqli_query($this->con, $sql);
        }
        
        // Hàm kiểm tra trùng mã sản phẩm
        function checktrungMaSP($masp){
            $sql = "SELECT * FROM sanpham2 WHERE masp = '$masp'";
            $result = mysqli_query($this->con, $sql);
            if(mysqli_num_rows($result) > 0)
                return true; // Trùng mã sản phẩm
            else
                return false; // Không trùng mã sản phẩm
        }
        
        // Hàm tìm kiếm sản phẩm (kèm tên nhà cung cấp)
        function Sanpham_find($masp, $tensp){
            $sql = "SELECT s.*, n.tenncc FROM sanpham2 s
                    LEFT JOIN nhacungcap n ON s.mancc = n.mancc
                    WHERE s.masp LIKE '%$masp%' AND s.tensp LIKE '%$tensp%'
                    ORDER BY LENGTH(s.masp), s.masp";
            return mysqli_query($this->con, $sql);
        }
        
        // Hàm sửa sản phẩm
        function Sanpham_update($masp, $tensp, $gia, $soluong, $mancc){
            $sql = "UPDATE sanpham2 SET tensp = '$tensp', gia = '$gia', 
            soluong = '$soluong', mancc = '$mancc' WHERE masp = '$masp'";
            return mysqli_query($this->con, $sql);
        }
        
        // Hàm xóa sản phẩm
        function Sanpham_delete($masp){
            $sql = "DELETE FROM sanpham2 WHERE masp = '$masp'";
            return mysqli_query($this->con, $sql);
        }
        
        // Hàm lấy tất cả sản phẩm với thông tin nhà cung cấp
        function Sanpham_getAll(){
            $sql = "SELECT s.*, n.tenncc FROM sanpham2 s
                    LEFT JOIN nhacungcap n ON s.mancc = n.mancc
                    ORDER BY LENGTH(s.masp), s.masp";
            return mysqli_query($this->con, $sql);
        }
        
        // Hàm lấy chi tiết sản phẩm
        function Sanpham_getById($masp){
            $sql = "SELECT s.*, n.tenncc FROM sanpham2 s
                    LEFT JOIN nhacungcap n ON s.mancc = n.mancc
                    WHERE s.masp = '$masp'
                    ORDER BY LENGTH(s.masp), s.masp";
            return mysqli_query($this->con, $sql);
        }
    }
?>