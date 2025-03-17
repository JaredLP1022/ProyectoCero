<?php
include("C:/xampp/htdocs/ProyectoCero/config/db.php");

$pdo = new db();
$PDO = $pdo->conexion();

// Verificar que se recibió el cliente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cliente_id'])) {
    $cliente_id = $_POST['cliente_id'];
    $cliente_nombre = $_POST['cliente_nombre'];

    // Obtener los tickets
    $queryTickets = "SELECT t.id_ticket, v.detalle_producto, t.fecha, t.prioridad, t.estado
                     FROM ticket t
                     JOIN venta v ON t.id_venta = v.id_venta
                     WHERE v.id_cliente = ?";
    $stmtTickets = $PDO->prepare($queryTickets);
    $stmtTickets->execute([$cliente_id]);
    $tickets = $stmtTickets->fetchAll(PDO::FETCH_ASSOC);

    // Definir nombre del archivo
    $filename = "Tickets_" . str_replace(" ", "_", $cliente_nombre) . ".xls";

    // Encabezados para descarga del archivo
    header("Content-Type: application/vnd.ms-excel; charset=UTF-16LE");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    // Agregar BOM UTF-16LE para Excel
    echo "\xFF\xFE"; // BOM UTF-16LE para Excel
    
    // Encabezado del archivo Excel
    echo "Tickets del Cliente: " . $cliente_nombre . "\n\n";  // Cliente sin htmlspecialchars() para evitar doble escape
    echo "ID Ticket\tProducto/Servicio\tFecha\tPrioridad\tEstado\n";

    // Insertar datos
    foreach ($tickets as $ticket) {
        echo $ticket['id_ticket'] . "\t";
        echo $ticket['detalle_producto'] . "\t";
        echo date("d/m/Y", strtotime($ticket['fecha'])) . "\t";
        echo $ticket['prioridad'] . "\t";
        echo $ticket['estado'] . "\n";
    }

    exit();
} else {
    echo "No se recibió ningún dato.";
}
?>
