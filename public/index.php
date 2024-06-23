<?php
session_start();
require_once '../private/includes/db.php';
require_once '../private/includes/functions.php';

if (isset($_SESSION['usuario'])) {
    $user = $_SESSION['usuario'];
}

$products = getAllProducts();
$bannerWidth = '100%';
$bannerHeight = '300px';
$bannerImage = '/proyecto/public/assets/images/banner.jpg';
?>

<?php include 'includes/header.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Tienda de Robots Educativos</title>
    <link rel="stylesheet" href="/proyecto/public/assets/css/index.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="banner" style="background-image: url('<?php echo htmlspecialchars($bannerImage); ?>'); height: <?php echo htmlspecialchars($bannerHeight); ?>;">
        <div class="banner-content">
            <h1>Bienvenido a la Tienda de Robots Educativos</h1>
        </div>
    </div>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Catálogo de productos</h2>
            <form class="form-inline" method="GET" action="search.php">
                <input type="text" name="query" placeholder="Buscar productos..." class="form-control mr-2" required>
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
        </div>
        <div class="product-grid">
            <?php
            if ($products && count($products) > 0) {
                foreach ($products as $product) {
                    echo '<div class="product-item">';
                    echo '<img src="assets/images/' . htmlspecialchars($product['imagen'] ?? 'default.png') . '" alt="' . htmlspecialchars($product['nombre'] ?? 'Sin nombre') . '">';
                    echo '<div class="product-info">';
                    echo '<h3>' . htmlspecialchars($product['nombre'] ?? 'Sin nombre') . '</h3>';
                    echo '<p>' . htmlspecialchars($product['descripcion'] ?? 'Sin descripción') . '</p>';
                    echo '<p>Precio: ' . htmlspecialchars($product['precio'] ?? '0.00') . '€</p>';
                    echo '<div class="button-container">';
                    echo '<a href="products/details.php?id=' . htmlspecialchars($product['id'] ?? '#') . '" class="btn btn-secondary">Ver Detalles</a>';
                    echo '<button class="btn btn-primary" onclick="addToCart(' . htmlspecialchars($product['id'] ?? 0) . ')">Agregar al carrito</button>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No hay productos disponibles en este momento.</p>';
            }
            ?>
        </div>
    </div>

    <script>
        function addToCart(productId) {
            var form = document.createElement('form');
            form.setAttribute('method', 'post');
            form.setAttribute('action', '/proyecto/public/cart/add_to_cart.php');

            var productIdField = document.createElement('input');
            productIdField.setAttribute('type', 'hidden');
            productIdField.setAttribute('name', 'product_id');
            productIdField.setAttribute('value', productId);

            form.appendChild(productIdField);
            document.body.appendChild(form);

            form.submit();
        }
    </script>

</body>
</html>

<?php include 'includes/footer.php'; ?>
