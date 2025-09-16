<?php
require '../connect.php';
include 'admin_header.php';

// Xóa sản phẩm
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $mysqli->query("DELETE FROM products WHERE id=$id");
    header("Location: admin_products.php");
    exit();
}

// Lấy danh sách sản phẩm
$sql = "SELECT p.*, c.name AS category_name 
        FROM products p 
        JOIN categories c ON p.category_id = c.id 
        ORDER BY p.created_at DESC";
$result = $mysqli->query($sql);

// Lấy danh mục
$categories = $mysqli->query("SELECT * FROM categories");
?>

<h2>Quản lý sản phẩm</h2>

<!-- Nút hiển thị form -->
<button id="btnShowForm">Thêm sản phẩm mới</button>

<!-- Form thêm sản phẩm (mặc định ẩn) -->
<div id="productForm" style="display:none; margin-top:15px;">
    <form method="POST" action="admin_add_product.php" enctype="multipart/form-data">
        <label>Tên sản phẩm:</label><br>
        <input type="text" name="name" required><br>

        <label>Danh mục:</label><br>
        <select name="category_id" required>
            <?php while($cat = $categories->fetch_assoc()): ?>
                <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
            <?php endwhile; ?>
        </select><br>

        <label>Mô tả:</label><br>
        <textarea name="description"></textarea><br>

        <label>Giá:</label><br>
        <input type="number" name="price" step="0.01" required><br>

        <label>Số lượng:</label><br>
        <input type="number" name="stock" required><br>

        <label>Ảnh:</label><br>
        <input type="file" name="image"><br><br>

        <button type="submit">Thêm sản phẩm</button>
    </form>
</div>

<script>
    const btnShowForm = document.getElementById('btnShowForm');
    const productForm = document.getElementById('productForm');

    btnShowForm.addEventListener('click', () => {
        // Toggle hiển thị form
        if(productForm.style.display === 'none'){
            productForm.style.display = 'block';
        } else {
            productForm.style.display = 'none';
        }
    });
</script>

<hr>

<table border="1" cellpadding="8" cellspacing="0">
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
