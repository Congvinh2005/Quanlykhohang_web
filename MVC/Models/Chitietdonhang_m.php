<?php
class Chitietdonhang_m extends connectDB
{
    // Hàm lấy chi tiết đơn hàng theo mã đơn hàng (kèm thông tin món ăn)
    function Chitietdonhang_getByOrderId($ma_don_hang)
    {
        $sql = "SELECT c.*, t.ten_mon, t.img_thuc_don
                    FROM chi_tiet_don_hang c
                    LEFT JOIN thuc_don t ON c.ma_thuc_don = t.ma_thuc_don
                    WHERE c.ma_don_hang = '$ma_don_hang'";
        $result = mysqli_query($this->con, $sql);

        $details = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $details[] = $row;
        }

        return $details;
    }

    // Hàm thêm chi tiết đơn hàng với tự động tạo mã
    function Chitietdonhang_ins($ma_don_hang, $ma_thuc_don, $so_luong, $gia_tai_thoi_diem_dat, $ghi_chu = '')
    {
        // Tạo mã chi tiết đơn hàng tự động
        $ma_ctdh = $this->generateChiTietDonHangId();

        $sql = "INSERT INTO chi_tiet_don_hang (ma_ctdh, ma_don_hang, ma_thuc_don, so_luong, gia_tai_thoi_diem_dat, ghi_chu)
                    VALUES ('$ma_ctdh', '$ma_don_hang', '$ma_thuc_don', '$so_luong', '$gia_tai_thoi_diem_dat', '$ghi_chu')";
        return mysqli_query($this->con, $sql);
    }

    // Hàm tạo mã chi tiết đơn hàng tự động
    private function generateChiTietDonHangId()
    {
        $sql = "SELECT ma_ctdh FROM chi_tiet_don_hang ORDER BY CAST(SUBSTRING(ma_ctdh, 3) AS UNSIGNED) DESC LIMIT 1";
        $result = mysqli_query($this->con, $sql);

        $new_id = 'CT1'; // Default starting ID

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $last_id = $row['ma_ctdh'];

            // Extract the numeric part from the last ID
            preg_match('/^CT(\d+)$/', $last_id, $matches);

            if (isset($matches[1])) {
                $number = intval($matches[1]);
                $number++; // Increment the number
                $new_id = 'CT' . $number; // Format the ID correctly
            } else {
                // If the last ID doesn't match the expected format, start from CT1
                $new_id = 'CT1';
            }
        }

        return $new_id;
    }
}
