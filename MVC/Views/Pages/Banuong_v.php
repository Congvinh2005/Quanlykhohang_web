<!DOCTYPE html>
<html lang="vi">

<body>
    <style>
    /* Reuse styles similar to Danhmuc */
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
                <h1><i class="fa-solid fa-chair"></i> Quản lý Bàn Uống</h1>
                <p class="lead">Thêm, sửa, xóa thông tin bàn uống.</p>
            </div>
            <div class="actions">
                <a href="http://localhost/QLSP/Banuong/danhsach" class="btn-ghost"><i
                        class="fa-solid fa-arrow-left"></i> Trở lại</a>
            </div>
        </div>

        <form method="post" action="http://localhost/QLSP/Banuong/ins" class="form-search"
            style="margin-bottom:30px;border:1px dashed #cbd5e1;padding:20px;border-radius:12px;background:#f8fafc">
            <div>
                <label for="txtMaban">Mã bàn <span style="color:red;">(*)</span></label>
                <input type="text" id="txtMaban" name="txtMaban" placeholder="Nhập mã bàn..." required
                    value="<?php echo isset($data['ma_ban']) ? htmlspecialchars($data['ma_ban']) : ''; ?>" />
            </div>
            <div>
                <label for="txtTenban">Tên bàn</label>
                <input type="text" id="txtTenban" name="txtTenban" placeholder="Nhập tên bàn..." required
                    value="<?php echo isset($data['ten_ban']) ? htmlspecialchars($data['ten_ban']) : ''; ?>" />
            </div>
            <div>
                <label for="txtSochongoi">Số chỗ ngồi</label>
                <input type="number" id="txtSochongoi" name="txtSochongoi" placeholder="Nhập số chỗ ngồi..." required
                    value="<?php echo isset($data['so_cho_ngoi']) ? htmlspecialchars($data['so_cho_ngoi']) : ''; ?>" />
            </div>
            <div>
                <label for="txtTrangthai">Trạng thái bàn</label>
                <select id="txtTrangthai" name="txtTrangthai">
                    <option value="trong"
                        <?php echo (isset($data['trang_thai_ban']) && $data['trang_thai_ban'] == 'trong') ? 'selected' : ''; ?>>
                        Trống</option>
                    <option value="dang_su_dung"
                        <?php echo (isset($data['trang_thai_ban']) && $data['trang_thai_ban'] == 'dang_su_dung') ? 'selected' : ''; ?>>
                        Đang sử dụng</option>
                </select>
            </div>

            <div class="actions" style="margin-top:0;">
                <button type="submit" class="btn-primary" name="btnLuu"><i class="fa-solid fa-save"></i> Lưu</button>
                <a href="http://localhost/QLSP/Banuong/danhsach" class="btn-ghost">Hủy</a>
            </div>
        </form>
    </div>
</body>

</html>