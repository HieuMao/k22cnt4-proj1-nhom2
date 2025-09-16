<?php
include 'admin_header.php';

// Chỉ cho admin truy cập
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../connect.php';

// Lấy danh sách người dùng
$sql = "SELECT id, username, role, created_at FROM users ORDER BY created_at DESC";
$result = $mysqli->query($sql) or die("Lỗi SQL: " . $mysqli->error);

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý người dùng</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #333;
            color: #fff;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        .delete-btn {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }

        .delete-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <main>
        <h2>Quản lý người dùng</h2>
        <table border="1" cellpadding="8" cellspacing="0">
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
                            onclick="return confirm('Bạn có chắc muốn xóa người dùng này?')">Xóa</a>
                    </td>

                </tr>
            <?php endwhile; ?>
        </table>

    </main>
</body>

</html>