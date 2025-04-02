<?php
include_once dirname(__DIR__).'../../config/db.php';

$pdo = new db();
$PDO = $pdo->conexion();

$id_cliente = "";
$fechaV = "";
$detalle_producto = "";
$cantidad = "";
$precio = "";
$descuento = "";
$estado = "";

// Obtener lista de clientes
$clientes = $PDO->query("SELECT id, nombre FROM cliente")->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $comando = $PDO->prepare("SELECT * FROM venta WHERE id_venta = ?");
    $comando->execute([$id]);

    $result = $comando->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $id_cliente = $result["id_cliente"];
        $fechaV = $result["fechaV"];
        $detalle_producto = $result["detalle_producto"];
        $cantidad = $result["cantidad"];
        $precio = $result["precio"];
        $descuento = $result["descuento"];
        $estado = $result["estado"];
    }
}

if (isset($_POST['update'])) {
    $id = $_GET["id"];
    $id_cliente = $_POST["id_cliente"];
    $fechaV = $_POST["fechaV"];
    $detalle_producto = $_POST["detalle_producto"];
    $cantidad = $_POST["cantidad"];
    $precio = $_POST["precio"];
    $descuento = $_POST["descuento"];
    $estado = $_POST["estado"];

    $subtotal_venta = $cantidad * $precio;
    $total = $subtotal_venta * ($descuento / 100); 
    $total_ventas = $subtotal_venta - $total;

    $comando = $PDO->prepare("UPDATE venta SET id_cliente = ?, detalle_producto = ?, fechaV = ?, cantidad = ?, precio = ?, descuento = ?, total = ?, estado = ? WHERE id_venta = ?");
    $comando->execute([$id_cliente, $detalle_producto, $fechaV, $cantidad, $precio, $descuento, $total_ventas, $estado, $id]);

    header("Location: ventas.php");
    exit();
}
?>

<div class="contenedorPanel">
    <div class="botonCss">
        <button class="border-white botonCerrar ColorLetra" type="button" onclick="location.href='ventas.php'">
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
        <button class="border-white botonCerrar ColorLetra" type="button" onclick="location.href='Panel-Administrador.php'">
            Panel
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z"/>
            </svg>
        </button>
    </div>
</div>

<hr class="bg-white">
<form action="editarVenta.php?id=<?php echo $_GET['id']; ?>" method="POST">
    <p>Datos Asociar cliente:</p>
    <div class="form-row row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="cliente">Cliente:</label>
                <select class="form-control" name="id_cliente" required>
                    <option value="">Seleccione un cliente</option>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?php echo $cliente['id']; ?>" <?php echo ($cliente['id'] == $id_cliente) ? 'selected' : ''; ?>>
                            <?php echo $cliente['nombre']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="fechaV">Fecha:</label>
                <input class="form-control" type="date" value="<?php echo $fechaV; ?>" name="fechaV" required>
            </div>
        </div>
    </div>

    <hr class="bg-white">
    <p>Datos de la Venta:</p>
    <div class="form-row row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="detalle_producto">Detalle de los productos o Servicios:</label>
                <input class="form-control" type="text" value="<?php echo $detalle_producto; ?>" name="detalle_producto" required>
            </div>
        </div>
    </div>

    <br>
    <div class="form-row row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="cantidad">Cantidad</label>
                <input class="form-control" type="number" value="<?php echo $cantidad; ?>" name="cantidad" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="precio">Precio Unitario:</label>
                <input class="form-control" type="number" step="0.01" value="<?php echo $precio; ?>" name="precio" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="descuento">Descuento:</label>
                <input class="form-control" type="number" step="0.01" value="<?php echo $descuento; ?>" name="descuento" required>
            </div>
        </div>
    </div>

    <!-- Nuevo campo para estado de la venta -->
    <div class="form-group">
        <label for="estado">Estado de la venta:</label>
        <select class="form-control" name="estado" required>
            <option value="En proceso" <?php echo ($estado == "En proceso") ? 'selected' : ''; ?>>En proceso</option>
            <option value="Completada" <?php echo ($estado == "Completada") ? 'selected' : ''; ?>>Completada</option>
            <option value="Cancelada" <?php echo ($estado == "Cancelada") ? 'selected' : ''; ?>>Cancelada</option>
        </select>
    </div>

    <br>
    <div class="container">
        <button class="btn btn-primary btn-lg w-100" type="submit" name="update">Guardar Venta</button>
    </div>
</form>
