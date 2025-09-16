<?php
require '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $stmt = $mysqli->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $description);
    $stmt->execute();

    header("Location: admin_categories.php");
    exit();
}
?>
