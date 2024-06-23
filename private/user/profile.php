<?php
// Incluir el archivo de configuración de la base de datos y funciones
require_once '../includes/db.php'; // Ajusta la ruta según la estructura de tu proyecto
require_once '../includes/functions.php'; // Ajusta la ruta según la estructura de tu proyecto

// Verificar si el usuario está autenticado como administrador
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_name'])) {
    $_SESSION['error_message'] = "Acceso denegado. Debes iniciar sesión como administrador.";
    header('Location: ../admin/login.php');
    exit();
}

// Obtener datos del usuario actual (ejemplo)
$user = getUserByEmail($_SESSION['usuario']);

// Incluir el encabezado y la barra de navegación del perfil de usuario
include '../includes/header.php'; // Ajusta la ruta según la estructura de tu proyecto
?>

<div class="container">
    <h2>Perfil de Usuario</h2>
    <p>Nombre: <?php echo $user['nombre']; ?></p>
    <p>Email: <?php echo $user['email']; ?></p>
    <!-- Más información del perfil del usuario según sea necesario -->
</div>

<?php include '../includes/footer.php'; // Ajusta la ruta según la estructura de tu proyecto ?>
