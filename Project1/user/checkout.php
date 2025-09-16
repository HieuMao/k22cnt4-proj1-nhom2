<?php
session_start();
require '../connect.php'; // file kết nối $mysqli

// Nếu giỏ hàng trống thì đá về cart
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['customer_name']);
    $phone   = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if ($name && $phone && $address) {
        // Tính tổng tiền
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // user_id nếu đã đăng nhập, còn khách vãng lai thì NULL
        $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

        // Insert vào bảng orders
        $stmt = $mysqli->prepare(
            "INSERT INTO orders (user_id, customer_name, phone, address, total) VALUES (?,?,?,?,?)"
        );
        $stmt->bind_param("isssd", $user_id, $name, $phone, $address, $total);
        $stmt->execute();
        $order_id = $stmt->insert_id;
        $stmt->close();

        // Insert từng item
        $stmtItem = $mysqli->prepare(
            "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?,?,?,?)"
        );
        foreach ($_SESSION['cart'] as $id => $item) {
            $stmtItem->bind_param("iiid", $order_id, $id, $item['quantity'], $item['price']);
            $stmtItem->execute();
        }
        $stmtItem->close();

        // Xoá giỏ hàng
        unset($_SESSION['cart']);
        $message = "✅ Đặt hàng thành công! Cảm ơn bạn đã mua sắm.";
    } else {
        $message = "⚠️ Vui lòng nhập đầy đủ thông tin.";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Thanh toán</title>
<style>
    body {font-family: Arial, sans-serif; background:#f9f9f9; margin:0; padding:0;}
    header {background:#333; color:#fff; padding:15px; text-align:center;}
    .container {width:60%; margin:20px auto; background:#fff; padding:20px; border-radius:6px;}
    label {display:block; margin-top:10px;}
    input[type="text"], textarea {
        width:100%; padding:8px; margin-top:5px; border:1px solid #ccc; border-radius:4px;
    }
    button {
        margin-top:15px; padding:10px 20px; background:#28a745; color:#fff;
        border:none; border-radius:4px; cursor:pointer;
    }
    .msg {margin-bottom:15px; color:green; font-weight:bold;}
    .back-home {
        display:inline-block; margin-top:20px;
        background:#007bff; color:#fff; padding:8px 15px;
        border-radius:4px; text-decoration:none;
    }
</style>
</head>
<body>
<header><h1>💳 Thanh toán</h1></header>

<div class="container">
<?php if ($message): ?>
    <p class="msg"><?php echo $message; ?></p>
    <a href="../index.php" class="back-home">⬅ Về trang sản phẩm</a>
<?php else: ?>
    <h2>Thông tin giao hàng</h2>
    <form method="post" action="">
        <label>Họ và tên
            <input type="text" name="customer_name" required>
        </label>

        <label>Số điện thoại
            <input type="text" name="phone" required>
        </label>

        <label>Địa chỉ
            <textarea name="address" rows="3" required></textarea>
        </label>

        <button type="submit">Đặt hàng</button>
    </form>
<?php endif; ?>
</div>
</body>
</html>
