<?php
// Danh sách bàn (có thể lấy từ database sau này)
$tables = [
    ["name" => "Bàn 11", "status" => "Trống"],
    ["name" => "Bàn VIP", "status" => "Trống"],
    ["name" => "Sân Vườn", "status" => "Trống"],
    ["name" => "Sân Vườn 2", "status" => "Trống"],
];
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Cafe Manager</title>
    <link rel="stylesheet" href="nhanvien.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>

<body>
    <div class="app">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <h2 class="logo">☕ Cafe Manager</h2>
            <ul class="menu">
                <li class="active"><i class="fa-solid fa-chair"></i> Sơ đồ bàn</li>
                <li><i class="fa-solid fa-receipt"></i> Đơn hàng</li>
                <li><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</li>
            </ul>
        </aside>

        <!-- MAIN -->
        <main class="content">
            <h1>🪑 Sơ đồ bàn hiện tại</h1>

            <div class="tables">
                <?php foreach ($tables as $table): ?>
                <div class="table-card">
                    <i class="fa-solid fa-chair"></i>
                    <h3><?= $table['name'] ?></h3>
                    <span><?= $table['status'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </main>

    </div>
</body>

</html>