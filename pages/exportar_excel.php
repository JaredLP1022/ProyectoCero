<?php
include_once dirname(__DIR__) . '../config/db.php';

$pdo = new db();
$PDO = $pdo->conexion();

// Consulta para obtener los datos de ventas
$query = "SELECT detalle_producto, SUM(cantidad) AS total_vendido, SUM(descuento) AS total_descuento, SUM(total) AS ingresos_totales FROM venta GROUP BY detalle_producto ORDER BY total_vendido DESC";
$stmt = $PDO->prepare($query);
$stmt->execute();
$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consulta para obtener los datos de ventas
$query = "SELECT detalle_producto, SUM(cantidad) AS total_vendido, SUM(descuento) AS total_descuento, SUM(total) AS ingresos_totales FROM venta GROUP BY detalle_producto ORDER BY total_vendido DESC";
$stmt = $PDO->prepare($query);
$stmt->execute();
$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Configurar cabeceras para exportar como archivo Excel (.xls)
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=reporte_ventas.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);

// UTF-8 BOM para caracteres especiales
echo "\xEF\xBB\xBF"; 

// Crear tabla en formato HTML para Excel
echo "<table border='1'>";
echo "<tr>
        <th>Producto</th>
        <th>Cantidad Vendida</th>
        <th>Descuento Total</th>
        <th>Ingresos Totales</th>
      </tr>";

foreach ($ventas as $venta) {
    echo "<tr>
            <td>{$venta['detalle_producto']}</td>
            <td>{$venta['total_vendido']}</td>
            <td>{$venta['total_descuento']}</td>
            <td>{$venta['ingresos_totales']}</td>
          </tr>";
}

echo "</table>";
exit;
?>