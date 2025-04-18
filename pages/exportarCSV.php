<?php
include("C:/xampp/htdocs/ProyectoCero/config/db.php");

$pdo = new db();
$PDO = $pdo->conexion();

// Verificar que se recibió el cliente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cliente_id'])) {
    $cliente_id = $_POST['cliente_id'];
    $cliente_nombre = $_POST['cliente_nombre'];

    // Obtener los tickets con la descripción del problema
    $queryTickets = "SELECT t.id_ticket, v.detalle_producto, t.fecha, t.prioridad, t.estado, p.descripcionProblem
                     FROM ticket t
                     JOIN venta v ON t.id_venta = v.id_venta
                     LEFT JOIN problema p ON t.descripcionProblema = p.id_problema
                     WHERE v.id_cliente = ?";
    $stmtTickets = $PDO->prepare($queryTickets);
    $stmtTickets->execute([$cliente_id]);
    $tickets = $stmtTickets->fetchAll(PDO::FETCH_ASSOC);

    // Verificar si hay tickets
    if (empty($tickets)) {
        echo "No hay tickets disponibles para este cliente.";
        exit();
    }

    // Definir nombre del archivo
    $filename = "Tickets_" . str_replace(" ", "_", $cliente_nombre) . ".xls";

    // Encabezados para descarga del archivo
    header("Content-Type: application/vnd.ms-excel; charset=UTF-16LE");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    // Agregar BOM UTF-16LE para Excel
    echo "\xFF\xFE"; // BOM UTF-16LE para Excel

    // Utilidad para convertir y escribir línea
    function escribirLinea($texto) {
        echo mb_convert_encoding($texto, 'UTF-16LE', 'UTF-8');
    }

    // Encabezado del archivo Excel
    escribirLinea("Tickets del Cliente: " . $cliente_nombre . "\n\n");  // Cliente sin htmlspecialchars() para evitar doble escape
    escribirLinea("ID Ticket\tProducto/Servicio\tDescripción del Problema\tFecha\tPrioridad\tEstado\n");

    // Insertar datos
    foreach ($tickets as $ticket) {
        escribirLinea(
            $ticket['id_ticket'] . "\t" .
            $ticket['detalle_producto'] . "\t" .
            $ticket['descripcionProblem'] . "\t" .  // Descripción del problema
            date("d/m/Y", strtotime($ticket['fecha'])) . "\t" .
            $ticket['prioridad'] . "\t" .
            $ticket['estado'] . "\n"
        );
    }

    exit();
} else {
    echo "No se recibió ningún dato.";
}
?>
