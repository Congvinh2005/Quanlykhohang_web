        <?php
            // Handle order information
            if(isset($data['order']) && is_a($data['order'], 'mysqli_result')){
                $order = mysqli_fetch_array($data['order']);
                $status_text = $order['trang_thai_thanh_toan'] == 'da_thanh_toan' ? 'Đã thanh toán' : 'Chưa thanh toán';
                $status_class = $order['trang_thai_thanh_toan'] == 'da_thanh_toan' ? 'paid' : '';
                $order_ma_ban = $order['ma_ban'];
                $order_tong_tien = $order['tong_tien'];
            } else {
                // If order is not from database, try to get from the data array
                if(isset($data['order']) && is_array($data['order']) && count($data['order']) > 0) {
                    $order = $data['order'][0]; // Get first element if it's an array
                    $status_text = $order['trang_thai_thanh_toan'] == 'da_thanh_toan' ? 'Đã thanh toán' : 'Chưa thanh toán';
                    $status_class = $order['trang_thai_thanh_toan'] == 'da_thanh_toan' ? 'paid' : '';
                    $order_ma_ban = $order['ma_ban'];
                    $order_tong_tien = $order['tong_tien'];
                } else {
                    echo '<p>Không tìm thấy thông tin đơn hàng.</p>';
                    return;
                }
            }
        ?>
        <h1>Chi tiết đơn hàng bàn <b><?php echo htmlspecialchars($order_ma_ban); ?></b></h1>
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
                    // Debug: Check if order_details exists and its content
                    if(isset($data['order_details'])){
                        if(is_array($data['order_details']) && count($data['order_details']) > 0){
                            foreach($data['order_details'] as $detail) {
                ?>
                <tr>
                    <td>
                        <?php if(isset($detail['img_thuc_don']) && $detail['img_thuc_don']): ?>
                        <img src="<?php echo htmlspecialchars($detail['img_thuc_don']); ?>"
                            alt="<?php echo htmlspecialchars($detail['ten_mon']); ?>"
                            style="width:30px;height:30px;object-fit:cover;border-radius:4px;margin-right:8px;vertical-align:middle;">
                        <?php endif; ?>
                        <?php echo htmlspecialchars($detail['ten_mon']); ?>
                    </td>
                    <td><?php echo htmlspecialchars($detail['so_luong']); ?></td>
                    <td><?php echo number_format($detail['gia_tai_thoi_diem_dat'], 0, '.', '') . 'đ'; ?></td>
                    <td><?php echo number_format($detail['gia_tai_thoi_diem_dat'] * $detail['so_luong'], 0, '.', '') . 'đ'; ?>
                    </td>
                </tr>
                <?php
                            }
                        } else {
                            echo '<tr><td colspan="4">Không có chi tiết món ăn trong đơn hàng này.</td></tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4">Không tìm thấy dữ liệu chi tiết món ăn.</td></tr>';
                    }
                ?>
            </tbody>
        </table>

        <div class="total">
            Tổng cộng: <span><?php echo number_format($order_tong_tien, 0, '.', '') . 'đ'; ?></span>
        </div>

        <button class="btn-print">
            <i class="fa-solid fa-print"></i> In hóa đơn
        </button>
        </body>

        </html>