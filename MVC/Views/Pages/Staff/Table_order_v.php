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

    .table-info {
        font-size: 18px;
        font-weight: 600;
        color: #253243;
    }

    .table-status {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        margin-left: 15px;
    }

    .status-available {
        background: #d1fae5;
        color: #065f46;
    }

    .status-occupied {
        background: #fed7aa;
        color: #c2410c;
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
        height: calc(100vh - 300px);
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
        background: var(--accent);
        color: white;
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
            <h1><i class="fa-solid fa-utensils"></i> Đặt món cho
                <span class="table-info">
                    <?php echo htmlspecialchars($data['table_info']['ten_ban']); ?>
                    (<?php echo htmlspecialchars($data['table_info']['ma_ban']); ?>)
                </span>
                <span
                    class="table-status
                    <?php echo ($data['table_info']['trang_thai_ban'] == 'trong') ? 'status-available' : 'status-occupied'; ?>">
                    <?php echo ($data['table_info']['trang_thai_ban'] == 'trong') ? 'Trống' : 'Đang sử dụng'; ?>
                </span>
            </h1>

        </div>

        <div class="categories-nav">
            <button class="category-btn active" onclick="filterByCategory('all')">Tất cả</button>
            <?php
                if(isset($data['categories'])){
                    while($category = mysqli_fetch_array($data['categories'])){
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
                        if(isset($data['menu_items'])){
                            while($item = mysqli_fetch_array($data['menu_items'])){
                    ?>
                    <div class="menu-item" data-category="<?php echo $item['ma_danh_muc']; ?>"
                         onclick="addItemToCart('<?php echo $item['ma_thuc_don']; ?>', '<?php echo addslashes(htmlspecialchars($item['ten_mon'])); ?>', <?php echo $item['gia']; ?>)">
                        <?php if($item['img_thuc_don']): ?>
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
                    <div id="emptyCartMessage" style="text-align: center; color: #9ca3af; padding: 20px;">Giỏ hàng trống
                    </div>
                </div>

                <div class="order-total">
                    <span>Tổng cộng:</span>
                    <span id="totalAmount">0 ₫</span>
                </div>

                <div class="order-actions">
                    <a href="http://localhost/QLSP/Staff/table" class="btn-ghost"><i class="fa-solid fa-arrow-left"></i>
                        Quay
                        lại</a>
                    <button class="btn-reset" onclick="clearCart()">Làm mới</button>
                    <button class="btn-confirm" onclick="confirmOrder()">Tạo đơn hàng</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    let cart = {};

    // Load cart from server if available
    <?php if(isset($data['current_cart']) && !empty($data['current_cart'])): ?>
    cart = <?php echo json_encode($data['current_cart']); ?>;
    <?php endif; ?>

    // Initialize cart on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateCart();
        // Update cart in session periodically
        setInterval(saveCartToSession, 5000); // Save every 5 seconds
    });

    function addToCartFromInput(inputElement) {
        const id = inputElement.dataset.id;
        const name = inputElement.dataset.name;
        const price = parseFloat(inputElement.dataset.price);
        const quantity = parseInt(inputElement.value);

        // Get the image URL from the menu item
        const menuItem = inputElement.closest('.menu-item');
        const imgElement = menuItem.querySelector('img');
        const imgSrc = imgElement ? imgElement.src : '';

        // Get available quantity from menu item
        const availableQuantityElement = menuItem.querySelector('.menu-item-quantity');
        const availableText = availableQuantityElement ? availableQuantityElement.textContent : '0';
        const availableQuantity = parseInt(availableText.match(/\d+/)[0]) || 0;

        // Check if requested quantity exceeds available quantity
        if (quantity > availableQuantity) {
            alert(`Số lượng yêu cầu vượt quá số lượng hiện có (${availableQuantity} món).`);
            inputElement.value = availableQuantity;
            return;
        }

        if (quantity > 0) {
            // Add or update item in cart
            cart[id] = {
                id: id,
                name: name,
                price: price,
                quantity: quantity,
                img: imgSrc
            };
        } else {
            // Remove item if quantity is 0
            delete cart[id];
        }

        updateCart();
        saveCartToSession();
    }

    function addItemToCart(id, name, price) {
        // Find the menu item to get its image and available quantity
        const menuItem = document.querySelector(`.menu-item[onclick*="'${id}'"]`);
        let imgSrc = '';
        let availableQuantity = 0;

        if (menuItem) {
            const imgElement = menuItem.querySelector('img');
            if (imgElement) {
                imgSrc = imgElement.src;
            }

            // Get available quantity from menu item
            const availableQuantityElement = menuItem.querySelector('.menu-item-quantity');
            const availableText = availableQuantityElement ? availableQuantityElement.textContent : '0';
            availableQuantity = parseInt(availableText.match(/\d+/)[0]) || 0;
        }

        // Check if cart already has this item
        if (cart[id]) {
            // Check if adding one more would exceed available quantity
            if (cart[id].quantity >= availableQuantity) {
                alert(`Số lượng hiện có chỉ còn ${availableQuantity} món.`);
                return;
            }
            cart[id].quantity++;
        } else {
            // Check if adding one item is within available quantity
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

    function updateQuantity(id, change) {
        if (cart[id]) {
            // Get the menu item to check available quantity
            const menuItem = document.querySelector(`.menu-item[onclick*="'${id}'"]`);
            let availableQuantity = 0;

            if (menuItem) {
                const availableQuantityElement = menuItem.querySelector('.menu-item-quantity');
                const availableText = availableQuantityElement ? availableQuantityElement.textContent : '0';
                availableQuantity = parseInt(availableText.match(/\d+/)[0]) || 0;
            }

            const newQuantity = cart[id].quantity + change;

            // Check if the new quantity exceeds available quantity
            if (newQuantity > availableQuantity) {
                alert(`Số lượng hiện có chỉ còn ${availableQuantity} món.`);
                return;
            }

            cart[id].quantity = newQuantity;

            if (cart[id].quantity <= 0) {
                delete cart[id];
            } else {
                // Update the input field in the menu grid
                const inputField = document.querySelector(`input[data-id="${id}"]`);
                if (inputField) {
                    inputField.value = cart[id].quantity;
                }
            }
            updateCart();
            saveCartToSession();
        }
    }

    function removeItem(id) {
        delete cart[id];
        // Reset the input field in the menu grid to 0
        const inputField = document.querySelector(`input[data-id="${id}"]`);
        if (inputField) {
            inputField.value = 0;
        }
        updateCart();
        saveCartToSession();
    }

    // Function to update quantity from cart controls
    function updateQuantityFromCart(id, change) {
        if (cart[id]) {
            // Get the menu item to check available quantity
            const menuItem = document.querySelector(`.menu-item[onclick*="'${id}'"]`);
            let availableQuantity = 0;

            if (menuItem) {
                const availableQuantityElement = menuItem.querySelector('.menu-item-quantity');
                const availableText = availableQuantityElement ? availableQuantityElement.textContent : '0';
                availableQuantity = parseInt(availableText.match(/\d+/)[0]) || 0;
            }

            const newQuantity = cart[id].quantity + change;

            // Check if the new quantity exceeds available quantity
            if (newQuantity > availableQuantity) {
                alert(`Số lượng hiện có chỉ còn ${availableQuantity} món.`);
                return;
            }

            // Check if the new quantity is not negative
            if (newQuantity < 0) {
                return; // Don't allow negative quantities
            }

            cart[id].quantity = newQuantity;

            if (cart[id].quantity <= 0) {
                delete cart[id];
            }

            // Update the input field in the menu grid
            const inputField = document.querySelector(`input[data-id="${id}"]`);
            if (inputField) {
                inputField.value = cart[id] ? cart[id].quantity : 0;
            }
            updateCart();
            saveCartToSession();
        }
    }

    // Function to update quantity from menu grid input
    function addToCartFromInput(inputElement) {
        const id = inputElement.dataset.id;
        const name = inputElement.dataset.name;
        const price = parseFloat(inputElement.dataset.price);
        const quantity = parseInt(inputElement.value);

        // Get the image URL from the menu item
        const menuItem = inputElement.closest('.menu-item');
        const imgElement = menuItem.querySelector('img');
        const imgSrc = imgElement ? imgElement.src : '';

        // Get available quantity from menu item
        const availableQuantityElement = menuItem.querySelector('.menu-item-quantity');
        const availableText = availableQuantityElement ? availableQuantityElement.textContent : '0';
        const availableQuantity = parseInt(availableText.match(/\d+/)[0]) || 0;

        // Check if requested quantity exceeds available quantity
        if (quantity > availableQuantity) {
            alert(`Số lượng yêu cầu vượt quá số lượng hiện có (${availableQuantity} món).`);
            inputElement.value = availableQuantity;
            return;
        }

        if (quantity > 0) {
            // Add or update item in cart
            cart[id] = {
                id: id,
                name: name,
                price: price,
                quantity: quantity,
                img: imgSrc
            };
        } else {
            // Remove item if quantity is 0
            delete cart[id];
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

            // Get available quantity from menu item to show in cart
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

        // Update cart display
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
        // Update active button
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
        // Reset all input fields in the menu grid to 0
        const inputFields = document.querySelectorAll('.quantity-input');
        inputFields.forEach(input => {
            if (input.dataset.id) { // Only reset the menu item inputs, not the cart quantity inputs
                input.value = 0;
            }
        });
        updateCart();
        saveCartToSession();
    }

    function saveCartToSession() {
        if (Object.keys(cart).length > 0) {
            const cartData = {
                ma_ban: '<?php echo $data['table_info']['ma_ban']; ?>',
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
                    // console.log('Cart saved to session:', data.message);
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

        // Prepare order data
        const orderData = {
            ma_ban: '<?php echo $data['table_info']['ma_ban']; ?>',
            cart: cart
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
                    // Redirect to order detail page with the order ID
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