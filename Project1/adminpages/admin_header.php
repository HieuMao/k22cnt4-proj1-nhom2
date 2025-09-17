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
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background: #f5f6fa;
        }

        header {
            background: #333;
            color: #fff;
            padding: 15px 30px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
            font-size: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 14px;
        }

        .user-info span {
            color: #fff;
        }

        .logout-btn {
            background: linear-gradient(90deg, #ff6b6b, #ff4757);
            color: #fff;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s, transform 0.2s;
        }

        .logout-btn:hover {
            background: linear-gradient(90deg, #ff4757, #ff6b6b);
            transform: scale(1.05);
        }

        .sidebar {
            width: 200px;
            background: #f4f4f4;
            height: 100vh;
            float: left; /* giữ logic cũ */
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
        }

        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            margin: 4px 10px;
            border-radius: 6px;
            transition: 0.2s;
        }

        .sidebar a:hover, .sidebar a.active {
            background: #667eea;
            color: #fff;
        }

        .main {
            margin-left: 200px; /* giữ margin tương ứng sidebar */
            padding: 20px;
            min-height: 100vh;
        }

        .card {
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.05);
            background: #fff;
            transition: transform 0.2s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 18px rgba(0,0,0,0.08);
        }

        h1, h2 {
            margin: 0;
            color: #333;
        }

        p {
            margin: 5px 0 0;
        }

        /* Responsive nhẹ */
        @media screen and (max-width: 768px) {
            .sidebar { width: 60px; padding-top: 15px; }
            .sidebar a { padding: 10px 8px; font-size: 12px; text-align: center; margin: 2px 0; }
            .main { margin-left: 60px; padding: 15px; }
        }
    </style>
</head>
<body>
<header>
    <h1>Admin Panel</h1>
    <div class="user-info">
        <span>Xin chào <strong><?php echo $_SESSION['username']; ?></strong></span>
        <a href="../logout.php" class="logout-btn">Đăng xuất</a>
    </div>
</header>

<div class="sidebar">
    <a href="admin.php" class="active">Dashboard</a>
    <a href="admin_products.php">Sản phẩm</a>
    <a href="admin_orders.php">Đơn hàng</a>
    <a href="admin_users.php">Người dùng</a>
    <a href="admin_categories.php">Danh mục</a>
</div>

<div class="main">
<!-- Nội dung chính sẽ đặt ở đây -->
