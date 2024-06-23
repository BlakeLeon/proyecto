<?php
session_start();
include '../includes/header.php';
require_once '../../private/includes/db.php';
require_once '../../private/includes/functions.php';

// Verificar si el usuario está autenticado como administrador
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_name'])) {
    $_SESSION['error_message'] = "Acceso denegado. Debes iniciar sesión como administrador.";
    header('Location: login.php');
    exit();
}

$products = getAllProducts();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Gestionar Productos</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($products && count($products) > 0): ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><img src="../../public/assets/images/<?php echo htmlspecialchars($product['imagen'] ?? 'default.png'); ?>" alt="<?php echo htmlspecialchars($product['nombre'] ?? 'Sin nombre'); ?>" style="width: 100px;"></td>
                            <td><?php echo htmlspecialchars($product['nombre'] ?? 'Sin nombre'); ?></td>
                            <td><?php echo htmlspecialchars($product['descripcion'] ?? 'Sin descripción'); ?></td>
                            <td><?php echo htmlspecialchars($product['precio'] ?? '0.00'); ?></td>
                            <td>
                                <a href="edit_product.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="btn btn-warning">✏️Editar</a>
                                <button class="btn btn-danger" onclick="confirmDelete(<?php echo htmlspecialchars($product['id']); ?>)">🗑️Borrar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No hay productos disponibles</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
    function confirmDelete(productId) {
        if (confirm("¿Estás seguro de que deseas borrar este producto?")) {
            window.location.href = "delete_product.php?id=" + productId;
        }
    }
    </script>
    
</body>
</html>