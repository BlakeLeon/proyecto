<?php
session_start();
session_destroy(); // Destruye todas las variables de sesión

// Redirige al usuario de vuelta a la página de inicio de sesión o a donde consideres adecuado
header('Location: login.php');
exit();
?>
