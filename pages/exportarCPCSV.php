<?php
include("C:/xampp/htdocs/ProyectoCero/config/db.php");

$pdo = new db();
$PDO = $pdo->conexion();

// Verificar que se recibió el cliente o el producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['cliente_id']) || isset($_POST['producto']))) {
    $cliente_id = $_POST['cliente_id'] ?? '';
    $cliente_nombre = $_POST['cliente_nombre'] ?? 'Reporte';
    $producto = $_POST['producto'] ?? '';

    // Armar consulta base
    $queryTickets = "SELECT t.id_ticket, v.detalle_producto, t.fecha, t.prioridad, t.estado, p.descripcionProblem
                     FROM ticket t
                     JOIN venta v ON t.id_venta = v.id_venta
                     LEFT JOIN problema p ON t.descripcionProblema = p.id_problema";

    $condiciones = [];
    $parametros = [];

    if (!empty($cliente_id)) {
        $condiciones[] = "v.id_cliente = ?";
        $parametros[] = $cliente_id;
    }

    if (!empty($producto)) {
        $condiciones[] = "v.detalle_producto = ?";
        $parametros[] = $producto;
    }

    if (!empty($condiciones)) {
        $queryTickets .= " WHERE " . implode(" AND ", $condiciones);
    }

    $stmtTickets = $PDO->prepare($queryTickets);
    $stmtTickets->execute($parametros);
    $tickets = $stmtTickets->fetchAll(PDO::FETCH_ASSOC);

    if (empty($tickets)) {
        echo "No hay tickets disponibles para este filtro.";
        exit();
    }

    // Definir nombre del archivo
    $filename = "Tickets_" . str_replace(" ", "_", $cliente_nombre) . ".xls";

    // Encabezados para descargar Excel
    header("Content-Type: application/vnd.ms-excel; charset=UTF-16LE");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    // BOM UTF-16LE
    echo "\xFF\xFE";

    // Función para imprimir en UTF-16LE
    function escribirLinea($texto) {
        echo mb_convert_encoding($texto, 'UTF-16LE', 'UTF-8');
    }

    // Título del reporte
    escribirLinea("Tickets del Cliente: " . $cliente_nombre . "\n\n");
    escribirLinea("ID Ticket\tProducto/Servicio\tDescripción del Problema\tFecha\tPrioridad\tEstado\n");

    // Datos
    foreach ($tickets as $ticket) {
        escribirLinea(
            $ticket['id_ticket'] . "\t" .
            $ticket['detalle_producto'] . "\t" .
            $ticket['descripcionProblem'] . "\t" .
            date("d/m/Y", strtotime($ticket['fecha'])) . "\t" .
            $ticket['prioridad'] . "\t" .
            $ticket['estado'] . "\n"
        );
    }

    exit();
} else {
    echo "No se recibió ningún dato para filtrar.";
}
?>
