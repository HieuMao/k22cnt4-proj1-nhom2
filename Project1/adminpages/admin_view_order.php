<?php
require '../connect.php';
include 'admin_header.php';

$id = intval($_GET['id']);

$orderSql = "SELECT o.*, u.username 
             FROM orders o 
             LEFT JOIN users u ON o.user_id = u.id 
             WHERE o.id = ?";
$stmt = $mysqli->prepare($orderSql);
$stmt->bind_param("i", $id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

$itemSql = "SELECT oi.*, p.name 
            FROM order_items oi 
            JOIN products p ON oi.product_id = p.id 
            WHERE oi.order_id = ?";
$stmt2 = $mysqli->prepare($itemSql);
$stmt2->bind_param("i", $id);
$stmt2->execute();
$items = $stmt2->get_result();
?>

<style>
    body {
        font-family: 'Roboto', sans-serif;
        background: #f5f6fa;
        margin: 20px;
    }
    h2 {
        color: #333;
        margin-bottom: 20px;
    }
    .order-info {
        background: #fff;
        padding: 20px 25px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        margin-bottom: 25px;
    }
    .order-info p {
        margin: 8px 0;
        font-size: 16px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    th, td {
        padding: 12px 15px;
        text-align: center;
        border-bottom: 1px solid #eee;
    }
    th {
        background: linear-gradient(90deg, #667eea, #764ba2);
        color: #fff;
        font-weight: 600;
    }
    tr:hover {
        background: #f1f1f1;
    }
    a.back {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 18px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        color: #fff;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: transform 0.2s, background 0.3s;
    }
    a.back:hover {
        transform: scale(1.03);
        background: linear-gradient(90deg, #5a67d8, #6b4db0);
    }
</style>

<h2>Chi tiết đơn hàng #<?= $order['id'] ?></h2>

<div class="order-info">
    <p><strong>Khách hàng:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
    <p><strong>SĐT:</strong> <?= htmlspecialchars($order['phone']) ?></p>
    <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['address']) ?></p>
    <p><strong>Tổng tiền:</strong> <?= number_format($order['total'], 0, ',', '.') ?> đ</p>
    <p><strong>Người dùng:</strong> <?= $order['username'] ?? 'Khách vãng lai' ?></p>
    <p><strong>Ngày đặt:</strong> <?= $order['created_at'] ?></p>
</div>

<h3>Sản phẩm trong đơn:</h3>
<table>
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

<a href="admin_orders.php" class="back">← Quay lại danh sách đơn hàng</a>
