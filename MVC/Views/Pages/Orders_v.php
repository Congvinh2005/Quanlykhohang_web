<?php
// Danh sách đơn hàng (sau này có thể lấy từ database)
$orders = [
    [
        "id" => 10,
        "table" => "Sân Vườn",
        "total" => 47000,
        "status" => "Đã thanh toán",
        "time" => "09:59 30/05/2025"
    ],
    [
        "id" => 9,
        "table" => "Bàn VIP",
        "total" => 102000,
        "status" => "Đã thanh toán",
        "time" => "09:18 30/05/2025"
    ],
    [
        "id" => 8,
        "table" => "Bàn 11",
        "total" => 94000,
        "status" => "Đã thanh toán",
        "time" => "22:20 29/05/2025"
    ]
];
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Danh sách đơn hàng</title>
    <link rel="stylesheet" href="orders.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>

<body>
    <div class="app">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <h2 class="logo">☕ Cafe Manager</h2>
            <ul class="menu">
                <li><i class="fa-solid fa-chair"></i> Sơ đồ bàn</li>
                <li class="active"><i class="fa-solid fa-receipt"></i> Đơn hàng</li>
                <li><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</li>
            </ul>
        </aside>

        <!-- CONTENT -->
        <main class="content">
            <div class="breadcrumb">
                <a href="#">Trang chủ</a> / Danh sách đơn hàng
            </div>

            <div class="alert">Chào mừng nhân viên!</div>

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
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= $order['id'] ?></td>
                        <td><?= $order['table'] ?></td>
                        <td><?= number_format($order['total'], 0, ',', '.') ?>đ</td>
                        <td class="paid"><?= $order['status'] ?></td>
                        <td><?= $order['time'] ?></td>
                        <td>
                            <button class="btn-view">Xem</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>

    </div>
</body>

</html>