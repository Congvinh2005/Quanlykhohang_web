<?php
class Thucdon_m extends connectDB
{
    function thucdon_ins($ma_thuc_don, $ten_mon, $img_thuc_don, $gia, $so_luong, $ma_danh_muc)
    {
        $sql = "INSERT INTO thuc_don (ma_thuc_don, ten_mon, img_thuc_don, gia, so_luong, ma_danh_muc) VALUES ('$ma_thuc_don', '$ten_mon', '$img_thuc_don', '$gia', '$so_luong', '$ma_danh_muc')";
        return mysqli_query($this->con, $sql);
    }

    // Hàm kiểm tra trùng mã thực đơn
    function checktrungMaThucdon($ma_thuc_don)
    {
        $sql = "SELECT * FROM thuc_don WHERE ma_thuc_don = '$ma_thuc_don'";
        $result = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($result) > 0)
            return true; // Trùng mã thực đơn
        else
            return false; // Không trùng mã thực đơn
    }

    // Hàm tìm kiếm thực đơn (kèm tên danh mục)
    function Thucdon_find($ma_thuc_don, $ten_mon)
    {
        $sql = "SELECT t.*, d.ten_danh_muc FROM thuc_don t
                    LEFT JOIN danh_muc d ON t.ma_danh_muc = d.ma_danh_muc
                    WHERE t.ma_thuc_don LIKE '%$ma_thuc_don%' AND t.ten_mon LIKE '%$ten_mon%'
                    ORDER BY LENGTH(t.ma_thuc_don), t.ma_thuc_don";
        return mysqli_query($this->con, $sql);
    }

    // Hàm sửa thực đơn
    function Thucdon_update($ma_thuc_don, $ten_mon, $gia, $so_luong, $ma_danh_muc, $img_thuc_don)
    {
        $sql = "UPDATE thuc_don SET ten_mon = '$ten_mon', gia = '$gia',
            so_luong = '$so_luong', ma_danh_muc = '$ma_danh_muc', img_thuc_don = '$img_thuc_don' WHERE ma_thuc_don = '$ma_thuc_don'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm xóa thực đơn
    function Thucdon_delete($ma_thuc_don)
    {
        $sql = "DELETE FROM thuc_don WHERE ma_thuc_don = '$ma_thuc_don'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm kiểm tra xem món ăn có đang trong đơn hàng chưa thanh toán nào không
    function isMenuItemInActiveOrders($ma_thuc_don)
    {
        // Kiểm tra xem món ăn có trong bất kỳ chi tiết đơn hàng nào của đơn hàng chưa thanh toán không
        $sql = "SELECT COUNT(*) as count FROM chi_tiet_don_hang c
                INNER JOIN don_hang d ON c.ma_don_hang = d.ma_don_hang
                WHERE c.ma_thuc_don = '$ma_thuc_don' AND d.trang_thai_thanh_toan = 'chua_thanh_toan'";
        $result = mysqli_query($this->con, $sql);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['count'] > 0;
        }

        return false;
    }

    // Hàm lấy tất cả thực đơn với thông tin danh mục
    function Thucdon_getAll()
    {
        $sql = "SELECT t.*, d.ten_danh_muc FROM thuc_don t
                    LEFT JOIN danh_muc d ON t.ma_danh_muc = d.ma_danh_muc
                    ORDER BY LENGTH(t.ma_thuc_don), t.ma_thuc_don";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy chi tiết thực đơn
    function Thucdon_getById($ma_thuc_don)
    {
        $sql = "SELECT t.*, d.ten_danh_muc FROM thuc_don t
                    LEFT JOIN danh_muc d ON t.ma_danh_muc = d.ma_danh_muc
                    WHERE t.ma_thuc_don = '$ma_thuc_don'
                    ORDER BY LENGTH(t.ma_thuc_don), t.ma_thuc_don";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy tất cả thực đơn có sẵn (không hết bán) với thông tin danh mục
    function Thucdon_getAvailable()
    {
        $sql = "SELECT t.*, d.ten_danh_muc FROM thuc_don t
                    LEFT JOIN danh_muc d ON t.ma_danh_muc = d.ma_danh_muc
                    WHERE t.so_luong > 0
                    ORDER BY LENGTH(t.ma_thuc_don), t.ma_thuc_don";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy tất cả thực đơn (bao gồm cả những món có số lượng = 0) với thông tin danh mục
    function Thucdon_getAllWithQuantity()
    {
        $sql = "SELECT t.*, d.ten_danh_muc FROM thuc_don t
                    LEFT JOIN danh_muc d ON t.ma_danh_muc = d.ma_danh_muc
                    ORDER BY LENGTH(t.ma_thuc_don), t.ma_thuc_don";
        return mysqli_query($this->con, $sql);
    }
}
