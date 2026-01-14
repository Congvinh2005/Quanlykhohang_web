<!DOCTYPE html>
<html lang="vi">

<body>
    <style>
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e3e7ef;
    }

    .categories-nav {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        overflow-x: auto;
        padding-bottom: 10px;
    }

    .category-btn {
        padding: 10px 20px;
        border-radius: 30px;
        background: #f8fafc;
        border: 1px solid #e3e7ef;
        cursor: pointer;
        white-space: nowrap;
    }

    .category-btn.active {
        background: var(--accent);
        color: white;
        border-color: var(--accent);
    }

    .order-container {
        display: flex;
        gap: 20px;
        height: calc(100vh - 240px);
        /* Adjust based on header height */
    }

    .menu-section {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        overflow-y: auto;
        flex: 1;
        padding-right: 5px;
    }

    .menu-grid::-webkit-scrollbar {
        width: 8px;
    }

    .menu-grid::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .menu-grid::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }

    .menu-grid::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    .menu-item {
        background: var(--card);
        border-radius: var(--radius);
        padding: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        text-align: center;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.2s;
        min-height: 200px;
    }

    .menu-item:hover {
        border-color: #a57825ff;
        transform: translateY(-1px);
    }

    .menu-item img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 10px;
    }

    .menu-item-name {
        font-weight: 600;
        margin: 5px 0;
        color: #253243;
    }

    .menu-item-price {
        color: var(--accent);
        font-weight: 600;
    }

    .order-cart {
        background: var(--card);
        border-radius: var(--radius);
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        width: 350px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .cart-header {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #253243;
        flex-shrink: 0;
    }

    .cart-items {
        flex: 1;
        overflow-y: auto;
        margin-bottom: 15px;
    }

    .cart-items::-webkit-scrollbar {
        width: 6px;
    }

    .cart-items::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .cart-items::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .cart-items::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    .cart-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #e3e7ef;
    }

    .cart-item-info {
        flex: 1;
    }

    .cart-item-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .quantity-control {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .quantity-btn {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 1px solid #e3e7ef;
        background: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .quantity-input {
        width: 40px;
        text-align: center;
        border: 1px solid #e3e7ef;
        border-radius: 6px;
        padding: 4px;
    }

    .remove-btn {
        color: #dc3545;
        background: none;
        border: none;
        cursor: pointer;
        margin-left: 10px;
    }

    .order-total {
        display: flex;
        justify-content: space-between;
        font-size: 18px;
        font-weight: 600;
        padding: 10px 0;
        border-top: 2px solid #e3e7ef;
        margin-top: 10px;
        flex-shrink: 0;
    }

    .order-actions {
        display: flex;
        gap: 12px;
        margin-top: 20px;
        justify-content: flex-end;
        flex-shrink: 0;
    }

    .btn-confirm {
        background: #65ed85;
        color: black;
        border: none;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-ghost {
        padding: 12px 20px;
        background: transparent;
        border: 1px solid var(--border);
        color: var(--gray);
        border-radius: 10px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.2s;
        text-decoration: none;
    }

    .btn-ghost:hover {
        background: var(--light);
        color: var(--dark);
    }

    .btn-reset {
        background: #f3f4f6;
        color: #6b7280;
        border: none;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
    }
    </style>

    <div class="card">
        <div class="order-header">
            <h1><i class="fa-solid fa-utensils"></i> Đặt món trực tiếp</h1>
        </div>

        <div class="categories-nav">
            <button class="category-btn active" onclick="filterByCategory('all')">Tất cả</button>
            <?php
            if (isset($data['categories'])) {
                while ($category = mysqli_fetch_array($data['categories'])) {
            ?>
            <button class="category-btn" onclick="filterByCategory('<?php echo $category['ma_danh_muc']; ?>')">
                <?php echo htmlspecialchars($category['ten_danh_muc']); ?>
            </button>
            <?php
                }
            }
            ?>
        </div>

        <div class="order-container">
            <div class="menu-section">
                <div class="menu-grid" id="menuGrid">
                    <?php
                    if (isset($data['menu_items'])) {
                        while ($item = mysqli_fetch_array($data['menu_items'])) {
                    ?>
                    <div class="menu-item" data-category="<?php echo $item['ma_danh_muc']; ?>"
                        onclick="addItemToCart('<?php echo $item['ma_thuc_don']; ?>', '<?php echo addslashes(htmlspecialchars($item['ten_mon'])); ?>', <?php echo $item['gia']; ?>)">
                        <?php if ($item['img_thuc_don']): ?>
                        <img src="<?php echo !empty($item['img_thuc_don']) ? '/qlsp/Public/Pictures/thucdon/' . htmlspecialchars($item['img_thuc_don']) : '/qlsp/Public/Pictures/no-image.png'; ?>"
                            alt="<?php echo htmlspecialchars($item['ten_mon']); ?>" />
                        <?php else: ?>
                        <div
                            style="width:100%;height:120px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;border-radius:8px;">
                            <i class="fa-solid fa-utensils" style="font-size:40px;color:#d1d5db;"></i>
                        </div>
                        <?php endif; ?>
                        <div class="menu-item-name"><?php echo htmlspecialchars($item['ten_mon']); ?></div>
                        <div class="menu-item-price"><?php echo number_format($item['gia'], 0, ',', '.'); ?> ₫</div>
                        <div class="menu-item-quantity">
                            Hiện có <?php echo htmlspecialchars($item['so_luong']); ?>
                        </div>
                    </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="order-cart">
                <div class="cart-header">
                    <i class="fa-solid fa-shopping-cart"></i> Giỏ hàng
                </div>

                <div class="cart-items" id="cartItems">
                    <!-- Cart items will be added here dynamically -->
                    <div id="emptyCartMessage" style="text-align: center; color: #595e68; padding: 20px;">Giỏ hàng trống
                    </div>
                </div>

                <div class="order-total">
                    <span>Tổng cộng:</span>
                    <span id="totalAmount">0 ₫</span>
                </div>

                <div style="margin-top: 15px;">
                    <label for="orderNotes" style="display: block; margin-bottom: 5px; font-weight: 600;">Ghi chú đơn
                        hàng:</label>
                    <textarea id="orderNotes" placeholder="Ví dụ: ít đá, ít đường,..."
                        style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #e3e7ef; resize: vertical; min-height: 60px;"></textarea>
                </div>

                <div class="order-actions">
                    <button class="btn-reset" onclick="clearCart()">Làm mới</button>
                    <button class="btn-confirm" onclick="confirmOrder()">Tạo đơn hàng</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    let cart = {};

    // Tải giỏ hàng từ máy chủ nếu có sẵn
    <?php if (isset($data['current_cart']) && !empty($data['current_cart'])): ?>
    cart = <?php echo json_encode($data['current_cart']); ?>;
    <?php endif; ?>

    // Khởi tạo giỏ hàng khi tải trang
    document.addEventListener('DOMContentLoaded', function() {
        updateCart();
        // Cập nhật giỏ hàng trong phiên định kỳ
        setInterval(saveCartToSession, 5000); // Lưu mỗi 5 giây
    });


    function addItemToCart(id, name, price) {
        // Tìm mục menu để lấy hình ảnh và số lượng hiện có
        const menuItem = document.querySelector(`.menu-item[onclick*="'${id}'"]`);
        let imgSrc = '';
        let availableQuantity = 0;

        if (menuItem) {
            const imgElement = menuItem.querySelector('img');
            if (imgElement) {
                imgSrc = imgElement.src;
            }

            // Lấy số lượng hiện có từ mục menu
            const availableQuantityElement = menuItem.querySelector('.menu-item-quantity');
            const availableText = availableQuantityElement ? availableQuantityElement.textContent : '0';
            availableQuantity = parseInt(availableText.match(/\d+/)[0]) || 0;
        }

        // Kiểm tra nếu giỏ hàng đã có mặt hàng này
        if (cart[id]) {
            // Kiểm tra nếu thêm một nữa sẽ vượt quá số lượng hiện có
            if (cart[id].quantity >= availableQuantity) {
                alert(`Số lượng hiện có chỉ còn ${availableQuantity} món.`);
                return;
            }
            cart[id].quantity++;
        } else {
            // Kiểm tra nếu thêm một mặt hàng có trong số lượng hiện có
            if (availableQuantity < 1) {
                alert(`Món này đã hết hàng.`);
                return;
            }
            cart[id] = {
                id: id,
                name: name,
                price: price,
                quantity: 1,
                img: imgSrc
            };
        }
        updateCart();
        saveCartToSession();
    }

    function updateCart() {
        const cartItemsDiv = document.getElementById('cartItems');
        const totalAmountSpan = document.getElementById('totalAmount');
        let total = 0;
        let cartHtml = '';

        for (const id in cart) {
            const item = cart[id];
            const itemTotal = item.price * item.quantity;
            total += itemTotal;

            // Lấy số lượng hiện có từ mục menu để hiển thị trong giỏ hàng
            const menuItem = document.querySelector(`.menu-item[onclick*="'${item.id}'"]`);
            let availableQuantity = 0;

            if (menuItem) {
                const availableQuantityElement = menuItem.querySelector('.menu-item-quantity');
                const availableText = availableQuantityElement ? availableQuantityElement.textContent : '0';
                availableQuantity = parseInt(availableText.match(/\d+/)[0]) || 0;
            }

            // Create image element if image exists
            const imgHtml = item.img ?
                `<img src="${item.img}" alt="${item.name}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; margin-right: 10px;" />` :
                `<div style="width: 40px; height: 40px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; border-radius: 4px; margin-right: 10px;"><i class="fa-solid fa-utensils" style="color: #d1d5db;"></i></div>`;

            cartHtml += `
                    <div class="cart-item">
                        <div style="display: flex; align-items: center; gap: 10px; flex: 1;">
                            ${imgHtml}
                            <div class="cart-item-info">
                                <div>${item.name}</div>
                                <div style="color: #6b7280; font-size: 14px;">${item.price.toLocaleString('vi-VN')} ₫ × ${item.quantity} (Còn ${availableQuantity} món)</div>
                            </div>
                        </div>
                        <div class="cart-item-actions">
                            <div class="quantity-control">
                                <button class="quantity-btn" onclick="updateQuantityFromCart('${item.id}', -1)">-</button>
                                <input type="text" class="quantity-input" value="${item.quantity}" readonly />
                                <button class="quantity-btn" onclick="updateQuantityFromCart('${item.id}', 1)">+</button>
                            </div>
                            <button class="remove-btn" onclick="removeItem('${item.id}')"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
                `;
        }

        // Cập nhật hiển thị giỏ hàng
        const emptyCartMsg = document.getElementById('emptyCartMessage');
        if (cartHtml) {
            if (emptyCartMsg) emptyCartMsg.style.display = 'none';
            cartItemsDiv.innerHTML = cartHtml;
        } else {
            if (emptyCartMsg) emptyCartMsg.style.display = 'block';
            cartItemsDiv.innerHTML =
                '<div id="emptyCartMessage" style="text-align: center; color: #9ca3af; padding: 20px;">Giỏ hàng trống</div>';
        }

        if (totalAmountSpan) {
            totalAmountSpan.textContent = total.toLocaleString('vi-VN') + ' ₫';
        }
    }

    function filterByCategory(categoryId) {
        // Cập nhật nút hoạt động
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.classList.add('active');

        // Filter menu items
        const items = document.querySelectorAll('.menu-item');
        items.forEach(item => {
            if (categoryId === 'all' || item.dataset.category === categoryId) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function clearCart() {
        cart = {};
        // Đặt lại tất cả các ô nhập trong lưới menu về 0
        const inputFields = document.querySelectorAll('.quantity-input');
        inputFields.forEach(input => {
            if (input.dataset
                .id) { // Chỉ đặt lại các ô nhập mục menu, không đặt lại các ô nhập số lượng giỏ hàng
                input.value = 0;
            }
        });
        updateCart();
        saveCartToSession();
    }

    function saveCartToSession() {
        if (Object.keys(cart).length > 0) {
            const cartData = {
                ma_ban: 'Online', // Special identifier for customer orders without table
                cart: cart
            };

            fetch('http://localhost/QLSP/Banuong/update_cart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(cartData)
                })
                .then(response => response.json())
                .then(data => {
                    // console.log('Giỏ hàng đã lưu vào phiên:', data.message);
                })
                .catch(error => {
                    console.error('Error saving cart:', error);
                });
        }
    }

    function confirmOrder() {
        if (Object.keys(cart).length === 0) {
            alert('Vui lòng chọn ít nhất một món ăn!');
            return;
        }

        // Lấy ghi chú đơn hàng từ ô văn bản
        const orderNotes = document.getElementById('orderNotes').value.trim();

        // Prepare order data - use a special table ID for customer orders
        const orderData = {
            ma_ban: 'Online', // Special identifier for customer orders without table
            cart: cart,
            ghi_chu: orderNotes // Include order notes
        };

        // Send to server to create order
        fetch('http://localhost/QLSP/Banuong/create_order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(orderData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Tạo đơn hàng thành công!');
                    // Redirect to customer order detail page with the order ID
                    window.location.href = 'http://localhost/QLSP/Banuong/order_detail/' + data.order_id;
                } else {
                    alert('Tạo đơn hàng thất bại: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi tạo đơn hàng!');
            });
    }
    </script>
</body>

</html>