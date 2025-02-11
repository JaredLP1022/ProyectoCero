<?php
include_once dirname(__DIR__).'../../config/db.php';

$pdo = new db();
$PDO = $pdo->conexion();


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $comando = $PDO->query("SELECT * FROM venta WHERE id_venta = $id");
    $comando->execute();

    $result = $comando->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result AS $row){
            $id_cliente = $row["id_cliente"];
            $fechaV = $row["fechaV"];
            $detalle_producto = $row["detalle_producto"];
            $cantidad = $row["cantidad"];
            $precio = $row["precio"];
            $descuento = $row["descuento"];
        }
    
}

if (isset($_POST['update'])) {
    $id = $_GET["id"];
    $fechaV = $_POST["fechaV"];
    $detalle_producto = $_POST["detalle_producto"];
    $cantidad = $_POST["cantidad"];
    $precio = $_POST["precio"];
    $descuento = $_POST["descuento"];

    $subtotal_venta = $cantidad * $precio;
    $total = $subtotal_venta * ($descuento / 100); 
    $total_ventas = $subtotal_venta - $total;

    $comando = $PDO->query("UPDATE venta set detalle_producto = '$detalle_producto', fechaV = '$fechaV', cantidad = '$cantidad', precio = '$precio', descuento = '$descuento', total = '$total_ventas' WHERE id_venta = $id");
    $comando->execute();

    header("Location: ventas.php");
}

?>
<div class="contenedorPanel">
    <div class="botonCss">
        <button class=" border-white botonCerrar ColorLetra" type="submit" onclick="location.href='ventas.php'">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path
                    d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z" />
            </svg>
        </button>
    </div>
    <div class="tituloCss">
        <h4 class="text-center ColorLetra">Editar Venta</h4>
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
<form action="editarVenta.php?id=<?php echo $_GET['id']; ?>" method="POST" >
    <p>Datos Asociar cliente:</p>
     <div class="form-row row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="">ID cliente:</label>
                <input class="form-control" type="number" value="<?php echo $id_cliente; ?>" name="id_cliente" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="">Fecha:</label>
                <input class="form-control" type="date" value="<?php echo $fechaV; ?>" name="fechaV" required>
            </div>
        </div>
    </div>
    <hr class="bg-white">
    <p>Datos de la Venta:</p>
    <div class="form-row row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="">Detalle de los productos o Servicios:</label>
                <input class="form-control" type="text" value="<?php echo $detalle_producto; ?>"  name="detalle_producto" required>
            </div>
        </div>
        
    </div>
    <br>
    <div class="form-row row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Cantidad</label>
                <input class="form-control" type="number" value="<?php echo $cantidad; ?>" name="cantidad" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Precio Unitario:</label>
                <input class="form-control" type="float" value="<?php echo $precio; ?>"  name="precio" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Descuento:</label>
               <input class="form-control" type="float" value="<?php echo $descuento; ?>" name="descuento" required>
            </div>
        </div>
        
    </div>
    <hr class="bg-white">
   
    <br>
    <div class="container">
        <button class="btn btn-primary btn-lg w-100" type="submit" name="update">Guardar cliente</button>
    </div>

</form>