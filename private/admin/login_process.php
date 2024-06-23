<?php
session_start();
require_once '../../private/includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM usuarios WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            if ($user['es_admin'] == 1) {
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_name'] = $user['nombre'];
                header('Location: index.php');
                exit();
            } else {
                $_SESSION['error_message'] = "Acceso denegado. No eres administrador.";
                header('Location: login.php');
                exit();
            }
        } else {
            $_SESSION['error_message'] = "Contraseña incorrecta.";
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Usuario no encontrado.";
        header('Location: login.php');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}
?>
