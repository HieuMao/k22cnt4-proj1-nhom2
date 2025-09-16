<?php
session_start();
require 'connect.php';

// Lấy danh sách sản phẩm
$sql = "SELECT p.id, p.name, p.price, p.image, c.name AS category 
        FROM products p
        JOIN categories c ON p.category_id = c.id
        ORDER BY p.created_at DESC
        LIMIT 6";
$result = $mysqli->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang chủ - Project1</title>
    <style>
        body {font-family: Arial, sans-serif; background: #f5f5f5; margin:0; padding:0;}
        header {background: #333; color: #fff; padding: 15px; text-align: center;}
        nav {background: #444; padding: 10px; text-align: center;}
        nav a {color: #fff; margin: 0 15px; text-decoration: none; font-weight: bold;}
        nav a:hover {text-decoration: underline;}
        .container {width: 80%; margin: 20px auto;}
        h2 {color: #333; margin-bottom: 15px;}
        .products {display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px;}
        .product {background: #fff; padding: 15px; text-align: center; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);}
        .product img {width: 100%; max-height: 180px; object-fit: cover; border-radius: 5px;}
        .product h3 {font-size: 18px; margin: 10px 0;}
        .price {color: red; font-weight: bold; margin-bottom: 10px;}
        .product form {margin-top: 10px;}
        .product button {padding: 8px 15px; background: #28a745; color: #fff; border: none; border-radius: 5px; cursor: pointer;}
        .product button:hover {background: #218838;}
        footer {background: #333; color: #fff; padding: 15px; text-align: center; margin-top: 30px;}
        #cart-count {font-weight: bold; color: #ff0;}
    </style>
</head>
<body>

<header>
    <h1>💍 Cửa hàng Trang Sức - Project1</h1>
</header>

<nav>
    <a href="index.php">Trang chủ</a>
    <a href="#">Sản phẩm</a>
    <a href="#">Danh mục</a>
    <a href="user/cart.php">🛒 Giỏ hàng (<span id="cart-count">
        <?= isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'],'quantity')) : 0 ?>
    </span>)</a>

    <?php if (isset($_SESSION['username'])): ?>
        <a href="user/orders.php">Đơn hàng của tôi</a>
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="admin.php">Quản trị</a>
        <?php endif; ?>
        <span style="color: #fff; margin-left:20px;">Xin chào, <?= htmlspecialchars($_SESSION['username']); ?></span>
        <a href="logout.php">Đăng xuất</a>
    <?php else: ?>
        <a href="login.php">Đăng nhập</a>
    <?php endif; ?>
</nav>

<div class="container">
    <h2>Sản phẩm mới nhất</h2>
    <div class="products">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="product">
                    <img src="images/<?= htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['name']); ?>">
                    <h3><?= htmlspecialchars($row['name']); ?></h3>
                    <p class="price"><?= number_format($row['price']); ?> VNĐ</p>
                    <p>Danh mục: <?= htmlspecialchars($row['category']); ?></p>
                    
                    <!-- Form thêm giỏ hàng: đổi sang AJAX -->
                    <form class="add-to-cart-form">
                        <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                        <input type="hidden" name="product_name" value="<?= htmlspecialchars($row['name']); ?>">
                        <input type="hidden" name="product_price" value="<?= $row['price']; ?>">
                        <input type="hidden" name="product_image" value="<?= htmlspecialchars($row['image']); ?>">
                        <input type="number" name="quantity" value="1" min="1" style="width:50px;">
                        <button type="button" class="add-cart-btn">Thêm vào giỏ</button>
                    </form>

                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Chưa có sản phẩm nào!</p>
        <?php endif; ?>
    </div>
</div>

<footer>
    <p>&copy; 2025 Project1 - Website Trang Sức</p>
</footer>

<!-- JS: gọi API để thêm vào giỏ hàng không reload -->
<script>
document.querySelectorAll('.add-cart-btn').forEach(btn => {
    btn.addEventListener('click', async function () {
        const form = this.closest('.add-to-cart-form');
        const formData = new FormData(form);

        const res = await fetch('user/add_to_cart.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            document.getElementById('cart-count').textContent = data.totalQty;
            alert('✅ Đã thêm vào giỏ!');
        } else {
            alert('❌ Lỗi thêm sản phẩm');
        }
    });
});
</script>

</body>
</html>

<?php 
$mysqli->close();
?>
