<?php
// Include the helpers to make them available in all views
include_once __DIR__.'/../../Public/Classes/TimezoneHelper.php';
include_once __DIR__.'/../../Public/Classes/UrlHelper.php';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Sản Phẩm Pro</title>
    <base href="<?php echo UrlHelper::baseUrl(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet"
        href="<?php echo UrlHelper::url('Public/Css/style.css?v=2'); ?>">
</head>

<body>
    <div class="app-container">
        <aside class="sidebar">
            <div class="brand">
                ☕ Coffee Manager
            </div>
            <?php $current = isset($data['page']) ? $data['page'] : ''; ?>
            <nav class="menu_left1">
                <ul>
                    <li>
                        <a href="http://localhost/QLSP/Home"
                            class="<?php echo ($current === 'home') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-chart-pie"></i> Tổng quan
                        </a>
                    </li>
                    <li>
                        <a href="http://localhost/QLSP/Users/danhsach"
                            class="<?php echo ($current === 'Danhsachusers_v') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-users"></i> Quản lý người dùng
                        </a>

                    </li>
                    <li>
                        <a href="http://localhost/QLSP/Nhacungcap/danhsach"
                            class="<?php echo ($current === 'Danhsachnhacungcap_v') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-truck-fast"></i> Quản lý nhà cung cấp
                        </a>
                    </li>
                    <li>
                        <a href="http://localhost/QLSP/Banuong/danhsach"
                            class="<?php echo ($current === 'Danhsachbanuong_v') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-chair"></i> Quản lý bàn uống
                        </a>
                    </li>

                    <li>
                        <a href="http://localhost/QLSP/Sanpham/danhsach"
                            class="<?php echo ($current === 'Danhsachsanpham_v') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-list-ul"></i> Quản lý nguyên liệu
                        </a>
                    </li>
                    <li>
                        <a href="http://localhost/QLSP/Danhmuc/danhsach"
                            class="<?php echo ($current === 'Danhsachdanhmuc_v') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-file-excel"></i> Quản lý danh mục
                        </a>

                    </li>
                    <li>
                        <a href="http://localhost/QLSP/Khuyenmai/danhsach"
                            class="<?php echo ($current === 'Danhsachkhuyenmai_v') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-gift"></i> Quản lý khuyến mãi
                        </a>
                    </li>

                    <li>
                        <a href="http://localhost/QLSP/Thucdon/danhsach"
                            class="<?php echo ($current === 'Danhsachthucdon_v') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-utensils"></i> Quản lý thực đơn
                        </a>
                    </li>

                    <li>
                        <a href="http://localhost/QLSP/Donhang/danhsach"
                            class="<?php echo ($current === 'Danhsachdonhang_v') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-receipt"></i> Quản lý đơn hàng
                        </a>
                    </li>
                    <li>
                        <a href="http://localhost/QLSP/Thongke/thongke"
                            class="<?php echo ($current === 'Thongke_v') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-chart-line"></i> Thống kê
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <div class="main-content">
            <header class="top-header">
                <div class="page-title">
                    <?php
                        if($current == 'home') echo 'Dashboard';
                        elseif(strpos($current, 'Sanpham') !== false) echo 'Sản phẩm';
                        elseif(strpos($current, 'Thucdon') !== false) echo 'Thực đơn';
                        elseif(strpos($current, 'Khuyenmai') !== false) echo 'Khuyến mãi';
                        elseif(strpos($current, 'Donhang') !== false) echo 'Đơn hàng';
                        elseif(strpos($current, 'Thongke') !== false) echo 'Thống kê';
                        elseif(strpos($current, 'Nhacungcap') !== false) echo 'Nhà cung cấp';
                        elseif(strpos($current, 'Danhmuc') !== false) echo 'Danh mục';
                        elseif(strpos($current, 'Banuong') !== false) echo 'Bàn uống';
                        elseif(strpos($current, 'Users') !== false) echo 'Người dùng';
                        elseif(strpos($current, 'Staff') !== false) echo 'Nhân viên';
                        else echo 'Quản trị hệ thống';
                    ?>
                </div>
                <div class="user-info">
                    <?php if(isset($_SESSION['user_name'])): ?>
                    <span>
                        <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        <?php if(isset($_SESSION['user_role'])): ?>
                        <span
                            class="user-role">(<?php echo $_SESSION['user_role'] == 'admin' ? 'Quản trị viên' : 'Nhân viên'; ?>)</span>
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