<?php
session_start();
require '../connect.php';

// Bắt buộc đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);

// Lấy danh sách đơn của user
$sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
$orders = $mysqli->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Đơn hàng của tôi</title>
<style>
    body{font-family:Arial,sans-serif;background:#f5f5f5;margin:0;padding:0;}
    header{background:#333;color:#fff;padding:15px;text-align:center;}
    .container{width:80%;margin:20px auto;background:#fff;padding:20px;border-radius:6px;}
    table{width:100%;border-collapse:collapse;}
    th,td{border:1px solid #ddd;padding:10px;text-align:center;}
    th{background:#444;color:#fff;}
    a{color:#007bff;text-decoration:none;}
    a:hover{text-decoration:underline;}
</style>
</head>
<body>
<header>
    <h1>📦 Đơn hàng của tôi</h1>
</header>

<div class="container">
    <p><a href="../index.php">⬅ Quay lại trang chủ</a></p>

    <?php if ($orders->num_rows > 0): ?>
    <table>
        <tr>
            <th>Mã đơn</th>
            <th>Tên người nhận</th>
            <th>Số điện thoại</th>
            <th>Địa chỉ</th>
            <th>Tổng tiền</th>
            <th>Ngày đặt</th>
            <th>Chi tiết</th>
        </tr>
        <?php while($o = $orders->fetch_assoc()): ?>
        <tr>
            <td>#<?php echo $o['id']; ?></td>
            <td><?php echo htmlspecialchars($o['customer_name']); ?></td>
            <td><?php echo htmlspecialchars($o['phone']); ?></td>
            <td><?php echo htmlspecialchars($o['address']); ?></td>
            <td><?php echo number_format($o['total']); ?> VNĐ</td>
            <td><?php echo $o['created_at']; ?></td>
            <td><a href="order_detail.php?id=<?php echo $o['id']; ?>">Xem</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php else: ?>
        <p>Bạn chưa có đơn hàng nào.</p>
    <?php endif; ?>
</div>
</body>
</html>
