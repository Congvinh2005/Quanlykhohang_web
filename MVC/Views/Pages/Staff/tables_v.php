<!DOCTYPE html>
<html lang="vi">

<body>
    <style>
    .table-status {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-available {
        background: #d1fae5;
        color: #065f46;
    }

    .status-occupied {
        background: #fed7aa;
        color: #c2410c;
    }

    .table-actions {
        display: flex;
        gap: 8px;
    }

    .table-btn {
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 12px;
        cursor: pointer;
        border: none;
    }

    .btn-assign {
        background: #3b82f6;
        color: white;
    }

    .btn-view {
        background: #10b981;
        color: white;
    }
    </style>

    <div class="card">
        <div class="actions-top">
            <div>
                <h1><i class="fa-solid fa-chair"></i> Quản lý bàn</h1>
                <p class="lead">Xem trạng thái và quản lý các bàn trong quán.</p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
            <?php
                if(isset($data['tables']) && is_a($data['tables'], 'mysqli_result')){
                    while($table = mysqli_fetch_array($data['tables'])) {
                        $status_text = $table['trang_thai_ban'] == 'trong' ? 'Trống' : 'Đang sử dụng';
                        $status_class = $table['trang_thai_ban'] == 'trong' ? 'status-available' : 'status-occupied';
            ?>
            <div style="background: var(--card); border-radius: var(--radius); padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h3 style="margin: 0; color: #253243; font-size: 18px;"><?php echo htmlspecialchars($table['ten_ban']); ?></h3>
                    <span class="table-status <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                </div>
                <p style="margin: 10px 0; color: var(--muted);">Mã bàn: <?php echo htmlspecialchars($table['ma_ban']); ?></p>
                <p style="margin: 10px 0; color: var(--muted);">Số chỗ ngồi: <?php echo htmlspecialchars($table['so_cho_ngoi']); ?></p>

                <div class="table-actions">
                    <?php if($table['trang_thai_ban'] == 'trong'): ?>
                        <button class="table-btn btn-assign">Gán đơn</button>
                    <?php else: ?>
                        <a href="http://localhost/QLSP/Staff/orders" class="table-btn btn-view">Xem đơn</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php
                    }
                } else {
                    echo '<p>Không có dữ liệu bàn.</p>';
                }
            ?>
        </div>
    </div>
</body>

</html>