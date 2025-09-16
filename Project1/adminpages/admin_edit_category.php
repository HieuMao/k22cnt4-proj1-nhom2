<?php
require '../connect.php';
include 'admin_header.php';

$id = intval($_GET['id']);
$result = $mysqli->query("SELECT * FROM categories WHERE id=$id");
$category = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $stmt = $mysqli->prepare("UPDATE categories SET name=?, description=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $description, $id);
    $stmt->execute();

    header("Location: admin_categories.php");
    exit();
}
?>

<h2>Sửa danh mục</h2>
<form method="POST">
    <label>Tên danh mục:</label><br>
    <input type="text" name="name" value="<?= $category['name'] ?>" required><br>

    <label>Mô tả:</label><br>
    <textarea name="description"><?= $category['description'] ?></textarea><br>

    <button type="submit">Cập nhật</button>
</form>
