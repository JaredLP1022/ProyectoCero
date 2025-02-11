<?php

include("C:/xampp/htdocs/ProyectoCero/config/db.php");

$pdo = new db();
$pdo2 = new db();
$pdo3 = new db();

$PDO = $pdo->conexion();
$PDO2 = $pdo2->conexion();
$PDO3 = $pdo3->conexion();

$comando = $PDO->query("SELECT * FROM clientes");
$comando->execute();

$query = $PDO2->query("SELECT * FROM pais");
$query->execute();

$query2 = $PDO3->query("SELECT * FROM estado");
$query2->execute();

$result = $comando->fetchAll(PDO::FETCH_ASSOC);
$result2 = $query->fetchAll(PDO::FETCH_ASSOC);
$result3 = $query2->fetchAll(PDO::FETCH_ASSOC);
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
        <h4 class="text-center ColorLetra">Agregando clientes</h4>
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
<form action="AccionesClientes/GuardarCliente.php" method="POST">
    <p>Datos Personales:</p>
    <div class="form-row row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Nombre:</label>
                <input class="form-control" type="text" placeholder="Jared" name="nombre" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Apellido Paterno:</label>
                <input class="form-control" type="text" placeholder="Lopez" name="apellidoP" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Apellido Materno:</label>
                <input class="form-control" type="text" placeholder="Poseros" name="apellidoS" required>
            </div>
        </div>
    </div>
    <div class="form-row row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="">Correo electronico:</label>
                <input class="form-control" type="email" placeholder="kanek12010@hotmail.com" name="email" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="">Numero de telefono:</label>
                <input class="form-control" type="number" placeholder="2281223344" name="phone" required>
            </div>
        </div>
    </div>

    <hr class="bg-white">

    <p>Direccion:</p>
    <div class="form-row row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="">Nombre de la calle:</label>
                <input class="form-control" type="text" placeholder="Lazaro cardenas " name="calle" required>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Numero de la casa:</label>
                <input class="form-control" type="number" placeholder="444" name="numCasa" required>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Codigo postal:</label>
                <input class="form-control" type="number" placeholder="91180" name="codigoPostal" required>
            </div>
        </div>
    </div>
    <div class="form-row row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Ciudad:</label>
                <input class="form-control" type="text" placeholder="Xalapa" name="ciudad" required>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Estado:</label>
                <input class="form-control" type="text" placeholder="estado" name="pais" required>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Pais:</label>
                <input class="form-control" type="text" placeholder="pais" name="pais" required>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Fecha de registro:</label>
                <input class="form-control" type="date" placeholder="Mexico" name="fechaRegis" required>
            </div>
        </div>
    </div>
    <hr class="bg-white">
    <?php if (!empty($_GET["error"])): ?>
        <<div class="alert alert-danger">
            <?= !empty($_GET["error"]) ? $_GET["error"] : "" ?>
            </div>
        <?php endif; ?>
        <br>
        <div class="container">
            <button class="btn btn-primary btn-lg w-100" name="save" type="submit" onclick="confirmar()" >Guardar cliente</button>
        </div>

</form>

<script>
    function confirmar() {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 19000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        Toast.fire({
            icon: "success",
            title: "Usuario Guardado"
        });
    }

</script>