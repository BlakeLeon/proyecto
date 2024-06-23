<?php
session_start();
include '../includes/header.php';
require_once '../../private/includes/db.php';
require_once '../../private/includes/functions.php';

$users = getAllUsers();

// Verificar si el usuario está autenticado como administrador
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_name'])) {
    $_SESSION['error_message'] = "Acceso denegado. Debes iniciar sesión como administrador.";
    header('Location: ../admin/login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Usuarios</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Administrar Usuarios</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Fecha de Alta</th>
                    <th>Administrador</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($users && count($users) > 0): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['fecha_creacion']); ?></td>
                            <td><?php echo $user['es_admin'] ? 'Sí' : 'No'; ?></td> <!-- Muestra 'Sí' si es administrador, 'No' si no lo es -->
                            <td>
                                <a href="edit_user.php?id=<?php echo htmlspecialchars($user['id']); ?>" class="btn btn-warning">✏️ Editar</a>
                                <button class="btn btn-danger" onclick="confirmDelete(<?php echo htmlspecialchars($user['id']); ?>)">🗑️ Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No hay usuarios registrados</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
    function confirmDelete(userId) {
        if (confirm("¿Estás seguro de que deseas eliminar este usuario?")) {
            window.location.href = "delete_user.php?id=" + userId;
        }
    }
    </script>

    <?php include '../../public/includes/footer.php'; ?>
</body>
</html>
