<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
    body {
        font-family: Arial, sans-serif;
    }

    h1 {
        margin-bottom: 10px;
    }

    .status {
        margin-bottom: 20px;
    }

    .status .paid {
        color: green;
        font-weight: bold;
    }

    table.detail-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table.detail-table th,
    table.detail-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }

    table.detail-table th {
        background: #f5f5f5;
    }

    .total {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 20px;
        text-align: right;
    }

    /* ===== BUTTONS ===== */
    .action-buttons {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
    }

    .btn-back {
        background: #afb2b5ff;
        color: #fff;
        padding: 10px 18px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
    }

    .btn-back:hover {
        background: #5a6268;
    }

    .btn-print {
        background: #28a745;
        color: #211f1fff;
        border: none;
        padding: 10px 18px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
    }

    .btn-print:hover {
        background: #1cd444ff;
    }

    .btn-print.disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    </style>
</head>

<body>

    <?php
// Handle order information
if (isset($data['order']) && is_a($data['order'], 'mysqli_result')) {
    $order = mysqli_fetch_array($data['order']);
} elseif (isset($data['order']) && is_array($data['order']) && count($data['order']) > 0) {
    $order = $data['order'][0];
} else {
    echo '<p>Không tìm thấy thông tin đơn hàng.</p>';
    return;
}

$status_text  = $order['trang_thai_thanh_toan'] == 'da_thanh_toan' ? 'Đã thanh toán' : 'Chưa thanh toán';
$status_class = $order['trang_thai_thanh_toan'] == 'da_thanh_toan' ? 'paid' : '';
$order_ma_ban = $order['ma_ban'];
$order_tong_tien = $order['tong_tien'];
$tien_khuyen_mai = $order['tien_khuyen_mai'] ?? 0;
$so_tien_can_thanh_toan = $order_tong_tien - $tien_khuyen_mai;
?>

    <h1>Chi tiết đơn hàng bàn <b><?= htmlspecialchars($order_ma_ban) ?></b></h1>

    <p class="status">
        Trạng thái:
        <span class="<?= $status_class ?>"><?= $status_text ?></span>
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
            <?php if (isset($data['order_details']) && is_array($data['order_details']) && count($data['order_details']) > 0): ?>
            <?php foreach ($data['order_details'] as $detail): ?>
            <tr>
                <td style="text-align:left">
                    <?php if (!empty($detail['img_thuc_don'])): ?>
                    <img src="<?= htmlspecialchars($detail['img_thuc_don']) ?>"
                        style="width:30px;height:30px;object-fit:cover;border-radius:4px;margin-right:8px;vertical-align:middle;">
                    <?php endif; ?>
                    <?= htmlspecialchars($detail['ten_mon']) ?>
                </td>
                <td><?= $detail['so_luong'] ?></td>
                <td><?= number_format($detail['gia_tai_thoi_diem_dat'], 0, '.', '') ?>đ</td>
                <td><?= number_format($detail['gia_tai_thoi_diem_dat'] * $detail['so_luong'], 0, '.', '') ?>đ</td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="4">Không có chi tiết món ăn.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="total">
        Tổng cộng: <?= number_format($order_tong_tien, 0, '.', '') ?>đ<br>
        Giảm giá: -<?= number_format($tien_khuyen_mai, 0, '.', '') ?>đ<br>
        <strong>Số tiền cần thanh toán: <?= number_format($so_tien_can_thanh_toan, 0, '.', '') ?>đ</strong>
    </div>

    <div class="action-buttons">
        <a href="http://localhost/QLSP/Khachhang/orders" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>

        <?php if ($order['trang_thai_thanh_toan'] === 'da_thanh_toan'): ?>
        <!-- <a href="http://localhost/QLSP/Khachhang/generateInvoice/<?= $order['ma_don_hang'] ?>" class="btn-print"
            target="_blank">
            <i class="fa-solid fa-print"></i> In hóa đơn
        </a> -->
        <?php else: ?>
        <!-- <span class="btn-print disabled" style="background: #ccc; cursor: not-allowed;"
            title="Chỉ được in hóa đơn khi đơn hàng đã được thanh toán">
            <i class="fa-solid fa-print"></i> Chưa thanh toán nên không thể in hóa đơn
        </span> -->
        <?php endif; ?>
    </div>

</body>

</html>