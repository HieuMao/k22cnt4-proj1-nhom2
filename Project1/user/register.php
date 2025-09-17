<?php
session_start();
require_once "../connect.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if (empty($username) || empty($password) || empty($confirm)) {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    } elseif ($password !== $confirm) {
        $error = "Mật khẩu nhập lại không khớp!";
    } else {

        $check = $mysqli->prepare("SELECT id FROM users WHERE username=?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Tên đăng nhập đã tồn tại!";
        } else {
            $password_hash = hash('sha256', $password);
            $stmt = $mysqli->prepare("INSERT INTO users (username,password_hash,role) VALUES (?, ?, 'customer')");
            $stmt->bind_param("ss", $username, $password_hash);

            if ($stmt->execute()) {
                $success = "Đăng ký thành công! Bạn có thể <a href='../login.php'>đăng nhập</a>.";
            } else {
                $error = "Có lỗi xảy ra, vui lòng thử lại!";
            }

            $stmt->close();
        }

        $check->close();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Đăng ký tài khoản</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

* {
    box-sizing:border-box;
    margin:0;
    padding:0;
    font-family:'Roboto',sans-serif;
}

body {
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background: linear-gradient(135deg,#667eea,#764ba2);
}

.register-box {
    background: rgba(255,255,255,0.95);
    padding:50px 40px;
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,0.2);
    width:360px;
    text-align:center;
    transition: transform 0.3s, box-shadow 0.3s;
}

.register-box:hover {
    transform: translateY(-5px);
    box-shadow:0 15px 40px rgba(0,0,0,0.3);
}

h2 {
    margin-bottom:30px;
    font-size:28px;
    color:#333;
}

input {
    width:100%;
    padding:12px;
    margin-bottom:20px;
    border:1px solid #ccc;
    border-radius:6px;
    outline:none;
    transition:border 0.2s, box-shadow 0.2s;
}

input:focus {
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
    transition:background 0.3s, transform 0.2s;
}

button:hover {
    background: linear-gradient(90deg,#5a67d8,#6b4db0);
    transform: scale(1.03);
}

.error {
    color:#e74c3c;
    background:#fce4e4;
    padding:10px;
    border-radius:6px;
    margin-bottom:15px;
    font-size:14px;
}

.success {
    color:#2ecc71;
    background:#e6f7ed;
    padding:10px;
    border-radius:6px;
    margin-bottom:15px;
    font-size:14px;
}

a {
    color:#667eea;
    font-weight:500;
    text-decoration:none;
    transition: color 0.2s;
}

a:hover {
    color:#764ba2;
    text-decoration:underline;
}

.login-link {
    margin-top:20px;
    font-size:14px;
    color:#555;
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

<div class="register-box">

    <h2>Đăng ký</h2>

    <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p class="success"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="POST" action="">

        <input type="text" name="username" placeholder="Tên đăng nhập" required>

        <input type="password" name="password" placeholder="Mật khẩu" required>

        <input type="password" name="confirm" placeholder="Nhập lại mật khẩu" required>

        <button type="submit">Đăng ký</button>

    </form>

    <p class="login-link">
        Đã có tài khoản? 
        <a href="../login.php">Đăng nhập</a>
    </p>

    <a href="../index.php" class="back-home">⬅ Quay lại trang chủ</a>

</div>

</body>
</html>
