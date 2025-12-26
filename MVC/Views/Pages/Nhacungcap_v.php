<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Nhà cung cấp</title>
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
    input[type="email"],
    input[type="tel"],
    textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e3e7ef;
        border-radius: 10px;
        background: #fbfdff;
        font-size: 14px;
        outline: none;
    }

    input:focus,
    textarea:focus {
        box-shadow: 0 0 0 4px rgba(36, 99, 255, 0.08);
        border-color: var(--accent);
    }

    textarea {
        min-height: 90px;
        resize: vertical
    }

    .actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        grid-column: 1 / -1;
        margin-top: 6px
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

    .btn-primary:hover {
        background: #174fe0;
    }

    .btn-ghost {
        background: transparent;
        border: 1px solid #e6e9f2;
        color: var(--muted)
    }

    .btn-edit {
        background: #ffc107;
        padding: 6px 10px;
        border-radius: 6px;
        margin-right: 5px;
        color: #fff;
    }

    .btn-delete {
        background: #dc3545;
        padding: 6px 10px;
        border-radius: 6px;
        color: #fff;
    }

    table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse
    }

    th,
    td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #e3e7ef
    }

    th {
        background: #f8fafc;
        font-weight: 600
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

    .hint {
        font-size: 12px;
        color: var(--muted);
        margin-top: 6px
    }
    </style>
</head>

<body>
    <main class="card" role="main">
        <h1>Quản lý Nhà cung cấp</h1>
        <p class="lead">Tìm kiếm và quản lý thông tin nhà cung cấp.</p>

        <!-- Search Form - Moved to Top -->
        <form method="post" action="?url=Nhacungcap/tim"
            style="margin-bottom:30px;border:2px solid #e3e7ef;padding:20px;border-radius:10px;background:#f8fafc">
            <h2 style="margin:0 0 15px;font-size:18px">Tìm kiếm nhà cung cấp</h2>
            <div>
                <label for="searchId">Mã nhà cung cấp</label>
                <input type="text" id="searchId" name="txtMancc" placeholder="Nhập mã cần tìm" />
            </div>

            <div>
                <label for="searchName">Tên nhà cung cấp</label>
                <input type="text" id="searchName" name="txtTenncc" placeholder="Nhập tên cần tìm" />
            </div>

            <div class="actions">
                <button type="submit" class="btn-primary" name="btnTim">Tìm kiếm</button>
                <a href="?url=Nhacungcap/Get_data" class="btn-ghost" style="text-decoration:none">Xóa tìm</a>
            </div>
        </form>

        <div class="actions" style="margin-bottom:20px">
            <a href="?url=Nhacungcap/import_form" class="btn-ghost" style="text-decoration:none">Nhập Excel</a>
            <a href="?url=Nhacungcap/export" class="btn-primary" style="text-decoration:none">Xuất Excel</a>
        </div>

        <h2 style="margin:20px 0 15px;font-size:18px">Thêm nhà cung cấp mới</h2>
        <form method="post" id="nccForm" novalidate action="?url=Nhacungcap/ins">
            <div>
                <label for="nccId">Mã nhà cung cấp *</label>
                <input type="text" id="nccId" name="txtMancc" placeholder="VD: NCC001" required
                    value="<?php echo isset($data['mancc']) ? $data['mancc'] : '' ?>" />
            </div>

            <div>
                <label for="nccName">Tên nhà cung cấp *</label>
                <input type="text" id="nccName" name="txtTenncc" placeholder="Nhập tên nhà cung cấp" required
                    value="<?php echo isset($data['tenncc']) ? $data['tenncc'] : '' ?>" />
            </div>

            <div>
                <label for="phone">Điện thoại</label>
                <input type="tel" id="phone" name="txtDienthoai" placeholder="VD: 0912345678"
                    value="<?php echo isset($data['dienthoai']) ? $data['dienthoai'] : '' ?>" />
            </div>

            <div class="full">
                <label for="address">Địa chỉ</label>
                <textarea id="address" name="txtDiachi"
                    placeholder="Nhập địa chỉ nhà cung cấp"><?php echo isset($data['diachi']) ? $data['diachi'] : '' ?></textarea>
            </div>

            <div class="actions">
                <button type="reset" class="btn-ghost">Làm mới</button>
                <button type="submit" class="btn-primary" name="btnLuu">Lưu thông tin</button>
            </div>
        </form>

        <!-- Results Table -->
        <?php if(isset($data['dulieu'])){
        $count = 0;
?>
        <div style="display:flex;justify-content:space-between;align-items:center;margin:10px 0">
            <strong>Kết quả:</strong>
            <span id="resultCount" class="hint"></span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Mã NCC</th>
                    <th>Tên nhà cung cấp</th>
                    <th>Địa chỉ</th>
                    <th>Điện thoại</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="nccBody">
                <?php 
while($row = mysqli_fetch_array($data['dulieu'])){
        $count++;
?>
                <tr>
                    <td><?php echo $row['mancc'] ?></td>
                    <td><?php echo $row['tenncc'] ?></td>
                    <td><?php echo $row['diachi'] ?></td>
                    <td><?php echo $row['dienthoai'] ?></td>
                    <td>
                        <!-- <a href="?url=Nhacungcap/sua/<?php echo $row['mancc'] ?>">Sửa</a> |
                        <a href="?url=Nhacungcap/xoa/<?php echo $row['mancc'] ?>"
                            onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a> -->
                        <a href="?url=Nhacungcap/sua/<?php echo $row['mancc'] ?>"><button class="btn-edit">✏️
                                Sửa</button></a>
                        <a href="?url=Nhacungcap/xoa/<?php echo $row['mancc'] ?>"
                            onclick="return confirm('Bạn có chắc chắn muốn xoá không?')"><button class="btn-delete">🗑️
                                Xóa</button></a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <script>
        // Debounced AJAX filtering to server
        const idInput = document.getElementById('searchId');
        const nameInput = document.getElementById('searchName');
        const tbody = document.getElementById('nccBody');
        const resultCount = document.getElementById('resultCount');
        let timer;

        function renderRows(rows) {
            tbody.innerHTML = '';
            rows.forEach(r => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
            <td>${r.mancc}</td>
            <td>${r.tenncc}</td>
            <td>${r.diachi || ''}</td>
            <td>${r.dienthoai || ''}</td>
            <td>
                <a href="?url=Nhacungcap/sua/${r.mancc}">Sửa</a> |
                <a href="?url=Nhacungcap/xoa/${r.mancc}" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
            </td>`;
                tbody.appendChild(tr);
            });
        }
        async function fetchFilter() {
            const fd = new FormData();
            fd.append('q_mancc', idInput.value || '');
            fd.append('q_tenncc', nameInput.value || '');
            const res = await fetch('?url=Nhacungcap/tim_ajax', {
                method: 'POST',
                body: fd
            });
            const json = await res.json();
            const rows = (json && json.data) ? json.data : [];
            renderRows(rows);
            resultCount.textContent = rows.length + ' dòng';
        }

        function debounced() {
            clearTimeout(timer);
            timer = setTimeout(fetchFilter, 250);
        }
        idInput.addEventListener('input', debounced);
        nameInput.addEventListener('input', debounced);
        // init
        resultCount.textContent = '<?php echo $count; ?> dòng';
        </script>
        <?php } ?>
        <?php if(isset($data['dulieu']) && mysqli_num_rows($data['dulieu']) === 0){ ?>
        <div class="hint">Không có kết quả phù hợp.</div>
        <?php } ?>

    </main>
</body>

</html>