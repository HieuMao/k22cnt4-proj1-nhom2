<?php
require '../connect.php';
include 'admin_header.php';

if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $mysqli->query("DELETE FROM categories WHERE id=$id");
    header("Location: admin_categories.php");
    exit();
}

$result = $mysqli->query("SELECT * FROM categories ORDER BY id DESC");
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
    button#btnShowForm {
        padding: 10px 18px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        color: #fff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: transform 0.2s, background 0.3s;
        margin-bottom: 15px;
    }
    button#btnShowForm:hover {
        transform: scale(1.03);
        background: linear-gradient(90deg, #5a67d8, #6b4db0);
    }
    #categoryForm {
        background: #fff;
        padding: 20px 25px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        margin-bottom: 25px;
    }
    label {
        display: block;
        margin: 12px 0 6px;
        font-weight: 500;
        color: #555;
    }
    input[type="text"], textarea {
        width: 100%;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        outline: none;
        box-sizing: border-box;
        transition: border 0.2s, box-shadow 0.2s;
    }
    input:focus, textarea:focus {
        border-color: #667eea;
        box-shadow: 0 0 8px rgba(102,126,234,0.4);
    }
    textarea { resize: vertical; }
    #categoryForm button[type="submit"] {
        margin-top: 15px;
        padding: 10px 18px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        color: #fff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: transform 0.2s, background 0.3s;
    }
    #categoryForm button[type="submit"]:hover {
        transform: scale(1.03);
        background: linear-gradient(90deg, #5a67d8, #6b4db0);
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
        border-bottom: 1px solid #eee;
        text-align: center;
    }
    th {
        background: linear-gradient(90deg, #667eea, #764ba2);
        color: #fff;
        font-weight: 600;
    }
    tr:hover { background: #f1f1f1; }
    a {
        text-decoration: none;
        font-weight: 500;
        color: #667eea;
        transition: color 0.2s;
    }
    a:hover { color: #764ba2; text-decoration: underline; }
</style>

<h2>Quản lý danh mục</h2>

<button id="btnShowForm">Thêm danh mục mới</button>

<div id="categoryForm" style="display:none;">
    <form method="POST" action="admin_add_category.php">
        <label>Tên danh mục:</label>
        <input type="text" name="name" required>

        <label>Mô tả:</label>
        <textarea name="description"></textarea>

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

<table>
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
