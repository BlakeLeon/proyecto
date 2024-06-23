<?php
session_start();
include '../private/includes/db.php';
include '../private/includes/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);
    
    $user = getUserByEmail($email);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['usuario'] = $user;
        $_SESSION['user_id'] = $user['id']; // Almacena el ID del usuario
        header('Location: index.php'); // Redirigir a index.php
        exit();
    } else {
        $error = "Email o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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

    <?php include '../public/includes/header.php'; ?>

    <div class="container mt-5">
        <div class="title-container">
            <h2>Inicia sesión</h2>
        </div>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php" class="container">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Iniciar sesión</button>
        </form>
        
        <div class="mt-3 text-center">
            <p>¿Aún no tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
