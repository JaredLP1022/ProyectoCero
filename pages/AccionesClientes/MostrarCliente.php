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

    $nombre = htmlspecialchars($cliente["nombre"]);
    $email = htmlspecialchars($cliente["email"]);
    $telefono = htmlspecialchars($cliente["telefono"]);
    $direccion = htmlspecialchars($cliente["direccion"]);
    $fecha_registro = htmlspecialchars($cliente["fechaR"]);
    $fecha_modificacion = htmlspecialchars($cliente["fechaM"]);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
        // Validar datos antes de guardarlos
        $nombre = htmlspecialchars(trim($_POST['nombre']));
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $telefono = preg_replace('/\D/', '', $_POST['phone']); // Solo números
        $direccion = htmlspecialchars(trim($_POST['direccion']));
        $fecha_registro = date("Y-m-d", strtotime($_POST['fecha_registro'])); // Guardar en formato SQL
        $fecha_modificacion = date("Y-m-d"); // Fecha actual en formato SQL

        if (!preg_match('/^[0-9]{10}$/', $telefono)) {
            die("Error: El número de teléfono debe tener 10 dígitos.");
        }

        // Actualizar los datos de manera segura
        $update = $PDO->prepare("UPDATE cliente SET nombre = ?, email = ?, telefono = ?, direccion = ?, fechaR = ?, fechaM = ? WHERE id = ?");
        $resultado = $update->execute([$nombre, $email, $telefono, $direccion, $fecha_registro, $fecha_modificacion, $id]);

        if ($resultado) {
            $fecha_registro_local = date("d/m/Y", strtotime($fecha_registro));
            $fecha_modificacion_local = date("d/m/Y", strtotime($fecha_modificacion));


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
               $mail->addAddress($email, $nombreC);

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

            header("Location: Clientes.php");
            exit();
        } else {
            die("Error al actualizar los datos.");
        }
    }
} catch (Exception $e) {
    die("Error en la consulta: " . $e->getMessage());
}
?>
<div class="contenedorPanel">
    <div class="botonCss">
        <button class=" border-white botonCerrar ColorLetra" type="submit" onclick="location.href='clientes.php'">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path
                    d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z" />
            </svg>
        </button>
    </div>
    <div class="tituloCss">
        <h4 class="text-center ColorLetra">Editar Cliente</h4>
    </div>
    <div class="botonCss">
        <button class=" border-white botonCerrar ColorLetra" type="submit"
            onclick="location.href='Panel-Administrador.php'">Panel<svg xmlns="http://www.w3.org/2000/svg" width="16"
                height="16" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                <path
                    d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z" />
            </svg></button>
    </div>

</div>
<hr class="bg-white">
<form action="editarcliente.php?id=<?php echo $_GET['id']; ?>" method="POST">
    <p>Datos Personales:</p>
    <div class="form-row row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Nombre:</label>
                <input class="form-control" type="text" value="<?php echo $nombre; ?>" name="nombre">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Correo electronico:</label>
                <input class="form-control" type="email" value="<?php echo $email; ?> " name="email">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Numero de telefono:</label>
                <input class="form-control" type="phone" value="<?php echo $telefono; ?>" name="phone">
            </div>
        </div>
    </div>
    <br>
    <hr class="bg-white">
    <p>Direccion:</p>
    <div class="form-row row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="">Nombre de la calle:</label>
                <input class="form-control" type="text" value="<?php echo $direccion; ?> " name="direccion">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Fecha de registro:</label>
                <input class="form-control" type="date" value="<?php echo $fecha_registro; ?>" name="fecha_registro">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Fecha de actualizacion:</label>
                <input class="form-control" type="date" value="<?php echo $fecha_modificacion; ?>"
                    name="fecha_modificacion">
            </div>
        </div>
    </div><br>

    <br>
    <div class="container">
        <button class="btn btn-primary btn-lg w-100" type="submit" name="update">Guardar cliente</button>
    </div>
</form>