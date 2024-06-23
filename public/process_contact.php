<?php
// Incluir el archivo de configuración de la base de datos
require_once '../private/includes/db.php';

// Incluir PHPMailer
require '../public/assets/PHPMailer/src/PHPMailer.php';
require '../public/assets/PHPMailer/src/SMTP.php';
require '../public/assets/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Iniciar sesión
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Guardar en la base de datos
    $sql = "INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        // Enviar el correo electrónico
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
            $mail->Subject = 'Confirmación de contacto';
            $mail->Body = "Hola $name,\n\nGracias por contactarnos. Hemos recibido tu mensaje y te responderemos pronto.\n\nMensaje recibido:\n$message";

            $mail->send();
            $_SESSION['message'] = 'Mensaje enviado y guardado correctamente.';
            $_SESSION['message_type'] = 'success';
        } catch (Exception $e) {
            $_SESSION['message'] = "El mensaje no pudo ser enviado. Error de correo: {$mail->ErrorInfo}";
            $_SESSION['message_type'] = 'danger';
        }
    } else {
        $_SESSION['message'] = "Error al guardar el mensaje en la base de datos.";
        $_SESSION['message_type'] = 'danger';
    }

    $stmt->close();
    $conn->close();

    // Redirigir a la página de contacto
    header('Location: contact.php');
    exit();
}
?>
