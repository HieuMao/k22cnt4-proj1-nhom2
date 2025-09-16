<?php
session_start();
require '../connect.php';

// Lấy id đơn hàng
if (!isset($_GET['id'])) {
    die("Thiếu ID đơn hàng");
}
$order_id = (int)$_GET['id'];

// Lấy thông tin đơn hàng
$sql = "SELECT * FROM orders WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
if (!$order) {
    die("Không tìm thấy đơn hàng");
}

// Lấy danh sách sản phẩm trong đơn
$sql_items = "SELECT oi.*, p.name 
              FROM order_items oi
              JOIN products p ON oi.product_id = p.id
              WHERE oi.order_id = ?";
$stmt_items = $mysqli->prepare($sql_items);
$stmt_items->bind_param('i', $order_id);
$stmt_items->execute();
$items = $stmt_items->get_result();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Chi tiết đơn hàng #<?php echo $order_id; ?></title>
<style>
body{font-family:Arial,sans-serif;background:#f5f5f5;margin:0;padding:20px;}
table{width:100%;border-collapse:collapse;background:#fff;}
th,td{border:1px solid #ccc;padding:8px;text-align:left;}
th{background:#eee;}
</style>
</head>
<body>
<h1>Đơn hàng #<?php echo $order_id; ?></h1>
<p><b>Tên khách:</b> <?php echo htmlspecialchars($order['customer_name']); ?></p>
<p><b>SĐT:</b> <?php echo htmlspecialchars($order['phone']); ?></p>
<p><b>Địa chỉ:</b> <?php echo htmlspecialchars($order['address']); ?></p>
<p><b>Tổng tiền:</b> <?php echo number_format($order['total']); ?> VNĐ</p>

<h2>Sản phẩm</h2>
<table>
<tr>
  <th>Tên SP</th>
  <th>Số lượng</th>
  <th>Giá</th>
</tr>
<?php while($row = $items->fetch_assoc()): ?>
<tr>
  <td><?php echo htmlspecialchars($row['name']); ?></td>
  <td><?php echo $row['quantity']; ?></td>
  <td><?php echo number_format($row['price']); ?> VNĐ</td>
</tr>
<?php endwhile; ?>
</table>

<a href="orders.php">⬅ Quay lại danh sách</a>
</body>
</html>
