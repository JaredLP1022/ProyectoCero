<?php 
include("C:/xampp/htdocs/ProyectoCero/config/db.php");
$pdo = new db();
$PDO = $pdo->conexion();
$result;
$comando;
$nombre_buscar="";

// Código para buscar cliente por nombre
if (isset($_POST['buscar'])) {
    $nombre = $_POST["name"];
    $apellido1 = trim($_POST["apellido1"]);
    $apellidos = trim($_POST["apellidos"]);
    $nombre_buscar = $nombre." ".$apellido1." ".$apellidos;

}
$comando = $PDO->query("SELECT * FROM cliente WHERE nombre='$nombre_buscar'");
$comando->execute();

$result = $comando->fetchAll(PDO::FETCH_ASSOC);

?>
<div class="contenedorPanel">
<div class="botonCss">
    <button title="Volver" class=" border-white botonCerrar ColorLetra" type="submit" onclick="location.href='clientes.php'">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
  <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
</svg>
    </button>
    </div>
    <div class="tituloCss" >
    <h4 class="text-center ColorLetra">Buscar Clientes</h4>
    </div>
    <div class="botonCss">
        <button title="Home" class=" border-white botonCerrar ColorLetra" type="submit"
            onclick="location.href='Panel-Administrador.php'"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                height="16" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                <path
                    d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z" />
            </svg></button>
    </div>

</div>

<hr class="bg-white">

<form action="ClienteBuscar.php" method="POST">
    <label for="">Datos del cliente</label>
    <br>
    <hr class="bg-white">
    <div class="form-row row">
        <div class="col-md-4">
        <div class="form-group">
            <label for="">Nombre</label>
            <input type="text" name="name" required>
            </div>
        </div>
        <div class="col-md-4">
        <label for="">Apellido Paterno</label>
        <input type="text" name="apellido1" required>
        </div>
        <div class="col-md-4">
        <label for="">Apellido Materno</label required>
        <input type="text" name="apellidos">
        </div>
    </div>
    <br>
    <div class="container">
        <button class="btn btn-primary btn-lg w-100" type="submit" name="buscar">Buscar Cliente</button>
    </div>

</form>
<br>

        <!-- Mostrar resultados de la búsqueda -->
        
            <h2>Resultados de la Búsqueda</h2>
            <table class="table table-bordered border-white scroll">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Fecha Registro</th>
                        <th>Fecha Modificación</th>
                        <th>Estado</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result AS $row){?>
                        <tr>
                            <td><?php echo $row['nombre']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['telefono']; ?></td>
                            <td><?php echo $row['direccion']; ?></td>
                            <td><?php echo $row['fechaR']; ?></td>
                            <td><?php echo $row['fechaM']; ?></td>
                            <td><?php echo $row['estado']; ?></td>
                        </tr>
                </tbody>
                <?php }?>
            </table>
    </main>