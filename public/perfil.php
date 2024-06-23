<?php
session_start();
include '../private/includes/db.php';
include '../private/includes/functions.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit();
}

$user = $_SESSION['usuario'];
$user_id = $user['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['profile_image'])) {
    $profile_image = file_get_contents($_FILES['profile_image']['tmp_name']);
    $profile_image_base64 = base64_encode($profile_image);

    $direccion = sanitizeInput($_POST['direccion']);

    $query = "UPDATE usuarios SET imagen = '$profile_image_base64', direccion = '$direccion' WHERE id = $user_id";
    mysqli_query($db, $query);

    // Actualizar la variable $user después de la modificación
    $user = getUserById($user_id);
}

// Obtener pedidos del usuario
$query = "SELECT * FROM pedidos WHERE user_id = $user_id";
$result = mysqli_query($db, $query);
$pedidos = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Obtener productos de cada pedido
foreach ($pedidos as &$pedido) {
    $pedido_id = $pedido['id'];
    $query = "SELECT pp.*, p.nombre AS product_nombre FROM pedido_productos pp JOIN productos p ON pp.product_id = p.id WHERE pp.pedido_id = $pedido_id";
    $result = mysqli_query($db, $query);
    $pedido['productos'] = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<?php include '../public/includes/header.php'; ?>

<div class="container mt-5">
    <h2>Perfil de Usuario</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="profile_image">Foto de Perfil:</label>
            <input type="file" class="form-control-file" id="profile_image" name="profile_image">
            <?php if (!empty($user['imagen'])): ?>
                <img src="data:image/jpeg;base64,<?php echo $user['imagen']; ?>" alt="Imagen de Perfil" style="width: 100px; height: 100px;">
            <?php else: ?>
                <p>No hay imagen de perfil seleccionada.</p>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="direccion">Dirección:</label>
            <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo htmlspecialchars($user['direccion']); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Perfil</button>
    </form>

    <h3 class="mt-5">Mis Pedidos</h3>
    <div class="list-group">
        <?php foreach ($pedidos as $pedido): ?>
            <a href="#" class="list-group-item list-group-item-action">
                <h5 class="mb-1">Pedido #<?php echo $pedido['id']; ?></h5>
                <p class="mb-1">Fecha: <?php echo $pedido['fecha_creacion']; ?></p>
                <p class="mb-1">Estado: <?php echo $pedido['estado']; ?></p>
                <small>Total: €<?php echo $pedido['total']; ?></small>
                <h6>Productos:</h6>
                <ul>
                    <?php foreach ($pedido['productos'] as $producto): ?>
                        <li><?php echo htmlspecialchars($producto['product_nombre']); ?> (x<?php echo $producto['cantidad']; ?>) - €<?php echo number_format($producto['precio'], 2); ?></li>
                    <?php endforeach; ?>
                </ul>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<?php include '../public/includes/footer.php'; ?>

</body>
</html>
