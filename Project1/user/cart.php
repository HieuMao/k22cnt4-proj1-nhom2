<?php
session_start();
require '../connect.php';

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Xoá sản phẩm
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
        if ($qty > 0) $_SESSION['cart'][$id]['quantity'] = $qty;
        else unset($_SESSION['cart'][$id]);
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
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
/* ===== Fonts & Reset ===== */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
* {margin:0; padding:0; box-sizing:border-box;}
body {font-family:'Poppins', sans-serif; background:#fefefe; color:#333; line-height:1.6;}

/* ===== Header ===== */
header {
    background: linear-gradient(135deg,#f9f1e7,#fff8f0);
    color: #b8860b;
    text-align:center;
    padding:40px 20px;
    font-size:1.8rem;
    font-weight:600;
    border-bottom:2px solid #f1c40f;
}

/* ===== Container ===== */
.container {
    max-width:1100px;
    margin:30px auto;
    background:#fff;
    border-radius:12px;
    box-shadow:0 5px 20px rgba(0,0,0,0.08);
    padding:30px;
}

/* ===== Table ===== */
table {width:100%; border-collapse:collapse; margin-bottom:20px;}
th, td {
    padding:12px;
    text-align:center;
    border-bottom:1px solid #eee;
}
th {
    background:#b8860b; 
    color:#fff;
    text-transform:uppercase;
    font-weight:600;
    font-size:14px;
}
tr:nth-child(even) td { background:#fafafa; }
img { width:80px; height:80px; object-fit:cover; border-radius:8px; }

/* ===== Số lượng đẹp ===== */
.qty-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}
.qty-wrapper input[type="number"] {
    width:50px;
    text-align:center;
    padding:5px;
    border-radius:8px;
    border:1px solid #ccc;
    font-weight:500;
    font-size:14px;
}
.qty-btn {
    background:#b8860b;
    color:#fff;
    border:none;
    width:28px;
    height:28px;
    border-radius:6px;
    cursor:pointer;
    font-weight:bold;
    transition: all .2s;
}
.qty-btn:hover { background:#a9740b; transform:scale(1.1); }

/* ===== Buttons / Links ===== */
button, .btn-link {
    background:#b8860b; 
    color:#fff;
    border:none;
    padding:10px 18px;
    border-radius:25px;
    font-weight:600;
    cursor:pointer;
    transition: all .25s;
    text-decoration:none;
    display:inline-block;
}
button:hover, .btn-link:hover { background:#a9740b; transform:scale(1.05); }
.remove-link { color:#c0392b; font-weight:bold; text-decoration:none; transition:.2s; }
.remove-link:hover { text-decoration:underline; color:#e74c3c; }

/* ===== Total / Footer ===== */
.total { text-align:right; font-size:1.2rem; font-weight:600; margin-top:10px; }

@media (max-width:768px){
    table, th, td { font-size:13px; padding:8px; }
    img { width:60px; height:60px; }
    button, .btn-link { padding:8px 16px; font-size:14px; }
    .qty-wrapper input[type="number"] { width:40px; }
    .qty-btn { width:24px; height:24px; font-size:12px; }
}
</style>
</head>
<body>

<header>🛒 Giỏ Hàng Của Bạn</header>

<div class="container">
<form method="post">
    <table>
        <tr>
            <th>Ảnh</th>
            <th>Sản phẩm</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
            <th></th>
        </tr>
        <?php if(!empty($_SESSION['cart'])): ?>
            <?php 
            $total = 0;
            foreach($_SESSION['cart'] as $id => $item):
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
            <tr>
                <td><img src="../images/<?= htmlspecialchars($item['image']); ?>" alt=""></td>
                <td><?= htmlspecialchars($item['name']); ?></td>
                <td><?= number_format($item['price']); ?> VNĐ</td>
                <td>
                    <div class="qty-wrapper">
                        <button type="button" class="qty-btn minus">-</button>
                        <input type="number" name="qty[<?= $id; ?>]" value="<?= $item['quantity']; ?>" min="1">
                        <button type="button" class="qty-btn plus">+</button>
                    </div>
                </td>
                <td><?= number_format($subtotal); ?> VNĐ</td>
                <td><a href="cart.php?remove=<?= $id; ?>" class="remove-link">❌ Xoá</a></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="6" style="text-align:right;">
                    <button type="submit" name="update">🔄 Cập nhật giỏ hàng</button>
                </td>
            </tr>
        <?php else: ?>
            <tr><td colspan="6">Giỏ hàng trống!</td></tr>
        <?php endif; ?>
    </table>
</form>

<?php if(!empty($_SESSION['cart'])): ?>
    <p class="total">Tổng cộng: <?= number_format($total); ?> VNĐ</p>
    <a href="checkout.php" class="btn-link">💳 Thanh toán</a>
<?php endif; ?>

<a href="../index.php" class="btn-link" style="margin-top:15px;">⬅ Quay lại trang chủ</a>
</div>

<script>
// JS tăng / giảm số lượng
document.querySelectorAll('.qty-wrapper').forEach(wrapper=>{
    const input = wrapper.querySelector('input');
    wrapper.querySelector('.minus').addEventListener('click', ()=> {
        let val = parseInt(input.value);
        if(val>1) input.value = val-1;
    });
    wrapper.querySelector('.plus').addEventListener('click', ()=> {
        input.value = parseInt(input.value)+1;
    });
});
</script>

</body>
</html>
