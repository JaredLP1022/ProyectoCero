<?php
    include("./bodyPage/HeadPages.php");
?>
<?php
    include("./bodyPage/HeaderPages.php");
    if(empty($_SESSION['username'])){
        header("Location:../index.php");
    }
?>
<div class="container mt-5">
<?php
include("C:/xampp/htdocs/ProyectoCero/config/db.php");

$pdo = new db();
$PDO = $pdo->conexion();

// Obtener clientes con ventas
$queryClientes = "SELECT DISTINCT c.id, c.nombre 
                  FROM cliente c
                  JOIN venta v ON c.id = v.id_cliente";
$stmtClientes = $PDO->prepare($queryClientes);
$stmtClientes->execute();
$clientes = $stmtClientes->fetchAll(PDO::FETCH_ASSOC);

// Obtener productos distintos
$queryProductos = "SELECT DISTINCT detalle_producto FROM venta";
$stmtProductos = $PDO->prepare($queryProductos);
$stmtProductos->execute();
$productos = $stmtProductos->fetchAll(PDO::FETCH_ASSOC);

// Manejar la búsqueda
$tickets = [];
$cliente_nombre = "";
$producto_nombre = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buscar'])) {
    $cliente_id = $_POST['cliente_id'];
    $producto_nombre = isset($_POST['producto']) ? $_POST['producto'] : "";

    // Obtener nombre del cliente
    if (!empty($cliente_id)) {
        $queryCliente = "SELECT nombre FROM cliente WHERE id = ?";
        $stmtCliente = $PDO->prepare($queryCliente);
        $stmtCliente->execute([$cliente_id]);
        $cliente_nombre = $stmtCliente->fetchColumn();
    }

    // Consulta para obtener tickets filtrados por cliente y producto
    $queryTickets = "SELECT t.id_ticket, v.detalle_producto, t.fecha, t.prioridad, t.estado
                     FROM ticket t
                     JOIN venta v ON t.id_venta = v.id_venta";

    $condiciones = [];
    $parametros = [];

    if (!empty($cliente_id)) {
        $condiciones[] = "v.id_cliente = ?";
        $parametros[] = $cliente_id;
    }

    if (!empty($producto_nombre)) {
        $condiciones[] = "v.detalle_producto = ?";
        $parametros[] = $producto_nombre;
    }

    if (!empty($condiciones)) {
        $queryTickets .= " WHERE " . implode(" AND ", $condiciones);
    }

    $stmtTickets = $PDO->prepare($queryTickets);
    $stmtTickets->execute($parametros);
    $tickets = $stmtTickets->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>HorizonTech</title>
</head>
<body>
<!-- FORMULARIO DE BÚSQUEDA -->
<form action="" method="POST">
    <label for="cliente">Nombre del Cliente:</label>
    <select name="cliente_id" id="cliente" class="form-control">
        <option value="">Selecciona un cliente (opcional)</option>
        <?php foreach ($clientes as $cliente): ?>
            <option value="<?php echo $cliente['id']; ?>" 
                <?php echo (isset($_POST['cliente_id']) && $_POST['cliente_id'] == $cliente['id']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($cliente['nombre']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br>

    <label for="producto">Producto / Servicio:</label>
    <select name="producto" id="producto" class="form-control">
        <option value="">Selecciona un producto (opcional)</option>
        <?php foreach ($productos as $producto): ?>
            <option value="<?php echo htmlspecialchars($producto['detalle_producto']); ?>" 
                <?php echo (isset($_POST['producto']) && $_POST['producto'] == $producto['detalle_producto']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($producto['detalle_producto']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br>
    <button type="submit" name="buscar" class="btn btn-primary">Buscar Tickets</button>
</form>

<br>

<!-- MOSTRAR RESULTADOS -->
<?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buscar'])): ?>
    <?php if (!empty($tickets)): ?>
        <h3>Tickets <?php echo !empty($cliente_nombre) ? "del Cliente: " . htmlspecialchars($cliente_nombre) : ''; ?> 
        <?php echo $producto_nombre ? "- Producto: " . htmlspecialchars($producto_nombre) : ''; ?></h3>
        <table class="table table-bordered border-white">
            <thead>
                <tr>
                    <th>ID Ticket</th>
                    <th>Producto/Servicio</th>
                    <th>Fecha</th>
                    <th>Prioridad</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ticket['id_ticket']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['detalle_producto']); ?></td>
                        <td><?php echo date("d/m/Y", strtotime($ticket['fecha'])); ?></td>
                        <td><?php echo htmlspecialchars($ticket['prioridad']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['estado']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- BOTÓN PARA GENERAR REPORTE -->
        <form action="exportarCPCSV.php" method="POST">
            <input type="hidden" name="cliente_id" value="<?php echo htmlspecialchars($cliente_id); ?>">
            <input type="hidden" name="cliente_nombre" value="<?php echo htmlspecialchars($cliente_nombre); ?>">
            <input type="hidden" name="producto" value="<?php echo htmlspecialchars($producto_nombre); ?>">
            <button type="submit" class="btn btn-success">📄 Generar Reporte</button>
        </form>

    <?php else: ?>
        <p class="alert alert-warning">No hay tickets registrados para este cliente o producto.</p>
    <?php endif; ?>
<?php endif; ?>
</div>
</body>
</html>
<?php
    include("./bodyPage/FooterPages.php");
?>
