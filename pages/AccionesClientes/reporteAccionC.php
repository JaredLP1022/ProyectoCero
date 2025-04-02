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

$comando = $PDO->query("SELECT * FROM cliente WHERE fechaR BETWEEN '$fecha1' and '$fecha2'");
$comando->execute();

$result = $comando->fetchAll(PDO::FETCH_ASSOC);

// Si no hay resultados, mostramos la alerta y redirigimos
if ($comando->rowCount() == 0) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Error',
                text: 'No se encontraron resultados para las fechas proporcionadas.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.location.href = '../ReporteClientes.php';
            });
        });
    </script>";
    exit;  // Terminamos el script para no continuar con la generación del Excel
}

// Continuamos con la generación del Excel
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=ReporteClientes.xls");
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
    <!-- Agregar SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php if ($comando->rowCount() > 0): ?>
    <table class="table table-bordered border-white scroll">
        <thead>
            <tr>
                <th class="ColorLetra">Nombre del cliente</th>
                <th class="ColorLetra">Correo</th>
                <th class="ColorLetra">Telefono</th>
                <th class="ColorLetra">Direccion</th>
                <th class="ColorLetra">Fecha de registro</th>
                <th class="ColorLetra">Fecha de modificacion</th>
                <th class="ColorLetra">Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($result AS $row) { ?>
                <tr>
                    <td><?php echo $row['nombre'] ?></td>
                    <td><?php echo $row['email'] ?></td>
                    <td><?php echo $row['telefono'] ?></td>
                    <td><?php echo $row['direccion'] ?></td>
                    <td><?php echo str_replace('-', '/', date('d-m-y', strtotime($row['fechaR']))) ?></td>
                    <td><?php echo str_replace('-', '/', date('d-m-y', strtotime($row['fechaM']))) ?></td>
                    <td><?php echo $row['estado'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php endif; ?>

</body>
</html>
