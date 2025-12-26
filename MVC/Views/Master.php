<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Sản Phẩm Pro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet"
        href="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/qlsp/Public/Css/style.css?v=2'; ?>">
</head>

<body>
    <div class="app-container">
        <aside class="sidebar">
            <div class="brand">
                <i class="fa-solid fa-cube"></i> CôngVinhShop
            </div>
            <?php $current = isset($data['page']) ? $data['page'] : ''; ?>
            <nav class="menu_left1">
                <ul>
                    <!-- <li>
                        <a href="?url=Home" class="<?php echo ($current === 'home') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-chart-pie"></i> Tổng quan
                        </a>
                    </li>
                    <li> -->
                    <a href="?url=Sanpham"
                        class="<?php echo (in_array($current, ['Sanpham_v','Sanpham_sua'])) ? 'active' : ''; ?>">
                        <i class="fa-solid fa-box-open"></i> Quản lý Sản phẩm
                    </a>
                    </li>
                    <!-- <li>
                        <a href="?url=Sanpham"
                            class="<?php echo (in_array($current, ['Sanpham_v','Sanpham_sua'])) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-box-open"></i> Quản lý người dùng
                        </a>
                    </li>
                    <li>
                        <a href="?url=Sanpham"
                            class="<?php echo (in_array($current, ['Sanpham_v','Sanpham_sua'])) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-box-open"></i> Quản lý lý thực đơn
                        </a>
                    </li>
                    <li>
                        <a href="?url=Sanpham"
                            class="<?php echo (in_array($current, ['Sanpham_v','Sanpham_sua'])) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-box-open"></i> Quản lý lý bàn
                        </a>
                    </li> -->
                    <li>
                        <a href="?url=Sanpham/danhsach"
                            class="<?php echo ($current === 'Danhsachsanpham_v') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-list-ul"></i> Danh sách SP
                        </a>
                    </li>
                    <li>
                        <a href="?url=Nhacungcap"
                            class="<?php echo (in_array($current, ['Nhacungcap_v','Nhacungcap_sua'])) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-truck-fast"></i> Nhà cung cấp
                        </a>

                    </li>

                    <li>
                        <a href="?url=Nhacungcap"
                            class="<?php echo (in_array($current, ['Nhacungcap_v','Nhacungcap_sua'])) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-file-excel"></i>Xuất exel 📥
                        </a>

                    </li>
                    <li>
                        <a href="?url=Nhacungcap"
                            class="<?php echo (in_array($current, ['Nhacungcap_v','Nhacungcap_sua'])) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-cloud-arrow-up"></i> Upload exel 🔍
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
                        elseif(strpos($current, 'Nhacungcap') !== false) echo 'Đối tác';
                        else echo 'Quản trị hệ thống';
                    ?>
                </div>
                <div class="user-info">
                    <span>Đào Văn Vinh</span>
                    <div class="avatar">
                        <img src="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/qlsp/Public/Pictures/anh.jpg'; ?>"
                            alt="Avatar">
                    </div>
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