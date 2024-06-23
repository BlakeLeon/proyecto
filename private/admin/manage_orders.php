<?php
session_start();
include '../../private/includes/functions.php';

// Verificar si el usuario es administrador
if (!isAdmin()) {
    redirect('/public/login.php');
}

// Conectar a la base de datos
include '../../private/includes/db.php';

// Obtener todos los pedidos
$sql = "SELECT * FROM pedidos";
$result = $conn->query($sql);
$pedidos = [];

while ($row = $result->fetch_assoc()) {
    $pedidos[] = $row;
}

$conn->close();
?>

<?php include '../../public/includes/header.php'; ?>

<h2>Gestionar Pedidos</h2>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID Pedido</th>
            <th>Usuario</th>
            <th>Fecha</th>
            <th>Total</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pedidos as $pedido): ?>
        <tr>
            <td><?php echo $pedido['id']; ?></td>
            <td><?php echo $pedido['usuario_email']; ?></td>
            <td><?php echo $pedido['fecha']; ?></td>
            <td><?php echo $pedido['total']; ?></td>
            <td><?php echo $pedido['estado']; ?></td>
            <td>
                <form method="post" action="update_order_status.php">
                    <input type="hidden" name="pedido_id" value="<?php echo $pedido['id']; ?>">
                    <select name="estado">
                        <option value="pendiente" <?php echo $pedido['estado'] == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                        <option value="procesado" <?php echo $pedido['estado'] == 'procesado' ? 'selected' : ''; ?>>Procesado</option>
                        <option value="enviado" <?php echo $pedido['estado'] == 'enviado' ? 'selected' : ''; ?>>Enviado</option>
                        <option value="completado" <?php echo $pedido['estado'] == 'completado' ? 'selected' : ''; ?>>Completado</option>
                        <option value="cancelado" <?php echo $pedido['estado'] == 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                    </select>
                    <input type="submit" value="Actualizar">
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../../public/includes/footer.php'; ?>
