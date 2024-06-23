<?php
session_start();
include '../../private/includes/db.php';
include '../../private/includes/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = sanitizeInput($_POST['product_id']);

    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }

    redirect('view_cart.php');
} else {
    redirect('../products/index.php');
}
?>
