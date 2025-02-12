<?php
// Desactivar cualquier salida antes del PDF
ob_start();

require_once("C:/xampp/htdocs/ProyectoCero/TCPDF-main/tcpdf.php");
include("C:/xampp/htdocs/ProyectoCero/config/db.php");

$pdo = new db();
$PDO = $pdo->conexion();

// Obtener ID de la venta desde la URL
$id_venta = isset($_GET['id']) ? $_GET['id'] : 0;

// Obtener la información de la venta y cliente
$query = $PDO->prepare("SELECT v.*, c.nombre AS cliente, c.email, c.telefono FROM venta v 
                        JOIN cliente c ON v.id_cliente = c.id 
                        WHERE v.id_venta = ?");
$query->execute([$id_venta]);
$venta = $query->fetch(PDO::FETCH_ASSOC);

if (!$venta) {
    // Detener la salida y redirigir si no se encuentra la venta
    ob_end_clean();
    header("Location: TablaVentas.php");
    exit();
}

// Crear un nuevo documento PDF
$pdf = new TCPDF();
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->AddPage();

// Configuración del PDF
$pdf->SetFont("helvetica", "", 12);

// Título de la factura
$pdf->Cell(0, 10, "Factura de Venta", 0, 1, "C");

// Datos del Cliente
$pdf->Ln(5);
$pdf->SetFont("helvetica", "B", 10);
$pdf->Cell(0, 10, "Datos del Cliente", 0, 1);
$pdf->SetFont("helvetica", "", 10);
$pdf->Cell(0, 7, "Nombre: " . $venta['cliente'], 0, 1);
$pdf->Cell(0, 7, "Correo: " . $venta['email'], 0, 1);
$pdf->Cell(0, 7, "Teléfono: " . $venta['telefono'], 0, 1);

// Datos de la Venta
$pdf->Ln(5);
$pdf->SetFont("helvetica", "B", 10);
$pdf->Cell(0, 10, "Detalles de la Venta", 0, 1);
$pdf->SetFont("helvetica", "", 10);
$pdf->Cell(0, 7, "Producto: " . $venta['detalle_producto'], 0, 1);
$pdf->Cell(0, 7, "Cantidad: " . $venta['cantidad'] . " pz", 0, 1);
$pdf->Cell(0, 7, "Precio Unitario: $" . number_format($venta['precio'], 2), 0, 1);
$pdf->Cell(0, 7, "Total Venta: $" . number_format($venta['total'], 2), 0, 1);
$pdf->Cell(0, 7, "Estado: " . $venta['estado'], 0, 1);
$pdf->Cell(0, 7, "Fecha de Venta: " . date("d/m/Y", strtotime($venta['fechaV'])), 0, 1);

$pdf->Ln(10);
$pdf->SetFont("helvetica", "B", 10);
$pdf->Cell(0, 10, "¡Gracias por su compra!", 0, 1, "C");

// Limpiar el buffer y descargar el PDF
ob_end_clean();
$pdf->Output("Factura_Venta_" . $id_venta . ".pdf", "D");

// Redirigir a la tabla de ventas después de la descarga
header("Location: TablaVentas.php");
exit();
?>
