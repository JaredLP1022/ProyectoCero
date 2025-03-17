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

// Manejar la búsqueda de tickets si se envió el formulario
$tickets = [];
$cliente_nombre = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buscar'])) {
    $cliente_id = $_POST['cliente_id'];

    // Obtener nombre del cliente
    $queryCliente = "SELECT nombre FROM cliente WHERE id = ?";
    $stmtCliente = $PDO->prepare($queryCliente);
    $stmtCliente->execute([$cliente_id]);
    $cliente_nombre = $stmtCliente->fetchColumn();

    $queryTickets = "SELECT t.id_ticket, v.detalle_producto, t.fecha, t.prioridad, t.estado
                     FROM ticket t
                     JOIN venta v ON t.id_venta = v.id_venta
                     WHERE v.id_cliente = ?";
    $stmtTickets = $PDO->prepare($queryTickets);
    $stmtTickets->execute([$cliente_id]);
    $tickets = $stmtTickets->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HorizonTech</title>
</head>
<body>
<!-- FORMULARIO DE BÚSQUEDA -->
<form action="" method="POST">
    <label for="cliente">Nombre del Cliente:</label>
    <select name="cliente_id" id="cliente" class="form-control" required>
        <option value="">Selecciona un cliente</option>
        <?php foreach ($clientes as $cliente): ?>
            <option value="<?php echo $cliente['id']; ?>" 
                <?php echo (isset($_POST['cliente_id']) && $_POST['cliente_id'] == $cliente['id']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($cliente['nombre']); ?>
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
        <h3>Tickets del Cliente: <?php echo htmlspecialchars($cliente_nombre); ?></h3>
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
        <form action="exportarCSV.php" method="POST">
            <input type="hidden" name="cliente_id" value="<?php echo htmlspecialchars($cliente_id); ?>">
            <input type="hidden" name="cliente_nombre" value="<?php echo htmlspecialchars($cliente_nombre); ?>">
            <button type="submit" class="btn btn-success">📄 Generar Reporte</button>
        </form>

    <?php else: ?>
        <p class="alert alert-warning">No hay tickets registrados para este cliente.</p>
    <?php endif; ?>
<?php endif; ?>


</div>
</body>
</html>
<?php
    include("./bodyPage/FooterPages.php");
?>
