<?php
// Incluir el archivo de configuración de la base de datos
require_once '../../private/includes/db.php';

// Iniciar o reanudar la sesión
session_start();

// Verificar si el usuario está autenticado como administrador
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_name'])) {
    $_SESSION['error_message'] = "Acceso denegado. Debes iniciar sesión como administrador.";
    header('Location: login.php');
    exit();
}

// Obtener todos los mensajes de la base de datos
$sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Mensajes - Tienda de Robots Educativos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../includes/header.php'; ?>
    <div class="container mt-5">
        <h2>Mensajes Recibidos</h2>
        
        <?php if (isset($_SESSION['response_message'])): ?>
            <div class="alert alert-<?= $_SESSION['response_message_type']; ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['response_message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['response_message']); unset($_SESSION['response_message_type']); ?>
        <?php endif; ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Mensaje</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['name']; ?></td>
                        <td><?= $row['email']; ?></td>
                        <td><?= $row['message']; ?></td>
                        <td><?= $row['created_at']; ?></td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#responseModal" data-id="<?= $row['id']; ?>" data-email="<?= $row['email']; ?>" data-name="<?= $row['name']; ?>">Responder</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para responder -->
    <div class="modal fade" id="responseModal" tabindex="-1" aria-labelledby="responseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="responseModalLabel">Responder al Mensaje</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="send_response.php" method="POST">
                        <input type="hidden" id="messageId" name="message_id">
                        <input type="hidden" id="email" name="email">
                        <input type="hidden" id="name" name="name">
                        <div class="mb-3">
                            <label for="response" class="form-label">Tu Respuesta:</label>
                            <textarea id="response" name="response" rows="4" class="form-control" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-secondary">Enviar Respuesta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        var responseModal = document.getElementById('responseModal');
        responseModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var messageId = button.getAttribute('data-id');
            var email = button.getAttribute('data-email');
            var name = button.getAttribute('data-name');

            var modalTitle = responseModal.querySelector('.modal-title');
            var modalBodyInputId = responseModal.querySelector('#messageId');
            var modalBodyInputEmail = responseModal.querySelector('#email');
            var modalBodyInputName = responseModal.querySelector('#name');

            modalTitle.textContent = 'Responder a ' + name;
            modalBodyInputId.value = messageId;
            modalBodyInputEmail.value = email;
            modalBodyInputName.value = name;
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include '../../public/includes/footer.php'; ?>
</body>
</html>
