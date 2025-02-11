<?php

include("C:/xampp/htdocs/ProyectoCero/config/db.php");
$pdo = new db();
$PDO = $pdo->conexion();

$fecha1 = "";
$fecha2 = "";

if (isset($_POST['genera'])) {
    $fecha1 = $_POST["fechaPrimera"];
    $fecha2 = $_POST["fechaSegunda"];

}
$comando = $PDO->query("SELECT * FROM venta WHERE fechaV BETWEEN '$fecha1' and '$fecha2'");
$comando->execute();

$result = $comando->fetchAll(PDO::FETCH_ASSOC);

header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename =ReporteVentas.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas</title>
</head>
<body>
<br>

<br> 

<table class="table table-bordered border-white" id="tabla-servicios">
            <thead>
                <tr>
                    <th class="ColorLetra">Cliente</th>
                    <th class="ColorLetra">Detalle Producto</th>
                    <th class="ColorLetra">Cantidad</th>
                    <th class="ColorLetra">Precio Unitario</th>
                    <th class="ColorLetra">Total Venta</th>
                    <th class="ColorLetra">Estado de la venta</th>
                    <th class="ColorLetra">Fecha de Venta</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($result as $row) {
                    ?>
                    <tr>
                        <td>
                            <?php
                            $sql = $PDO->query("SELECT nombre FROM cliente WHERE id = $row[id_cliente]");
                            $sql->execute();

                            $cliente = $sql->fetchColumn();
                            echo $cliente
                                ?>

                        </td>
                        <td><?php echo $row['detalle_producto'] ?></td>
                        <td><?php echo $row['cantidad'] ?>pz</td>
                        <td>$<?php echo $row['precio'] ?></td>
                        <td>$<?php echo $row['total'] ?></td>
                        <td><?php echo $row['estado'] ?></td>
                        <td><?php echo str_replace('-', '/', date('d-m-y', strtotime($row['fechaV']))) ?></td>


                    </tr>
                <?php } ?>
            </tbody>
        </table>
<br>
</body>
</html>