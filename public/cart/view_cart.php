<?php
session_start();
include '../../private/includes/db.php';
include '../../private/includes/functions.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$products = [];

foreach ($cart as $product_id => $quantity) {
    $product = getProductById($product_id);
    if ($product) {
        $product['quantity'] = $quantity;
        $products[] = $product;
    }
}

$total = array_reduce($products, function ($sum, $product) {
    return $sum + ($product['precio'] * $product['quantity']);
}, 0);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="../assets/css/carro.css">
    <style>
        body {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="cart-container">
        <h1>Carrito de Compras</h1>
        <?php if (count($products) > 0): ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($product['precio']); ?>€</td>
                            <td>
                                <form action="update_cart.php" method="post">
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                                    <input type="number" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" min="1">
                                    <button type="submit">Actualizar</button>
                                </form>
                            </td>
                            <td>€<?php echo htmlspecialchars($product['precio'] * $product['quantity']); ?></td>
                            <td>
                                <form action="remove_from_cart.php" method="post">
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                                    <button type="submit" class="delete-btn">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="cart-footer">
                <h3 class="total">Total: <?php echo htmlspecialchars($total); ?>€</h3>
                <?php if (isset($_SESSION['usuario'])): ?>
                    <a href="checkout.php" class="proceed-btn">Proceder al Pago</a>
                <?php else: ?>
                    <a href="../login.php" class="proceed-btn">Iniciar Sesión para Proceder al Pago</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p class="empty-cart">Tu carrito está vacío.</p>
        <?php endif; ?>
    </div>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>
