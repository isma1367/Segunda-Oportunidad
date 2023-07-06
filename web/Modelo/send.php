<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require_once('PHPMailer/src/Exception.php');
    require_once('PHPMailer/src/PHPMailer.php');
    require_once('PHPMailer/src/SMTP.php');
    require_once("../Modelo/usuarioDAO.php");

    $usuarioDAO = new usuarioDAO();

try {
    $mail = new PHPMailer(true);  
               //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->SMTPSecure = "tls";                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'Tu correo';           //SMTP username
    $mail->Password   = 'Tu contraseña';                               //SMTP password
    $mail->Port       = "587";                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('Tu correo');
    $mail->addAddress($correo);     //Add a recipient

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Recuperar Contrasena';
    $mail->Body    = "
    <center>
    <h1 style= 'font-family: monospace;'>Recuperación de contraseña</h1>
    <div style='background-color: #E7CBCB; font-family: monospace; font-size: 1.5em; padding: 10px; width:50%;'>
        <p>Estimado usuario,</p>
        <p>Hemos recibido una solicitud para restablecer la contraseña de su cuenta.</p>
        <p style= 'font-weight: bold;'>Su código de recuperación es:<span style= 'font-family: fantasy;'> ".$codigo."</span></p>
        <p>Por favor, utilice este código para completar el proceso de recuperación de contraseña.</p>
        <p>Si no has solicitado esta recuperación de contraseña, puedes ignorar este mensaje.</p>
        <p>Gracias,</p>
        <p>El equipo de soporte</p>
    </div>
    </center>";
    $mail->AltBody = "El código para recuperar la contraseña es : ".$codigo;

    $mail->send();
    // echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    return false;
}
?>
