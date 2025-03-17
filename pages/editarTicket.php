<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Asegúrate de que la zona horaria esté configurada correctamente
date_default_timezone_set('America/Mexico_City'); // Ajusta a tu zona horaria

// Verifica que los datos fueron recibidos
if (!isset($_POST['id_ticket'], $_POST['estado'], $_POST['prioridad'], $_POST['descripcionProblema'])) {
    echo "Datos incompletos recibidos.";
    exit;
}

// Verificar valores de los datos recibidos
error_log("id_ticket: " . $_POST['id_ticket']);
error_log("estado: " . $_POST['estado']);
error_log("prioridad: " . $_POST['prioridad']);
error_log("descripcionProblema: " . $_POST['descripcionProblema']);

require 'C:/xampp/htdocs/ProyectoCero/PHPMailer/src/PHPMailer.php';
require 'C:/xampp/htdocs/ProyectoCero/PHPMailer/src/Exception.php';
require 'C:/xampp/htdocs/ProyectoCero/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Asegúrate de incluir la conexión a la base de datos
include_once __DIR__ . '/../config/db.php';

$pdo = new db();
$PDO = $pdo->conexion();

// Verificar que se recibieron los datos del formulario
if (isset($_POST['id_ticket'], $_POST['estado'], $_POST['prioridad'], $_POST['descripcionProblema'])) {
    // Obtener los valores del formulario
    $idTicket = $_POST['id_ticket'];
    $estado = $_POST['estado'];
    $prioridad = $_POST['prioridad'];
    $descripcionProblema = $_POST['descripcionProblema'];

    // Depuración: Verificar los datos recibidos
    error_log("Datos recibidos: id_ticket = $idTicket, estado = $estado, prioridad = $prioridad, descripcionProblema = $descripcionProblema");

    // Validar que los datos no estén vacíos
    if (empty($idTicket) || empty($estado) || empty($prioridad) || empty($descripcionProblema)) {
        echo json_encode(['success' => false, 'message' => 'Uno o más campos están vacíos']);
        exit;
    }

    // Obtener el correo del cliente asociado al ticket
    $query = "SELECT c.email, c.nombre FROM cliente c 
              JOIN venta v ON c.id = v.id_cliente
              JOIN ticket t ON t.id_venta = v.id_venta
              WHERE t.id_ticket = ?";
    $stmt = $PDO->prepare($query);
    $stmt->execute([$idTicket]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    try {
        // Configuración de PHPMailer y conexión SMTP
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Servidor SMTP (Gmail)
        $mail->SMTPAuth = true;
        $mail->Username = 'eduarposeros@gmail.com'; // Tu correo Gmail
        $mail->Password = 'gsvr opmn ipug xnoj'; // Tu contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
    
        // Remitente y destinatario
        $mail->setFrom('eduarposeros@gmail.com', 'HorizonTech');
        $mail->addAddress($cliente['email'], $cliente['nombre']); // Correo del cliente
    
        $mail->isHTML(true);
        $mail->Subject = 'Notificación de actualización de ticket';
        $mail->Body = "
            <h3>Hola, {$cliente['nombre']}</h3>
            <p>Te notificamos que tu ticket <strong>#{$idTicket}</strong> ha sido actualizado.</p>
            <p><strong>Estado:</strong> {$estado}</p>
            <p><strong>Prioridad:</strong> {$prioridad}</p>
            <p><strong>Descripción del Problema:</strong> {$descripcionProblema}</p>
            <p>Gracias por tu paciencia.</p>
        ";
    
        // Intentar enviar el correo
        if ($mail->send()) {
            error_log("Correo enviado correctamente a {$cliente['email']}");
        } else {
            error_log("Error al enviar el correo: " . $mail->ErrorInfo);
        }
    } catch (Exception $e) {
        error_log("Error en PHPMailer: " . $e->getMessage());
    }

    // Definir la fecha de resolución si el estado es "Cerrado"
    $fechaResolucion = ($estado === "Cerrado") ? date('Y-m-d H:i:s') : NULL; // Usar la fecha actual si está cerrado

    // Consulta SQL para actualizar el ticket
    $query = "UPDATE ticket SET estado = ?, prioridad = ?, descripcionProblema = ?, fecha_resolucion = ? WHERE id_ticket = ?";
    $stmt = $PDO->prepare($query);

    // Ejecutar la consulta
    if ($stmt->execute([$estado, $prioridad, $descripcionProblema, $fechaResolucion, $idTicket])) {
        // Si la actualización fue exitosa
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Ticket actualizado correctamente'
        ]);
        
    } else {
        // Si hubo un error
        error_log("Error al ejecutar la consulta SQL: " . implode(", ", $stmt->errorInfo()));
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el ticket.']);
    }
} else {
    // Si no se recibieron los datos correctamente
    error_log("Datos incompletos: id_ticket, estado, prioridad o descripcionProblema no recibidos.");
    echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
}
?>
