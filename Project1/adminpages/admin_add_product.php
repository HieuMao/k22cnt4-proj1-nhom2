<?php
require '../connect.php';

// Xử lý thêm sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = $_POST['name'];
    $category_id = intval($_POST['category_id']);
    $description = $_POST['description'];
    $price       = $_POST['price'];
    $stock       = $_POST['stock'];

    // Xử lý upload ảnh
    $image = '';
    if (!empty($_FILES['image']['name'])) {
        $targetDir  = "../images/";
        $image      = basename($_FILES['image']['name']); // chỉ lấy tên gốc
        $targetFile = $targetDir . $image;

        // di chuyển file upload vào thư mục images
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            // ok
        } else {
            echo "Lỗi khi upload ảnh!";
            exit;
        }
    }

    // Thêm vào DB
    $stmt = $mysqli->prepare("INSERT INTO products (category_id, name, description, price, image, stock) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdsi", $category_id, $name, $description, $price, $image, $stock);
    $stmt->execute();

    header("Location: admin_products.php");
    exit();
}
?>