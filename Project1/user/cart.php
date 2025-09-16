<?php
session_start();
require '../connect.php';

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Xóa sản phẩm
if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit();
}

// Cập nhật số lượng
if (isset($_POST['update'])) {
    foreach ($_POST['qty'] as $id => $qty) {
        $qty = (int)$qty;
        if ($qty > 0) {
            $_SESSION['cart'][$id]['quantity'] = $qty;
        } else {
            unset($_SESSION['cart'][$id]);
        }
    }
    header("Location: cart.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Giỏ hàng</title>
<style>
    body {font-family: Arial, sans-serif; background:#f9f9f9; margin:0; padding:0;}
    header {background:#333; color:#fff; padding:15px; text-align:center;}
    .container {width:80%; margin:20px auto;}
    table {width:100%; border-collapse:collapse; background:#fff;}
    th, td {border:1px solid #ddd; padding:10px; text-align:center;}
    th {background:#444; color:#fff;}
    img {width:80px; height:80px; object-fit:cover;}
    .total {text-align:right; margin-top:20px; font-size:18px;}
    button {padding:8px 15px; margin:5px; cursor:pointer;}
    a {text-decoration:none; color:#c00;}
    .back-home {
        display:inline-block;
        background:#007bff;
        color:#fff;
        padding:8px 15px;
        border-radius:4px;
        text-decoration:none;
        margin-top:15px;
    }
</style>
</head>
<body>
<header>
    <h1>🛒 Giỏ hàng của bạn</h1>
</header>

<div class="container">
<form method="post" action="">
    <table>
        <tr>
            <th>Hình ảnh</th>
            <th>Sản phẩm</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
            <th>Hành động</th>
        </tr>
        <?php if (!empty($_SESSION['cart'])): ?>
            <?php 
            $total = 0;
            foreach ($_SESSION['cart'] as $id => $item): 
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
            <tr>
                <td><img src="../images/<?php echo $item['image']; ?>" alt=""></td>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td><?php echo number_format($item['price']); ?> VNĐ</td>
                <td>
                    <input type="number" name="qty[<?php echo $id; ?>]" value="<?php echo $item['quantity']; ?>" min="1">
                </td>
                <td><?php echo number_format($subtotal); ?> VNĐ</td>
                <td><a href="cart.php?remove=<?php echo $id; ?>">❌ Xóa</a></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="6">
                    <button type="submit" name="update">Cập nhật giỏ hàng</button>
                </td>
            </tr>
        <?php else: ?>
            <tr><td colspan="6">Giỏ hàng trống!</td></tr>
        <?php endif; ?>
    </table>
</form>

<?php if (!empty($_SESSION['cart'])): ?>
    <p class="total"><strong>Tổng cộng: <?php echo number_format($total); ?> VNĐ</strong></p>
    <a href="checkout.php"><button>Thanh toán</button></a>
<?php endif; ?>

<!-- Nút quay lại luôn hiển thị -->
<div><a href="../index.php" class="back-home">⬅ Quay lại trang sản phẩm</a></div>
</div>
</body>
</html>
