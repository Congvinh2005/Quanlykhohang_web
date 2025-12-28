<?php 
    class Users_m extends connectDB{
        // Thêm user
        function users_ins($ma_user, $ten_user, $password, $email, $phan_quyen){
            $sql = "INSERT INTO users (ma_user, ten_user, password, email, phan_quyen) VALUES ('$ma_user', '$ten_user', '$password', '$email', '$phan_quyen')";
            return mysqli_query($this->con, $sql);
        }

        // Kiểm tra trùng mã user
        function checktrungMaUser($ma_user){
            $sql = "SELECT * FROM users WHERE ma_user = '$ma_user'";
            $result = mysqli_query($this->con, $sql);
            return (mysqli_num_rows($result) > 0);
        }

        public function checktrungEmail($email, $ma_user)
    {
        $sql = "SELECT * FROM users 
            WHERE email = '$email' 
            AND ma_user != '$ma_user'";
        return mysqli_query($this->con, $sql);
    }
        // Tìm kiếm user
        function Users_find($ma_user, $ten_user){
            $sql = "SELECT * FROM users WHERE ma_user LIKE '%$ma_user%' AND ten_user LIKE '%$ten_user%' ORDER BY LENGTH(ma_user), ma_user";
            return mysqli_query($this->con, $sql);
        }

        // Cập nhật user
        function Users_update($ma_user, $ten_user, $password, $email, $phan_quyen){
            $sql = "UPDATE users SET ten_user = '$ten_user', password = '$password', email = '$email', phan_quyen = '$phan_quyen' WHERE ma_user = '$ma_user'";
            return mysqli_query($this->con, $sql);
        }

        // Xóa user
        function Users_delete($ma_user){
            $sql = "DELETE FROM users WHERE ma_user = '$ma_user'";
            return mysqli_query($this->con, $sql);
        }

        // Lấy tất cả user
        function Users_getAll(){
            $sql = "SELECT * FROM users ORDER BY LENGTH(ma_user), ma_user";
            return mysqli_query($this->con, $sql);
        }

        // Lấy user theo id
        function Users_getById($ma_user){
            $sql = "SELECT * FROM users WHERE ma_user = '$ma_user'";
            return mysqli_query($this->con, $sql);
        }
    }
?>