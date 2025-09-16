<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = (int)$_POST['product_id'];
    $name  = $_POST['product_name'];
    $price = (float)$_POST['product_price'];
    $image = $_POST['product_image'];
    $qty   = max(1, (int)$_POST['quantity']);

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity'] += $qty;
    } else {
        $_SESSION['cart'][$id] = [
            'name'     => $name,
            'price'    => $price,
            'image'    => $image,
            'quantity' => $qty
        ];
    }

    $totalQty = array_sum(array_column($_SESSION['cart'], 'quantity'));
    echo json_encode(['success' => true, 'totalQty' => $totalQty]);
    exit;
}

echo json_encode(['success' => false]);
