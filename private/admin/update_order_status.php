<?php
session_start();
include '../../private/includes/functions.php';

// Verificar si el usuario es administrador
if (!isAdmin()) {
    redirect('/public/login.php');
}

// Verificar si se recibió un formulario POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../../private/includes/db.php';
    
    $pedido_id = sanitizeInput($_POST['pedido_id']);
    $estado = sanitizeInput($_POST['estado']);
    
    $sql = "UPDATE pedidos SET estado='$estado' WHERE id='$pedido_id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Estado del pedido actualizado exitosamente";
    } else {
        echo "Error actualizando el estado: " . $conn->error;
    }
    
    $conn->close();
    
    // Redirigir de vuelta a la página de gestión de pedidos
    redirect('manage_orders.php');
} else {
    // Redirigir de vuelta si se accede al script directamente
    redirect('manage_orders.php');
}
?>
