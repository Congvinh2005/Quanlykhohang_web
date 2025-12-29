<!DOCTYPE html>
<html lang="vi">

<body>
    <style>
    .order-summary {
        background: var(--card);
        border-radius: var(--radius);
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .summary-item {
        display: flex;
        flex-direction: column;
    }

    .summary-label {
        font-size: 14px;
        color: var(--muted);
        margin-bottom: 4px;
    }

    .summary-value {
        font-size: 18px;
        font-weight: 600;
        color: #253243;
    }

    .order-details-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .order-details-table th {
        background: #f8fafc;
        padding: 12px;
        text-align: left;
        border-bottom: 2px solid #e3e7ef;
    }

    .order-details-table td {
        padding: 12px;
        border-bottom: 1px solid #e3e7ef;
    }

    .order-details-table img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 6px;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-paid {
        background: #d1fae5;
        color: #065f46;
    }

    .status-unpaid {
        background: #fecaca;
        color: #b91c1c;
    }
    </style>

    <div class="card">
        <div class="actions-top">
            <div>
                <h1><i class="fa-solid fa-file-invoice"></i> Chi tiết đơn hàng</h1>
                <p class="lead">Thông tin chi tiết về đơn hàng và các món đã đặt.</p>
            </div>
            <div class="actions">
                <a href="http://localhost/QLSP/Staff/orders" class="btn-ghost"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
            </div>
        </div>

        <?php
            if(isset($data['order']) && is_a($data['order'], 'mysqli_result')){
                $order = mysqli_fetch_array($data['order']);
                $status_text = $order['trang_thai_thanh_toan'] == 'da_thanh_toan' ? 'Đã thanh toán' : 'Chưa thanh toán';
                $status_class = $order['trang_thai_thanh_toan'] == 'da_thanh_toan' ? 'status-paid' : 'status-unpaid';
        ?>

        <div class="order-summary">
            <div class="summary-grid">
                <div class="summary-item">
                    <span class="summary-label">Mã đơn hàng</span>
                    <span class="summary-value"><?php echo htmlspecialchars($order['ma_don_hang']); ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Bàn</span>
                    <span class="summary-value"><?php echo htmlspecialchars($order['ma_ban']); ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Khách hàng</span>
                    <span class="summary-value"><?php echo htmlspecialchars($order['ten_user']); ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Trạng thái</span>
                    <span class="summary-value">
                        <span class="status-badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                    </span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Ngày tạo</span>
                    <span class="summary-value"><?php echo htmlspecialchars($order['ngay_tao']); ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Tổng tiền</span>
                    <span class="summary-value"><?php echo number_format($order['tong_tien'], 0, ',', '.'); ?> ₫</span>
                </div>
            </div>
        </div>

        <h2>Chi tiết món ăn</h2>
        <div class="table-container">
            <table class="order-details-table">
                <thead>
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Tên món</th>
                        <th>Số lượng</th>
                        <th>Giá tại thời điểm đặt</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if(isset($data['order_details']) && is_array($data['order_details'])){
                            foreach($data['order_details'] as $detail) {
                    ?>
                    <tr>
                        <td>
                            <?php if($detail['img_thuc_don']): ?>
                                <img src="<?php echo htmlspecialchars($detail['img_thuc_don']); ?>" alt="<?php echo htmlspecialchars($detail['ten_mon']); ?>">
                            <?php else: ?>
                                <span>Không có</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($detail['ten_mon']); ?></td>
                        <td><?php echo htmlspecialchars($detail['so_luong']); ?></td>
                        <td><?php echo number_format($detail['gia_tai_thoi_diem_dat'], 0, ',', '.'); ?> ₫</td>
                        <td><?php echo htmlspecialchars($detail['ghi_chu'] ?? ''); ?></td>
                    </tr>
                    <?php
                            }
                        } else {
                            echo '<tr><td colspan="5">Không có chi tiết món ăn.</td></tr>';
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
            } else {
                echo '<p>Không tìm thấy thông tin đơn hàng.</p>';
            }
        ?>
    </div>
</body>

</html>