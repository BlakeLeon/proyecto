<?php
session_start();
require_once '../../private/includes/db.php';
require_once '../../private/includes/functions.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit();
}

$user = $_SESSION['usuario'];
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (empty($cart)) {
    header('Location: view_cart.php');
    exit();
}

// Calcular el total del pedido
$total = 0;
foreach ($cart as $product_id => $quantity) {
    $product = getProductById($product_id);
    $total += $product['precio'] * $quantity;
}

// Insertar el pedido en la base de datos
$query = "INSERT INTO pedidos (user_id, total) VALUES ('{$user['id']}', '$total')";
mysqli_query($db, $query);
$pedido_id = mysqli_insert_id($db);

// Insertar los productos del pedido
foreach ($cart as $product_id => $quantity) {
    $product = getProductById($product_id);
    $precio = $product['precio'];
    $query = "INSERT INTO pedido_productos (pedido_id, product_id, cantidad, precio) VALUES ('$pedido_id', '$product_id', '$quantity', '$precio')";
    mysqli_query($db, $query);
}

// Vaciar el carrito
$_SESSION['cart'] = [];

// Redirigir al perfil
header('Location: ../perfil.php');
exit();
?>
