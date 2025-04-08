<?php
include("C:/xampp/htdocs/ProyectoCero/config/db.php");

$pdo = new db();
$PDO = $pdo->conexion();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cliente_id = $_POST['cliente_id'] ?? '';
    $cliente_nombre = $_POST['cliente_nombre'] ?? 'Todos';
    $tipo_producto = $_POST['tipo_producto'] ?? '';
    $tipo_producto_texto = $_POST['tipo_producto_texto'] ?? 'Todos';

    $query = "SELECT v.id_venta, c.nombre AS cliente, v.detalle_producto, v.fechaV, v.cantidad, v.precio, v.total 
              FROM venta v
              JOIN cliente c ON v.id_cliente = c.id";

    $condiciones = [];
    $parametros = [];

    if (!empty($cliente_id)) {
        $condiciones[] = "v.id_cliente = ?";
        $parametros[] = $cliente_id;
    }
    if (!empty($tipo_producto)) {
        $condiciones[] = "v.detalle_producto = ?";
        $parametros[] = $tipo_producto;
    }

    if (!empty($condiciones)) {
        $query .= " WHERE " . implode(" AND ", $condiciones);
    }

    $stmt = $PDO->prepare($query);
    $stmt->execute($parametros);
    $ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Nombre del archivo
    $filename = "Ventas_" . str_replace(" ", "_", $cliente_nombre) . ".xls";

    // Encabezados
    header("Content-Type: application/vnd.ms-excel; charset=UTF-16LE");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    // BOM UTF-16LE
    echo "\xFF\xFE";

    // Utilidad para convertir y escribir línea
    function escribirLinea($texto) {
        echo mb_convert_encoding($texto, 'UTF-16LE', 'UTF-8');
    }

    // Títulos
    escribirLinea("Reporte de Ventas\n");
    escribirLinea("Cliente: " . $cliente_nombre . "\tTipo de Producto: " . $tipo_producto_texto . "\n\n");

    // Cabecera
    escribirLinea("ID Venta\tCliente\tProducto\tFecha\tCantidad\tPrecio\tTotal\n");

    foreach ($ventas as $venta) {
        escribirLinea(
            $venta['id_venta'] . "\t" .
            $venta['cliente'] . "\t" .
            $venta['detalle_producto'] . "\t" .
            date("d/m/Y", strtotime($venta['fechaV'])) . "\t" .
            $venta['cantidad'] . "\t" .
            $venta['precio'] . "\t" .
            $venta['total'] . "\n"
        );
    }

    exit();
} else {
    echo "No se recibió ningún dato para exportar.";
}
