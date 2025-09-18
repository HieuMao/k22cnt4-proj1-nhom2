<?php
include 'admin_header.php';
require '../connect.php'; // kết nối database

// Lấy tổng số sản phẩm
$product_count = 0;
$res = $mysqli->query("SELECT COUNT(*) AS total FROM products");
if ($res && $row = $res->fetch_assoc()) {
    $product_count = $row['total'];
}

// Lấy tổng số đơn hàng
$order_count = 0;
$res = $mysqli->query("SELECT COUNT(*) AS total FROM orders");
if ($res && $row = $res->fetch_assoc()) {
    $order_count = $row['total'];
}

// Lấy tổng số người dùng
$user_count = 0;
$res = $mysqli->query("SELECT COUNT(*) AS total FROM users");
if ($res && $row = $res->fetch_assoc()) {
    $user_count = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Dashboard Admin</title>
<style>
    body {
        font-family: 'Roboto', sans-serif;
        background: #f5f6fa;
        margin: 0;
        padding: 20px;
    }
    h2 {
        color: #333;
        margin-bottom: 30px;
        font-size: 32px;
    }
    .dashboard {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }
    .card {
        background: #fff;
        flex: 1 1 250px;
        padding: 25px 20px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transition: transform 0.2s, box-shadow 0.2s;
        text-align: center;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .card h3 {
        margin-bottom: 15px;
        color: #667eea;
        font-size: 20px;
    }
    .card p {
        font-size: 24px;
        font-weight: bold;
        color: #333;
        margin: 0;
    }
</style>
</head>
<body>

<h2>📊 Dashboard</h2>
<div class="dashboard">
    <div class="card">
        <h3>Tổng sản phẩm</h3>
        <p><?= $product_count ?> sản phẩm</p>
    </div>
    <div class="card">
        <h3>Tổng đơn hàng</h3>
        <p><?= $order_count ?> đơn hàng</p>
    </div>
    <div class="card">
        <h3>Tổng người dùng</h3>
        <p><?= $user_count ?> người dùng</p>
    </div>
</div>

</body>
</html>
