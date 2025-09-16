<?php
session_start();
require_once "../connect.php"; // kết nối DB

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    // Kiểm tra nhập trống
    if (empty($username) || empty($password) || empty($confirm)) {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    } elseif ($password !== $confirm) {
        $error = "Mật khẩu nhập lại không khớp!";
    } else {
        // Kiểm tra username đã tồn tại chưa
        $check = $mysqli->prepare("SELECT id FROM users WHERE username=?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Tên đăng nhập đã tồn tại!";
        } else {
            // Mã hóa mật khẩu SHA256
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
        body {font-family: Arial, sans-serif; background: #f2f2f2; display:flex; justify-content:center; align-items:center; height:100vh;}
        .register-box {background:#fff; padding:30px; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1); width:350px;}
        h2 {text-align:center; margin-bottom:20px;}
        input {width:100%; padding:10px; margin:10px 0; border:1px solid #ccc; border-radius:5px;}
        button {width:100%; padding:10px; background:#333; color:#fff; border:none; border-radius:5px; cursor:pointer;}
        button:hover {background:#555;}
        .error {color:red; text-align:center;}
        .success {color:green; text-align:center;}
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
        <p style="text-align:center;margin-top:10px;">Đã có tài khoản? <a href="../login.php">Đăng nhập</a></p>
    </div>
</body>
</html>
