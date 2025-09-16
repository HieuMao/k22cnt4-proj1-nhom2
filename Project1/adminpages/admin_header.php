<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <style>
        body { margin:0; font-family: Arial; }
        header { background:#333; color:#fff; padding:15px; }
        .sidebar { width:200px; background:#f4f4f4; height:100vh; float:left; padding-top:20px; }
        .sidebar a { display:block; padding:10px 15px; color:#333; text-decoration:none; }
        .sidebar a:hover { background:#ddd; }
        .main { margin-left:200px; padding:20px; }
        .card { border:1px solid #ddd; padding:15px; margin-bottom:15px; border-radius:5px; }
    </style>
</head>
<body>
<header>
    <h1>Admin Panel</h1>
    <p>Xin chào <?php echo $_SESSION['username']; ?> | <a href="../logout.php" style="color:#fff;">Đăng xuất</a></p>
</header>

<div class="sidebar">
    <a href="admin.php">Dashboard</a>
    <a href="admin_products.php">Sản phẩm</a>
    <a href="admin_orders.php">Đơn hàng</a>
    <a href="admin_users.php">Người dùng</a>
    <a href="admin_categories.php">Danh mục</a>
</div>

<div class="main">
<!-- Nội dung chính sẽ đặt ở đây -->
