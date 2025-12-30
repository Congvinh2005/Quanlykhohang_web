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
                    <td><?php echo number_format($detail['gia_tai_thoi_diem_dat'] * $detail['so_luong'], 0, '.', '') . 'đ'; ?>
                    </td>
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