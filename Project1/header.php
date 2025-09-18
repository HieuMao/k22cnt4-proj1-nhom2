<?php
// header.php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<header>
    <h1>💍 Cửa Hàng Trang Sức</h1>
    <p>Nơi tôn vinh vẻ đẹp và đẳng cấp của bạn</p>
</header>

<nav>
    <a href="index.php" class="<?= basename($_SERVER['PHP_SELF'])==='index.php' ? 'active' : '' ?>">Trang chủ</a>
    <a href="products.php" class="<?= basename($_SERVER['PHP_SELF'])==='products.php' ? 'active' : '' ?>">Sản phẩm</a>
    <a href="user/cart.php">🛒 Giỏ (<span id="cart-count">
        <?= isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'],'quantity')) : 0 ?>
    </span>)</a>

    <?php if(isset($_SESSION['username'])): ?>
        <a href="user/orders.php">Đơn hàng</a>
        <?php if($_SESSION['role']==='admin'): ?><a href="admin.php">Quản trị</a><?php endif; ?>
        <span class="user-info">Xin chào, <?= htmlspecialchars($_SESSION['username']); ?></span>
        <a href="logout.php" class="logout-btn">Đăng xuất</a>
    <?php else: ?>
        <a href="login.php">Đăng nhập</a>
        <a href="user/register.php">Đăng ký</a>
    <?php endif; ?>
</nav>
