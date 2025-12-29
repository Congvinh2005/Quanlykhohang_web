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

    <div class="card">
        <div class="actions-top">
            <div>
                <h1><i class="fa-solid fa-receipt"></i> Quản lý đơn hàng</h1>
                <p class="lead">Xem và cập nhật trạng thái đơn hàng.</p>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Mã ĐH</th>
                        <th>Bàn</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody id="orderBody">
                    <?php
                        if(isset($data['orders']) && is_a($data['orders'], 'mysqli_result')){
                            while($order = mysqli_fetch_array($data['orders'])) {
                                $status_text = $order['trang_thai_thanh_toan'] == 'da_thanh_toan' ? 'Đã thanh toán' : 'Chưa thanh toán';
                                $status_class = $order['trang_thai_thanh_toan'] == 'da_thanh_toan' ? 'status-paid' : 'status-unpaid';
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['ma_don_hang']); ?></td>
                        <td><?php echo htmlspecialchars($order['ma_ban']); ?></td>
                        <td><?php echo number_format($order['tong_tien'], 0, ',', '.'); ?> ₫</td>
                        <td><span class="status-badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                        <td><?php echo htmlspecialchars($order['ngay_tao']); ?></td>
                        <td>
                            <div class="order-actions">
                                <a href="http://localhost/QLSP/Staff/order_detail/<?php echo urlencode($order['ma_don_hang']); ?>" class="order-btn btn-view">Xem</a>
                                <?php if($order['trang_thai_thanh_toan'] == 'chua_thanh_toan'): ?>
                                    <a href="http://localhost/QLSP/Staff/update_order_status/<?php echo urlencode($order['ma_don_hang']); ?>" class="order-btn btn-update">Cập nhật</a>
                                <?php endif; ?>
                            </div>
                        </td>
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
    </div>
</body>

</html>