<?php
require 'C:/xampp/htdocs/ProyectoCero/PHPMailer/src/PHPMailer.php';
require 'C:/xampp/htdocs/ProyectoCero/PHPMailer/src/Exception.php';
require 'C:/xampp/htdocs/ProyectoCero/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


include("C:/xampp/htdocs/ProyectoCero/config/db.php");

$pdo = new db();
$PDO = $pdo->conexion();

// Iniciar buffer de salida
ob_start();

// Inicializar mensaje y tipo para evitar errores si no hay POST
$mensaje = "";
$tipo = "error";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $apellidoP = htmlspecialchars(trim($_POST['apellidoP']));
    $apellidoS = htmlspecialchars(trim($_POST['apellidoS']));
    $email = htmlspecialchars(trim($_POST['email']));
    $telefono = htmlspecialchars(trim($_POST['phone']));
    $calle = htmlspecialchars(trim($_POST['calle']));
    $numCasa = htmlspecialchars(trim($_POST['numCasa']));
    $codigoPostal = htmlspecialchars(trim($_POST['codigoPostal']));
    $ciudad = htmlspecialchars(trim($_POST['ciudad']));
    $estado = htmlspecialchars(trim($_POST['estado']));
    $pais = htmlspecialchars(trim($_POST['pais']));
    $fecha_registro = htmlspecialchars(trim($_POST['fechaRegis']));

    $nombreC = "$nombre $apellidoP $apellidoS";
    $direccion = "$calle, $numCasa, $codigoPostal, $ciudad, $estado, $pais";
    $estadoC = "Activo";
    $archivar = "Desarchivado";
    $fecha_modificacion = $fecha_registro;

    // Verificar si el email ya existe en la base de datos
    $checkEmail = $PDO->prepare("SELECT id FROM cliente WHERE email = ?");
    $checkEmail->execute([$email]);

    if ($checkEmail->rowCount() > 0) {
        $mensaje = "El email ya está registrado.";
        $tipo = "error";
    } else {
        // Insertar en la base de datos
        $query = "INSERT INTO cliente (nombre, email, telefono, direccion, fechaR, fechaM, estado, archivar)  
                  VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $PDO->prepare($query);
        $resultado = $stmt->execute([$nombreC, $email, $telefono, $direccion, $fecha_registro, $fecha_modificacion, $estadoC, $archivar]);

        if ($resultado) {
            $mensaje = "El cliente ha sido guardado correctamente.";
            $tipo = "success";

            // 📧 **Enviar correo al cliente**
            $mail = new PHPMailer(true);
            try {
                // Configuración del servidor SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Servidor SMTP (Gmail)
                $mail->SMTPAuth = true;
                $mail->Username = 'eduarposeros@gmail.com'; // Tu correo Gmail
                $mail->Password = 'gsvr opmn ipug xnoj'; // Tu contraseña o contraseña de aplicación
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Configurar UTF-8 para los caracteres especiales
                $mail->CharSet = 'UTF-8';

                // Remitente y destinatario
                $mail->setFrom('eduarposeros@gmail.com', 'HorizonTech');
                $mail->addAddress($email, $nombreC);

                // Contenido del correo
                $mail->isHTML(true);
                $mail->Subject = 'Hola Usted ha sido registrado en Horizontech como cliente';
                $mail->Body = "
                    <h2>¡Bienvenido, $nombreC!</h2>
                    <p>Tu registro ha sido exitoso en nuestra empresa. Aquí están tus datos:</p>
                    <ul>
                        <li><strong>Nombre:</strong> $nombreC</li>
                        <li><strong>Correo:</strong> $email</li>
                        <li><strong>Teléfono:</strong> $telefono</li>
                        <li><strong>Dirección:</strong> $direccion</li>
                        <li><strong>Fecha de Registro:</strong> $fecha_registro</li>
                    </ul>
                    <p>Si necesitas más información, contáctanos.</p>
                    <p><strong>HorizonTech eduarposeros@gmail.com</strong></p>
                ";

                // Enviar el correo
                $mail->send();
            } catch (Exception $e) {
                $mensaje .= " (No se pudo enviar el correo: {$mail->ErrorInfo})";
            }
        } else {
            $mensaje = "Hubo un problema al guardar el cliente.";
            $tipo = "error";
        }
    }
}

// Limpiar buffer si hay salida previa
if (ob_get_length()) { ob_end_clean(); }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guardar Cliente</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <script>
        Swal.fire({
            title: "<?php echo ($tipo == 'success') ? 'Cliente Guardado' : 'Error'; ?>",
            text: "<?php echo $mensaje; ?>",
            icon: "<?php echo $tipo; ?>",
            confirmButtonText: "OK"
        }).then(() => {
            <?php if ($tipo == 'success') { ?>
                window.location.href = '../Clientes.php'; // Redirigir a clientes.php si fue exitoso
            <?php } else { ?>
                window.history.back(); // Volver atrás si hubo un error
            <?php } ?>
        });
    </script>
</body>
</html>
