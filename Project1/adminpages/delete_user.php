<?php
session_start();

// Chỉ cho admin truy cập
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require '../connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Không cho xóa chính admin đang đăng nhập
    if ($id == $_SESSION['user_id']) {
        echo "Bạn không thể tự xóa tài khoản của mình!";
        exit();
    }

    // Xóa user
    $stmt = $mysqli->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: admin_users.php");
        exit();
    } else {
        echo "Lỗi khi xóa: " . $mysqli->error;
    }
} else {
    header("Location: admin_users.php");
    exit();
}
?>
