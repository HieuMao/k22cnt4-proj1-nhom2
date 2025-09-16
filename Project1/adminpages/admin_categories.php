<?php
require '../connect.php';
include 'admin_header.php';

// Xóa danh mục
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $mysqli->query("DELETE FROM categories WHERE id=$id");
    header("Location: admin_categories.php");
    exit();
}

// Lấy danh sách danh mục
$result = $mysqli->query("SELECT * FROM categories ORDER BY id DESC");
?>

<h2>Quản lý danh mục</h2>

<!-- Nút hiển thị form -->
<button id="btnShowForm">Thêm danh mục mới</button>

<!-- Form thêm danh mục (ẩn mặc định) -->
<div id="categoryForm" style="display:none; margin-top:15px;">
    <form method="POST" action="admin_add_category.php">
        <label>Tên danh mục:</label><br>
        <input type="text" name="name" required><br>

        <label>Mô tả:</label><br>
        <textarea name="description"></textarea><br>

        <button type="submit">Thêm</button>
    </form>
</div>

<script>
    const btnShowForm = document.getElementById('btnShowForm');
    const categoryForm = document.getElementById('categoryForm');
    btnShowForm.addEventListener('click', () => {
        categoryForm.style.display = (categoryForm.style.display === 'none') ? 'block' : 'none';
    });
</script>

<hr>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Tên danh mục</th>
        <th>Mô tả</th>
        <th>Hành động</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['description'] ?></td>
            <td>
                <a href="admin_edit_category.php?id=<?= $row['id'] ?>">Sửa</a> | 
                <a href="admin_categories.php?delete_id=<?= $row['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
