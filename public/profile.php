<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include '../public/includes/header.php'; ?>

    <div class="container mt-5">
        <h2>Bienvenido, <?php echo htmlspecialchars($user['nombre']); ?>!</h2>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
        <!-- Más información del perfil -->
    </div>

    <?php include '../public/includes/footer.php'; ?>
</body>
</html>
