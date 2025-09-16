<?php
require '../connect.php';
include 'admin_header.php';

// Lấy danh sách đơn hàng (JOIN với user để biết ai đặt)
$sql = "SELECT o.id, o.customer_name, o.phone, o.address, o.total, o.created_at, u.username
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        ORDER BY o.created_at DESC";
$result = $mysqli->query($sql);
?>

<h2>Quản lý đơn hàng</h2>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
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
