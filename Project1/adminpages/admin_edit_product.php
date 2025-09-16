<?php
require '../connect.php';
include 'admin_header.php';


if (!isset($_GET['id'])) {
    header("Location: admin_products.php");
    exit();
}

$id = intval($_GET['id']);

// Lấy thông tin sản phẩm
$product = $mysqli->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();

// Lấy danh mục
$categories = $mysqli->query("SELECT * FROM categories");

// Xử lý cập nhật
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $image = $product['image'];
    if (!empty($_FILES['image']['name'])) {
        $image = time().'_'.$_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], '../images/'.$image);
    }

    $stmt = $mysqli->prepare("UPDATE products SET category_id=?, name=?, description=?, price=?, stock=?, image=? WHERE id=?");
    $stmt->bind_param("issdisi", $category_id, $name, $description, $price, $stock, $image, $id);
    $stmt->execute();

    header("Location: admin_products.php");
    exit();
}
?>

<h2>Sửa sản phẩm</h2>
<form method="POST" enctype="multipart/form-data">
    <label>Tên sản phẩm:</label><br>
    <input type="text" name="name" value="<?= $product['name'] ?>" required><br>

    <label>Danh mục:</label><br>
    <select name="category_id" required>
        <?php while($cat = $categories->fetch_assoc()): ?>
            <option value="<?= $cat['id'] ?>" <?= $cat['id']==$product['category_id']?'selected':'' ?>><?= $cat['name'] ?></option>
        <?php endwhile; ?>
    </select><br>

    <label>Mô tả:</label><br>
    <textarea name="description"><?= $product['description'] ?></textarea><br>

    <label>Giá:</label><br>
    <input type="number" name="price" step="0.01" value="<?= $product['price'] ?>" required><br>

    <label>Số lượng:</label><br>
    <input type="number" name="stock" value="<?= $product['stock'] ?>" required><br>

    <label>Ảnh hiện tại:</label><br>
    <?php if($product['image']): ?>
        <img src="../images/<?= $product['image'] ?>" width="80"><br>
    <?php endif; ?>
    <label>Thay ảnh mới (nếu có):</label><br>
    <input type="file" name="image"><br><br>

    <button type="submit">Cập nhật</button>
    <a href="admin_products.php">Hủy</a>
</form>
