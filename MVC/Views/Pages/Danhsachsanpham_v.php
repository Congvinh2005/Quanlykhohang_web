<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách Sản phẩm</title>
    <style>
    :root {
        --bg: #f5f7fb;
        --card: #ffffff;
        --accent: #2463ff;
        --muted: #6b7280;
        --radius: 12px;
        font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
    }

    * {
        box-sizing: border-box
    }

    .card {
        width: 100%;
        max-width: 1200px;
        background: var(--card);
        border-radius: var(--radius);
        box-shadow: 0 8px 30px rgba(24, 99, 255, 0.08);
        padding: 28px;
    }

    h1 {
        margin: 0 0 6px;
        font-size: 22px
    }

    p.lead {
        margin: 0 0 20px;
        color: var(--muted);
        font-size: 14px
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
        font-weight: 600;
        font-size: 14px
    }

    td {
        font-size: 14px
    }

    a {
        color: var(--accent);
        text-decoration: none
    }

    a:hover {
        text-decoration: underline
    }

    .btn-primary {
        display: inline-block;
        padding: 10px 16px;
        border-radius: 10px;
        background: var(--accent);
        color: #fff;
        text-decoration: none;
        font-size: 14px;
    }



    .btn-primary:hover {
        background: #174fe0;
        text-decoration: none
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
    </style>
</head>

<body>
    <main class="card" role="main">
        <div class="card">
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
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
            <div>
                <h1>Danh sách Sản phẩm</h1>
                <p class="lead">Tất cả sản phẩm trong hệ thống</p>
            </div>
            <a href="?url=Sanpham/Get_data" class="btn-primary">+ Thêm mới</a>
        </div>

        <?php if(isset($data['dulieu'])){ ?>
        <table>
            <thead>
                <tr>
                    <th>Mã SP</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá (VNĐ)</th>
                    <th>Số lượng</th>
                    <th>Nhà cung cấp</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php 
while($row = mysqli_fetch_array($data['dulieu'])){
?>
                <tr>
                    <td><?php echo $row['masp'] ?></td>
                    <td><?php echo $row['tensp'] ?></td>
                    <td><?php echo number_format($row['gia'], 0, ',', '.') ?></td>
                    <td><?php echo $row['soluong'] ?></td>
                    <td><?php echo isset($row['tenncc']) ? $row['tenncc'] : 'N/A' ?></td>
                    <td>
                        <!-- <a href="?url=Sanpham/sua/<?php echo $row['masp'] ?>">Sửa</a> |

                        <a href="?url=Sanpham/xoa/<?php echo $row['masp'] ?>"
                            onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a> -->

                        <a href="?url=Sanpham/sua/<?php echo $row['masp'] ?>"><button class="btn-edit">✏️
                                Sửa</button></a>
                        <a href="?url=Sanpham/xoa/<?php echo $row['masp'] ?>"
                            onclick="return confirm('Bạn có chắc chắn muốn xoá không?')"><button class="btn-delete">🗑️
                                Xóa</button></a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } else { ?>
        <p style="text-align:center;color:var(--muted);padding:40px">Không có sản phẩm nào.</p>
        <?php } ?>

    </main>
</body>

</html>