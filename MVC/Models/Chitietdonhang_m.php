<?php
    class Chitietdonhang_m extends connectDB{
        // Hàm lấy chi tiết đơn hàng theo mã đơn hàng (kèm thông tin món ăn)
        function Chitietdonhang_getByOrderId($ma_don_hang){
            $sql = "SELECT c.*, t.ten_mon, t.img_thuc_don
                    FROM chi_tiet_don_hang c
                    LEFT JOIN thuc_don t ON c.ma_thuc_don = t.ma_thuc_don
                    WHERE c.ma_don_hang = '$ma_don_hang'";
            $result = mysqli_query($this->con, $sql);

            $details = [];
            while($row = mysqli_fetch_assoc($result)) {
                $details[] = $row;
            }

            return $details;
        }
    }
?>