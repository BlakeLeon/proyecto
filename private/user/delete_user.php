<?php
session_start();
require_once '../../private/includes/db.php';
require_once '../../private/includes/functions.php';

// Verificar si el usuario está autenticado como administrador
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_name'])) {
    $_SESSION['error_message'] = "Acceso denegado. Debes iniciar sesión como administrador.";
    header('Location: ../admin/login.php');
    exit();
}

// Verificar si se ha enviado el ID del usuario por GET
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Llamar a la función para eliminar el usuario
    if (deleteUser($user_id)) {
        $_SESSION['success_message'] = "Usuario eliminado correctamente.";
    } else {
        $_SESSION['error_message'] = "Error al eliminar el usuario.";
    }
} else {
    $_SESSION['error_message'] = "ID de usuario no especificado.";
}

// Redirigir de vuelta a la página de gestión de usuarios
header('Location: manage_users.php');
exit();
?>
