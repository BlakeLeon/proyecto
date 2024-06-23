<?php
// Incluir el archivo de configuración de la base de datos y funciones
require_once '../private/includes/db.php';
require_once '../private/includes/functions.php';

// Iniciar o reanudar la sesión
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - Tienda de Robots Educativos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-M46fJ2J21ZIRdwbC7tDv7M3U8HSUOv7MEo3FoO+YdBAX0k6tFcItAd0LyI6p1E1C" crossorigin="anonymous">
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
            <h2>Contacto</h2>
        </div>

        <!-- Mostrar mensaje de éxito o error -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
        <?php endif; ?>

        <!-- formulario de contacto -->
        <form action="process_contact.php" method="POST" class="mt-4">
            <div class="mb-3">
                <label for="name" class="form-label">Nombre:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label for="message" class="form-label">Mensaje:</label>
                <textarea id="message" name="message" rows="4" class="form-control" required></textarea>
            </div>
        
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>

    </div>

    <?php include '../public/includes/footer.php'; // pie de pagina ?>

</body>
</html>
