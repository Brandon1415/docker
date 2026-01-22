<?php
include '../constants/settings.php';
require '../mail/vendor/autoload.php'; // Asegúrate de que la ruta es correcta

use PHPMailer\PHPMailer\PHPMailer; // Usa el espacio de nombres de PHPMailer

$myfname = ucwords($_POST['fullname']);
$myemail = $_POST['email'];
$mymessage = $_POST['message'];

// Crea una instancia de PHPMailer
$mail = new PHPMailer;

// Configura el servidor SMTP
$mail->isSMTP();  
$mail->Host = $smtp_host;
$mail->SMTPAuth = true;
$mail->Username = $smtp_user;
$mail->Password = $smtp_pass;
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

// Configura el remitente y destinatario
$mail->setFrom($myemail, $myfname);
$mail->addAddress($contact_mail);

// Configura el correo
$mail->isHTML(true);
$mail->Subject = 'Contact';
$mail->Body    = $mymessage;
$mail->AltBody = $mymessage;

// Enviar el correo
if (!$mail->send()) {
    // Si no se envía el correo, muestra el error detallado
    $errorMessage = $mail->ErrorInfo; // Obtenemos el mensaje de error de PHPMailer
    echo "<script>
            alert('Hubo un problema al enviar el correo: $errorMessage. Por favor, intenta nuevamente.');
            window.location.href = '../contact.php?r=2974';
          </script>";
} else {
    echo "<script>
            alert('¡Correo enviado con éxito!');
            window.location.href = '../contact.php?r=5634';
          </script>";
}
?>
