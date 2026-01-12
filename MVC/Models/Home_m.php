<?php
    class Home_m extends connectDB{
        // Lấy các đơn hàng gần đây
        function getRecentOrders($limit = 5){
            $sql = "SELECT dh.ma_don_hang, dh.ngay_tao, u.ten_user, dh.tong_tien, dh.trang_thai_thanh_toan
                    FROM don_hang dh
                    LEFT JOIN users u ON dh.ma_user = u.ma_user
                    ORDER BY dh.ngay_tao DESC
                    LIMIT $limit";
            $result = mysqli_query($this->con, $sql);
            $data = [];
            if($result) {
                while($row = mysqli_fetch_assoc($result)) {
                    $data[] = $row;
                }
            }
            return $data;
        }

        // Lấy các sản phẩm sắp hết hàng
        function getLowStockProducts($limit = 5){
            $sql = "SELECT ma_thuc_don, ten_mon, so_luong, don_vi_tinh
                    FROM thuc_don
                    WHERE so_luong <= 5 AND so_luong > 0
                    ORDER BY so_luong ASC
                    LIMIT $limit";
            $result = mysqli_query($this->con, $sql);
            $data = [];
            if($result) {
                while($row = mysqli_fetch_assoc($result)) {
                    $data[] = $row;
                }
            }
            return $data;
        }

        // Lấy các nhân viên gần đây
        function getRecentEmployees($limit = 5){
            $sql = "SELECT ma_user, ten_user, email, ngay_tao
                    FROM users
                    WHERE phan_quyen = 'nhan_vien'
                    ORDER BY ngay_tao DESC
                    LIMIT $limit";
            $result = mysqli_query($this->con, $sql);
            $data = [];
            if($result) {
                while($row = mysqli_fetch_assoc($result)) {
                    $data[] = $row;
                }
            }
            return $data;
        }

        // Lấy các hoạt động gần đây tổng hợp
        function getRecentActivities($limit = 10){
            $activities = [];

            // Lấy đơn hàng gần đây
            $orders = $this->getRecentOrders(2);
            foreach($orders as $order) {
                $time_diff = $this->getTimeDiff($order['ngay_tao']);
                $status_text = $order['trang_thai_thanh_toan'] == 'da_thanh_toan' ? 'đã thanh toán' : 'chưa thanh toán';
                $status_icon = $order['trang_thai_thanh_toan'] == 'da_thanh_toan' ? 'fa-check-circle' : 'fa-clock';
                $status_color = $order['trang_thai_thanh_toan'] == 'da_thanh_toan' ? 'success' : 'warning';

                $activities[] = [
                    'type' => 'order_created',
                    'icon' => $status_icon,
                    'title' => 'Đơn hàng #' . $order['ma_don_hang'] . ' - ' . $status_text,
                    'description' => 'Khách hàng: ' . ($order['ten_user'] ?? 'Ẩn danh') . ' • ' . $time_diff,
                    'status' => $status_color
                ];
            }

            // Lấy sản phẩm sắp hết hàng
            $low_stock = $this->getLowStockProducts(2);
            foreach($low_stock as $product) {
                $activities[] = [
                    'type' => 'low_stock',
                    'icon' => 'fa-exclamation',
                    'title' => 'Sản phẩm sắp hết hàng',
                    'description' => $product['ten_mon'] . ' (Còn ' . $product['so_luong'] . ' ' . $product['don_vi_tinh'] . ')',
                    'status' => 'danger'
                ];
            }

            // Lấy thực đơn mới thêm/sửa
            $recent_menu = $this->getRecentMenuItems(2);
            foreach($recent_menu as $item) {
                $time_diff = $this->getTimeDiff($item['ngay_tao']);
                $action = isset($item['ngay_sua']) ? 'cập nhật' : 'thêm mới';
                $icon = isset($item['ngay_sua']) ? 'fa-edit' : 'fa-plus';

                $activities[] = [
                    'type' => 'menu_updated',
                    'icon' => $icon,
                    'title' => 'Thực đơn: ' . $item['ten_mon'] . ' (' . $action . ')',
                    'description' => 'Giá: ' . number_format($item['gia']) . 'đ • ' . $time_diff,
                    'status' => 'info'
                ];
            }

            // Lấy danh mục mới thêm/sửa
            $recent_categories = $this->getRecentCategories(1);
            foreach($recent_categories as $cat) {
                $time_diff = $this->getTimeDiff($cat['ngay_tao']);
                $action = isset($cat['ngay_sua']) ? 'cập nhật' : 'thêm mới';
                $icon = isset($cat['ngay_sua']) ? 'fa-edit' : 'fa-plus';

                $activities[] = [
                    'type' => 'category_updated',
                    'icon' => $icon,
                    'title' => 'Danh mục: ' . $cat['ten_danh_muc'] . ' (' . $action . ')',
                    'description' => $time_diff,
                    'status' => 'primary'
                ];
            }

            // Lấy khuyến mãi mới
            $recent_promotions = $this->getRecentPromotions(1);
            foreach($recent_promotions as $promo) {
                $time_diff = $this->getTimeDiff($promo['ngay_tao']);
                $discount_text = $promo['loai_khuyen_mai'] == 'phan_tram' ?
                    $promo['gia_tri_khuyen_mai'] . '%' :
                    number_format($promo['gia_tri_khuyen_mai']) . 'đ';

                $activities[] = [
                    'type' => 'promotion_added',
                    'icon' => 'fa-gift',
                    'title' => 'Khuyến mãi: ' . $promo['ten_khuyen_mai'],
                    'description' => 'Giảm ' . $discount_text . ' • ' . $time_diff,
                    'status' => 'success'
                ];
            }

            // Lấy nhân viên mới
            $employees = $this->getRecentEmployees(1);
            foreach($employees as $emp) {
                $time_diff = $this->getTimeDiff($emp['ngay_tao']);
                $activities[] = [
                    'type' => 'new_employee',
                    'icon' => 'fa-user-plus',
                    'title' => 'Thêm nhân viên mới',
                    'description' => $emp['ten_user'] . ' • ' . $time_diff,
                    'status' => 'info'
                ];
            }

            // Lấy nhà cung cấp mới
            $suppliers = $this->getRecentSuppliers(1);
            foreach($suppliers as $supplier) {
                $time_diff = $this->getTimeDiff($supplier['ngay_tao']);
                $activities[] = [
                    'type' => 'new_supplier',
                    'icon' => 'fa-truck',
                    'title' => 'Nhà cung cấp: ' . $supplier['ten_nha_cung_cap'],
                    'description' => $supplier['email'] . ' • ' . $time_diff,
                    'status' => 'warning'
                ];
            }

            // Sắp xếp theo thời gian
            usort($activities, function($a, $b) {
                // Sort by time (most recent first)
                $time_a = $this->extractTimeFromDesc($a['description']);
                $time_b = $this->extractTimeFromDesc($b['description']);
                return $time_b <=> $time_a; // Descending order
            });

            // Limit results
            return array_slice($activities, 0, $limit);
        }

        // Lấy các thực đơn gần đây
        function getRecentMenuItems($limit = 5){
            $sql = "SELECT ma_thuc_don, ten_mon, gia, don_vi_tinh, ngay_tao, ngay_sua
                    FROM thuc_don
                    ORDER BY ngay_tao DESC
                    LIMIT $limit";
            $result = mysqli_query($this->con, $sql);
            $data = [];
            if($result) {
                while($row = mysqli_fetch_assoc($result)) {
                    $data[] = $row;
                }
            }
            return $data;
        }

        // Lấy các danh mục gần đây
        function getRecentCategories($limit = 5){
            $sql = "SELECT ma_danh_muc, ten_danh_muc, mo_ta, ngay_tao, ngay_sua
                    FROM danh_muc
                    ORDER BY ngay_tao DESC
                    LIMIT $limit";
            $result = mysqli_query($this->con, $sql);
            $data = [];
            if($result) {
                while($row = mysqli_fetch_assoc($result)) {
                    $data[] = $row;
                }
            }
            return $data;
        }

        // Lấy các khuyến mãi gần đây
        function getRecentPromotions($limit = 5){
            $sql = "SELECT ma_khuyen_mai, ten_khuyen_mai, gia_tri_khuyen_mai, loai_khuyen_mai, ngay_tao
                    FROM khuyen_mai
                    ORDER BY ngay_tao DESC
                    LIMIT $limit";
            $result = mysqli_query($this->con, $sql);
            $data = [];
            if($result) {
                while($row = mysqli_fetch_assoc($result)) {
                    $data[] = $row;
                }
            }
            return $data;
        }

        // Lấy các nhà cung cấp gần đây
        function getRecentSuppliers($limit = 5){
            $sql = "SELECT ma_nha_cung_cap, ten_nha_cung_cap, email, ngay_tao
                    FROM nha_cung_cap
                    ORDER BY ngay_tao DESC
                    LIMIT $limit";
            $result = mysqli_query($this->con, $sql);
            $data = [];
            if($result) {
                while($row = mysqli_fetch_assoc($result)) {
                    $data[] = $row;
                }
            }
            return $data;
        }

        // Hàm chuyển đổi thời gian thành chuỗi dễ đọc
        private function getTimeDiff($datetime) {
            $timestamp = strtotime($datetime);
            $current_time = time();
            $time_diff = $current_time - $timestamp;

            if ($time_diff < 60) {
                return $time_diff . ' giây trước';
            } elseif ($time_diff < 3600) {
                return floor($time_diff / 60) . ' phút trước';
            } elseif ($time_diff < 86400) {
                return floor($time_diff / 3600) . ' giờ trước';
            } elseif ($time_diff < 2592000) {
                return floor($time_diff / 86400) . ' ngày trước';
            } else {
                return date('d/m/Y', $timestamp);
            }
        }

        // Hàm trích xuất thời gian từ mô tả (dành cho sắp xếp)
        private function extractTimeFromDesc($desc) {
            if (strpos($desc, 'giây trước') !== false) {
                preg_match('/(\d+) giây trước/', $desc, $matches);
                return time() - (int)$matches[1];
            } elseif (strpos($desc, 'phút trước') !== false) {
                preg_match('/(\d+) phút trước/', $desc, $matches);
                return time() - ((int)$matches[1] * 60);
            } elseif (strpos($desc, 'giờ trước') !== false) {
                preg_match('/(\d+) giờ trước/', $desc, $matches);
                return time() - ((int)$matches[1] * 3600);
            } elseif (strpos($desc, 'ngày trước') !== false) {
                preg_match('/(\d+) ngày trước/', $desc, $matches);
                return time() - ((int)$matches[1] * 86400);
            } else {
                return 0;
            }
        }
    }
?>