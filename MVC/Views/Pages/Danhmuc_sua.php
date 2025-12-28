<!DOCTYPE html>
<html lang="vi">

<body>
    <style>
    /* Simple form styles following existing pattern */
    .card {
        width: 100%;
        background: #fff;
        padding: 28px;
        border-radius: 12px
    }

    label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600
    }

    input,
    select {
        width: 100%;
        padding: 10px;
        border-radius: 10px;
        border: 1px solid #e3e7ef
    }

    .actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px
    }

    .btn-back {
        background: #6b7280;
        color: #fff;
        padding: 8px 15px;
        border-radius: 10px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px
    }

    .btn-ghost {
        background: transparent;
        border: 1px solid #e6e9f2;
        color: #6b7280;
        padding: 10px 16px;
        border-radius: 10px
    }

    .btn-primary {
        background: #2463ff;
        color: #fff;
        padding: 10px 16px;
        border-radius: 10px;
        border: 0
    }
    </style>

    <div class="card">
        <h1>Sửa Danh mục</h1>
        <p class="lead">Chỉnh sửa thông tin danh mục.</p>
        <form method="post" action="http://localhost/QLSP/Danhmuc/update">
            <div>
                <label>Mã danh mục <span style="color:red">*</span></label>
                <input type="text" name="txtMadanhmuc" required readonly
                    value="<?php echo isset($data['ma_danh_muc'])?htmlspecialchars($data['ma_danh_muc']):'' ?>" />
            </div>
            <div>
                <label>Tên danh mục <span style="color:red">*</span></label>
                <input type="text" name="txtTendanhmuc" required
                    value="<?php echo isset($data['ten_danh_muc'])?htmlspecialchars($data['ten_danh_muc']):'' ?>" />
            </div>
            <div>
                <label>Hình ảnh</label>
                <input type="text" name="txtImage"
                    value="<?php echo isset($data['image'])?htmlspecialchars($data['image']):'' ?>" />
            </div>

            <div class="actions">
                <a href="http://localhost/QLSP/Danhmuc/danhsach" class="btn-back"><i class="fa-solid fa-arrow-left"></i>
                    Quay lại</a>
                <div style="display:flex;gap:12px">
                    <button type="reset" class="btn-ghost">Reset</button>
                    <button type="submit" name="btnCapnhat" class="btn-primary">Cập nhật</button>
                </div>
            </div>
        </form>
    </div>

</body>

</html>