<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng</title>

    <!-- FONT + ICON -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, Helvetica, sans-serif;
    }

    body {
        background: #f5f6fa;
    }

    .app {
        display: flex;
        min-height: 100vh;
    }

    /* ===== SIDEBAR ===== */
    .sidebar {
        width: 240px;
        background: #ffffff;
        border-right: 1px solid #ddd;
        padding: 20px;
    }

    .logo {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 30px;
    }

    .menu {
        list-style: none;
    }

    .menu li {
        padding: 12px 10px;
        margin-bottom: 8px;
        border-radius: 6px;
        cursor: pointer;
        color: #333;
    }

    .menu li i {
        width: 25px;
    }

    .menu li.active,
    .menu li:hover {
        background: #f1f2f6;
        font-weight: bold;
    }

    /* ===== CONTENT ===== */
    .content {
        flex: 1;
        padding: 25px 35px;
    }

    .breadcrumb {
        font-size: 14px;
        margin-bottom: 15px;
        color: #555;
    }

    h1 {
        font-size: 26px;
        margin-bottom: 8px;
    }

    .status {
        margin-bottom: 20px;
    }

    .status span {
        color: #e67e22;
        font-weight: bold;
    }

    /* ===== TABLE ===== */
    table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        margin-bottom: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    th,
    td {
        padding: 12px;
        border-bottom: 1px solid #eee;
        text-align: center;
    }

    th {
        background: #fafafa;
        font-weight: bold;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    td:first-child {
        text-align: left;
    }

    .btn-delete {
        background: #e74c3c;
        border: none;
        color: #fff;
        padding: 6px 10px;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-delete:hover {
        background: #c0392b;
    }

    .table-container {
        max-height: 400px;
        overflow-y: auto;
        margin-bottom: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* ===== THÊM MÓN ===== */
    .add-item {
        margin-top: 15px;
    }

    .add-row {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .add-row select {
        flex: 2;
        padding: 8px;
    }

    .add-row input {
        width: 90px;
        padding: 8px;
        text-align: center;
    }

    .btn-success {
        background: #27ae60;
        color: #fff;
    }


    .total {
        font-weight: bold;
        margin: 10px 0 25px;
        font-size: 18px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #28a745;
    }

    /* ===== ACTION BLOCK ===== */
    .box {
        background: #fff;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    select {
        padding: 8px;
        width: 250px;
    }

    .btn {
        padding: 8px 14px;
        border-radius: 5px;
        border: none;
        cursor: pointer;
    }

    .btn-warning {
        background: #ffc107;
        color: #212529;
    }

    .btn-primary {
        background: #007bff;
        color: #fff;
    }

    .btn-success {
        background: #28a745;
        color: #fff;
    }

    .order-summary {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }

    .summary-total {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        font-weight: bold;
        font-size: 18px;
        border-top: 2px solid #dee2e6;
        margin-top: 10px;
    }

    .cart-item-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
        margin-right: 10px;
    }

    .cart-item-details {
        display: flex;
        align-items: center;
    }

    .temp-order-note {
        background: #fff3cd;
        padding: 10px;
        border-radius: 6px;
        margin-bottom: 15px;
        border-left: 4px solid #ffc107;
        color: #856404;
    }
    </style>
</head>

<body>

    <div class="app">



        <!-- CONTENT -->
        <main class="content">
            <div class="breadcrumb">
                <a href="http://localhost/QLSP/Staff">Trang chủ</a> / Chi tiết đơn hàng
            </div>

            <?php
            if(isset($data['order']) && is_a($data['order'], 'mysqli_result')){
                $order = mysqli_fetch_array($data['order']);
                $status_text = $order['trang_thai_thanh_toan'] == 'da_thanh_toan' ? 'Đã thanh toán' : 'Chưa thanh toán';
                $status_class = $order['trang_thai_thanh_toan'] == 'da_thanh_toan' ? 'status-paid' : 'status-unpaid';
            ?>

            <h1>Chi tiết đơn hàng <b><?php echo htmlspecialchars($order['ma_don_hang']); ?></b> - Bàn
                <b><?php echo htmlspecialchars($order['ma_ban']); ?></b>
            </h1>
            <p class="status">Trạng thái: <span class="<?php echo $status_class; ?>"><?php echo $status_text; ?></span>
            </p>

            <?php
            // Check if we're displaying session cart items (temporary order)
            $is_temp_order = false;
            if (isset($data['order_details']) && !empty($data['order_details'])) {
                // Check if the first item doesn't have a ma_ctdh (meaning it's from session, not database)
                $first_item = reset($data['order_details']);
                if (!isset($first_item['ma_ctdh'])) {
                    $is_temp_order = true;
                }
            }


            ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Món</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Tổng</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(isset($data['order_details']) && is_array($data['order_details'])){
                                foreach($data['order_details'] as $detail) {
                        ?>
                        <tr>
                            <td>
                                <div class="cart-item-details">
                                    <?php if(isset($detail['img_thuc_don']) && $detail['img_thuc_don']): ?>
                                    <img src="<?php echo htmlspecialchars($detail['img_thuc_don']); ?>"
                                        alt="<?php echo htmlspecialchars($detail['ten_mon']); ?>" class="cart-item-image" />
                                    <?php else: ?>
                                    <div
                                        style="width: 50px; height: 50px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; border-radius: 4px; margin-right: 10px;">
                                        <i class="fa-solid fa-utensils" style="color: #d1d5db;"></i>
                                    </div>
                                    <?php endif; ?>
                                    <div><?php echo htmlspecialchars($detail['ten_mon']); ?></div>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($detail['so_luong']); ?></td>
                            <td><?php echo number_format($detail['gia_tai_thoi_diem_dat'], 0, '.', '.') . 'đ'; ?></td>
                            <td><?php echo number_format($detail['gia_tai_thoi_diem_dat'] * $detail['so_luong'], 0, '.', '.') . 'đ'; ?>
                            </td>
                            <td><button class="btn-delete">Xóa</button></td>
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
            <div class="total">Tổng cộng: <?php echo number_format($order['tong_tien'], 0, '.', '.') . 'đ'; ?></div>


            <!-- Chọn phiếu giảm giá -->
            <div class="box add-item">
                <h3>Chọn phiếu giảm giá</h3>

                <div class="add-row">
                    <select id="discount-select">
                        <option selected disabled>-- Chọn phiếu giảm giá --</option>
                        <?php
                        if(isset($data['discount_vouchers']) && is_a($data['discount_vouchers'], 'mysqli_result')){
                            while($voucher = mysqli_fetch_array($data['discount_vouchers'])){
                                echo '<option value="'.$voucher['ma_khuyen_mai'].'" data-amount="'.$voucher['tien_khuyen_mai'].'">'.$voucher['ten_khuyen_mai'].'</option>';
                            }
                        }
                        ?>
                    </select>

                    <button class="btn btn-success" id="apply-discount-btn">
                        <i class="fa-solid fa-tag"></i> Áp dụng giảm giá
                    </button>
                </div>
            </div>


            <div class="total">Tiền cần thanh toán là :
                <?php echo number_format($order['tong_tien'] - $order['tien_khuyen_mai'], 0, '.', '.') . 'đ'; ?></div>

            <div class="box">
                <h3>Thanh toán</h3><br>
                <button class="btn btn-primary" id="paymentButton"
                    data-order-id="<?php echo htmlspecialchars($order['ma_don_hang']); ?>">
                    <i class="fa-solid fa-credit-card"></i> Thanh toán
                </button>
            </div>

            <?php
            } else {
                echo '<p>Không tìm thấy thông tin đơn hàng.</p>';
            }
            ?>


        </main>

    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentButton = document.getElementById('paymentButton');
        const discountSelect = document.getElementById('discount-select');
        const applyDiscountBtn = document.getElementById('apply-discount-btn');
        const orderId = paymentButton ? paymentButton.getAttribute('data-order-id') : null;

        // Handle payment button click
        if (paymentButton) {
            paymentButton.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');

                if (confirm('Bạn có chắc chắn muốn cập nhật trạng thái thanh toán cho đơn hàng này?')) {
                    // Show loading state
                    const originalText = paymentButton.innerHTML;
                    paymentButton.innerHTML =
                        '<i class="fa-solid fa-spinner fa-spin"></i> Đang xử lý...';
                    paymentButton.disabled = true;

                    // Make AJAX request to update payment status
                    fetch(`http://localhost/QLSP/Staff/update_payment_status/${orderId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                // Update the status display
                                const statusElement = document.querySelector('.status span');
                                statusElement.textContent = 'Đã thanh toán';
                                statusElement.className = 'status-paid';

                                // Update the button to show success
                                paymentButton.innerHTML =
                                    '<i class="fa-solid fa-check"></i> Đã thanh toán';
                                paymentButton.className = 'btn btn-success';
                                paymentButton.disabled = true;

                                alert(data.message);

                                // Optionally reload the page to ensure all data is updated
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                            } else {
                                alert('Lỗi: ' + data.message);
                                // Restore original button state
                                paymentButton.innerHTML = originalText;
                                paymentButton.disabled = false;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Có lỗi xảy ra khi cập nhật trạng thái thanh toán');
                            // Restore original button state
                            paymentButton.innerHTML = originalText;
                            paymentButton.disabled = false;
                        });
                }
            });
        }

        // Handle discount selection
        if (applyDiscountBtn && discountSelect) {
            applyDiscountBtn.addEventListener('click', function() {
                const selectedOption = discountSelect.options[discountSelect.selectedIndex];

                if (selectedOption.value) {
                    const maKhuyenMai = selectedOption.value;
                    const discountAmount = parseFloat(selectedOption.getAttribute('data-amount'));

                    // Make AJAX request to update order with discount
                    fetch(`http://localhost/QLSP/Staff/update_order_discount/${orderId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                ma_khuyen_mai: maKhuyenMai
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                // Update the displayed discount amount
                                const totalElements = document.querySelectorAll('.total');
                                if (totalElements.length >= 2) {
                                    // Update the second total element (the one showing final payment)
                                    const originalTotalText = totalElements[0].textContent;
                                    const originalTotal = parseFloat(originalTotalText.match(/\d+/g)
                                        .join(''));
                                    const finalTotal = originalTotal - data.tien_khuyen_mai;

                                    totalElements[1].innerHTML =
                                        `Tiền cần thanh toán là : ${finalTotal.toLocaleString('vi-VN')}đ`;

                                    alert(data.message);
                                }
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Có lỗi xảy ra khi cập nhật giảm giá');
                        });
                } else {
                    alert('Vui lòng chọn một phiếu giảm giá');
                }
            });
        }
    });
    </script>

</body>

</html>