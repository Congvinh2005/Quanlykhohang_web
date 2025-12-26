<!DOCTYPE html>
<html lang="vi">

<body>

    <style>
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
    </style>

    <!-- <div class="card">
        <div
            style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:15px">
            <div>
                <h1 style="margin:0">Quản lý Sản phẩm</h1>
                <p class="lead" style="margin:0">Tra cứu và cập nhật dữ liệu kho hàng</p>
            </div>
            <div class="actions" style="margin:0">
                <a href="?url=Sanpham/import_form" class="btn btn-ghost"><i class="fa-solid fa-file-excel"></i> Nhập
                    Excel</a>
                <a href="?url=Sanpham/export" class="btn btn-primary"><i class="fa-solid fa-download"></i> Xuất
                    Excel</a>
            </div>
        </div>

        <form method="post" action="?url=Sanpham/tim"
            style="background:#f8fafc;padding:20px;border-radius:12px;border:1px dashed #cbd5e1;">
            <div>
                <label for="searchId">Mã sản phẩm</label>
                <input type="text" id="searchId" name="txtMasanpham" placeholder="Nhập mã SP..." />
            </div>
            <div>
                <label for="searchName">Tên sản phẩm</label>
                <input type="text" id="searchName" name="txtTensanpham" placeholder="Nhập tên SP..." />
            </div>
            <div class="actions">
                <button type="submit" class="btn btn-primary" name="btnTim"><i class="fa-solid fa-search"></i> Tìm
                    kiếm</button>
                <a href="?url=Sanpham/Get_data" class="btn btn-ghost">Làm mới</a>
            </div>
        </form>
    </div> -->

    <div style="display:grid;grid-template-columns: 1fr 2fr; gap:30px">

        <div class="card" style="height:fit-content">
            <h2><i class="fa-solid fa-pen-to-square"></i> Thông tin</h2>
            <form method="post" id="productForm" action="?url=Sanpham/ins" style="grid-template-columns: 1fr;">
                <div>
                    <label>Mã sản phẩm <span style="color:red">*</span></label>
                    <input type="text" name="txtMasanpham" placeholder="VD: SP001" required
                        value="<?php echo isset($data['masp']) ? $data['masp'] : '' ?>" />
                </div>
                <div>
                    <label>Tên sản phẩm <span style="color:red">*</span></label>
                    <input type="text" name="txtTensanpham" placeholder="Tên sản phẩm" required
                        value="<?php echo isset($data['tensp']) ? $data['tensp'] : '' ?>" />
                </div>
                <div>
                    <label>Giá bán (VNĐ)</label>
                    <input type="number" name="txtGia" placeholder="0"
                        value="<?php echo isset($data['gia']) ? $data['gia'] : '' ?>" />
                </div>
                <div>
                    <label>Số lượng</label>
                    <input type="number" name="txtSoluong" placeholder="0"
                        value="<?php echo isset($data['soluong']) ? $data['soluong'] : '' ?>" />
                </div>
                <div>
                    <label>Nhà cung cấp</label>
                    <select name="ddlNhacungcap" required>
                        <option value="">-- Chọn NCC --</option>
                        <?php
                        if(isset($data['dsncc'])){
                            mysqli_data_seek($data['dsncc'], 0); // Reset pointer if needed
                            while($row = mysqli_fetch_array($data['dsncc'])){
                                $selected = (isset($data['mancc']) && $data['mancc'] == $row['mancc']) ? 'selected' : '';
                                echo "<option value='".$row['mancc']."' $selected>".$row['tenncc']."</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="actions" style="justify-content:space-between">
                    <button type="reset" class="btn btn-ghost">Reset</button>
                    <button type="submit" class="btn btn-primary" name="btnLuu">Lưu lại</button>
                </div>
            </form>
        </div>

        <!-- <div class="card">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <h2>Danh sách hiện tại</h2>
                <span id="resultCount" class="hint" style="margin:0"></span>
            </div>

            <?php if(isset($data['dulieu'])){ $count = 0; ?>
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Mã SP</th>
                            <th>Tên SP</th>
                            <th>Giá</th>
                            <th>SL</th>
                            <th>Nhà cung cấp</th>
                            <th style="text-align:right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="spBody">
                        <?php while($row = mysqli_fetch_array($data['dulieu'])){ $count++; ?>
                        <tr>
                            <td><span style="font-weight:600;color:var(--primary)"><?php echo $row['masp'] ?></span>
                            </td>
                            <td><?php echo $row['tensp'] ?></td>
                            <td><?php echo number_format($row['gia'], 0, ',', '.') ?> ₫</td>
                            <td>
                                <span
                                    style="background:<?php echo $row['soluong']>0?'#d1fae5':'#fee2e2'; ?>;color:<?php echo $row['soluong']>0?'#065f46':'#991b1b'; ?>;padding:4px 8px;border-radius:6px;font-size:12px;font-weight:600">
                                    <?php echo $row['soluong'] ?>
                                </span>
                            </td>
                            <td><?php echo isset($row['tenncc']) ? $row['tenncc'] : 'N/A' ?></td>
                            <td style="text-align:right">
                              
                                <a href="?url=Sanpham/sua/<?php echo $row['masp'] ?>"><button class="btn-edit">✏️
                                        Sửa</button></a>
                                <a href="?url=Sanpham/xoa/<?php echo $row['masp'] ?>"
                                    onclick="return confirm('Bạn có chắc chắn muốn xoá không?')"><button
                                        class="btn-delete">🗑️
                                        Xóa</button></a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div> -->
        <script>
        // Giữ nguyên logic JS của bạn, chỉ cập nhật HTML render
        const idInput = document.getElementById('searchId');
        const nameInput = document.getElementById('searchName');
        const tbody = document.getElementById('spBody');
        const resultCount = document.getElementById('resultCount');
        let timer;

        function renderRows(rows) {
            tbody.innerHTML = '';
            rows.forEach(r => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                            <td><span style="font-weight:600;color:var(--primary)">${r.masp}</span></td>
                            <td>${r.tensp}</td>
                            <td>${Number(r.gia).toLocaleString('vi-VN')} ₫</td>
                            <td>
                                <span style="background:${r.soluong>0?'#d1fae5':'#fee2e2'};color:${r.soluong>0?'#065f46':'#991b1b'};padding:4px 8px;border-radius:6px;font-size:12px;font-weight:600">
                                    ${r.soluong}
                                </span>
                            </td>
                            <td>${r.tenncc || ''}</td>
                            <td style="text-align:right">
                                <a href="?url=Sanpham/sua/${r.masp}" style="color:var(--warning);margin-right:10px"><i class="fa-solid fa-pen"></i></a>
                                <a href="?url=Sanpham/xoa/${r.masp}" onclick="return confirm('Bạn có chắc muốn xóa?')" style="color:var(--danger)"><i class="fa-solid fa-trash"></i></a>
                            </td>`;
                tbody.appendChild(tr);
            });
        }
        async function fetchFilter() {
            const fd = new FormData();
            fd.append('q_masp', idInput.value || '');
            fd.append('q_tensp', nameInput.value || '');
            const res = await fetch('?url=Sanpham/tim_ajax', {
                method: 'POST',
                body: fd
            });
            const json = await res.json();
            renderRows((json && json.data) ? json.data : []);
        }

        function debounced() {
            clearTimeout(timer);
            timer = setTimeout(fetchFilter, 250);
        }
        idInput.addEventListener('input', debounced);
        nameInput.addEventListener('input', debounced);
        resultCount.textContent = '<?php echo $count; ?> bản ghi';
        </script>
        <?php } ?>
    </div>
    </div>
</body>

</html>