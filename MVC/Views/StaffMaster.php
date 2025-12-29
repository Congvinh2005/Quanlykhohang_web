<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Cafe Manager</title>
    <base href="/QLSP/">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet"
        href="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/QLSP/Public/Css/style.css?v=2'; ?>">
</head>

<body>
    <div class="app-container">
        <aside class="sidebar">
            <div class="brand">
                <i class="fa-solid fa-mug-hot"></i> Cafe Staff
            </div>
            <?php $current = isset($data['page']) ? $data['page'] : ''; ?>
            <nav class="menu_left1">
                <ul>
                    <li>
                        <a href="http://localhost/QLSP/Staff"
                            class="<?php echo ($current === 'Staff/dashboard_v') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-gauge"></i> Dashboard
                        </a>
                    </li>

                    <li>
                        <a href="http://localhost/QLSP/Staff/tables"
                            class="<?php echo ($current === 'Staff/tables_v') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-chairs"></i> Quản lý bàn
                        </a>
                    </li>
                    <li>
                        <a href="http://localhost/QLSP/Staff/orders"
                            class="<?php echo ($current === 'Staff/orders_v') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-receipt"></i> Quản lý đơn hàng
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <div class="main-content">
            <header class="top-header">
                <div class="page-title">
                    <?php
                        if(strpos($current, 'Staff') !== false) {
                            if(strpos($current, 'dashboard') !== false) echo 'Dashboard';
                            elseif(strpos($current, 'tables') !== false) echo 'Quản lý bàn';
                            elseif(strpos($current, 'orders') !== false) echo 'Quản lý đơn hàng';
                            elseif(strpos($current, 'order_detail') !== false) echo 'Chi tiết đơn hàng';
                            else echo 'Nhân viên';
                        } else {
                            echo 'Dashboard';
                        }
                    ?>
                </div>
                <div class="user-info">
                    <?php if(isset($_SESSION['user_name'])): ?>
                        <span>
                            <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                            <?php if(isset($_SESSION['user_role'])): ?>
                                <span class="user-role">(<?php echo $_SESSION['user_role'] == 'admin' ? 'Quản trị viên' : 'Nhân viên'; ?>)</span>
                            <?php endif; ?>
                        </span>
                        <div class="avatar">
                            <img src="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/qlsp/Public/Pictures/anh.jpg'; ?>"
                                alt="Avatar">
                        </div>
                        <a href="http://localhost/QLSP/Users/logout" class="logout-btn" title="Đăng xuất">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </a>
                    <?php else: ?>
                        <span>Đào Văn Vinh</span>
                        <div class="avatar">
                            <img src="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/qlsp/Public/Pictures/anh.jpg'; ?>"
                                alt="Avatar">
                        </div>
                    <?php endif; ?>
                </div>
            </header>

            <div class="content-area">
                <?php
                    if(isset($data['page'])){
                        include_once __DIR__.'/Pages/'.$data['page'].".php";
                    }
                ?>
            </div>
        </div>
    </div>
</body>

</html>