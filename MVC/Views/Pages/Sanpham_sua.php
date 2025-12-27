<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Sản phẩm</title>
    <style>
    :root {
        --bg: #f5f7fb;
        --card: #ffffff;
        --accent: #2463ff;
        --muted: #6b7280;
        --radius: 12px;
        --gap: 16px;
        font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
    }

    * {
        box-sizing: border-box
    }

    .card {
        width: 100%;
        max-width: 820px;
        background: var(--card);
        border-radius: var(--radius);
        box-shadow: 0 8px 30px rgba(24, 99, 255, 0.08);
        padding: 28px;
    }

    h1 {
        margin: 0 0 6px;
        font-size: 20px
    }

    p.lead {
        margin: 0 0 20px;
        color: var(--muted);
        font-size: 14px
    }

    form {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: var(--gap);
    }

    .full {
        grid-column: 1 / -1
    }

    label {
        display: block;
        font-size: 15px;
        color: #253243;
        margin-bottom: 6px;
        font-weight: bold;
    }

    input[type="text"],
    input[type="number"],
    select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e3e7ef;
        border-radius: 10px;
        background: #fbfdff;
        font-size: 14px;
        outline: none;
    }

    input:focus,
    select:focus {
        box-shadow: 0 0 0 4px rgba(36, 99, 255, 0.08);
        border-color: var(--accent);
    }

    .actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        grid-column: 1 / -1;
        margin-top: 6px
    }

    button,
    a.btn-ghost {
        padding: 10px 16px;
        border-radius: 10px;
        border: 0;
        font-size: 14px;
        cursor: pointer
    }

    .btn-primary {
        background: var(--accent);
        color: #fff;
        transition: 0.2s;
    }

    .btn-primary:hover {
        background: #174fe0;
    }

    .btn-ghost {
        background: transparent;
        border: 1px solid #e6e9f2;
        color: var(--muted)
    }

    .btn-back {
        background: #6b7280;
        padding: 8px 15px;
        border-radius: 10px;
        color: #fff;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    @media (max-width:720px) {
        form {
            grid-template-columns: 1fr
        }

        .actions {
            justify-content: stretch
        }

        .actions button {
            width: 100%
        }
    }
    </style>
</head>

<body>
    <main class="card" role="main">
        <h1>Sửa thông tin Sản phẩm</h1>
        <p class="lead">Cập nhật thông tin sản phẩm.</p>

        <form method="post" action="http://localhost/QLSP/Sanpham/update">
            <div>
                <label for="productId">Mã sản phẩm *</label>
                <input type="text" id="productId" name="txtMasanpham" readonly
                    value="<?php echo isset($data['masp']) ? $data['masp'] : '' ?>" />
            </div>

            <div>
                <label for="productName">Tên sản phẩm *</label>
                <input type="text" id="productName" name="txtTensanpham" required
                    value="<?php echo isset($data['tensp']) ? $data['tensp'] : '' ?>" />
            </div>

            <div>
                <label for="price">Giá sản phẩm (VNĐ)</label>
                <input type="number" id="price" name="txtGia" step="0.01" min="0"
                    value="<?php echo isset($data['gia']) ? $data['gia'] : '' ?>" />
            </div>

            <div>
                <label for="quantity">Số lượng</label>
                <input type="number" id="quantity" name="txtSoluong" min="0"
                    value="<?php echo isset($data['soluong']) ? $data['soluong'] : '' ?>" />
            </div>

            <div class="full">
                <label for="supplier">Nhà cung cấp *</label>
                <select id="supplier" name="ddlNhacungcap" required>
                    <option value="">-- Chọn nhà cung cấp --</option>
                    <?php
if(isset($data['dsncc'])){
    mysqli_data_seek($data['dsncc'], 0);
    while($row = mysqli_fetch_array($data['dsncc'])){
        $selected = (isset($data['mancc']) && $data['mancc'] == $row['mancc']) ? 'selected' : '';
        echo "<option value='".$row['mancc']."' $selected>".$row['tenncc']."</option>";
    }
}
?>
                </select>
            </div>

            <div class="actions">
                <a href="http://localhost/QLSP/Sanpham/danhsach" class="btn-back"><i
                        class="fa-solid fa-arrow-left"></i> Quay lại</a>
                <button type="submit" class="btn-primary" name="btnCapnhat">Cập nhật</button>
            </div>
        </form>

    </main>
</body>

</html>