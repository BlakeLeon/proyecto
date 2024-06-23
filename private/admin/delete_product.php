<?php
session_start();
require_once '../../private/includes/db.php';
require_once '../../private/includes/functions.php';

if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_name'])) {
    $_SESSION['error_message'] = "Acceso denegado. Debes iniciar sesión como administrador.";
    header('Location: login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $product_id = sanitizeInput($_GET['id']);

    // Obtener la información del producto para mostrar en el mensaje de confirmación
    $product = getProductById($product_id);

    if (!$product) {
        die("Producto no encontrado.");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_delete'])) {
    $product_id = sanitizeInput($_POST['product_id']);

    if (deleteProduct($product_id)) {
        header('Location: index.php');
        exit();
    } else {
        die("Error al intentar borrar el producto.");
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="container mt-4">
        <h2>Confirmar Eliminación de Producto</h2>
        <?php if ($product): ?>
            <div class="alert alert-danger">
                <p>Estás a punto de eliminar el siguiente producto:</p>
                <p><strong>ID:</strong> <?php echo $product['id']; ?></p>
                <p><strong>Nombre:</strong> <?php echo $product['nombre']; ?></p>
                <p><strong>Descripción:</strong> <?php echo $product['descripcion']; ?></p>
                <p><strong>Precio:</strong> $<?php echo $product['precio']; ?></p>
            </div>
            <form method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button type="submit" class="btn btn-danger" name="confirm_delete">Confirmar Eliminación</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </form>
        <?php else: ?>
            <p>Producto no encontrado.</p>
        <?php endif; ?>
    </div>
    <?php include '../../public/includes/footer.php'; ?>
</body>
</html>
