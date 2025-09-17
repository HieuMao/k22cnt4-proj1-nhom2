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
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
/* ---------- BASE ---------- */
body {
    font-family:'Poppins', sans-serif;
    background:#f7f7f7;
    color:#333;
    margin:0;
    line-height:1.6;
}

/* ---------- HEADER ---------- */
header {
    background: linear-gradient(135deg,#ffecd2,#fcb69f);
    color:#b85c00;
    text-align:center;
    padding:25px 20px;
    font-size:1.8rem;
    font-weight:600;
    box-shadow:0 3px 10px rgba(0,0,0,0.1);
    border-radius:0 0 12px 12px;
}

/* ---------- CONTAINER ---------- */
.container {
    max-width:900px;
    width:90%;
    margin:30px auto;
    background:#fff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
}

/* ---------- INFO ---------- */
.info p {
    margin:8px 0;
    font-size:16px;
}
.info b {
    color:#b85c00;
}

/* ---------- TABLE ---------- */
table {
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}
th, td {
    border:1px solid #eee;
    padding:12px;
    text-align:center;
}
th {
    background:#ff7f50;
    color:#fff;
    font-weight:600;
}
tr:nth-child(even) td {
    background:#fafafa;
}
tr:hover td {
    background:#fff3e0;
    transition:0.2s;
}

/* ---------- BUTTON ---------- */
.back-btn {
    display:inline-block;
    margin-top:20px;
    background:#ff7f50;
    color:#fff;
    padding:10px 18px;
    border-radius:8px;
    text-decoration:none;
    font-weight:500;
    transition:background .2s, transform .2s;
}
.back-btn:hover {
    background:#e76b3a;
    transform:scale(1.03);
}

/* ---------- RESPONSIVE ---------- */
@media(max-width:768px) {
    th, td { font-size:14px; padding:8px; }
    .back-btn { padding:8px 16px; font-size:14px; }
}
</style>
</head>
<body>

<header>
    Chi tiết đơn hàng #<?php echo $order_id; ?>
</header>

<div class="container">
    <div class="info">
        <p><b>Tên khách:</b> <?php echo htmlspecialchars($order['customer_name']); ?></p>
        <p><b>SĐT:</b> <?php echo htmlspecialchars($order['phone']); ?></p>
        <p><b>Địa chỉ:</b> <?php echo htmlspecialchars($order['address']); ?></p>
        <p><b>Tổng tiền:</b> <?= number_format($order['total']); ?> VNĐ</p>
        <p><b>Ngày đặt:</b> <?= htmlspecialchars($order['created_at']); ?></p>
    </div>

    <h2>Sản phẩm</h2>
    <table>
        <tr>
            <th>Sản phẩm</th>
            <th>Số lượng</th>
            <th>Giá</th>
        </tr>
        <?php while($row = $items->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['name']); ?></td>
            <td><?= $row['quantity']; ?></td>
            <td><?= number_format($row['price']); ?> VNĐ</td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a href="orders.php" class="back-btn">⬅ Quay lại danh sách</a>
</div>

</body>
</html>
