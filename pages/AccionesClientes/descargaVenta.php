<?php
include("C:/xampp/htdocs/ProyectoCero/config/db.php");

$pdo = new db();
$PDO = $pdo->conexion();

$comando = $PDO->query("SELECT * FROM venta WHERE estado = 'En proceso'");
$comando->execute();

$result = $comando->fetchAll(PDO::FETCH_ASSOC);


header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename =Clientes.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);
?>

<table class="table table-bordered border-white" id="tabla-servicios" >
            <thead>
                <tr>
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
                    foreach($result AS $row){
                ?>
                <tr>
                    <td><?php echo $row['detalle_producto']?></td>
                    <td><?php echo $row['cantidad']?></td>
                    <td><?php echo $row['precio']?></td>
                    <td><?php echo $row['total']?></td>
                    <td><?php echo $row['estado']?></td>
                    <td><?php echo str_replace('-', '/', date('d-m-y', strtotime($row['fecha_venta']))) ?></td>


                </tr>
                <?php } ?>
            </tbody>
        </table>