<?php
session_start();
include '../private/includes/db.php';
include '../private/includes/functions.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : ''; // Captura el nombre del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Verifica si las contraseñas coinciden
    if ($password == $confirm_password) {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        // Verifica si el email ya está registrado
        if (getUserByEmail($email)) {
            $error = "Este email ya está registrado.";
        } else {
            // Intenta registrar el nuevo usuario
            if (registerUser($nombre, $email, $password_hashed)) {
                $success = "Registro exitoso. Ahora puedes iniciar sesión.";
            } else {
                $error = "Error al registrar el usuario.";
            }
        }
    } else {
        $error = "Las contraseñas no coinciden.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .title-container {
            text-align: center; /* Alinea el contenido al centro */
            margin-top: 50px; /* Espacio superior para separar el título */
            margin-bottom: 20px; /* Espacio inferior opcional */
        }
    </style>
</head>
<body>

    <?php include '../public/includes/header.php'; // CABECERA: mantener dentro del body para que se aplique el estilo de fondo ?>

    <div class="container mt-5">

        <!-- titulo -->
        <div class="title-container">
            <h2>Registrate</h2>
        </div>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- formulario -->
        <form method="POST" action="register.php" class="mt-4">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmar Contraseña:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrarse</button>

        </form>

    </div>

    <?php include '../public/includes/footer.php'; // pie de pagina ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

