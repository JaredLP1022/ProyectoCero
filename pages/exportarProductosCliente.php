<?php
include("C:/xampp/htdocs/ProyectoCero/config/db.php");
$pdo = new db();
$PDO = $pdo->conexion();

// Verificar si se recibió un clienteID
if (!isset($_POST["clienteID"]) || empty($_POST["clienteID"])) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Error',
                text: 'No se ha seleccionado un cliente válido.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.location.href = 'ReporteVC.php';
            });
        });
    </script>";
    exit;
}

$clienteID = $_POST["clienteID"];

// Consulta para obtener las ventas del cliente
$comando = $PDO->prepare("SELECT * FROM venta WHERE id_cliente = ?");
$comando->execute([$clienteID]);
$result = $comando->fetchAll(PDO::FETCH_ASSOC);

// Si no hay productos comprados, mostramos una alerta
if (empty($result)) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Sin resultados',
                text: 'Este cliente no tiene productos comprados.',
                icon: 'info',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.location.href = 'ReporteVC.php';
            });
        });
    </script>";
    exit;
}

// Generar el archivo Excel
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=ReporteProductosCliente.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Productos por Cliente</title>
</head>
<body>

<table >
    <thead>
        <tr>
            <th>Detalle Producto</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Total Venta</th>
            <th>Fecha de Venta</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($result as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['detalle_producto']) ?></td>
                <td><?= $row['cantidad'] ?> pz</td>
                <td>$<?= $row['precio'] ?></td>
                <td>$<?= $row['total'] ?></td>
                <td><?= date('d-m-Y', strtotime($row['fechaV'])) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
