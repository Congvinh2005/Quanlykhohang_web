<?php
class Donhang_m extends connectDB
{
    // Hàm thêm đơn hàng
    function Donhang_ins($ma_don_hang, $ma_ban, $ma_user, $ma_khuyen_mai, $tien_khuyen_mai, $tong_tien, $thanh_toan, $trang_thai_thanh_toan, $ngay_tao, $ghi_chu = null)
    {
        // Escape input values to prevent SQL injection
        $ma_don_hang = mysqli_real_escape_string($this->con, $ma_don_hang);
        $ma_ban = mysqli_real_escape_string($this->con, $ma_ban);
        $ma_user = mysqli_real_escape_string($this->con, $ma_user);
        $ma_khuyen_mai = $ma_khuyen_mai !== null ? mysqli_real_escape_string($this->con, $ma_khuyen_mai) : null;
        $tien_khuyen_mai = floatval($tien_khuyen_mai);
        $tong_tien = floatval($tong_tien);
        $thanh_toan = floatval($thanh_toan);
        $trang_thai_thanh_toan = mysqli_real_escape_string($this->con, $trang_thai_thanh_toan);
        $ngay_tao = mysqli_real_escape_string($this->con, $ngay_tao);
        $ghi_chu = $ghi_chu !== null ? mysqli_real_escape_string($this->con, $ghi_chu) : null;

        // Handle nullable fields properly
        $ma_khuyen_mai_sql = $ma_khuyen_mai !== null ? "'$ma_khuyen_mai'" : "NULL";
        $ghi_chu_sql = $ghi_chu !== null ? "'$ghi_chu'" : "NULL";

        $sql = "INSERT INTO don_hang (ma_don_hang, ma_ban, ma_user, ma_khuyen_mai, tien_khuyen_mai, tong_tien, thanh_toan, trang_thai_thanh_toan, ngay_tao, ghi_chu) VALUES ('$ma_don_hang', '$ma_ban', '$ma_user', $ma_khuyen_mai_sql, $tien_khuyen_mai, $tong_tien, $thanh_toan, '$trang_thai_thanh_toan', '$ngay_tao', $ghi_chu_sql)";
        return mysqli_query($this->con, $sql);
    }

    // Hàm kiểm tra trùng mã đơn hàng
    function checktrungMaDonhang($ma_don_hang)
    {
        $sql = "SELECT * FROM don_hang WHERE ma_don_hang = '$ma_don_hang'";
        $result = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($result) > 0)
            return true; // Trùng mã đơn hàng
        else
            return false; // Không trùng mã đơn hàng
    }

    // Hàm tìm kiếm đơn hàng (kèm thông tin bàn, user và khuyến mãi)
    function Donhang_find($ma_don_hang, $ten_ban, $ten_user = '')
    {
        $sql = "SELECT d.*, bu.ten_ban, u.ten_user, km.tien_khuyen_mai as khuyen_mai_amount FROM don_hang d
                    LEFT JOIN ban_uong bu ON d.ma_ban = bu.ma_ban
                    LEFT JOIN users u ON d.ma_user = u.ma_user
                    LEFT JOIN khuyen_mai km ON d.ma_khuyen_mai = km.ma_khuyen_mai
                    WHERE d.ma_don_hang LIKE '%$ma_don_hang%' AND bu.ten_ban LIKE '%$ten_ban%' AND u.ten_user LIKE '%$ten_user%'
                    ORDER BY CAST(SUBSTRING(d.ma_don_hang, 3) AS UNSIGNED) ASC";
        return mysqli_query($this->con, $sql);
    }

    // function Donhang_find($ma_don_hang, $ten_ban, $thanh_toan)
    // {
    //     $sql = "SELECT d.*, bu.ten_ban, u.ten_user, km.tien_khuyen_mai as khuyen_mai_amount FROM don_hang d
    //                 LEFT JOIN ban_uong bu ON d.ma_ban = bu.ma_ban
    //                 LEFT JOIN users u ON d.ma_user = u.ma_user
    //                 LEFT JOIN khuyen_mai km ON d.ma_khuyen_mai = km.ma_khuyen_mai
    //                 WHERE d.ma_don_hang LIKE '%$ma_don_hang%' AND bu.ten_ban LIKE '%$ten_ban%' 
    //                 AND d.thanh_toan  LIKE '%$thanh_toan%'
    //                 ORDER BY CAST(SUBSTRING(d.ma_don_hang, 3) AS UNSIGNED) ASC";
    //     return mysqli_query($this->con, $sql);
    // }

    // Hàm xóa đơn hàng
    function Donhang_delete($ma_don_hang)
    {
        $sql = "DELETE FROM don_hang WHERE ma_don_hang = '$ma_don_hang'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy tất cả đơn hàng với thông tin bàn, user và khuyến mãi
    function Donhang_getAll()
    {
        $sql = "SELECT d.*, bu.ten_ban, u.ten_user, km.tien_khuyen_mai as khuyen_mai_amount FROM don_hang d
                    LEFT JOIN ban_uong bu ON d.ma_ban = bu.ma_ban
                    LEFT JOIN users u ON d.ma_user = u.ma_user
                    LEFT JOIN khuyen_mai km ON d.ma_khuyen_mai = km.ma_khuyen_mai
                    ORDER BY CAST(SUBSTRING(d.ma_don_hang, 3) AS UNSIGNED) DESC";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy chi tiết đơn hàng
    function Donhang_getById($ma_don_hang)
    {
        $sql = "SELECT d.*, bu.ten_ban, u.ten_user, km.tien_khuyen_mai as khuyen_mai_amount FROM don_hang d
                    LEFT JOIN ban_uong bu ON d.ma_ban = bu.ma_ban
                    LEFT JOIN users u ON d.ma_user = u.ma_user
                    LEFT JOIN khuyen_mai km ON d.ma_khuyen_mai = km.ma_khuyen_mai
                    WHERE d.ma_don_hang = '$ma_don_hang'";
        return mysqli_query($this->con, $sql);
    }



    // Hàm lấy các đơn hàng trong ngày hôm nay
    function getTodaysOrders()
    {
        $today = date('Y-m-d');
        $sql = "SELECT COUNT(*) as total_orders FROM don_hang WHERE DATE(ngay_tao) = '$today'";
        $result = mysqli_query($this->con, $sql);
        return mysqli_fetch_assoc($result);
    }

    // Hàm đếm tổng số đơn hàng
    function getTotalOrdersCount()
    {
        $sql = "SELECT COUNT(*) as total FROM don_hang";
        $result = mysqli_query($this->con, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }



    // Hàm lấy các đơn hàng cho nhân viên (theo user_id) với phân trang
    function getOrdersForEmployeeWithPagination($user_id, $limit, $offset)
    {
        $sql = "SELECT d.*, bu.ten_ban, u.ten_user, km.tien_khuyen_mai as khuyen_mai_amount FROM don_hang d
                    LEFT JOIN ban_uong bu ON d.ma_ban = bu.ma_ban
                    LEFT JOIN users u ON d.ma_user = u.ma_user
                    LEFT JOIN khuyen_mai km ON d.ma_khuyen_mai = km.ma_khuyen_mai
                    WHERE d.ma_user = '$user_id'
                    ORDER BY CAST(SUBSTRING(d.ma_don_hang, 3) AS UNSIGNED) DESC
                    LIMIT $limit OFFSET $offset";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy các đơn hàng cho khách hàng với phân trang
    function getOrdersForKhachhangWithPagination($limit, $offset)
    {
        $user_id = $_SESSION['user_id'] ?? 0;
        $sql = "SELECT d.*, bu.ten_ban, u.ten_user, km.tien_khuyen_mai as khuyen_mai_amount FROM don_hang d
                    LEFT JOIN ban_uong bu ON d.ma_ban = bu.ma_ban
                    LEFT JOIN users u ON d.ma_user = u.ma_user
                    LEFT JOIN khuyen_mai km ON d.ma_khuyen_mai = km.ma_khuyen_mai
                    WHERE d.ma_user = '$user_id'
                    ORDER BY CAST(SUBSTRING(d.ma_don_hang, 3) AS UNSIGNED) DESC
                    LIMIT $limit OFFSET $offset";
        return mysqli_query($this->con, $sql);
    }

    // Hàm đếm tổng số đơn hàng cho nhân viên
    function getTotalEmployeeOrdersCount($user_id)
    {
        $sql = "SELECT COUNT(*) as total FROM don_hang WHERE ma_user = '$user_id'";
        $result = mysqli_query($this->con, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }

    // Hàm cập nhật trạng thái đơn hàng
    function update_order_status($ma_don_hang, $new_status)
    {
        $sql = "UPDATE don_hang SET trang_thai_thanh_toan = '$new_status' WHERE ma_don_hang = '$ma_don_hang'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy thống kê theo ngày
    function getStatisticsByDate($tu_ngay, $den_ngay)
    {
        $sql = "SELECT
                        COUNT(*) as total_orders,
                        COUNT(CASE WHEN trang_thai_thanh_toan = 'da_thanh_toan' THEN 1 END) as paid_orders,
                        COALESCE(SUM(CASE WHEN trang_thai_thanh_toan = 'da_thanh_toan' THEN tong_tien END), 0) as total_revenue,
                        COALESCE(SUM(CASE WHEN trang_thai_thanh_toan = 'da_thanh_toan' THEN tien_khuyen_mai END), 0) as total_discount,
                        COALESCE(SUM(CASE WHEN trang_thai_thanh_toan = 'da_thanh_toan' THEN (tong_tien - tien_khuyen_mai) END), 0) as actual_revenue
                    FROM don_hang
                    WHERE ngay_tao BETWEEN '$tu_ngay 00:00:00' AND '$den_ngay 23:59:59'";

        $result = mysqli_query($this->con, $sql);
        return mysqli_fetch_assoc($result);
    }

    // Hàm lấy doanh thu theo ngày (cho biểu đồ đường)
    function getDailyRevenue($tu_ngay, $den_ngay)
    {
        $sql = "SELECT
                        DATE(ngay_tao) as date,
                        COALESCE(SUM(CASE WHEN trang_thai_thanh_toan = 'da_thanh_toan' THEN tong_tien END), 0) as daily_revenue
                    FROM don_hang
                    WHERE ngay_tao BETWEEN '$tu_ngay 00:00:00' AND '$den_ngay 23:59:59'
                    GROUP BY DATE(ngay_tao)
                    ORDER BY DATE(ngay_tao)";

        $result = mysqli_query($this->con, $sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    // Hàm lấy doanh thu theo danh mục (cho biểu đồ tròn)
    function getRevenueByCategory($tu_ngay, $den_ngay)
    {
        $sql = "SELECT
                        dm.ten_danh_muc,
                        SUM(ct.so_luong * ct.gia_tai_thoi_diem_dat) as revenue
                    FROM chi_tiet_don_hang ct
                    JOIN don_hang dh ON ct.ma_don_hang = dh.ma_don_hang
                    JOIN thuc_don td ON ct.ma_thuc_don = td.ma_thuc_don
                    JOIN danh_muc dm ON td.ma_danh_muc = dm.ma_danh_muc
                    WHERE dh.ngay_tao BETWEEN '$tu_ngay 00:00:00' AND '$den_ngay 23:59:59'
                    AND dh.trang_thai_thanh_toan = 'da_thanh_toan'
                    GROUP BY dm.ma_danh_muc, dm.ten_danh_muc
                    ORDER BY revenue DESC";

        $result = mysqli_query($this->con, $sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    // Hàm lấy tất cả các phiếu giảm giá
    function getDiscountVouchers()
    {
        $sql = "SELECT ma_khuyen_mai, ten_khuyen_mai, tien_khuyen_mai FROM khuyen_mai";
        return mysqli_query($this->con, $sql);
    }

    // Hàm cập nhật đơn hàng với thông tin giảm giá
    function updateOrderWithDiscount($ma_don_hang, $ma_khuyen_mai, $tien_khuyen_mai)
    {
        $ma_khuyen_mai_sql = $ma_khuyen_mai ? "'$ma_khuyen_mai'" : "NULL";
        // Also recalculate thanh_toan as tong_tien - tien_khuyen_mai
        $sql = "UPDATE don_hang SET ma_khuyen_mai = $ma_khuyen_mai_sql, tien_khuyen_mai = '$tien_khuyen_mai', thanh_toan = tong_tien - '$tien_khuyen_mai' WHERE ma_don_hang = '$ma_don_hang'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy đơn hàng theo ID bao gồm cả thông tin giảm giá
    function Donhang_getByIdWithDiscount($ma_don_hang)
    {
        $sql = "SELECT d.*, bu.ten_ban, u.ten_user, km.tien_khuyen_mai as khuyen_mai_amount FROM don_hang d
                    LEFT JOIN ban_uong bu ON d.ma_ban = bu.ma_ban
                    LEFT JOIN users u ON d.ma_user = u.ma_user
                    LEFT JOIN khuyen_mai km ON d.ma_khuyen_mai = km.ma_khuyen_mai
                    WHERE d.ma_don_hang = '$ma_don_hang'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy doanh thu hôm nay
    function getTodaysRevenue()
    {
        $today = date('Y-m-d');
        $sql = "SELECT COALESCE(SUM(tong_tien), 0) as total_revenue FROM don_hang WHERE DATE(ngay_tao) = '$today' AND trang_thai_thanh_toan = 'da_thanh_toan'";
        $result = mysqli_query($this->con, $sql);
        return mysqli_fetch_assoc($result);
    }

    // Hàm đếm tổng số đơn hàng cho khách hàng
    function getTotalKhachhangOrdersCount()
    {
        $user_id = $_SESSION['user_id'] ?? 0;
        $sql = "SELECT COUNT(*) as total FROM don_hang WHERE ma_user = '$user_id'";
        $result = mysqli_query($this->con, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }

    // Hàm lấy số đơn hàng trong ngày hôm nay cho khách hàng
    function getTodaysCustomerOrders($user_id)
    {
        $today = date('Y-m-d');
        $sql = "SELECT COUNT(*) as total_orders FROM don_hang WHERE DATE(ngay_tao) = '$today' AND ma_user = '$user_id'";
        $result = mysqli_query($this->con, $sql);
        return mysqli_fetch_assoc($result);
    }

    // Hàm lấy doanh thu trong ngày hôm nay cho khách hàng
    function getTodaysCustomerRevenue($user_id)
    {
        $today = date('Y-m-d');
        $sql = "SELECT COALESCE(SUM(tong_tien), 0) as total_revenue FROM don_hang WHERE DATE(ngay_tao) = '$today' AND trang_thai_thanh_toan = 'da_thanh_toan' AND ma_user = '$user_id'";
        $result = mysqli_query($this->con, $sql);
        return mysqli_fetch_assoc($result);
    }

    // Hàm lấy số đơn hàng trong ngày hôm nay cho nhân viên
    function getTodaysOrdersByStaff($staff_id)
    {
        $today = date('Y-m-d');
        $sql = "SELECT COUNT(*) as total_orders FROM don_hang WHERE DATE(ngay_tao) = '$today' AND ma_user = '$staff_id'";
        $result = mysqli_query($this->con, $sql);
        return mysqli_fetch_assoc($result);
    }

    // Hàm lấy doanh thu trong ngày hôm nay cho nhân viên
    function getTodaysRevenueByStaff($staff_id)
    {
        $today = date('Y-m-d');
        $sql = "SELECT COALESCE(SUM(tong_tien), 0) as total_revenue FROM don_hang WHERE DATE(ngay_tao) = '$today' AND trang_thai_thanh_toan = 'da_thanh_toan' AND ma_user = '$staff_id'";
        $result = mysqli_query($this->con, $sql);
        return mysqli_fetch_assoc($result);
    }
}
