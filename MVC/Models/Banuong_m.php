<?php
    class Banuong_m extends connectDB{
        // Thêm bàn uống
        function banuong_ins($ma_ban, $ten_ban, $so_cho_ngoi, $trang_thai_ban){
            $sql = "INSERT INTO ban_uong (ma_ban, ten_ban, so_cho_ngoi, trang_thai_ban) VALUES ('$ma_ban', '$ten_ban', '$so_cho_ngoi', '$trang_thai_ban')";
            return mysqli_query($this->con, $sql);
        }

        // Kiểm tra trùng mã bàn
        function checktrungMaBan($ma_ban){
            $sql = "SELECT * FROM ban_uong WHERE ma_ban = '$ma_ban'";
            $result = mysqli_query($this->con, $sql);
            return (mysqli_num_rows($result) > 0);
        }

        // Tìm kiếm bàn uống
        function Banuong_find($ma_ban, $ten_ban, $so_cho_ngoi){
            $sql = "SELECT * FROM ban_uong WHERE ma_ban LIKE '%$ma_ban%' AND ten_ban LIKE '%$ten_ban%' AND so_cho_ngoi LIKE '%$so_cho_ngoi%' ORDER BY LENGTH(ma_ban), ma_ban";
            return mysqli_query($this->con, $sql);
        }

        // Cập nhật bàn uống
        function Banuong_update($ma_ban, $ten_ban, $so_cho_ngoi, $trang_thai_ban){
            $sql = "UPDATE ban_uong SET ten_ban = '$ten_ban', so_cho_ngoi = '$so_cho_ngoi', trang_thai_ban = '$trang_thai_ban' WHERE ma_ban = '$ma_ban'";
            return mysqli_query($this->con, $sql);
        }

        // Xóa bàn uống
        function Banuong_delete($ma_ban){
            $sql = "DELETE FROM ban_uong WHERE ma_ban = '$ma_ban'";
            return mysqli_query($this->con, $sql);
        }

        // Lấy tất cả bàn uống
        function Banuong_getAll(){
            $sql = "SELECT * FROM ban_uong ORDER BY LENGTH(ma_ban), ma_ban";
            return mysqli_query($this->con, $sql);
        }

        // Lấy bàn uống theo id
        function Banuong_getById($ma_ban){
            $sql = "SELECT * FROM ban_uong WHERE ma_ban = '$ma_ban'";
            return mysqli_query($this->con, $sql);
        }

        // Lấy các bàn đang hoạt động
        function getActiveTables(){
            $sql = "SELECT * FROM ban_uong WHERE trang_thai_ban = 'dang_su_dung'";
            return mysqli_query($this->con, $sql);
        }
    }
?>