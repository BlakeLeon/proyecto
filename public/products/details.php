<?php
session_start();
include '../../private/includes/db.php';
include '../../private/includes/functions.php';

// Verificar si se ha especificado el ID del producto
if (!isset($_GET['id'])) {
    die("Producto no especificado.");
}

// Obtener información del producto
$product = getProductById($_GET['id']);

if (!$product) {
    die("Producto no encontrado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Producto</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/details.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <img src="../../public/assets/images/<?php echo htmlspecialchars($product['imagen'] ?? 'default.png'); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product['nombre'] ?? 'Sin nombre'); ?>">
        </div>
        <div class="col-md-6">
            <h1><?php echo htmlspecialchars($product['nombre']); ?></h1>
            <p><?php echo htmlspecialchars($product['descripcion']); ?></p>
            <p>Precio: <?php echo number_format($product['precio'], 2); ?>€</p>
            <form action="../cart/add_to_cart.php" method="post" class="mt-3">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button type="submit" class="btn btn-danger">Agregar al carrito</button>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
