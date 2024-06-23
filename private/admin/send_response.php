<?php
// Incluir el archivo de configuración de la base de datos
require_once '../../private/includes/db.php';

// Incluir PHPMailer
require '../../public/assets/PHPMailer/src/PHPMailer.php';
require '../../public/assets/PHPMailer/src/SMTP.php';
require '../../public/assets/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Iniciar sesión
session_start();

// Verificar si el usuario está autenticado como administrador
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_name'])) {
    $_SESSION['error_message'] = "Acceso denegado. Debes iniciar sesión como administrador.";
    header('Location: login.php'); // Redirigir al inicio de sesión si no está autenticado
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $messageId = $_POST['message_id'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $response = $_POST['response'];

    // Enviar el correo electrónico de respuesta
    $mail = new PHPMailer(true);
    try {
        // Configuración del servidor SMTP de Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tiendarobotsadri@gmail.com';//poner correo
        $mail->Password = 'cphl gdcj xwmb odah'; // Utiliza una contraseña de aplicación o generar una contraseña para la app
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configuración del correo
        $mail->setFrom('tiendarobotsadri@gmail.com', 'Tienda de Robots Educativos'); // poner mail para que la gente pudiera responder
        $mail->addAddress($email, $name);
        $mail->Subject = 'Respuesta a tu mensaje';
        $mail->Body = "Hola $name,\n\nGracias por contactarnos. Aquí está nuestra respuesta a tu mensaje:\n\n$response";

        $mail->send();
        $_SESSION['response_message'] = 'Respuesta enviada correctamente.';
        $_SESSION['response_message_type'] = 'success';
    } catch (Exception $e) {
        $_SESSION['response_message'] = "La respuesta no pudo ser enviada. Error de correo: {$mail->ErrorInfo}";
        $_SESSION['response_message_type'] = 'danger';
    }

    header('Location: admin_messages.php');
    exit();
}
?>
