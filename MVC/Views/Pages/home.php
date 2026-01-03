<div class="dashboard-container">
    <!-- Welcome Section -->
    <div class="welcome-section">
        <div class="welcome-card">
            <div class="welcome-content">
                <h1>Xin chào,
                    <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Đào Văn Vinh'; ?>!
                    👋</h1>
                <p>Chào mừng bạn quay trở lại hệ thống quản lý cà phê chuyên nghiệp</p>
                <div class="date-time">
                    <i class="fa-solid fa-calendar-day"></i>
                    <span><?php echo date('l, d/m/Y'); ?></span>
                </div>
            </div>
            <div class="welcome-icon">
                <i class="fa-solid fa-mug-hot"></i>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="fa-solid fa-utensils"></i>
                </div>
                <div class="stat-info">
                    <h3>128</h3>
                    <p>Thực đơn</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <i class="fa-solid fa-chair"></i>
                </div>
                <div class="stat-info">
                    <h3>24</h3>
                    <p>Bàn</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-warning">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div class="stat-info">
                    <h3>86</h3>
                    <p>Đơn hàng hôm nay</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-danger">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <div class="stat-info">
                    <h3>14.2M</h3>
                    <p>Doanh thu</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="main-grid">
        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2><i class="fa-solid fa-bolt"></i> Hành động nhanh</h2>
            <div class="actions-grid">
                <a href="http://localhost/QLSP/Sanpham/danhsach" class="action-card">
                    <div class="action-icon bg-blue">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <div class="action-content">
                        <h3>Quản lý nguyên liệu</h3>
                        <p>Thêm, sửa, xóa nguyên liệu</p>
                    </div>
                </a>

                <a href="http://localhost/QLSP/Thucdon/danhsach" class="action-card">
                    <div class="action-icon bg-green">
                        <i class="fa-solid fa-utensils"></i>
                    </div>
                    <div class="action-content">
                        <h3>Thực đơn</h3>
                        <p>Quản lý danh mục món</p>
                    </div>
                </a>

                <a href="http://localhost/QLSP/Donhang/danhsach" class="action-card">
                    <div class="action-icon bg-orange">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    <div class="action-content">
                        <h3>Đơn hàng</h3>
                        <p>Theo dõi đơn hàng mới</p>
                    </div>
                </a>

                <a href="http://localhost/QLSP/Danhmuc/danhsach" class="action-card">
                    <div class="action-icon bg-purple">
                        <i class="fa-solid fa-file-excel"></i>
                    </div>
                    <div class="action-content">
                        <h3>Danh mục</h3>
                        <p>Quản lý danh mục</p>
                    </div>
                </a>

                <a href="http://localhost/QLSP/Khuyenmai/danhsach" class="action-card">
                    <div class="action-icon bg-pink">
                        <i class="fa-solid fa-gift"></i>
                    </div>
                    <div class="action-content">
                        <h3>Khuyến mãi</h3>
                        <p>Chương trình ưu đãi</p>
                    </div>
                </a>

                <a href="http://localhost/QLSP/Thongke/thongke" class="action-card">
                    <div class="action-icon bg-teal">
                        <i class="fa-solid fa-chart-simple"></i>
                    </div>
                    <div class="action-content">
                        <h3>Thống kê</h3>
                        <p>Báo cáo doanh thu</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="recent-activity">
            <h2><i class="fa-solid fa-clock-rotate-left"></i> Hoạt động gần đây</h2>
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-icon success">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div class="activity-content">
                        <h4>Đơn hàng #ORD-00123 đã hoàn thành</h4>
                        <p>Khách hàng: Nguyễn Văn A • 10 phút trước</p>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-icon warning">
                        <i class="fa-solid fa-box"></i>
                    </div>
                    <div class="activity-content">
                        <h4>Nhập hàng nguyên liệu mới</h4>
                        <p>Cà phê Arabica • 30 phút trước</p>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-icon info">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <div class="activity-content">
                        <h4>Thêm nhân viên mới</h4>
                        <p>Phạm Thị B • 1 giờ trước</p>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-icon danger">
                        <i class="fa-solid fa-exclamation"></i>
                    </div>
                    <div class="activity-content">
                        <h4>Sản phẩm sắp hết hàng</h4>
                        <p>Cà phê Robusta • 2 giờ trước</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.welcome-section {
    margin-bottom: 30px;
}

.welcome-card {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    border-radius: var(--radius);
    padding: 30px;
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
}

.welcome-card::before {
    content: "";
    position: absolute;
    top: -50px;
    right: -50px;
    width: 200px;
    height: 200px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
}

.welcome-content h1 {
    margin: 0 0 10px 0;
    font-size: 28px;
}

.welcome-content p {
    margin: 0 0 15px 0;
    opacity: 0.9;
    font-size: 16px;
}

.date-time {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    opacity: 0.9;
}

.welcome-icon {
    font-size: 80px;
    opacity: 0.2;
}

.stats-section {
    margin-bottom: 30px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
}

.stat-card {
    background: var(--white);
    border-radius: var(--radius);
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.stat-info h3 {
    margin: 0;
    font-size: 24px;
    color: var(--dark);
}

.stat-info p {
    margin: 5px 0 0;
    color: var(--gray);
    font-size: 14px;
}

.bg-primary {
    background: var(--primary);
}

.bg-success {
    background: var(--success);
}

.bg-warning {
    background: var(--warning);
}

.bg-danger {
    background: var(--danger);
}

.main-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
}

.quick-actions h2,
.recent-activity h2 {
    margin: 0 0 20px 0;
    color: var(--dark);
    display: flex;
    align-items: center;
    gap: 10px;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
}

.action-card {
    background: var(--white);
    border-radius: var(--radius);
    padding: 20px;
    text-decoration: none;
    transition: 0.3s;
    display: flex;
    flex-direction: column;
    gap: 15px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
}

.action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px -10px rgba(0, 0, 0, 0.15);
}

.action-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.action-content h3 {
    margin: 0;
    color: var(--dark);
    font-size: 16px;
}

.action-content p {
    margin: 5px 0 0;
    color: var(--gray);
    font-size: 13px;
}

.bg-blue {
    background: var(--primary);
}

.bg-green {
    background: var(--success);
}

.bg-orange {
    background: var(--warning);
}

.bg-purple {
    background: #8b5cf6;
}

.bg-pink {
    background: #ec4899;
}

.bg-teal {
    background: #14b8a6;
}

.recent-activity {
    background: var(--white);
    border-radius: var(--radius);
    padding: 25px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
}

.activity-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.activity-item {
    display: flex;
    gap: 15px;
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
    flex-shrink: 0;
}

.activity-icon.success {
    background: var(--success);
}

.activity-icon.warning {
    background: var(--warning);
}

.activity-icon.info {
    background: var(--secondary);
}

.activity-icon.danger {
    background: var(--danger);
}

.activity-content h4 {
    margin: 0;
    color: var(--dark);
    font-size: 15px;
}

.activity-content p {
    margin: 5px 0 0;
    color: var(--gray);
    font-size: 13px;
}

@media (max-width: 992px) {
    .main-grid {
        grid-template-columns: 1fr;
    }

    .welcome-card {
        flex-direction: column;
        text-align: center;
        gap: 20px;
    }

    .welcome-icon {
        opacity: 0.1;
    }
}
</style>