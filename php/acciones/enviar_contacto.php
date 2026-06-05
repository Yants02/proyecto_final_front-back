<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../includes/PHPMailer/Exception.php';
require '../includes/PHPMailer/PHPMailer.php';
require '../includes/PHPMailer/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre = htmlspecialchars($_POST['nombre']);
    $email = htmlspecialchars($_POST['email']);
    $consulta = htmlspecialchars($_POST['consulta']);
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'gerbisu@gmail.com';
        $mail->Password   = 'bahp dets sfxq miny';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';
        $mail->setFrom('gerbisu@gmail.com', 'Kiosko Web');
        $mail->addAddress('gerbisu@gmail.com', 'Germán Bisutti'); 
        $mail->addReplyTo($email, $nombre); 

        $mail->isHTML(true);
        $mail->Subject = 'Nueva consulta web de: ' . $nombre;
        $mail->Body    = '<h2 style="color: #155DFC;">Nueva consulta desde Kiosko Manager</h2>
                          <p><strong>Nombre:</strong> ' . $nombre . '</p>
                          <p><strong>Email:</strong> ' . $email . '</p>
                          <p><strong>Mensaje:</strong><br>' . nl2br($consulta) . '</p>';

        $mail->send();
        $mail->clearAddresses(); 
        $mail->clearReplyTos();  

        $mail->setFrom('gerbisu@gmail.com', 'Kiosko Manager');
        $mail->addAddress($email, $nombre); 

        $mail->Subject = 'Recibimos tu mensaje - Kiosko Manager';
        $mail->Body    = '<div style="font-family: Arial, sans-serif; color: #333;">
                            <h2 style="color: #155DFC;">¡Hola ' . $nombre . '!</h2>
                            <p>Queríamos avisarte que <strong>recibimos tu mensaje correctamente</strong>.</p>
                            <p>Nos vamos a estar poniendo en contacto con vos a la brevedad a esta misma dirección de correo electrónico.</p>
                            <hr style="border: 1px solid #eee; margin: 20px 0;">
                            <p style="font-size: 12px; color: #777;">
                                <strong>Tu mensaje original fue:</strong><br>
                                <i>"' . nl2br($consulta) . '"</i>
                            </p>
                            <p>Saludos cordiales,<br><strong>El equipo de Kiosko Manager</strong></p>
                          </div>';

        $mail->send();

        header("Location: ../../index.php?mensaje=enviado#contacto");
        exit();

    } catch (Exception $e) {
        echo "Error al enviar el mensaje. Detalle técnico: {$mail->ErrorInfo}";
    }
} else {
    header("Location: ../../index.php");
    exit();
}
?>