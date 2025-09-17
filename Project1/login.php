<?php
session_start();
require 'connect.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password_hash = hash('sha256', $password);

    $sql = "SELECT * FROM users WHERE username=? AND password_hash=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $username, $password_hash);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: adminpages/admin.php");
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
<style>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

* {
    box-sizing: border-box;
    margin:0;
    padding:0;
    font-family:'Roboto',sans-serif;
}

body {
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background: linear-gradient(135deg, #667eea, #764ba2);
}

.login-box {
    background: rgba(255,255,255,0.95);
    padding:50px 40px;
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,0.2);
    width:350px;
    text-align:center;
    transition: transform 0.3s, box-shadow 0.3s;
}

.login-box:hover {
    transform: translateY(-5px);
    box-shadow:0 15px 40px rgba(0,0,0,0.3);
}

h2 {
    margin-bottom:30px;
    font-size:28px;
    color:#333;
}

.error {
    color:#e74c3c;
    margin-bottom:15px;
    font-size:14px;
    background:#fce4e4;
    padding:10px;
    border-radius:6px;
}

label {
    display:block;
    text-align:left;
    margin-bottom:6px;
    font-weight:500;
    color:#555;
}

input[type="text"],
input[type="password"] {
    width:100%;
    padding:12px;
    margin-bottom:20px;
    border:1px solid #ccc;
    border-radius:6px;
    outline:none;
    transition: border 0.2s, box-shadow 0.2s;
}

input[type="text"]:focus,
input[type="password"]:focus {
    border-color:#667eea;
    box-shadow:0 0 8px rgba(102,126,234,0.4);
}

button {
    width:100%;
    padding:12px;
    background: linear-gradient(90deg,#667eea,#764ba2);
    color:#fff;
    border:none;
    border-radius:6px;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
    transition: background 0.3s, transform 0.2s;
}

button:hover {
    background: linear-gradient(90deg,#5a67d8,#6b4db0);
    transform: scale(1.03);
}

.register-link {
    margin-top:20px;
    font-size:14px;
    color:#555;
}

.register-link a {
    color:#667eea;
    font-weight:500;
    text-decoration:none;
    transition: color 0.2s;
}

.register-link a:hover {
    color:#764ba2;
    text-decoration:underline;
}

.back-home {
    display:inline-block;
    margin-top:15px;
    padding:10px 18px;
    background:#333;
    color:#fff;
    border-radius:6px;
    text-decoration:none;
    font-weight:500;
    transition: background 0.2s;
}

.back-home:hover {
    background:#555;
}
</style>
</head>
<body>

<div class="login-box">

    <h2>Đăng nhập</h2>

    <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="">

        <label for="username">Tên đăng nhập:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Mật khẩu:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Đăng nhập</button>

    </form>

    <div class="register-link">
        Chưa có tài khoản? 
        <a href="user/register.php">Đăng ký ngay</a>
    </div>

    <a href="index.php" class="back-home">⬅ Quay lại trang chủ</a>

</div>

</body>
</html>
