<!DOCTYPE html>
<html lang="vi">

<body>
    <style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: var(--card);
        border-radius: var(--radius);
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        border-left: 4px solid var(--accent);
    }

    .stat-card.tables {
        border-left-color: #f59e0b;
    }

    .stat-card.orders {
        border-left-color: #10b981;
    }

    .stat-card.revenue {
        border-left-color: #8b5cf6;
    }

    .stat-label {
        color: var(--muted);
        font-size: 14px;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #253243;
    }

    .stat-title {
        font-size: 16px;
        font-weight: 600;
        color: #253243;
        margin-top: 5px;
    }

    .quick-links {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .link-card {
        background: var(--card);
        border-radius: var(--radius);
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        text-align: center;
        transition: transform 0.2s;
        border: 1px solid #e3e7ef;
    }

    .link-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .link-icon {
        font-size: 32px;
        margin-bottom: 10px;
        color: var(--accent);
    }

    .link-title {
        font-weight: 600;
        color: #253243;
        margin-bottom: 5px;
    }

    .link-desc {
        font-size: 14px;
        color: var(--muted);
    }

    .recent-orders {
        margin-top: 30px;
    }

    .recent-orders h2 {
        margin-bottom: 15px;
        color: #253243;
    }

    .order-list {
        background: var(--card);
        border-radius: var(--radius);
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        max-height: 400px;
        overflow-y: auto;
    }

    .order-list::-webkit-scrollbar {
        width: 8px;
    }

    .order-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .order-list::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }

    .order-list::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    .order-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #e3e7ef;
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .order-info {
        flex: 1;
    }

    .order-status {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-paid {
        background: #d1fae5;
        color: #065f46;
    }

    .status-unpaid {
        background: #fed7aa;
        color: #c2410c;
    }
    </style>

    <div class="card">
        <div>
            <h1><i class="fa-solid fa-user-tie"></i> Trang chủ Khách hàng</h1>
            <p class="lead">Đặt món và xem đơn hàng trong quán.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card tables">
                <div class="stat-label">Bàn đang hoạt động</div>
                <div class="stat-value"><?php echo $data['active_tables']['total_tables']; ?></div>
                <div class="stat-title">Bàn</div>
            </div>
            <div class="stat-card orders">
                <div class="stat-label">Đơn hàng hôm nay</div>
                <div class="stat-value"><?php echo $data['todays_orders']['total_orders']; ?></div>
                <div class="stat-title">Đơn</div>
            </div>
            <div class="stat-card revenue">
                <div class="stat-label">Doanh thu hôm nay</div>
                <div class="stat-value">
                    <?php echo number_format($data['todays_revenue']['total_revenue'], 0, ',', '.'); ?> ₫</div>
                <div class="stat-title">VND</div>
            </div>
        </div>

        <h2>Liên kết nhanh</h2>
        <div class="quick-links">
            <a href="http://localhost/QLSP/Khachhang/table" class="link-card">
                <div class="link-icon"><i class="fa-solid fa-chair"></i></div>
                <div class="link-title">Quản lý bàn</div>
                <div class="link-desc">Xem và quản lý các bàn đang sử dụng</div>
            </a>
            <a href="http://localhost/QLSP/Khachhang/orders" class="link-card">
                <div class="link-icon"><i class="fa-solid fa-receipt"></i></div>
                <div class="link-title">Quản lý đơn hàng</div>
                <div class="link-desc">Theo dõi và cập nhật đơn hàng</div>
            </a>
        </div>

        <div class="recent-orders">
            <h2>Đơn hàng gần đây</h2>
            <div class="order-list">
                <?php
                // Get recent orders
                $recent_orders = $this->model("Donhang_m")->getOrdersForStaffWithPagination(5, 0);
                if ($recent_orders && mysqli_num_rows($recent_orders) > 0) {
                    while($order = mysqli_fetch_array($recent_orders)) {
                        $status_class = $order['trang_thai_thanh_toan'] == 'da_thanh_toan' ? 'status-paid' : 'status-unpaid';
                        $status_text = $order['trang_thai_thanh_toan'] == 'da_thanh_toan' ? 'Đã thanh toán' : 'Chưa thanh toán';
                ?>
                <div class="order-item">
                    <div class="order-info">
                        <div><strong><?php echo htmlspecialchars($order['ma_don_hang']); ?></strong> - Bàn
                            <?php echo htmlspecialchars($order['ma_ban']); ?></div>
                        <div style="font-size: 14px; color: #6b7280;">
                            <?php echo htmlspecialchars($order['ngay_tao']); ?></div>
                    </div>
                    <div class="order-info" style="text-align: right;">
                        <div><?php echo number_format($order['tong_tien'], 0, ',', '.'); ?> ₫</div>
                        <div class="order-status <?php echo $status_class; ?>"><?php echo $status_text; ?></div>
                    </div>
                </div>
                <?php
                    }
                } else {
                    echo '<div style="text-align: center; padding: 20px; color: #9ca3af;">Không có đơn hàng gần đây</div>';
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>