<?php
// Incluir el archivo de conexión a la base de datos y funciones
require_once '../private/includes/db.php'; // Ajusta la ruta según la estructura de tu proyecto
require_once '../private/includes/functions.php'; // Ajusta la ruta según la estructura de tu proyecto

// Obtener el término de búsqueda desde la URL
$searchQuery = $_GET['query'] ?? '';

// Realizar la búsqueda de productos según el término
$products = searchProducts($searchQuery); // Implementa esta función en functions.php

// Incluir el encabezado y otros elementos HTML necesarios
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de la búsqueda</title>
    <link rel="stylesheet" href="/proyecto/public/assets/css/index.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Resultados de la búsqueda para "<?php echo htmlspecialchars($searchQuery); ?>"</h2>

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
                    echo '<button class="btn btn-secondary" onclick="addToCart(' . htmlspecialchars($product['id'] ?? 0) . ')">Agregar al carrito</button>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No se encontraron productos que coincidan con la búsqueda.</p>';
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
