<?php
    class Danhmuc_m extends connectDB{
        // Thêm danh mục
        function danhmuc_ins($ma_danh_muc, $ten_danh_muc, $image){
            $sql = "INSERT INTO danh_muc (ma_danh_muc, ten_danh_muc, image) VALUES ('$ma_danh_muc', '$ten_danh_muc', '$image')";
            return mysqli_query($this->con, $sql);
        }

        // Kiểm tra trùng mã danh mục
        function checktrungMaDanhmuc($ma_danh_muc){
            $sql = "SELECT * FROM danh_muc WHERE ma_danh_muc = '$ma_danh_muc'";
            $result = mysqli_query($this->con, $sql);
            return (mysqli_num_rows($result) > 0);
        }

        // Tìm kiếm danh mục
        function Danhmuc_find($ma_danh_muc, $ten_danh_muc){
            $sql = "SELECT * FROM danh_muc WHERE ma_danh_muc LIKE '%$ma_danh_muc%' AND ten_danh_muc LIKE '%$ten_danh_muc%' ORDER BY LENGTH(ma_danh_muc), ma_danh_muc";
            return mysqli_query($this->con, $sql);
        }

        // Cập nhật danh mục
        function Danhmuc_update($ma_danh_muc, $ten_danh_muc, $image){
            $sql = "UPDATE danh_muc SET ten_danh_muc = '$ten_danh_muc', image = '$image' WHERE ma_danh_muc = '$ma_danh_muc'";
            return mysqli_query($this->con, $sql);
        }

        // Xóa danh mục
        function Danhmuc_delete($ma_danh_muc){
            $sql = "DELETE FROM danh_muc WHERE ma_danh_muc = '$ma_danh_muc'";
            return mysqli_query($this->con, $sql);
        }

        // Lấy tất cả danh mục
        function Danhmuc_getAll(){
            $sql = "SELECT * FROM danh_muc ORDER BY LENGTH(ma_danh_muc), ma_danh_muc";
            return mysqli_query($this->con, $sql);
        }

        // Lấy danh mục theo id
        function Danhmuc_getById($ma_danh_muc){
            $sql = "SELECT * FROM danh_muc WHERE ma_danh_muc = '$ma_danh_muc'";
            return mysqli_query($this->con, $sql);
        }
    }
?>