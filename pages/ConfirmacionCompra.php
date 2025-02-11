<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include dirname(__DIR__).'/PHPMailer/src/PHPMailer.php';
include dirname(__DIR__).'/PHPMailer/src/SMTP.php';
include dirname(__DIR__).'/PHPMailer/src/Exception.php';

$mail = new PHPMailer(true);

//CREATE INSTANCE

try {
    $mail->SMTPDebug = SMTP::DEBUG_SERVER; //DEBUG_OFF;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Servidor SMTP de Gmail
    $mail->SMTPAuth = true;
    $mail->Username = 'eduarposerosqgmail.com'; // Tu correo de Gmail
    $mail->Password = 'ac-102296*LOPEZ#22'; // Tu contraseña de aplicación
    $mail->SMTPSecure = 'tls';  // Encriptación TLS
    $mail->Port = 587;  // Puerto TLS de Gmail

    // Remitente y destinatario
    $mail->setFrom('eduarposerosqgmail.com', 'HorizonTech'); // Tu correo y nombre de remitente
    $mail->addAddress('kanek12010@hotmail.com'); // Correo del cliente

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Detalles de su compra';

    $Detalles_compra = '<p>Usted ha realizado la compra de: </p>'.$detalle_producto.'<br> <p>Con un total de: </p>'.$total_ventas;  

    $mail->Body = utf8_decode($Detalles_compra);

    // Enviar correo
    $mail->send();
} catch (\Throwable $th) {
    //throw $th;
}


?>