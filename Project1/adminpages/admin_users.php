<?php
include 'admin_header.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../connect.php';

$sql = "SELECT id, username, role, created_at FROM users ORDER BY created_at DESC";
$result = $mysqli->query($sql) or die("Lỗi SQL: " . $mysqli->error);
?>

<style>
    body {
        font-family: 'Roboto', sans-serif;
        background: #f5f6fa;
        margin: 20px;
    }
    h2 {
        color: #333;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    th, td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    th {
        background: linear-gradient(90deg, #667eea, #764ba2);
        color: #fff;
        font-weight: 600;
    }
    tr:hover {
        background: #f1f1f1;
    }
    .delete-btn {
        display: inline-block;
        padding: 6px 12px;
        background: #e74c3c;
        color: #fff;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: transform 0.2s, background 0.3s;
    }
    .delete-btn:hover {
        transform: scale(1.05);
        background: #c0392b;
    }
</style>

<h2>Quản lý người dùng</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Tên đăng nhập</th>
        <th>Vai trò</th>
        <th>Ngày tạo</th>
        <th>Hành động</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= $row['role'] ?></td>
            <td><?= $row['created_at'] ?></td>
            <td>
                <a href="delete_user.php?id=<?= $row['id'] ?>" 
                   class="delete-btn"
                   onclick="return confirm('Bạn có chắc muốn xóa người dùng này?')">Xóa</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
