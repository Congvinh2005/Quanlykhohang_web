<!DOCTYPE html>
<html lang="vi">

<body>
    <style>
    .status-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-pending {
        background: #fef3c7;
        color: #d97706;
    }

    .status-paid {
        background: #d1fae5;
        color: #065f46;
    }

    .status-unpaid {
        background: #fecaca;
        color: #b91c1c;
    }

    .order-actions {
        display: flex;
        gap: 8px;
    }

    .order-btn {
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 12px;
        cursor: pointer;
        border: none;
    }

    .btn-view {
        background: #3b82f6;
        color: white;
    }

    .btn-update {
        background: #10b981;
        color: white;
    }
    </style>

        <h1>Danh sách đơn hàng</h1>

        <table class="order-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Bàn</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Thời gian</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
                <?php
                    if(isset($data['orders']) && is_a($data['orders'], 'mysqli_result')){
                        while($order = mysqli_fetch_array($data['orders'])) {
                            $status_text = $order['trang_thai_thanh_toan'] == 'da_thanh_toan' ? 'Đã thanh toán' : 'Chưa thanh toán';
                            $status_class = $order['trang_thai_thanh_toan'] == 'da_thanh_toan' ? 'paid' : '';
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['ma_don_hang']); ?></td>
                    <td><?php echo htmlspecialchars($order['ma_ban']); ?></td>
                    <td><?php echo number_format($order['tong_tien'], 0, '.', '') . 'đ'; ?></td>
                    <td class="<?php echo $status_class; ?>"><?php echo $status_text; ?></td>
                    <td><?php echo date('H:i d/m/Y', strtotime($order['ngay_tao'])); ?></td>
                    <td><button class="btn-view" onclick="window.location.href='http://localhost/QLSP/Staff/order_detail/<?php echo urlencode($order['ma_don_hang']); ?>'">Xem</button></td>
                </tr>
                <?php
                        }
                    } else {
                        echo '<tr><td colspan="6">Không có dữ liệu đơn hàng.</td></tr>';
                    }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>