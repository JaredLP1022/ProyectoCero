<?php
include("C:/xampp/htdocs/ProyectoCero/config/db.php");

$pdo = new db();
$PDO = $pdo->conexion();

$comando = $PDO->query("SELECT * FROM cliente WHERE archivar = 'Desarchivado'");
$comando->execute();

$result = $comando->fetchAll(PDO::FETCH_ASSOC);


header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename =Clientes.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HorizonTech</title>
</head>
<body>
<table class="table table-bordered border-white">
            <thead class="bg-primary">
                <tr>
                    <th class="ColorLetra">Nombre del cliente</th>
                    <th class="ColorLetra">Correo</th>
                    <th class="ColorLetra">Telefono</th>
                    <th class="ColorLetra">Direccion</th>
                    <th class="ColorLetra">Fecha de registro</th>
                    <th class="ColorLetra">Fecha de modificacion</th>
                    <th class="ColorLetra">Estado Activo/Inactivo</th>
                    <th class="ColorLetra">Estado Archivado/Desarchivado</th>
                </tr>
            </thead>
            <tbody class="bg-info" >
                <?php
                    foreach($result AS $row){
                ?>
                <tr>
                    <td><?php echo $row['nombre']?></td>
                    <td><?php echo $row['email']?></td>
                    <td><?php echo $row['telefono']?></td>
                    <td><?php echo $row['direccion']?></td>
                    <td><?php echo str_replace('-', '/', date('d-m-y', strtotime($row['fechaR'])))?></td>
                    <td><?php echo str_replace('-', '/', date('d-m-y', strtotime($row['fechaM'])))?></td>
                    <td><?php echo $row['estado']?></td>
                    <td><?php echo $row['archivar']?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
</body>
</html>