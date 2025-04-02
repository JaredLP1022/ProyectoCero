<?php
require 'C:/xampp/htdocs/ProyectoCero/PHPMailer/src/PHPMailer.php';
require 'C:/xampp/htdocs/ProyectoCero/PHPMailer/src/Exception.php';
require 'C:/xampp/htdocs/ProyectoCero/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include("C:/xampp/htdocs/ProyectoCero/config/db.php");

$pdo = new db();
$PDO = $pdo->conexion();

if (isset($_POST["save"])) {
    $id_cliente = $_POST["cliente_id"];
    $fecha_venta = $_POST["fecha_venta"];
    $detalles_producto = trim($_POST["detalle_producto"]);
    $cantidad = $_POST["cantidad"];
    $precio_unitario = $_POST["precio_unitario"];
    $descuento = $_POST["descuento"];

    if (empty($detalles_producto)) {
        die("Error: El detalle del producto no puede estar vacío.");
    }

    $subtotal_venta = $cantidad * $precio_unitario;
    $total = $subtotal_venta * ($descuento / 100);
    $total_ventas = $subtotal_venta - $total;
    $estado_venta = "En proceso";
    $venta_archivada = "Desarchivado";

    // Obtener el correo del cliente
    $querym = "SELECT nombre, email FROM cliente WHERE id = ?";
    $result = $PDO->prepare($querym);
    $result->execute([$id_cliente]);
    
    if ($result->rowCount() > 0) {
        $cliente = $result->fetch(PDO::FETCH_ASSOC);
        $nombre_cliente = $cliente['nombre'];
        $email_cliente = $cliente['email'];
    } else {
        echo "Cliente no encontrado.";
        exit();
    }

    // Insertar la venta en la base de datos
    $query = "INSERT INTO venta (id_cliente, detalle_producto, fechaV, cantidad, precio, descuento, total, estado, archivada) 
              VALUES (?,?,?,?,?,?,?,?,?)";
    
    $stmt = $PDO->prepare($query);
    $resultado = $stmt->execute([$id_cliente, $detalles_producto, $fecha_venta, $cantidad, $precio_unitario, $descuento, $total_ventas, $estado_venta, $venta_archivada]);

    if ($resultado) {
        // Enviar correo al cliente
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'eduarposeros@gmail.com';
            $mail->Password = 'gsvr opmn ipug xnoj';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            $mail->setFrom('eduarposeros@gmail.com', 'HorizonTech');
            $mail->addAddress($email_cliente, $nombre_cliente);

            $mail->isHTML(true);
            $mail->Subject = 'Confirmación de compra en HorizonTech';
            $mail->Body = "<h2>Gracias por su compra, $nombre_cliente!</h2>
                            <hr>
                            <p>Detalles de su compra:</p>
                            <ul>
                                <li><strong>Producto:</strong> $detalles_producto</li>
                                <li><strong>Cantidad:</strong> $cantidad</li>
                                <li><strong>Total pagado:</strong> $$total_ventas</li>
                                <li><strong>Fecha de compra:</strong> $fecha_venta</li>
                            </ul>
                            <p>Ayúdanos a mejorar llenando nuestra <a href='https://tu-enlace-a-la-encuesta.com'>encuesta de satisfacción</a>.</p>
                            <br>
                            <p>Gracias por confiar en HorizonTech.</p>";

            $mail->send();
        } catch (Exception $e) {
            echo "No se pudo enviar el correo: " . $mail->ErrorInfo;
        }

        // Redirigir a la página de ventas
        header("Location: ../ventas.php");
        exit();
    } else {
        echo "Hubo un error al guardar la venta.";
    }
}
?>
