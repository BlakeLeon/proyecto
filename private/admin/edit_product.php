<?php
session_start();
require_once '../../private/includes/db.php';
require_once '../../private/includes/functions.php';

if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_name'])) {
    $_SESSION['error_message'] = "Acceso denegado. Debes iniciar sesión como administrador.";
    header('Location: login.php');
    exit();
}

// Verificar si se envió el formulario por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = sanitizeInput($_POST['product_id']);
    $nombre = sanitizeInput($_POST['nombre']);
    $descripcion = sanitizeInput($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $imagen = ''; // Variable para almacenar el nombre de la imagen

    // Procesamiento de la imagen si se ha subido una nueva
    if ($_FILES['imagen']['size'] > 0) {
        // Configurar la carpeta de destino para las imágenes
        $uploadDirectory = '../../public/assets/images/';

        // Obtener información del archivo
        $fileName = basename($_FILES['imagen']['name']);
        $targetPath = $uploadDirectory . $fileName;
        $fileType = pathinfo($targetPath, PATHINFO_EXTENSION);

        // Permitir solo ciertos tipos de archivos (ejemplo: jpg, jpeg, png)
        $allowedTypes = array('jpg', 'jpeg', 'png');
        if (in_array(strtolower($fileType), $allowedTypes)) {
            // Intentar mover el archivo a la carpeta de destino
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $targetPath)) {
                // Guardar solo el nombre del archivo en la variable $imagen
                $imagen = $fileName;
            } else {
                $_SESSION['error_message'] = "Error al subir el archivo.";
                header('Location: edit_product.php?id=' . $product_id);
                exit();
            }
        } else {
            $_SESSION['error_message'] = "Solo se permiten archivos JPG, JPEG y PNG.";
            header('Location: edit_product.php?id=' . $product_id);
            exit();
        }
    } else {
        // Si no se seleccionó una nueva imagen, mantener la existente sin cambios
        $imagen = sanitizeInput($_POST['imagen_actual']);
    }

    // Llamar a la función para actualizar el producto
    if (updateProduct($product_id, $nombre, $descripcion, $precio, $imagen)) {
        $_SESSION['success_message'] = "Producto actualizado correctamente.";
        header('Location: index.php');
        exit();
    } else {
        $_SESSION['error_message'] = "Error al actualizar el producto.";
        header('Location: edit_product.php?id=' . $product_id);
        exit();
    }
}

// Obtener datos del producto para mostrar en el formulario
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $product = getProductById($product_id);
    if (!$product) {
        $_SESSION['error_message'] = "Producto no encontrado.";
        header('Location: index.php');
        exit();
    }
} else {
    $_SESSION['error_message'] = "ID de producto no especificado.";
    header('Location: manage_products.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .title-container {
            text-align: center;
            margin-top: 50px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <?php include '../includes/header.php'; ?>

    <div class="container mt-5">
        <div class="title-container">
            <h2>Editar Producto</h2>
        </div>
        <?php if (isset($_SESSION['error_message'])): ?>
            <p style="color: red;"><?php echo $_SESSION['error_message']; ?></p>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <form method="POST" action="edit_product.php" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
            <input type="hidden" name="imagen_actual" value="<?php echo htmlspecialchars($product['imagen']); ?>">

            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($product['nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea class="form-control" id="descripcion" name="descripcion"><?php echo htmlspecialchars($product['descripcion']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="precio">Precio:</label>
                <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="<?php echo htmlspecialchars($product['precio']); ?>" required>
            </div>
            <div class="form-group">
                <label for="imagen">Imagen:</label>
                <input type="file" class="form-control-file" id="imagen" name="imagen">
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Producto</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <?php include '../../public/includes/footer.php'; ?>

</body>
</html>
