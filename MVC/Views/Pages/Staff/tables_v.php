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

        <div class="tables">
            <?php
                if(isset($data['tables']) && is_a($data['tables'], 'mysqli_result')){
                    while($table = mysqli_fetch_array($data['tables'])) {
                        $status_text = $table['trang_thai_ban'] == 'trong' ? 'Trống' : 'Đang sử dụng';
                        $status_color = $table['trang_thai_ban'] == 'trong' ? '#6bbf59' : '#f59e0b'; // Green: Trong, Amber: Dang su dung
            ?>
            <div class="table-card" style="background: <?php echo $status_color; ?>;">
                <i class="fa-solid fa-chair"></i>
                <h3><?php echo htmlspecialchars($table['ten_ban']); ?></h3>
                <span><?php echo $status_text; ?></span>
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