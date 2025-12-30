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
    </style>

    <div class="card">
        <div>
            <h1><i class="fa-solid fa-user-tie"></i> Trang chủ Nhân viên</h1>
            <p class="lead">Quản lý bàn và đơn hàng trong quán.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card tables">
                <div class="stat-label">Bàn đang hoạt động</div>
                <div class="stat-value">3</div>
                <div class="stat-title">Bàn</div>
            </div>
            <div class="stat-card orders">
                <div class="stat-label">Đơn hàng hôm nay</div>
                <div class="stat-value">15</div>
                <div class="stat-title">Đơn</div>
            </div>
            <div class="stat-card revenue">
                <div class="stat-label">Doanh thu hôm nay</div>
                <div class="stat-value">2,500,000 ₫</div>
                <div class="stat-title">VND</div>
            </div>
        </div>

        <h2>Liên kết nhanh</h2>
        <div class="quick-links">
            <a href="http://localhost/QLSP/Staff/table" class="link-card">
                <div class="link-icon"><i class="fa-solid fa-chair"></i></div>
                <div class="link-title">Quản lý bàn</div>
                <div class="link-desc">Xem và quản lý các bàn đang sử dụng</div>
            </a>
            <a href="http://localhost/QLSP/Staff/orders" class="link-card">
                <div class="link-icon"><i class="fa-solid fa-receipt"></i></div>
                <div class="link-title">Quản lý đơn hàng</div>
                <div class="link-desc">Theo dõi và cập nhật đơn hàng</div>
            </a>

        </div>
    </div>
</body>

</html>