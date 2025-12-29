<!DOCTYPE html>
<html lang="vi">

<body>
    <style>
    /* Reuse styles similar to Sanpham */
    .btn-create {
        background: #10b981;
        /* Màu xanh lá cây */
        padding: 8px 15px;
        border-radius: 10px;
        color: #fff;
        font-weight: 600;
        text-decoration: none;
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

    /* Các style cơ bản khác giữ nguyên */
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

    p.lead {
        margin: 0 0 20px;
        color: var(--muted);
        font-size: 14px
    }

    .form-search {
        display: flex;
        gap: var(--gap);
        align-items: flex-end;
    }

    .form-search>div {
        flex: 1 1 200px;
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
    select,
    input[type="file"] {
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
    }

    .actions-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    button {
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

    .btn-ghost {
        background: transparent;
        border: 1px solid #e6e9f2;
        color: var(--muted);
        padding: 10px 16px;
        border-radius: 10px;
        text-decoration: none;
        display: inline-block;
        line-height: 1;
    }

    .btn-excel {
        background: #e34ae5ff;
        padding: 10px 16px;
        border-radius: 10px;
        color: #fff;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-excel:hover {
        background: #e50f9aff;
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
    </style>

    <div class="card">
        <div class="actions-top">
            <div>
                <h1><i class="fa-solid fa-utensils"></i> Cập nhật Thực Đơn</h1>
                <p class="lead">Cập nhật thông tin món ăn.</p>
            </div>
            <div class="actions">
                <a href="http://localhost/QLSP/Thucdon/danhsach" class="btn-ghost"><i
                        class="fa-solid fa-list"></i> Danh sách</a>
            </div>
        </div>

        <form method="post" action="http://localhost/QLSP/Thucdon/update" class="form-search"
            style="margin-bottom:30px;border:1px dashed #cbd5e1;padding:20px;border-radius:12px;background:#f8fafc"
            enctype="multipart/form-data">
            <div>
                <label for="txtMathucdon">Mã thực đơn <span style="color:red;">(*)</span></label>
                <input type="text" id="txtMathucdon" name="txtMathucdon" placeholder="Nhập mã thực đơn..."
                    value="<?php echo htmlspecialchars($data['ma_thuc_don']); ?>" readonly />
            </div>
            <div>
                <label for="txtTenmon">Tên món <span style="color:red;">(*)</span></label>
                <input type="text" id="txtTenmon" name="txtTenmon" placeholder="Nhập tên món..."
                    value="<?php echo htmlspecialchars($data['ten_mon']); ?>" />
            </div>
            <div>
                <label for="txtImage">Hình ảnh món</label>
                <input type="file" id="txtImage" name="txtImage" accept="image/*" />
                <div class="hint">Chọn hình ảnh mới (nếu muốn thay đổi)</div>
                <?php if(!empty($data['img_thuc_don'])): ?>
                <div style="margin-top:10px;">
                    <img src="<?php echo htmlspecialchars($data['img_thuc_don']); ?>" alt="<?php echo htmlspecialchars($data['ten_mon']); ?>" style="max-width:100px;max-height:100px;">
                </div>
                <?php endif; ?>
            </div>
            <div>
                <label for="txtGia">Giá</label>
                <input type="number" id="txtGia" name="txtGia" placeholder="Nhập giá..."
                    value="<?php echo htmlspecialchars($data['gia']); ?>" />
            </div>
            <div>
                <label for="ddlDanhmuc">Danh mục</label>
                <select id="ddlDanhmuc" name="ddlDanhmuc">
                    <option value="">-- Chọn danh mục --</option>
                    <?php
                    if(isset($data['dsdm'])){
                        while($row = mysqli_fetch_array($data['dsdm'])){
                            $selected = ($data['ma_danh_muc'] == $row['ma_danh_muc']) ? 'selected' : '';
                            echo '<option value="'.$row['ma_danh_muc'].'" '.$selected.'>'.htmlspecialchars($row['ten_danh_muc']).'</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="txtTrangthai">Trạng thái</label>
                <select id="txtTrangthai" name="txtTrangthai">
                    <option value="con_ban" <?php echo ($data['trang_thai'] == 'con_ban') ? 'selected' : ''; ?>>Còn bán</option>
                    <option value="het_ban" <?php echo ($data['trang_thai'] == 'het_ban') ? 'selected' : ''; ?>>Hết bán</option>
                </select>
            </div>

            <div class="actions" style="margin-top:0;">
                <button type="submit" class="btn-primary" name="btnCapnhat"><i class="fa-solid fa-sync"></i> Cập nhật</button>
                <a href="http://localhost/QLSP/Thucdon/danhsach" class="btn-ghost">Hủy</a>
            </div>
        </form>
    </div>
</body>

</html>