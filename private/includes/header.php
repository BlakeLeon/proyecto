<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda de Robots Educativos</title>
    <link rel="stylesheet" href="/public/assets/css/header.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
      body {
          background-color: #f8f9fa; /* Fondo gris claro */
      }

      .navbar-brand {
          color: #ffffff !important; /* Color rojo para la marca */
      }

      .navbar-nav .nav-item .nav-link {
          color: #ffffff !important; /* Color blanco para los enlaces */
      }

      .navbar-nav .nav-item .nav-link:hover {
          color: grey !important; /* Color gris al pasar el ratón */
      }

      .container {
          margin-top: 20px;
      }
    </style>
  </head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="/proyecto/private/admin">Tienda de Robots</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item"><a class="nav-link" href="/proyecto/private/admin">Productos</a></li>
      <li class="nav-item"><a class="nav-link" href="/proyecto/private/admin/add_product.php">Añadir producto</a></li>
      <li class="nav-item"><a class="nav-link" href="/proyecto/private/user/manage_users.php">Usuarios</a></li>
      <li class="nav-item"><a class="nav-link" href="/proyecto/private/admin/admin_orders.php">Pedidos</a></li>
      <li class="nav-item"><a class="nav-link" href="/proyecto/private/admin/admin_messages.php">Mensajes</a></li>
      <li class="nav-item"><a class="nav-link" href="/proyecto/private/admin/cerrar_sesion.php">Cerrar sesion</a></li>
    </ul>
  </div>
</nav>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
