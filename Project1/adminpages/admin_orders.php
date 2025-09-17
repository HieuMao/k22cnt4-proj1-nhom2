<?php
require '../connect.php';
include 'admin_header.php';

$sql = "SELECT o.id, o.customer_name, o.phone, o.address, o.total, o.created_at, u.username
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        ORDER BY o.created_at DESC";
$result = $mysqli->query($sql);
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
    a {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
    }
    a:hover {
        color: #764ba2;
        text-decoration: underline;
    }
</style>

<h2>Quản lý đơn hàng</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Khách hàng</th>
        <th>Số điện thoại</th>
        <th>Địa chỉ</th>
        <th>Tổng tiền</th>
        <th>Người dùng</th>
        <th>Ngày đặt</th>
        <th>Hành động</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['customer_name']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['address']) ?></td>
            <td><?= number_format($row['total'], 0, ',', '.') ?> đ</td>
            <td><?= $row['username'] ?? 'Khách vãng lai' ?></td>
            <td><?= $row['created_at'] ?></td>
            <td>
                <a href="admin_view_order.php?id=<?= $row['id'] ?>">Xem chi tiết</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
