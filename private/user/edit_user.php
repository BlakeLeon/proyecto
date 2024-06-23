<?php
session_start();
include '../includes/header.php';
require_once '../../private/includes/db.php';
require_once '../../private/includes/functions.php';

// Verificar si el usuario está autenticado como administrador
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_name'])) {
    $_SESSION['error_message'] = "Acceso denegado. Debes iniciar sesión como administrador.";
    header('Location: ../admin/login.php');
    exit();
}

// Verificar si se envió el formulario por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = sanitizeInput($_POST['user_id']);
    $nombre = sanitizeInput($_POST['nombre']);
    $email = sanitizeInput($_POST['email']);
    $es_admin = isset($_POST['es_admin']) ? 1 : 0; // Convertir a 1 si está marcado, 0 si no

    // Llamar a la función para actualizar el usuario
    if (updateUser($user_id, $nombre, $email, $es_admin)) {
        $_SESSION['success_message'] = "Usuario actualizado correctamente.";
        header('Location: manage_users.php');
        exit();
    } else {
        $error = "Error al actualizar el usuario.";
    }
}

// Obtener datos del usuario para mostrar en el formulario
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $user = getUserById($user_id);
    if (!$user) {
        $_SESSION['error_message'] = "Usuario no encontrado.";
        header('Location: manage_users.php');
        exit();
    }
} else {
    $_SESSION['error_message'] = "ID de usuario no especificado.";
    header('Location: manage_users.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

    <div class="container mt-5">
        <h2>Editar Usuario</h2>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" action="edit_user.php">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
            
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="es_admin" name="es_admin" <?php echo $user['es_admin'] ? 'checked' : ''; ?>>
                <label class="form-check-label" for="es_admin">Es administrador</label>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Actualizar Usuario</button>
        </form>
    </div>

</body>
<?php include '../../public/includes/footer.php'; ?>
</html>
