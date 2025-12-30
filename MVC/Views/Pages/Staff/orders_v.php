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
                    <td><?php echo htmlspecialchars($order['ten_ban']); ?></td>
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