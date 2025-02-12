<?php
require 'C:/xampp/htdocs/ProyectoCero/PHPMailer/src/PHPMailer.php';
require 'C:/xampp/htdocs/ProyectoCero/PHPMailer/src/Exception.php';
require 'C:/xampp/htdocs/ProyectoCero/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include("C:/xampp/htdocs/ProyectoCero/config/db.php");

$pdo = new db();
$PDO = $pdo->conexion();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Acceso no autorizado.");
}

$id = (int) $_GET['id']; // Convertir a entero para evitar inyecciones SQL

try {
    // Obtener datos del cliente de forma segura
    $comando = $PDO->prepare("SELECT * FROM cliente WHERE id = ?");
    $comando->execute([$id]);
    $cliente = $comando->fetch(PDO::FETCH_ASSOC);

    if (!$cliente) {
        die("Cliente no encontrado.");
    }

    // Asignar datos al cliente
    $nombre = htmlspecialchars($cliente["nombre"]);
    $email = htmlspecialchars($cliente["email"]);
    $telefono = htmlspecialchars($cliente["telefono"]);
    $direccion = htmlspecialchars($cliente["direccion"]);
    $fecha_registro = date("d/m/Y", strtotime($cliente["fechaR"])); // Formato de fecha local
    $fecha_modificacion = date("Y-m-d"); // Fecha actual en formato SQL para la actualización

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
        // Validar datos antes de guardarlos
        $nombre = htmlspecialchars(trim($_POST['nombre']));
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $telefono = preg_replace('/\D/', '', $_POST['phone']); // Solo números
        $direccion = htmlspecialchars(trim($_POST['direccion']));
        $fecha_registro = $_POST['fecha_registro']; // No debe cambiarse
        $fecha_modificacion = date("Y-m-d"); // Se mantiene la fecha de modificación como hoy

        // Validar el número de teléfono
        if (!preg_match('/^[0-9]{10}$/', $telefono)) {
            die("Error: El número de teléfono debe tener 10 dígitos.");
        }

        // Preparar la consulta de actualización
        $update = $PDO->prepare("UPDATE cliente SET nombre = ?, email = ?, telefono = ?, direccion = ?, fechaM = ? WHERE id = ?");
        $resultado = $update->execute([$nombre, $email, $telefono, $direccion, $fecha_modificacion, $id]);

        if ($resultado) {
            // Enviar correo de confirmación
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'eduarposeros@gmail.com'; // Tu correo Gmail
                $mail->Password = 'gsvr opmn ipug xnoj'; // Tu contraseña o contraseña de aplicación
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Configurar UTF-8 para los caracteres especiales
                $mail->CharSet = 'UTF-8';

                // Remitente y destinatario
                $mail->setFrom('eduarposeros@gmail.com', 'HorizonTech');
                $mail->addAddress($email, $nombre);

                $mail->isHTML(true);
                $mail->Subject = 'Actualización de Datos';
                $mail->Body = "
                    <h3>¡Hola, $nombre!</h3>
                    <p>Tus datos han sido actualizados en nuestro sistema.</p>
                    <p><strong>Teléfono:</strong> $telefono</p>
                    <p><strong>Dirección:</strong> $direccion</p>
                    <p><strong>Fecha de Registro:</strong> $fecha_registro</p>
                    <p><strong>Última Modificación:</strong> $fecha_modificacion</p>
                    <br>
                    <p>Si no realizaste esta actualización, por favor contáctanos.</p>
                    <p>Gracias.</p>
                ";

                $mail->send();
            } catch (Exception $e) {
                error_log("Error al enviar correo: " . $mail->ErrorInfo);
            }

            // Alerta exitosa y redirección
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                Swal.fire({
                    title: '¡Cliente Modificado!',
                    text: 'Los datos del cliente se actualizaron correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    window.location.href = 'clientes.php';
                });
            </script>";
            exit();
        } else {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al actualizar los datos.',
                    icon: 'error',
                    confirmButtonText: 'Intentar de nuevo'
                });
            </script>";
            exit();
        }
    }
} catch (Exception $e) {
    die("Error en la consulta: " . $e->getMessage());
}
?>

<!-- Formulario para editar cliente -->
<div class="contenedorPanel">
    <div class="botonCss">
        <button class="border-white botonCerrar ColorLetra" type="button" onclick="location.href='clientes.php'">
            🏠 Volver a Clientes
        </button>
    </div>
    <div class="tituloCss">
        <h4 class="text-center ColorLetra">Editar Cliente</h4>
    </div>
</div>

<hr class="bg-white">
<form action="editarcliente.php?id=<?php echo $_GET['id']; ?>" method="POST">
    <p>Datos Personales:</p>
    <div class="form-row row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input class="form-control" type="text" value="<?php echo $nombre; ?>" name="nombre" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="email">Correo electrónico:</label>
                <input class="form-control" type="email" value="<?php echo $email; ?>" name="email" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="phone">Número de teléfono:</label>
                <input class="form-control" type="tel" value="<?php echo $telefono; ?>" name="phone" 
                       pattern="[0-9]{10}" maxlength="10" required
                       title="El número de teléfono debe tener exactamente 10 dígitos numéricos.">
            </div>
        </div>
    </div>

    <hr class="bg-white">
    <p>Dirección:</p>
    <div class="form-row row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input class="form-control" type="text" value="<?php echo $direccion; ?>" name="direccion" required>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="fecha_registro">Fecha de Registro:</label>
                <input class="form-control" type="text" value="<?php echo $fecha_registro; ?>" name="fecha_registro" readonly>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="fecha_modificacion">Última Modificación:</label>
                <input class="form-control" type="text" value="<?php echo $fecha_modificacion; ?>" name="fecha_modificacion" readonly>
            </div>
        </div>
    </div>

    <br>
    <div class="container">
        <button class="btn btn-primary btn-lg w-100" type="submit" name="update">Guardar cliente</button>
    </div>
</form>
