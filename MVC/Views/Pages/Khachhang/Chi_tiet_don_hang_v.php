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
            margin: 10px 10px 25px;
            font-size: 18px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #28a745;
            flex: 1;
            min-width: 200px;
        }

        .total-row {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .total-discount {
            display: block;
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

        /* Payment Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 0;
            border: none;
            border-radius: 10px;
            width: 600px;
            max-width: 90%;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            animation: modalFadeIn 0.3s ease;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 20px 25px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
            border-radius: 10px 10px 0 0;
        }

        .modal-header h2 {
            margin: 0;
            color: #333;
            font-size: 22px;
        }

        .close {
            font-size: 28px;
            font-weight: bold;
            color: #aaa;
            cursor: pointer;
            line-height: 1;
        }

        .close:hover,
        .close:focus {
            color: #000;
        }

        .modal-body {
            padding: 25px;
        }

        .payment-methods {
            display: flex;
            justify-content: space-around;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .payment-method {
            flex: 1;
            min-width: 150px;
            text-align: center;
            padding: 20px 10px;
            border: 2px solid #e3e7ef;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .payment-method:hover {
            border-color: #007bff;
            background-color: #f0f8ff;
        }

        .payment-method.selected {
            border-color: #007bff;
            background-color: #e3f2fd;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.2);
        }

        .payment-method i {
            font-size: 32px;
            margin-bottom: 10px;
            display: block;
            color: #007bff;
        }

        .payment-content {
            display: none;
        }

        .payment-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .qr-container {
            text-align: center;
            margin: 20px 0;
        }

        .qr-placeholder {
            width: 200px;
            height: 200px;
            margin: 0 auto;
            background: #f8f9fa;
            border: 2px dashed #ccc;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .qr-icon {
            font-size: 60px;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .card-form {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .form-row .form-group input {
            width: 100%;
        }
    </style>
</head>

<body>

    <div class="app">



        <!-- CONTENT -->
        <main class="content">
            <div class="breadcrumb">
                <a href="http://localhost/QLSP/Khachhang">Trang chủ</a> / Chi tiết đơn hàng
            </div>

            <?php
            if (isset($data['order']) && is_a($data['order'], 'mysqli_result')) {
                $order = mysqli_fetch_array($data['order']);
                $status_text = $order['trang_thai_thanh_toan'] == 'da_thanh_toan' ? 'Đã thanh toán' : 'Chưa thanh toán';
                $status_class = $order['trang_thai_thanh_toan'] == 'da_thanh_toan' ? 'status-paid' : 'status-unpaid';
            ?>

                <h1>Chi tiết đơn hàng <b><?php echo htmlspecialchars($order['ma_don_hang']); ?></b> - Bàn
                    <b><?php echo htmlspecialchars($order['ma_ban']); ?></b>
                </h1>
                <p class="status">Trạng thái: <span class="<?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                </p>

                <?php if (!empty($order['ghi_chu'])): ?>
                    <div class="temp-order-note">
                        <strong>Ghi chú đơn hàng:</strong> <?php echo htmlspecialchars($order['ghi_chu']); ?>
                    </div>
                <?php endif; ?>

                <?php
                // Kiểm tra nếu chúng ta đang hiển thị các mặt hàng trong giỏ phiên (đơn hàng tạm thời)
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
                                <!-- <th>Hành động</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($data['order_details']) && is_array($data['order_details'])) {
                                foreach ($data['order_details'] as $detail) {
                            ?>
                                    <tr>
                                        <td>
                                            <div class="cart-item-details">
                                                <?php if (isset($detail['img_thuc_don']) && $detail['img_thuc_don']): ?>
                                                    <img src="<?php echo !empty($detail['img_thuc_don']) ? '/qlsp/Public/Pictures/thucdon/' . htmlspecialchars($detail['img_thuc_don']) : '/qlsp/Public/Pictures/no-image.png'; ?>"
                                                        alt="<?php echo htmlspecialchars($detail['ten_mon']); ?>"
                                                        class="cart-item-image" />
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
                                        <!-- <td><button class="btn-delete">Xóa</button></td> -->
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


                <!-- Chọn phiếu giảm giá -->
                <div class="box add-item">
                    <h3>Chọn phiếu giảm giá</h3>

                    <div class="add-row">
                        <select id="discount-select">
                            <option selected disabled>-- Chọn phiếu giảm giá --</option>
                            <?php
                            if (isset($data['discount_vouchers']) && is_a($data['discount_vouchers'], 'mysqli_result')) {
                                while ($voucher = mysqli_fetch_array($data['discount_vouchers'])) {
                                    echo '<option value="' . $voucher['ma_khuyen_mai'] . '" data-amount="' . $voucher['tien_khuyen_mai'] . '">' . $voucher['ten_khuyen_mai'] . '</option>';
                                }
                            }
                            ?>
                        </select>

                        <button class="btn btn-success" id="apply-discount-btn">
                            <i class="fa-solid fa-tag"></i> Áp dụng giảm giá
                        </button>
                    </div>
                </div>


                <div class="total-row">
                    <div class="total">Tổng tiền: <?php echo number_format($order['tong_tien'], 0, '.', '.') . 'đ'; ?></div>
                    <div class="total total-discount"
                        style="<?php echo ($order['tien_khuyen_mai'] > 0) ? 'display: block;' : 'display: none;'; ?>">Giảm
                        giá: <?php echo number_format($order['tien_khuyen_mai'], 0, '.', '.') . 'đ'; ?></div>
                    <div class="total">Tiền cần thanh toán là:
                        <?php echo number_format($order['tong_tien'] - $order['tien_khuyen_mai'], 0, '.', '.') . 'đ'; ?>
                    </div>
                </div>

                <div class="box">
                    <h3>Thanh toán</h3><br>
                    <button class="btn btn-primary" id="paymentButton"
                        data-order-id="<?php echo htmlspecialchars($order['ma_don_hang']); ?>">
                        <i class="fa-solid fa-credit-card"></i> Thanh toán
                    </button>
                </div>

                <!-- Payment Modal -->
                <div id="paymentModal" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Phương thức thanh toán</h2>
                            <span class="close" id="closeModal">&times;</span>
                        </div>
                        <div class="modal-body">
                            <div class="payment-methods">
                                <div class="payment-method" data-method="cash">
                                    <i class="fa-solid fa-money-bill-wave"></i>
                                    <span>Thanh toán tiền mặt</span>
                                </div>
                                <div class="payment-method" data-method="card">
                                    <i class="fa-solid fa-credit-card"></i>
                                    <span>Thẻ ngân hàng</span>
                                </div>
                                <div class="payment-method" data-method="qr">
                                    <i class="fa-solid fa-qrcode"></i>
                                    <span>Quét mã QR</span>
                                </div>
                            </div>

                            <div class="payment-details" id="paymentDetails">
                                <!-- Cash payment content -->
                                <div id="cashPayment" class="payment-content">
                                    <h3>Thanh toán bằng tiền mặt</h3>
                                    <p>Tổng tiền cần thanh toán:
                                        <strong><?php echo number_format($order['tong_tien'] - $order['tien_khuyen_mai'], 0, '.', '.') . 'đ'; ?></strong>
                                    </p>
                                    <button class="btn btn-success" id="confirmCashPayment">Xác nhận thanh toán</button>
                                </div>

                                <!-- Card payment content -->
                                <div id="cardPayment" class="payment-content" style="display: none;">
                                    <h3>Thanh toán bằng thẻ ngân hàng</h3>
                                    <p>Tổng tiền cần thanh toán:
                                        <strong><?php echo number_format($order['tong_tien'] - $order['tien_khuyen_mai'], 0, '.', '.') . 'đ'; ?></strong>
                                    </p>
                                    <div class="card-form">
                                        <div class="form-group">
                                            <label for="cardNumber">Số thẻ</label>
                                            <input type="text" id="cardNumber" placeholder="Nhập số thẻ" maxlength="19">
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label for="expiryDate">Ngày hết hạn</label>
                                                <input type="text" id="expiryDate" placeholder="MM/YY" maxlength="5">
                                            </div>
                                            <div class="form-group">
                                                <label for="cvv">CVV</label>
                                                <input type="password" id="cvv" placeholder="CVV" maxlength="3">
                                            </div>
                                        </div>
                                        <button class="btn btn-success" id="confirmCardPayment">Xác nhận thanh toán</button>
                                    </div>
                                </div>

                                <!-- QR payment content -->
                                <div id="qrPayment" class="payment-content" style="display: none;">
                                    <h3>Quét mã QR để thanh toán</h3>
                                    <p>Tổng tiền cần thanh toán :
                                        <strong><?php echo number_format($order['tong_tien'] - $order['tien_khuyen_mai'], 0, '.', '.') . 'đ'; ?></strong>
                                    </p>
                                    <div class="qr-container">
                                        <div class="qr-placeholder">
                                            <img id="qrCodeImage"
                                                src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?php echo urlencode('Thanh toan don hang ' . $order['ma_don_hang'] . ' - So tien: ' . ($order['tong_tien'] - $order['tien_khuyen_mai'])); ?>"
                                                alt="QR Code thanh toán" style="width: 200px; height: 200px;">
                                        </div>
                                        <div>Cà phê 123 QR</div>
                                    </div>
                                    <button class="btn btn-success" id="confirmQRPayment">Xác nhận thanh toán</button>
                                </div>
                            </div>
                        </div>
                    </div>
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

            // Check if order is paid by looking at the status element
            const statusElement = document.querySelector('.status span');
            const isOrderPaid = statusElement && statusElement.classList.contains('status-paid');





            // Payment modal elements
            const paymentModal = document.getElementById('paymentModal');
            const closeModal = document.getElementById('closeModal');
            const paymentMethods = document.querySelectorAll('.payment-method');
            const paymentDetails = document.getElementById('paymentDetails');
            const confirmCashPayment = document.getElementById('confirmCashPayment');
            const confirmCardPayment = document.getElementById('confirmCardPayment');
            const confirmQRPayment = document.getElementById('confirmQRPayment');

            // Xử lý khi nhấn nút thanh toán - mở modal thay vì thanh toán trực tiếp
            if (paymentButton) {
                paymentButton.addEventListener('click', function() {
                    // Open the payment modal
                    paymentModal.style.display = 'block';

                    // Đặt lại phương thức thanh toán mặc định (tiền mặt)
                    document.querySelectorAll('.payment-content').forEach(content => {
                        content.classList.remove('active');
                        content.style.display = 'none';
                    });
                    document.getElementById('cashPayment').classList.add('active');
                    document.getElementById('cashPayment').style.display = 'block';

                    // Đặt lại lựa chọn phương thức thanh toán
                    paymentMethods.forEach(method => {
                        method.classList.remove('selected');
                    });
                    document.querySelector('.payment-method[data-method="cash"]').classList.add('selected');
                });
            }

            // Close modal when clicking the close button
            if (closeModal) {
                closeModal.addEventListener('click', function() {
                    paymentModal.style.display = 'none';
                });
            }

            // Close modal when clicking outside the modal
            window.addEventListener('click', function(event) {
                if (event.target === paymentModal) {
                    paymentModal.style.display = 'none';
                }
            });

            // Xử lý lựa chọn phương thức thanh toán
            paymentMethods.forEach(method => {
                method.addEventListener('click', function() {
                    const selectedMethod = this.getAttribute('data-method');

                    // Cập nhật phương thức đã chọn
                    paymentMethods.forEach(m => m.classList.remove('selected'));
                    this.classList.add('selected');

                    // Hiển thị nội dung thanh toán tương ứng
                    document.querySelectorAll('.payment-content').forEach(content => {
                        content.classList.remove('active');
                        content.style.display = 'none';
                    });

                    const selectedContent = document.getElementById(selectedMethod + 'Payment');
                    selectedContent.style.display = 'block';
                    setTimeout(() => {
                        selectedContent.classList.add('active');
                    }, 10);
                });
            });

            // Xử lý xác nhận thanh toán tiền mặt
            if (confirmCashPayment) {
                confirmCashPayment.addEventListener('click', function() {
                    const orderId = paymentButton.getAttribute('data-order-id');

                    if (confirm(
                            'Bạn có chắc chắn muốn xác nhận thanh toán bằng tiền mặt cho đơn hàng này?')) {
                        // Hiển thị trạng thái đang tải
                        const originalText = confirmCashPayment.innerHTML;
                        confirmCashPayment.innerHTML =
                            '<i class="fa-solid fa-spinner fa-spin"></i> Đang xử lý...';
                        confirmCashPayment.disabled = true;

                        // Gửi yêu cầu AJAX để cập nhật trạng thái thanh toán
                        fetch(`http://localhost/QLSP/Khachhang/update_payment_status/${orderId}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    // Cập nhật hiển thị trạng thái
                                    const statusElement = document.querySelector('.status span');
                                    statusElement.textContent = 'Đã thanh toán';
                                    statusElement.className = 'status-paid';

                                    // Cập nhật nút để hiển thị thành công
                                    paymentButton.innerHTML =
                                        '<i class="fa-solid fa-check"></i> Đã thanh toán';
                                    paymentButton.className = 'btn btn-success';
                                    paymentButton.disabled = true;

                                    alert('Thanh toán thành công!');

                                    // Đóng modal và chuyển hướng đến danh sách đơn hàng sau một khoảng thời gian ngắn
                                    paymentModal.style.display = 'none';
                                    setTimeout(() => {
                                        window.location.href =
                                            'http://localhost/QLSP/Khachhang/orders';
                                    }, 1500);
                                } else {
                                    alert('Lỗi: ' + data.message);
                                    // Khôi phục trạng thái nút gốc
                                    confirmCashPayment.innerHTML = originalText;
                                    confirmCashPayment.disabled = false;
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Có lỗi xảy ra khi cập nhật trạng thái thanh toán');
                                // Khôi phục trạng thái nút gốc
                                confirmCashPayment.innerHTML = originalText;
                                confirmCashPayment.disabled = false;
                            });
                    }
                });
            }

            // Xử lý xác nhận thanh toán thẻ
            if (confirmCardPayment) {
                confirmCardPayment.addEventListener('click', function() {
                    const orderId = paymentButton.getAttribute('data-order-id');

                    // Get card details
                    const cardNumber = document.getElementById('cardNumber').value;
                    const expiryDate = document.getElementById('expiryDate').value;
                    const cvv = document.getElementById('cvv').value;

                    // Validate card details
                    if (!cardNumber || !expiryDate || !cvv) {
                        alert('Vui lòng nhập đầy đủ thông tin thẻ');
                        return;
                    }

                    if (confirm('Bạn có chắc chắn muốn xác nhận thanh toán bằng thẻ cho đơn hàng này?')) {
                        // Hiển thị trạng thái đang tải
                        const originalText = confirmCardPayment.innerHTML;
                        confirmCardPayment.innerHTML =
                            '<i class="fa-solid fa-spinner fa-spin"></i> Đang xử lý...';
                        confirmCardPayment.disabled = true;

                        // Gửi yêu cầu AJAX để cập nhật trạng thái thanh toán
                        fetch(`http://localhost/QLSP/Khachhang/update_payment_status/${orderId}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    // Cập nhật hiển thị trạng thái
                                    const statusElement = document.querySelector('.status span');
                                    statusElement.textContent = 'Đã thanh toán';
                                    statusElement.className = 'status-paid';

                                    // Cập nhật nút để hiển thị thành công
                                    paymentButton.innerHTML =
                                        '<i class="fa-solid fa-check"></i> Đã thanh toán';
                                    paymentButton.className = 'btn btn-success';
                                    paymentButton.disabled = true;

                                    alert('Thanh toán thành công!');

                                    // Đóng modal và chuyển hướng đến danh sách đơn hàng sau một khoảng thời gian ngắn
                                    paymentModal.style.display = 'none';
                                    setTimeout(() => {
                                        window.location.href =
                                            'http://localhost/QLSP/Khachhang/orders';
                                    }, 1500);
                                } else {
                                    alert('Lỗi: ' + data.message);
                                    // Khôi phục trạng thái nút gốc
                                    confirmCardPayment.innerHTML = originalText;
                                    confirmCardPayment.disabled = false;
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Có lỗi xảy ra khi cập nhật trạng thái thanh toán');
                                // Khôi phục trạng thái nút gốc
                                confirmCardPayment.innerHTML = originalText;
                                confirmCardPayment.disabled = false;
                            });
                    }
                });
            }

            // Xử lý xác nhận thanh toán QR
            if (confirmQRPayment) {
                confirmQRPayment.addEventListener('click', function() {
                    const orderId = paymentButton.getAttribute('data-order-id');

                    if (confirm('Bạn có chắc chắn muốn xác nhận thanh toán bằng QR cho đơn hàng này?')) {
                        // Hiển thị trạng thái đang tải
                        const originalText = confirmQRPayment.innerHTML;
                        confirmQRPayment.innerHTML =
                            '<i class="fa-solid fa-spinner fa-spin"></i> Đang xử lý...';
                        confirmQRPayment.disabled = true;

                        // Gửi yêu cầu AJAX để cập nhật trạng thái thanh toán
                        fetch(`http://localhost/QLSP/Khachhang/update_payment_status/${orderId}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    // Cập nhật hiển thị trạng thái
                                    const statusElement = document.querySelector('.status span');
                                    statusElement.textContent = 'Đã thanh toán';
                                    statusElement.className = 'status-paid';

                                    // Cập nhật nút để hiển thị thành công
                                    paymentButton.innerHTML =
                                        '<i class="fa-solid fa-check"></i> Đã thanh toán';
                                    paymentButton.className = 'btn btn-success';
                                    paymentButton.disabled = true;

                                    alert('Thanh toán thành công!');

                                    // Đóng modal và chuyển hướng đến danh sách đơn hàng sau một khoảng thời gian ngắn
                                    paymentModal.style.display = 'none';
                                    setTimeout(() => {
                                        window.location.href =
                                            'http://localhost/QLSP/Khachhang/orders';
                                    }, 1500);
                                } else {
                                    alert('Lỗi: ' + data.message);
                                    // Khôi phục trạng thái nút gốc
                                    confirmQRPayment.innerHTML = originalText;
                                    confirmQRPayment.disabled = false;
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Có lỗi xảy ra khi cập nhật trạng thái thanh toán');
                                // Khôi phục trạng thái nút gốc
                                confirmQRPayment.innerHTML = originalText;
                                confirmQRPayment.disabled = false;
                            });
                    }
                });
            }

            // Xử lý lựa chọn giảm giá
            if (applyDiscountBtn && discountSelect) {
                applyDiscountBtn.addEventListener('click', function() {
                    const selectedOption = discountSelect.options[discountSelect.selectedIndex];

                    if (selectedOption.value) {
                        const maKhuyenMai = selectedOption.value;
                        const discountAmount = parseFloat(selectedOption.getAttribute('data-amount'));

                        // Gửi yêu cầu AJAX để cập nhật đơn hàng với giảm giá
                        fetch(`http://localhost/QLSP/Khachhang/update_order_discount/${orderId}`, {
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
                                    // Cập nhật phần tử giảm giá
                                    const discountElement = document.querySelector('.total-discount');

                                    // Cập nhật số tiền thanh toán cuối cùng (luôn là phần tử tổng cuối cùng)
                                    const allTotalElements = document.querySelectorAll('.total');
                                    const finalPaymentElement = allTotalElements[allTotalElements
                                        .length - 1];

                                    // Lấy tổng gốc từ phần tử đầu tiên (Tổng tiền)
                                    const firstTotalElement = allTotalElements[0];
                                    const firstTotalText = firstTotalElement.textContent;
                                    const originalTotal = parseInt(firstTotalText.match(/\d+/g).join(
                                        '')) || 0;

                                    if (data.tien_khuyen_mai > 0) {
                                        // Cập nhật nội dung phần tử giảm giá và hiển thị nó
                                        const discountAmount = parseInt(data.tien_khuyen_mai) || 0;
                                        discountElement.innerHTML =
                                            `Giảm giá: ${discountAmount.toLocaleString('vi-VN')}đ`;
                                        discountElement.style.display = 'block';
                                    } else {
                                        // Ẩn phần tử giảm giá nếu không có giảm giá
                                        discountElement.style.display = 'none';
                                    }

                                    // Calculate final payment amount
                                    const discountAmount = parseInt(data.tien_khuyen_mai) || 0;
                                    const finalAmount = originalTotal - discountAmount;

                                    // Cập nhật số tiền thanh toán cuối cùng
                                    finalPaymentElement.innerHTML =
                                        `Tiền cần thanh toán là: ${finalAmount.toLocaleString('vi-VN')}đ`;

                                    // Cập nhật số tiền trong modal thanh toán
                                    updatePaymentAmounts(finalAmount);

                                    alert(data.message);
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

            // Hàm để cập nhật số tiền thanh toán trong modal
            function updatePaymentAmounts(amount) {
                // Cập nhật hiển thị tất cả các phương thức thanh toán
                const cashPaymentAmount = document.querySelector('#cashPayment p strong');
                const cardPaymentAmount = document.querySelector('#cardPayment p strong');
                const qrPaymentAmount = document.querySelector('#qrPayment p strong');

                if (cashPaymentAmount) {
                    cashPaymentAmount.textContent = parseInt(amount).toLocaleString('vi-VN') + 'đ';
                }

                if (cardPaymentAmount) {
                    cardPaymentAmount.textContent = parseInt(amount).toLocaleString('vi-VN') + 'đ';
                }

                if (qrPaymentAmount) {
                    qrPaymentAmount.textContent = parseInt(amount).toLocaleString('vi-VN') + 'đ';
                }
            }
        });
    </script>


</body>

</html>