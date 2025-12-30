<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - Cafe Manager</title>

    <!-- FONT + ICON -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        min-height: 100vh;
        background: linear-gradient(135deg, #6f4e37, #a47148);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .container {
        width: 900px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
        display: flex;
        overflow: hidden;
    }

    /* LEFT - LOGIN */
    .left {
        width: 50%;
        padding: 40px;
    }

    .logo {
        font-size: 40px;
        text-align: center;
        margin-bottom: 10px;
    }

    .left h2 {
        text-align: center;
        margin-bottom: 5px;
    }

    .left p {
        text-align: center;
        color: #666;
        margin-bottom: 25px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    label {
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
    }

    input {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        border: 2px solid #ddd;
        font-size: 15px;
    }

    input:focus {
        outline: none;
        border-color: #6f4e37;
    }

    .btn {
        width: 100%;
        padding: 12px;
        background: #6f4e37;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn:hover {
        background: #5a3e2b;
    }

    .error {
        background: #fdecea;
        color: #b71c1c;
        padding: 10px;
        border-radius: 6px;
        margin-bottom: 15px;
        text-align: center;
    }

    /* RIGHT - INFO */
    .right {
        width: 50%;
        background: linear-gradient(135deg, #6f4e37, #a47148);
        color: white;
        padding: 40px;
        text-align: center;
    }

    .right i {
        font-size: 60px;
        margin-bottom: 15px;
    }

    .right h2 {
        margin-bottom: 10px;
    }

    .right p {
        margin-bottom: 20px;
    }

    .right ul {
        list-style: none;
        text-align: left;
        margin-top: 20px;
    }

    .right ul li {
        margin-bottom: 10px;
        font-size: 15px;
    }

    .right ul li::before {
        content: "✔ ";
        color: #c8e6c9;
        font-weight: bold;
    }
    </style>
</head>

<body>

    <div class="container">

        <!-- LEFT -->
        <div class="left">
            <div class="logo">☕</div>
            <h2>Đăng nhập hệ thống</h2>
            <p>Quản lý quán cà phê</p>

            <?php
            // Check for error from URL parameter (since we're using direct include)
            $error = isset($_GET['error']) ? $_GET['error'] : null;
            if ($error):
            ?>
            <div class="error">
                <?= htmlspecialchars(urldecode($error)) ?>
            </div>
            <?php endif; ?>

            <form method="post" action="http://localhost/QLSP/Login/process">
                <div class="form-group">
                    <label>Tên đăng nhập</label>
                    <input type="text" name="username" placeholder="Nhập tên đăng nhập" required>
                </div>

                <div class="form-group">
                    <label>Mật khẩu</label>
                    <input type="password" name="password" placeholder="Nhập mật khẩu" required>
                </div>

                <button class="btn">Đăng nhập</button>
            </form>
        </div>

        <!-- RIGHT -->
        <div class="right">
            <i class="fa-solid fa-mug-hot"></i>
            <h2>Cafe Manager</h2>
            <p>Mỗi ngày một ly – Mỗi ngày một niềm vui</p>

            <ul>
                <li>Bán cà phê, trà, nước uống</li>
                <li>Quản lý đơn hàng & bàn</li>
                <li>Theo dõi pha chế & thanh toán</li>
                <li>Hỗ trợ bánh ngọt & đồ ăn nhẹ</li>
            </ul>
        </div>

    </div>

</body>

</html>