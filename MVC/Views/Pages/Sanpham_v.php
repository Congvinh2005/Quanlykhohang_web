<!DOCTYPE html>
<html lang="vi">

<body>
    <style>
    /* Giữ lại style cơ bản nếu cần */
    .btn-success {
        background-color: #10b981;
        color: #fff;
        padding: 8px 15px;
        border-radius: 6px;
        border: none;
        text-decoration: none;
        font-weight: 500;
    }

    .btn-success:hover {
        background-color: #059669;
    }

    .btn-create {
        background: #10b981;
        padding: 8px 15px;
        border-radius: 10px;
        color: #fff;
        font-weight: 600;
        text-decoration: none;
    }

    .btn-create:hover {
        background: #059669;
    }

    .btn-edit {
        background: #ffc107;
        padding: 6px 10px;
        border-radius: 6px;
        margin-right: 5px;
        color: #fff;
        text-decoration: none;
        display: inline-block;
    }

    .btn-delete {
        background: #dc3545;
        padding: 6px 10px;
        border-radius: 6px;
        color: #fff;
        text-decoration: none;
        display: inline-block;
    }

    .btn-ghost {
        background: transparent;
        border: 1px solid #e6e9f2;
        color: #6b7280;
        padding: 10px 16px;
        border-radius: 10px;
        text-decoration: none;
        display: inline-block;
        line-height: 1;
    }

    .btn-primary {
        background: #2463ff;
        color: #fff;
        padding: 10px 16px;
        border-radius: 10px;
        border: none;
        font-size: 14px;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn {
        padding: 10px 16px;
        border-radius: 10px;
        border: 0;
        font-size: 14px;
        cursor: pointer;
    }

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
        background: var(--card);
        border-radius: var(--radius);
        box-shadow: 0 8px 30px rgba(24, 99, 255, 0.08);
        padding: 28px;
        margin-bottom: 20px;
    }

    h1 {
        margin: 0 0 6px;
        font-size: 20px
    }

    h2 {
        margin: 0 0 6px;
        font-size: 18px
    }

    p.lead {
        margin: 0 0 20px;
        color: var(--muted);
        font-size: 14px
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

    .table-container {
        max-height: 500px;
        overflow-y: auto;
        margin-top: 20px;
        border: 1px solid #e3e7ef;
        border-radius: var(--radius);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
    }

    thead th {
        position: sticky;
        top: 0;
        background: #f8fafc;
        z-index: 10;
        border-bottom: 2px solid #e3e7ef;
        font-weight: 600;
    }

    th,
    td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #e3e7ef;
    }

    tbody tr:hover {
        background-color: #f8fafc;
    }

    .hint {
        font-size: 12px;
        color: var(--muted);
        margin-top: 6px
    }

    .form-search {
        display: flex;
        gap: var(--gap);
        align-items: flex-end;
    }

    .form-search>div {
        flex: 1 1 200px;
    }

    .actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    .actions-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
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
    </style>

    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h1 style="margin:0"><i class="fa-solid fa-plus-circle"></i> Thêm mới Sản phẩm</h1>
        </div>

        <p class="lead" style="margin-top:-10px; margin-bottom: 20px;">Nhập thông tin sản phẩm mới vào hệ thống.</p>

        <form method="post" id="productForm" action="http://localhost/QLSP/Sanpham/ins"
            style="grid-template-columns: 1fr; display:grid; gap: 15px;">
            <div>
                <label>Mã sản phẩm <span style="color:red">*</span></label>
                <input type="text" name="txtMasanpham" placeholder="VD: SP001" required
                    value="<?php echo isset($data['Masanpham']) ? htmlspecialchars($data['Masanpham']) : '' ?>" />
            </div>
            <div>
                <label>Tên sản phẩm <span style="color:red">*</span></label>
                <input type="text" name="txtTensanpham" placeholder="Tên sản phẩm" required
                    value="<?php echo isset($data['Tensanpham']) ? htmlspecialchars($data['Tensanpham']) : '' ?>" />
            </div>
            <div>
                <label>Giá bán (VNĐ)</label>
                <input type="number" name="txtGia" placeholder="0" required
                    value="<?php echo isset($data['Gia']) ? htmlspecialchars($data['Gia']) : '' ?>" />
            </div>
            <div>
                <label>Số lượng</label>
                <input type="number" name="txtSoluong" placeholder="0" required
                    value="<?php echo isset($data['Soluong']) ? htmlspecialchars($data['Soluong']) : '' ?>" />
            </div>
            <div>
                <label>Nhà cung cấp</label>
                <select name="ddlNhacungcap" required>
                    <option value="">-- Chọn NCC --</option>
                    <?php
                    if (isset($data['dsncc'])) {
                        mysqli_data_seek($data['dsncc'], 0); // Đặt lại con trỏ
                        while ($row = mysqli_fetch_array($data['dsncc'])) {
                            $selected = (isset($data['mancc']) && $data['mancc'] == $row['mancc']) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($row['mancc']) . "' $selected>" . htmlspecialchars($row['tenncc']) . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="actions"
                style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                <a href="http://localhost/QLSP/Sanpham/danhsach" class="btn-back"><i class="fa-solid fa-arrow-left"></i>
                    Quay lại</a>
                <div style="display: flex; gap: 12px;">
                    <!-- <button type="reset" class="btn-ghost"><i class="fa-solid fa-rotate-left"></i> Reset</button> -->
                    <button type="submit" class="btn-primary" name="btnLuu"><i class="fa-solid fa-save"></i> Lưu thông
                        tin</button>
                </div>
            </div>
        </form>
    </div>
</body>

</html>