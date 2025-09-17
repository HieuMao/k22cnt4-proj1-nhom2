<?php
require '../connect.php';
include 'admin_header.php';

if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $mysqli->query("DELETE FROM products WHERE id=$id");
    header("Location: admin_products.php");
    exit();
}

$sql = "SELECT p.*, c.name AS category_name 
        FROM products p 
        JOIN categories c ON p.category_id = c.id 
        ORDER BY p.created_at DESC";
$result = $mysqli->query($sql);

$categories = $mysqli->query("SELECT * FROM categories");
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
    #btnShowForm {
        padding: 10px 20px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        border: none;
        color: #fff;
        border-radius: 6px;
        cursor: pointer;
        margin-bottom: 15px;
        transition: transform 0.2s, background 0.3s;
    }
    #btnShowForm:hover {
        transform: scale(1.05);
        background: linear-gradient(90deg, #5a67d8, #6b4db0);
    }
    #productForm {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    #productForm input, #productForm select, #productForm textarea, #productForm button {
        width: 100%;
        padding: 10px;
        margin: 8px 0;
        border-radius: 6px;
        border: 1px solid #ccc;
        box-sizing: border-box;
    }
    #productForm button {
        background: linear-gradient(90deg, #667eea, #764ba2);
        color: #fff;
        border: none;
        cursor: pointer;
        font-weight: 600;
    }
    #productForm button:hover {
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
    table th, table td {
        padding: 12px 15px;
        text-align: center;
        border-bottom: 1px solid #eee;
    }
    table th {
        background: #667eea;
        color: #fff;
        font-weight: 600;
    }
    table tr:hover {
        background: #f1f1f1;
    }
    table img {
        border-radius: 6px;
    }
    a {
        color: #667eea;
        text-decoration: none;
        transition: color 0.2s;
    }
    a:hover {
        color: #764ba2;
    }
</style>

<h2>Quản lý sản phẩm</h2>

<button id="btnShowForm">Thêm sản phẩm mới</button>

<div id="productForm" style="display:none;">
    <form method="POST" action="admin_add_product.php" enctype="multipart/form-data">
        <label>Tên sản phẩm:</label>
        <input type="text" name="name" required>

        <label>Danh mục:</label>
        <select name="category_id" required>
            <?php while($cat = $categories->fetch_assoc()): ?>
                <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
            <?php endwhile; ?>
        </select>

        <label>Mô tả:</label>
        <textarea name="description"></textarea>

        <label>Giá:</label>
        <input type="number" name="price" step="0.01" required>

        <label>Số lượng:</label>
        <input type="number" name="stock" required>

        <label>Ảnh:</label>
        <input type="file" name="image">

        <button type="submit">Thêm sản phẩm</button>
    </form>
</div>

<script>
    const btnShowForm = document.getElementById('btnShowForm');
    const productForm = document.getElementById('productForm');

    btnShowForm.addEventListener('click', () => {
        productForm.style.display = productForm.style.display === 'none' ? 'block' : 'none';
    });
</script>

<table>
    <tr>
        <th>ID</th>
        <th>Ảnh</th>
        <th>Tên</th>
        <th>Danh mục</th>
        <th>Giá</th>
        <th>Số lượng</th>
        <th>Hành động</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td>
                <?php if($row['image']): ?>
                    <img src="../images/<?= $row['image'] ?>" width="60">
                <?php endif; ?>
            </td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['category_name'] ?></td>
            <td><?= number_format($row['price'],0,',','.') ?> đ</td>
            <td><?= $row['stock'] ?></td>
            <td>
                <a href="admin_edit_product.php?id=<?= $row['id'] ?>">Sửa</a> | 
                <a href="admin_products.php?delete_id=<?= $row['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
