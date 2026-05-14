<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'includes/credenciales.php';
// Cargamos la librería manualmente como la tenés en tu carpeta includes
require 'includes/PHPMailer/Exception.php';
require 'includes/PHPMailer/PHPMailer.php';
require 'includes/PHPMailer/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre = $_POST['nombre'];
    $email_cliente = $_POST['email'];
    $consulta = $_POST['consulta'];

    $mail = new PHPMailer(true);

    try {
        // --- CONFIGURACIÓN DEL SERVIDOR SMTP (Tu Gmail) ---
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USER;       // Tu email
        $mail->Password   = MAIL_PASS;    // La clave de aplicación de Google
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // --- ENVÍO 1: NOTIFICACIÓN PARA VOS (ADMIN) ---
        $mail->setFrom('gerbisu@gmail.com', 'Sistema de Gestion');
        $mail->addAddress('gerbisu@gmail.com', 'German Admin'); // Te lo enviás a vos mismo
        
        // Configuramos para que si le das a "Responder", le escribas al cliente
        $mail->addReplyTo($email_cliente, $nombre);

        $mail->isHTML(true);
        $mail->Subject = "Nueva consulta web de $nombre";
        $mail->Body    = "<h3>Detalles de la consulta:</h3>
                          <p><b>Nombre:</b> $nombre</p>
                          <p><b>Email:</b> $email_cliente</p>
                          <p><b>Mensaje:</b><br>$consulta</p>";

        $mail->send(); // Se envía el primero

        // --- ENVÍO 2: RESPUESTA AUTOMÁTICA PARA EL CLIENTE ---
        $mail->clearAddresses(); // Limpiamos tu dirección para poner la del cliente
        $mail->clearReplyTos();  // Limpiamos el ReplyTo anterior

        $mail->addAddress($email_cliente, $nombre); // Ahora el destino es el cliente
        $mail->Subject = 'Recibimos tu mensaje - Sistema de Gestion';
        $mail->Body    = "Hola <b>$nombre</b>,<br><br>
                          Gracias por contactarte con nosotros. Hemos recibido tu consulta correctamente y 
                          te responderemos a la brevedad.<br><br>
                          Saludos,<br>Germán.";

        $mail->send(); // Se envía el segundo

        // Redirección con mensaje de éxito
        header("Location: ../onepage.php?mensaje=enviado#contacto");
        exit();

    } catch (Exception $e) {
        echo "Error al enviar el mensaje: {$mail->ErrorInfo}";
    }
}