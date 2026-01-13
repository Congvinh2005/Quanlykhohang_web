<?php
// Include the helpers to make them available in all views
include_once __DIR__ . '/../../Public/Classes/TimezoneHelper.php';
include_once __DIR__ . '/../../Public/Classes/UrlHelper.php';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Cafe Manager customers</title>
    <base href="<?php echo UrlHelper::baseUrl(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: url('https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb') no-repeat center/cover;
            height: 100vh;
        }

        .app {
            display: flex;
            height: 100%;
            background: rgba(255, 255, 255, 0.85);
        }

        /* SIDEBAR */
        .sidebar {
            width: 220px;
            background: #ffffff;
            border-right: 1px solid #ddd;
            padding: 20px;
        }

        .logo {
            font-size: 20px;
            margin-bottom: 30px;
        }

        .menu li {
            list-style: none;
            padding: 12px;
            cursor: pointer;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .menu li i {
            margin-right: 8px;
        }

        .menu li:hover,
        .menu li.active {
            background: #e7f3ff;
        }

        .menu a {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        /* CONTENT */
        .content {
            flex: 1;
            padding: 30px;
        }

        .breadcrumb {
            margin-bottom: 15px;
            color: #555;
        }

        .breadcrumb a {
            color: #2c7be5;
            text-decoration: none;
        }

        .alert {
            background: #e6f4ea;
            color: #1e4620;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        h1 {
            margin-bottom: 25px;
        }

        /* TABLE CARDS */
        .tables {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .table-card {
            width: 150px;
            height: 120px;
            background: #6bbf59;
            color: white;
            border-radius: 12px;
            text-align: center;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: 0.3s;
        }

        .table-card:hover {
            transform: translateY(-5px);
            background: #5aaa4a;
        }

        .table-card i {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .table-card span {
            display: block;
            margin-top: 5px;
            font-weight: bold;
        }

        /* ORDER TABLE */
        .order-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 15px;
        }

        .order-table thead {
            background: #2f343a;
            color: white;
        }

        .order-table th,
        .order-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .order-table tbody tr:hover {
            background: #f5f7fa;
        }

        .paid {
            color: #2e7d32;
            font-weight: bold;
        }

        /* BUTTON */
        .btn-view {
            background: #6ec1e4;
            border: none;
            padding: 6px 14px;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-view:hover {
            background: #4faad0;
        }

        /* DETAIL TABLE */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 15px;
        }

        .detail-table th,
        .detail-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .detail-table thead {
            background: #f5f6f8;
            font-weight: bold;
        }

        .detail-table tbody tr:hover {
            background: #f9fafb;
        }

        /* STATUS */
        .status {
            margin-bottom: 20px;
            font-size: 15px;
        }

        .paid {
            color: #2e7d32;
            font-weight: bold;
        }

        /* TOTAL */
        .total {
            margin-top: 15px;
            font-size: 18px;
            font-weight: bold;
        }

        /* PRINT BUTTON */
        .btn-print {
            margin-top: 20px;
            padding: 10px 16px;
            background: white;
            border: 1px solid #ccc;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 25px;
        }

        .btn-print i {
            margin-right: 6px;
        }

        .btn-print:hover {
            background: #d8adadff;
        }
    </style>
</head>

<body>

    <div class="app">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <h2 class="logo">☕ Cafe Manager</h2>
            <?php $current = isset($data['page']) ? $data['page'] : ''; ?>
            <ul class="menu">

                <!-- <li class="<?php echo ($current === 'Khachhang/Dashboard_v') ? 'active' : ''; ?>">
                    <a href="http://localhost/QLSP/Khachhang/dashboard">
                        <i class="fa-solid fa-home"></i> Dashboard
                    </a>
                </li> -->

                <li class="<?php echo ($current === 'Khachhang/Direct_menu_v') ? 'active' : ''; ?>">
                    <a href="http://localhost/QLSP/Khachhang/direct_menu">
                        <i class="fa-solid fa-chair"></i> Đặt món
                    </a>
                </li>
                <li
                    class="<?php echo ($current === 'Khachhang/orders_v' || $current === 'Khachhang/order_detail_v') ? 'active' : ''; ?>">
                    <a href="http://localhost/QLSP/Khachhang/orders">
                        <i class="fa-solid fa-receipt"></i> Đơn hàng
                    </a>
                </li>
                <li>
                    <a href="http://localhost/QLSP/Users/logout">
                        <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                    </a>
                </li>
            </ul>
        </aside>

        <!-- MAIN -->
        <main class="content">
            <?php
            if (strpos($current, 'order_detail') !== false) {
                echo '<div class="breadcrumb">';
                echo '<a href="http://localhost/QLSP/Khachhang">Trang chủ</a> / Chi tiết đơn hàng';
                echo '</div>';
            } else if (strpos($current, 'orders') !== false) {
                echo '<div class="breadcrumb">';
                echo '<a href="http://localhost/QLSP/Khachhang">Trang chủ</a> / Danh sách đơn hàng';
                echo '</div>';

                echo '<div class="alert">';
                $ten_user = isset($_SESSION['ten_user']) ? $_SESSION['ten_user'] : (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'khách hàng');
                echo 'Chào mừng khách hàng ' . $ten_user . '!';
                echo '</div>';
            } else
            if (strpos($current, 'Direct_menu_v') !== false) {
                echo '<div class="breadcrumb">';
                echo '<a href="http://localhost/QLSP/Khachhang">Trang chủ</a> / Chọn món';
                echo '</div>';
            }

            if (isset($data['page'])) {
                include_once __DIR__ . '/Pages/' . $data['page'] . ".php";
            }
            ?>
        </main>
    </div>

</body>

</html>