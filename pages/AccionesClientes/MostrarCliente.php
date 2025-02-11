<?php
include("C:/xampp/htdocs/ProyectoCero/config/db.php");

$pdo = new db();
$PDO = $pdo->conexion();


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $comando = $PDO->query("SELECT * FROM cliente WHERE id = $id");
    $comando->execute();

    $result = $comando->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result AS $row){
            $nombre = $row["nombre"];
            $email = $row["email"];
            $telefono = $row["telefono"];
            $direccion = $row["direccion"];
            $fecha_registro = $row["fechaR"];
            $fecha_modificacion = $row["fechaM"];
        }
    
}

if (isset($_POST['update'])) {
    $id = $_GET['id'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['phone'];
    $direccion = $_POST['direccion'];
    $fecha_registro = $_POST['fecha_registro'];
    $fecha_modificacion = $_POST['fecha_modificacion'];

    $comando = $PDO->query("UPDATE cliente set nombre = '$nombre', email = '$email', telefono = '$telefono', direccion = '$direccion', fechaR = '$fecha_registro', fechaM = '$fecha_modificacion' WHERE id = $id");
    $comando->execute();

    header("Location: Clientes.php");

}
?>
<div class="contenedorPanel">
    <div class="botonCss">
        <button class=" border-white botonCerrar ColorLetra" type="submit" onclick="location.href='clientes.php'">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path
                    d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z" />
            </svg>
        </button>
    </div>
    <div class="tituloCss">
        <h4 class="text-center ColorLetra">Editar Cliente</h4>
    </div>
    <div class="botonCss">
        <button class=" border-white botonCerrar ColorLetra" type="submit"
            onclick="location.href='Panel-Administrador.php'">Panel<svg xmlns="http://www.w3.org/2000/svg" width="16"
                height="16" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                <path
                    d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z" />
            </svg></button>
    </div>

</div>
<hr class="bg-white">
<form action="editarcliente.php?id=<?php echo $_GET['id']; ?>" method="POST">
    <p>Datos Personales:</p>
    <div class="form-row row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Nombre:</label>
                <input class="form-control" type="text" value="<?php echo $nombre; ?>" name="nombre">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Correo electronico:</label>
                <input class="form-control" type="email" value="<?php echo $email; ?> " name="email">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Numero de telefono:</label>
                <input class="form-control" type="phone" value="<?php echo $telefono; ?>" name="phone">
            </div>
        </div>
    </div>
    <br>
    <hr class="bg-white">
    <p>Direccion:</p>
    <div class="form-row row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="">Nombre de la calle:</label>
                <input class="form-control" type="text" value="<?php echo $direccion; ?> " name="direccion">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Fecha de registro:</label>
                <input class="form-control" type="date" value="<?php echo $fecha_registro; ?>" name="fecha_registro">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Fecha de actualizacion:</label>
                <input class="form-control" type="date" value="<?php echo $fecha_modificacion; ?>"
                    name="fecha_modificacion">
            </div>
        </div>
    </div><br>

    <br>
    <div class="container">
        <button class="btn btn-primary btn-lg w-100" type="submit" name="update">Guardar cliente</button>
    </div>
</form>

