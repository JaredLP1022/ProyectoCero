<?php
ob_start();
include_once __DIR__ . '/../TCPDF-main/tcpdf.php';
include_once __DIR__ . '/../config/db.php';

// Crear conexión a la base de datos
$pdo = new db();
$PDO = $pdo->conexion();

// Obtener los tickets
$query = "SELECT t.id_ticket, v.detalle_producto, t.fecha, t.prioridad, t.estado, 
                 c.nombre AS cliente, p.descripcionProblem AS descripcionProblema,
                 t.fecha_resolucion, 
                 DATEDIFF(t.fecha_resolucion, t.fecha) AS tiempo_resolucion
          FROM ticket t
          JOIN venta v ON t.id_venta = v.id_venta
          JOIN cliente c ON v.id_cliente = c.id
          LEFT JOIN problema p ON t.descripcionProblema = p.id_problema
          WHERE t.archivado = 'Desarchivado'";

$stmt = $PDO->prepare($query);
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Crear nuevo PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tu Empresa');
$pdf->SetTitle('Reporte de Tickets');
$pdf->SetHeaderData('', 0, 'Reporte de Tickets', date('d/m/Y'));
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->SetFont('helvetica', '', 10);
$pdf->AddPage();

// Contenido de la tabla
$html = '<h2 style="text-align:center;">Reporte de Tickets</h2>
<table border="1" cellspacing="3" cellpadding="4">
    <tr>
        <th><b>Cliente</b></th>
        <th><b>Producto/Servicio</b></th>
        <th><b>Descripción del Problema</b></th>
        <th><b>Fecha de Registro</b></th>
        <th><b>Prioridad</b></th>
        <th><b>Estado</b></th>
        <th><b>Tiempo de Resolución</b></th>
    </tr>';

foreach ($tickets as $ticket) {
    $tiempo_resolucion = $ticket['tiempo_resolucion'] !== null ? "{$ticket['tiempo_resolucion']} días" : "Pendiente";
    $html .= "<tr>
        <td>{$ticket['cliente']}</td>
        <td>{$ticket['detalle_producto']}</td>
        <td>{$ticket['descripcionProblema']}</td>
        <td>" . date("d/m/Y", strtotime($ticket['fecha'])) . "</td>
        <td>{$ticket['prioridad']}</td>
        <td>{$ticket['estado']}</td>
        <td>{$tiempo_resolucion}</td>
    </tr>";
}

$html .= '</table>';

// Agregar contenido al PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Limpiar cualquier salida previa para evitar errores de TCPDF
ob_end_clean();

// Salida del PDF
$pdf->Output('reporte_tickets.pdf', 'D');
?>