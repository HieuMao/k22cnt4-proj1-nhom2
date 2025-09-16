<?php
require '../connect.php';
include 'admin_header.php';

$id = intval($_GET['id']);

// Lấy thông tin đơn hàng
$orderSql = "SELECT o.*, u.username 
             FROM orders o 
             LEFT JOIN users u ON o.user_id = u.id 
             WHERE o.id = ?";
$stmt = $mysqli->prepare($orderSql);
$stmt->bind_param("i", $id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

// Lấy chi tiết sản phẩm trong đơn
$itemSql = "SELECT oi.*, p.name 
            FROM order_items oi 
            JOIN products p ON oi.product_id = p.id 
            WHERE oi.order_id = ?";
$stmt2 = $mysqli->prepare($itemSql);
$stmt2->bind_param("i", $id);
$stmt2->execute();
$items = $stmt2->get_result();
?>

<h2>Chi tiết đơn hàng #<?= $order['id'] ?></h2>
<p><strong>Khách hàng:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
<p><strong>SĐT:</strong> <?= htmlspecialchars($order['phone']) ?></p>
<p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['address']) ?></p>
<p><strong>Tổng tiền:</strong> <?= number_format($order['total'], 0, ',', '.') ?> đ</p>
<p><strong>Người dùng:</strong> <?= $order['username'] ?? 'Khách vãng lai' ?></p>
<p><strong>Ngày đặt:</strong> <?= $order['created_at'] ?></p>

<h3>Sản phẩm trong đơn:</h3>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Sản phẩm</th>
        <th>Số lượng</th>
        <th>Giá</th>
        <th>Thành tiền</th>
    </tr>
    <?php while ($row = $items->fetch_assoc()): ?>
        <tr>
            <td><?= $row['name'] ?></td>
            <td><?= $row['quantity'] ?></td>
            <td><?= number_format($row['price'], 0, ',', '.') ?> đ</td>
            <td><?= number_format($row['quantity'] * $row['price'], 0, ',', '.') ?> đ</td>
        </tr>
    <?php endwhile; ?>
</table>

<br>
<a href="admin_orders.php">← Quay lại danh sách đơn hàng</a>
