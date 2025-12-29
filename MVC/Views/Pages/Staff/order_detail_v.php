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

        <?php
            if(isset($data['order']) && is_a($data['order'], 'mysqli_result')){
                $order = mysqli_fetch_array($data['order']);
                $status_text = $order['trang_thai_thanh_toan'] == 'da_thanh_toan' ? 'Đã thanh toán' : 'Chưa thanh toán';
                $status_class = $order['trang_thai_thanh_toan'] == 'da_thanh_toan' ? 'paid' : '';
        ?>
        <h1>Chi tiết đơn hàng bàn <b><?php echo htmlspecialchars($order['ma_ban']); ?></b></h1>
        <p class="status">
            Trạng thái: <span class="<?php echo $status_class; ?>"><?php echo $status_text; ?></span>
        </p>

        <table class="detail-table">
            <thead>
            <tr>
                <th>Món</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Tổng</th>
            </tr>
            </thead>
            <tbody>
                <?php
                    if(isset($data['order_details']) && is_array($data['order_details'])){
                        foreach($data['order_details'] as $detail) {
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($detail['ten_mon']); ?></td>
                    <td><?php echo htmlspecialchars($detail['so_luong']); ?></td>
                    <td><?php echo number_format($detail['gia_tai_thoi_diem_dat'], 0, '.', '') . 'đ'; ?></td>
                    <td><?php echo number_format($detail['gia_tai_thoi_diem_dat'] * $detail['so_luong'], 0, '.', '') . 'đ'; ?></td>
                </tr>
                <?php
                        }
                    } else {
                        echo '<tr><td colspan="4">Không có chi tiết món ăn.</td></tr>';
                    }
                ?>
            </tbody>
        </table>

        <div class="total">
            Tổng cộng: <span><?php echo number_format($order['tong_tien'], 0, '.', '') . 'đ'; ?></span>
        </div>

        <button class="btn-print">
            <i class="fa-solid fa-print"></i> In hóa đơn
        </button>
        <?php
            } else {
                echo '<p>Không tìm thấy thông tin đơn hàng.</p>';
            }
        ?>
    </div>
</body>

</html>