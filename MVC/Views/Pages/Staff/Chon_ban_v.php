<!DOCTYPE html>
<html lang="vi">

<body>
    <style>
    .tables-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        /* tăng cột */
        gap: 40px;
        margin-top: 30px;
    }

    .table-card {
        background: var(--card);
        border-radius: var(--radius);
        padding: 25px 20px;
        /* gọn lại cho cân */
        text-align: center;
        box-shadow: 0 6px 9px rgba(0, 0, 0, 0.05);
        cursor: pointer;
        transition: all 0.3s;
        border: 3px solid transparent;
        position: relative;
        overflow: hidden;

        min-height: 230px;
    }


    .table-card:hover {
        transform: translateY(-7.5px);
        /* -5px * 1.5 */
        box-shadow: 0 12px 22.5px rgba(0, 0, 0, 0.1);
    }

    .table-icon {
        font-size: 40px;
        margin-bottom: 10px;
    }

    .table-number {
        font-size: 20px;
        font-weight: 600;
        color: #253243;
        margin: 5px 0;
        word-wrap: break-word;
        overflow-wrap: break-word;
        line-height: 1.4;
    }

    .table-status {
        font-size: 14px;
        padding: 4px 8px;
        border-radius: 20px;
        display: inline-block;
    }

    .table-capacity {
        font-size: 16px;
        color: #253243;
        margin-top: 10px;
    }

    .table-card.available {
        border-color: #10b981;
        background: #f0fdf4;
    }

    .table-card.occupied {
        border-color: #f15a5aff;
        background: #edababff;
    }

    .table-card.reserved {
        border-color: #3b82f6;
        background: #eff6ff;
    }

    .status-available {
        background: #d1fae5;
        color: #065f46;
    }

    .status-occupied {
        background: #fed7aa;
        color: #c2410c;
    }

    .status-reserved {
        background: #bfdbfe;
        color: #1e40af;
    }


    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .filter-buttons {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .filter-btn {
        padding: 8px 16px;
        border-radius: 20px;
        border: 1px solid #e3e7ef;
        background: white;
        cursor: pointer;
    }

    .filter-btn.active {
        background: var(--accent);
        color: white;
        border-color: var(--accent);
    }

    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        background: #10b981;
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: none;
        z-index: 1000;
    }
    </style>

    <div class="card">
        <div class="section-header">
            <div>
                <h1><i class="fa-solid fa-chair"></i> Chọn bàn</h1>
                <p class="lead">Chọn bàn để tạo đơn hàng.</p>
            </div>
        </div>

        <div class="filter-buttons">
            <button class="filter-btn active" onclick="filterTables('all', this)">Tất cả</button>
            <button class="filter-btn" onclick="filterTables('available', this)">Trống</button>
            <button class="filter-btn" onclick="filterTables('occupied', this)">Đang sử dụng</button>
        </div>

        <div class="tables-grid" id="tablesGrid">
            <?php
            if (isset($data['tables']) && is_a($data['tables'], 'mysqli_result')):
                while ($table = mysqli_fetch_array($data['tables'])):
                    $status_class = '';
                    $status_text = '';
                    $status_style = '';

                    switch ($table['trang_thai_ban']) {
                        case 'trong':
                            $status_class = 'available';
                            $status_text = 'Trống';
                            $status_style = 'status-available';
                            break;
                        case 'dang_su_dung':
                            $status_class = 'occupied';
                            $status_text = 'Đang sử dụng';
                            $status_style = 'status-occupied';
                            break;
                        default:
                            $status_class = 'reserved';
                            $status_text = 'Đã đặt trước';
                            $status_style = 'status-reserved';
                    }
            ?>
            <div class="table-card <?php echo $status_class; ?>" data-status="<?php echo $table['trang_thai_ban']; ?>"
                onclick="selectTable('<?php echo $table['ma_ban']; ?>', '<?php echo urlencode($table['ten_ban']); ?>')">
                <div class="table-icon">☕</div>
                <div class="table-number"><?php echo htmlspecialchars($table['ten_ban']); ?></div>
                <div class="table-status <?php echo $status_style; ?>"><?php echo $status_text; ?></div>
                <div class="table-capacity"><?php echo $table['so_cho_ngoi']; ?> chỗ</div>
            </div>
            <?php
                endwhile;
            else:
                ?>
            <div class="hint">Không có dữ liệu bàn.</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="notification" id="notification">
        Đang chuyển đến trang đặt món...
    </div>

    <script>
    function selectTable(tableId, tableName) {
        // Hiển thị thông báo
        const notification = document.getElementById('notification');
        notification.style.display = 'block';

        // Redirect to order page for this table
        window.location.href = `http://localhost/QLSP/Banuong/order/${tableId}`;
    }

    function filterTables(status, btnElement) {
        // Cập nhật nút hoạt động - truyền phần tử nút như là tham số
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
        });

        // Activate the clicked button
        btnElement.classList.add('active');

        // Ánh xạ trạng thái hiển thị sang trạng thái cơ sở dữ liệu
        let dbStatus = status;
        if (status === 'available') dbStatus = 'trong';
        if (status === 'occupied') dbStatus = 'dang_su_dung';

        // Filter tables
        const tables = document.querySelectorAll('.table-card');
        tables.forEach(table => {
            if (status === 'all') {
                table.style.display = 'block';
            } else if (table.dataset.status === dbStatus) {
                table.style.display = 'block';
            } else {
                table.style.display = 'none';
            }
        });
    }

    // Khởi tạo với tất cả các bàn
    document.querySelectorAll('.table-card').forEach(table => {
        table.style.display = 'block';
    });
    </script>
</body>

</html>