<?php
class Khuyenmai_m extends connectDB
{
    // Hàm thêm khuyến mãi
    function khuyenmai_ins($ma_khuyen_mai, $ten_khuyen_mai, $tien_khuyen_mai, $ghi_chu)
    {
        $sql = "INSERT INTO khuyen_mai (ma_khuyen_mai, ten_khuyen_mai, tien_khuyen_mai, ghi_chu) VALUES ('$ma_khuyen_mai', '$ten_khuyen_mai', '$tien_khuyen_mai', '$ghi_chu')";
        return mysqli_query($this->con, $sql);
    }

    // Hàm kiểm tra trùng mã khuyến mãi
    function checktrungMaKhuyenMai($ma_khuyen_mai)
    {
        $sql = "SELECT * FROM khuyen_mai WHERE ma_khuyen_mai = '$ma_khuyen_mai'";
        $result = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($result) > 0)
            return true; // Trùng mã khuyến mãi
        else
            return false; // Không trùng mã khuyến mãi
    }

    // Hàm tìm kiếm khuyến mãi
    function Khuyenmai_find($ma_khuyen_mai, $ten_khuyen_mai)
    {
        $sql = "SELECT * FROM khuyen_mai 
                    WHERE ma_khuyen_mai LIKE '%$ma_khuyen_mai%' AND ten_khuyen_mai LIKE '%$ten_khuyen_mai%'
                    ORDER BY LENGTH(ma_khuyen_mai), ma_khuyen_mai";
        return mysqli_query($this->con, $sql);
    }

    // Hàm sửa khuyến mãi
    function Khuyenmai_update($ma_khuyen_mai, $ten_khuyen_mai, $tien_khuyen_mai, $ghi_chu)
    {
        $sql = "UPDATE khuyen_mai SET ten_khuyen_mai = '$ten_khuyen_mai', tien_khuyen_mai = '$tien_khuyen_mai', ghi_chu = '$ghi_chu' WHERE ma_khuyen_mai = '$ma_khuyen_mai'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm xóa khuyến mãi
    function Khuyenmai_delete($ma_khuyen_mai)
    {
        $sql = "DELETE FROM khuyen_mai WHERE ma_khuyen_mai = '$ma_khuyen_mai'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy tất cả khuyến mãi
    function Khuyenmai_getAll()
    {
        $sql = "SELECT * FROM khuyen_mai ORDER BY LENGTH(ma_khuyen_mai), ma_khuyen_mai";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy chi tiết khuyến mãi
    function Khuyenmai_getById($ma_khuyen_mai)
    {
        $sql = "SELECT * FROM khuyen_mai WHERE ma_khuyen_mai = '$ma_khuyen_mai'";
        return mysqli_query($this->con, $sql);
    }
}
