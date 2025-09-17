<?php
require '../connect.php';
include 'admin_header.php';

if (!isset($_GET['id'])) {
    header("Location: admin_products.php");
    exit();
}

$id = intval($_GET['id']);
$product = $mysqli->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
$categories = $mysqli->query("SELECT * FROM categories");

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
    form {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        max-width: 500px;
    }
    label {
        display: block;
        margin: 12px 0 6px;
        font-weight: 500;
        color: #555;
    }
    input[type="text"], input[type="number"], select, textarea, input[type="file"] {
        width: 100%;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        outline: none;
        box-sizing: border-box;
        transition: border 0.2s, box-shadow 0.2s;
    }
    input:focus, select:focus, textarea:focus {
        border-color: #667eea;
        box-shadow: 0 0 8px rgba(102,126,234,0.4);
    }
    textarea {
        resize: vertical;
    }
    button {
        padding: 12px 20px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        color: #fff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        margin-top: 15px;
        transition: transform 0.2s, background 0.3s;
    }
    button:hover {
        transform: scale(1.03);
        background: linear-gradient(90deg, #5a67d8, #6b4db0);
    }
    a.cancel {
        display: inline-block;
        margin-left: 15px;
        padding: 12px 20px;
        background: #ccc;
        color: #333;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: background 0.3s;
    }
    a.cancel:hover {
        background: #bbb;
    }
    img {
        margin-top: 10px;
        border-radius: 6px;
    }
</style>

<h2>Sửa sản phẩm</h2>
<form method="POST" enctype="multipart/form-data">
    <label>Tên sản phẩm:</label>
    <input type="text" name="name" value="<?= $product['name'] ?>" required>

    <label>Danh mục:</label>
    <select name="category_id" required>
        <?php while($cat = $categories->fetch_assoc()): ?>
            <option value="<?= $cat['id'] ?>" <?= $cat['id']==$product['category_id']?'selected':'' ?>><?= $cat['name'] ?></option>
        <?php endwhile; ?>
    </select>

    <label>Mô tả:</label>
    <textarea name="description"><?= $product['description'] ?></textarea>

    <label>Giá:</label>
    <input type="number" name="price" step="0.01" value="<?= $product['price'] ?>" required>

    <label>Số lượng:</label>
    <input type="number" name="stock" value="<?= $product['stock'] ?>" required>

    <label>Ảnh hiện tại:</label>
    <?php if($product['image']): ?>
        <img src="../images/<?= $product['image'] ?>" width="100">
    <?php endif; ?>

    <label>Thay ảnh mới (nếu có):</label>
    <input type="file" name="image">

    <button type="submit">Cập nhật</button>
    <a href="admin_products.php" class="cancel">Hủy</a>
</form>
