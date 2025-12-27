<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Nhập Excel Nhà cung cấp</title>
    <style>
    .container {
        max-width: 720px;
        margin: 20px auto;
        background: #fff;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(24, 99, 255, 0.08)
    }

    label {
        display: block;
        margin-bottom: 8px;
        color: #253243;
        font-weight: 600
    }

    input[type=file] {
        padding: 10px;
        border: 1px solid #e3e7ef;
        border-radius: 10px;
        width: 100%
    }

    .actions {
        margin-top: 16px;
        display: flex;
        gap: 12px
    }

    button {
        padding: 10px 16px;
        border-radius: 10px;
        border: 0;
        background: #2463ff;
        color: #fff;
        cursor: pointer
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>Nhập dữ liệu Nhà cung cấp từ Excel</h2>
        <p>Tải file .xlsx với cột: Mã NCC, Tên NCC, Địa chỉ, Điện thoại.</p>
        <form method="post" enctype="multipart/form-data" action="http://localhost/QLSP/Nhacungcap/import">
            <label>Chọn file Excel (.xlsx)</label>
            <input type="file" name="file" accept=".xlsx,.xls" required>
            <div class="actions">
                <button type="submit">Tải lên và nhập</button>
                <a href="http://localhost/QLSP/Nhacungcap/export" style="text-decoration:none"><button
                        type="button">Xuất danh
                        sách</button></a>
                <a href="http://localhost/QLSP/Nhacungcap/template" style="text-decoration:none"><button
                        type="button">Tải mẫu
                        Excel</button></a>
            </div>
        </form>
    </div>
</body>

</html>