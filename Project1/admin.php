<?php
session_start();

// Chỉ cho admin truy cập
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang quản trị</title>
</head>
<body>
    <h2>Xin chào Admin, <?php echo $_SESSION['username']; ?>!</h2>
    <p>Bạn đang ở trang quản trị.</p>
    <a href="logout.php">Đăng xuất</a>
</body>
</html>
