        <h1>Danh sách đơn hàng</h1>

        <table class="order-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Bàn</th>
                    <th>Tổng tiền</th>
                    <th>Thanh toán</th>
                    <th>Trạng thái</th>
                    <th>Thời gian</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($data['orders']) && is_a($data['orders'], 'mysqli_result')) {
                    while ($order = mysqli_fetch_array($data['orders'])) {
                        $status_text = $order['trang_thai_thanh_toan'] == 'da_thanh_toan' ? 'Đã thanh toán' : 'Chưa thanh toán';
                        $status_class = $order['trang_thai_thanh_toan'] == 'da_thanh_toan' ? 'paid' : '';
                        // Calculate amount to pay (total amount minus discount)
                        $tong_tien = $order['tong_tien'];
                        $tien_khuyen_mai = $order['tien_khuyen_mai'] ?? 0;
                        $so_tien_can_thanh_toan = $order['thanh_toan'];
                ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['ma_don_hang']); ?></td>
                            <td><?php echo htmlspecialchars($order['ten_ban']); ?></td>
                            <td><?php echo number_format($order['tong_tien'], 0, '.', '') . 'đ'; ?></td>
                            <td><?php echo number_format($so_tien_can_thanh_toan, 0, '.', '') . 'đ'; ?></td>
                            <td class="<?php echo $status_class; ?>"><?php echo $status_text; ?></td>
                            <td><?php echo isset($order['ngay_tao']) ? TimezoneHelper::formatForDisplay($order['ngay_tao'], 'H:i:s d/m/Y') : ''; ?>
                            </td>
                            <td>
                                <?php if ($order['trang_thai_thanh_toan'] === 'chua_thanh_toan'): ?>
                                    <button class="btn-pay"
                                        onclick="window.location.href='http://localhost/QLSP/Banuong/order_detail/<?php echo urlencode($order['ma_don_hang']); ?>'">Thanh
                                        toán</button>

                                    <button class="btn-cancel"
                                        onclick="if(confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?'))
                            window.location.href='http://localhost/QLSP/Staff/cancel_order/<?php echo urlencode($order['ma_don_hang']); ?>'">Huỷ
                                        đơn</button>
                                <?php else: ?>
                                    <button class="btn-view"
                                        onclick="window.location.href='http://localhost/QLSP/Staff/order_detail/<?php echo urlencode($order['ma_don_hang']); ?>'">Xem
                                        chi tiết </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="7">Không có dữ liệu đơn hàng.</td></tr>';
                }
                ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php if (isset($data['total_pages']) && $data['total_pages'] > 1): ?>
            <div class="pagination">
                <div class="pagination-info">
                    Trang <?php echo $data['current_page']; ?> / <?php echo $data['total_pages']; ?>
                    (Tổng <?php echo $data['total_orders']; ?> đơn hàng)
                </div>
                <div class="pagination-controls">
                    <?php if ($data['current_page'] > 1): ?>
                        <a href="http://localhost/QLSP/Staff/orders/<?php echo $data['current_page'] - 1; ?>"
                            class="btn-page">Trang trước</a>
                    <?php endif; ?>

                    <?php
                    // Show page numbers with ellipsis for large page ranges
                    $start_page = max(1, $data['current_page'] - 2);
                    $end_page = min($data['total_pages'], $data['current_page'] + 2);

                    if ($start_page > 1): ?>
                        <a href="http://localhost/QLSP/Staff/orders/1" class="btn-page">1</a>
                        <?php if ($start_page > 2): ?>
                            <span class="ellipsis">...</span>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                        <a href="http://localhost/QLSP/Staff/orders/<?php echo $i; ?>"
                            class="btn-page <?php echo ($i == $data['current_page']) ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($end_page < $data['total_pages']): ?>
                        <?php if ($end_page < $data['total_pages'] - 1): ?>
                            <span class="ellipsis">...</span>
                        <?php endif; ?>
                        <a href="http://localhost/QLSP/Staff/orders/<?php echo $data['total_pages']; ?>"
                            class="btn-page"><?php echo $data['total_pages']; ?></a>
                    <?php endif; ?>

                    <?php if ($data['current_page'] < $data['total_pages']): ?>
                        <a href="http://localhost/QLSP/Staff/orders/<?php echo $data['current_page'] + 1; ?>"
                            class="btn-page">Trang sau</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        </div>

        <style>
            .pagination {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 20px;
                padding: 10px;
            }

            .pagination-info {
                color: #c45050ff;
                font-weight: bold;
                font-size: 14px;
                border: 1px solid #ddd;
                padding: 6px 12px;
                border-radius: 4px;
                background-color: #f9f9f9;
            }

            .pagination-controls {
                display: flex;
                gap: 5px;
            }

            .btn-page {
                padding: 8px 12px;
                text-decoration: none;
                border: 1px solid #ddd;
                background-color: white;
                color: #333;
                border-radius: 4px;
                transition: all 0.3s;
            }

            .btn-page:hover {
                background-color: #f0f0f0;
                border-color: #999;
            }

            .btn-page.active {
                background-color: #007bff;
                color: white;
                border-color: #007bff;
            }

            .ellipsis {
                padding: 8px 4px;
                display: flex;
                align-items: center;
            }

            .btn-pay {
                background: #28a745;
                color: white;
                border: none;
                padding: 6px 16px;
                border-radius: 4px;
                cursor: pointer;
                transition: background 0.3s;
            }

            .btn-pay:hover {
                background: #218838;
            }

            .btn-cancel {
                background: #dc3545;
                color: white;
                border: none;
                padding: 6px 25px;
                border-radius: 4px;
                margin-left: 0px;
                cursor: pointer;
                transition: background 0.3s;
            }

            .btn-cancel:hover {
                background: #c82333;
            }


            .order-table th:nth-child(7),
            .order-table td:nth-child(7) {
                max-width: 120px;
                word-break: break-word;
            }
        </style>
        </body>

        </html>