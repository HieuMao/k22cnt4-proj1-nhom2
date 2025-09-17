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
        padding: 25px 30px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        max-width: 500px;
        margin-bottom: 30px;
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
    button {
        margin-top: 15px;
        padding: 10px 20px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        color: #fff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: transform 0.2s, background 0.3s;
    }
    button:hover {
        transform: scale(1.03);
        background: linear-gradient(90deg, #5a67d8, #6b4db0);
    }
</style>

<h2>Sửa danh mục</h2>

<form method="POST">
    <label>Tên danh mục:</label>
    <input type="text" name="name" value="<?= $category['name'] ?>" required>

    <label>Mô tả:</label>
    <textarea name="description"><?= $category['description'] ?></textarea>

    <button type="submit">Cập nhật</button>
</form>
