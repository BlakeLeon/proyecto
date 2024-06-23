<?php
session_start();
include '../../private/includes/db.php';
include '../../private/includes/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = sanitizeInput($_POST['product_id']);
    $quantity = sanitizeInput($_POST['quantity']);

    if (isset($_SESSION['cart'][$product_id])) {
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id] = $quantity;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }

    redirect('view_cart.php');
} else {
    redirect('../products/index.php');
}
?>
