<?php
session_start();
require_once '../../private/includes/db.php';
require_once '../../private/includes/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);
        
        // Verificar si el producto existe en la base de datos
        $product = getProductById($product_id);
        
        if ($product) {
            // Inicializar o recuperar el carrito desde la sesión
            $_SESSION['cart'] = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
            
            // Verificar si el producto ya está en el carrito
            if (isset($_SESSION['cart'][$product_id])) {
                // Incrementar la cantidad si ya existe en el carrito
                $_SESSION['cart'][$product_id]++;
            } else {
                // Agregar el producto al carrito con cantidad 1
                $_SESSION['cart'][$product_id] = 1;
            }
            
            header('Location: ../index.php');
        } else {
            header('Location: ../index.php');
        }
    } else {
        header('Location: ../index.php');
    }
} else {
    echo "Acceso no permitido.";
}
?>
