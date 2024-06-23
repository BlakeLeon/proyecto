<?php
session_start();
require_once '../../private/includes/db.php';
require_once '../../private/includes/functions.php';

if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_name'])) {
    $_SESSION['error_message'] = "Acceso denegado. Debes iniciar sesión como administrador.";
    header('Location: login.php');
    exit();
}

// Actualizar el estado del pedido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pedido_id = intval($_POST['pedido_id']);
    $estado = $_POST['estado'];

    $query = "UPDATE pedidos SET estado = '$estado' WHERE id = $pedido_id";
    mysqli_query($db, $query);
}

// Obtener todos los pedidos
$query = "SELECT pedidos.*, usuarios.nombre AS usuario_nombre FROM pedidos JOIN usuarios ON pedidos.user_id = usuarios.id ORDER BY pedidos.fecha_creacion DESC";
$result = mysqli_query($db, $query);
$pedidos = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Obtener los productos de cada pedido
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
    <title>Administrar Pedidos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include '../includes/header.php'; ?>
    <div class="container mt-5">
        <h1>Administrar Pedidos</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Usuario</th>
                    <th>Total</th>
                    <th>Fecha de Creación</th>
                    <th>Estado</th>
                    <th>Actualizar Estado</th>
                    <th>Productos</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td><?php echo $pedido['id']; ?></td>
                        <td><?php echo htmlspecialchars($pedido['usuario_nombre']); ?></td>
                        <td>€<?php echo number_format($pedido['total'], 2); ?></td>
                        <td><?php echo $pedido['fecha_creacion']; ?></td>
                        <td><?php echo htmlspecialchars($pedido['estado']); ?></td>
                        <td>
                            <form action="admin_orders.php" method="post">
                                <input type="hidden" name="pedido_id" value="<?php echo $pedido['id']; ?>">
                                <select name="estado" class="form-control">
                                    <option value="confirmado" <?php if ($pedido['estado'] == 'confirmado') echo 'selected'; ?>>Confirmado</option>
                                    <option value="preparando" <?php if ($pedido['estado'] == 'preparando') echo 'selected'; ?>>Preparando</option>
                                    <option value="enviado" <?php if ($pedido['estado'] == 'enviado') echo 'selected'; ?>>Enviado</option>
                                    <option value="entregado" <?php if ($pedido['estado'] == 'entregado') echo 'selected'; ?>>Entregado</option>
                                </select>
                                <button type="submit" class="btn btn-primary mt-2">Actualizar</button>
                            </form>
                        </td>
                        <td>
                            <ul>
                                <?php foreach ($pedido['productos'] as $producto): ?>
                                    <li><?php echo htmlspecialchars($producto['product_nombre']); ?> (x<?php echo $producto['cantidad']; ?>) - €<?php echo number_format($producto['precio'], 2); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php include '../../public/includes/footer.php'; ?>
</body>
</html>
