<?php
session_start();
require 'connect.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Mã hoá mật khẩu giống trong DB
    $password_hash = hash('sha256', $password);

    $sql = "SELECT * FROM users WHERE username=? AND password_hash=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $username, $password_hash);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Lưu thông tin vào session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Chuyển hướng theo role
        if ($user['role'] === 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        $error = "Sai tên đăng nhập hoặc mật khẩu!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
</head>
<body>
    <h2>Đăng nhập</h2>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" action="">
        <label>Tên đăng nhập:</label>
        <input type="text" name="username" required><br><br>
        <label>Mật khẩu:</label>
        <input type="password" name="password" required><br><br>
        <button type="submit">Đăng nhập</button>
    </form>
</body>
</html>
