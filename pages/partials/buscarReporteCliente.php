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

// Obtener tipos de producto
$queryProductos = "SELECT DISTINCT detalle_producto FROM venta";
$stmtProductos = $PDO->prepare($queryProductos);
$stmtProductos->execute();
$productos = $stmtProductos->fetchAll(PDO::FETCH_ASSOC);

$ventas = [];
$cliente_nombre = "";
$producto_nombre = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buscar'])) {
    $cliente_id = $_POST['cliente_id'];

    if (isset($_POST['detalle_producto']) && !empty($_POST['detalle_producto'])) {
        $producto_nombre = $_POST['detalle_producto'];
    }

    if (!empty($cliente_id)) {
        $stmtCliente = $PDO->prepare("SELECT nombre FROM cliente WHERE id = ?");
        $stmtCliente->execute([$cliente_id]);
        $cliente_nombre = $stmtCliente->fetchColumn();
    }

    $queryVentas = "SELECT v.id_venta, v.detalle_producto, v.fechaV, v.cantidad, v.total
                    FROM venta v";

    $params = [];
    $conditions = [];

    if (!empty($cliente_id)) {
        $conditions[] = "v.id_cliente = ?";
        $params[] = $cliente_id;
    }

    if (!empty($producto_nombre)) {
        $conditions[] = "v.detalle_producto = ?";
        $params[] = $producto_nombre;
    }

    if (count($conditions) > 0) {
        $queryVentas .= " WHERE " . implode(" AND ", $conditions);
    }

    $stmtVentas = $PDO->prepare($queryVentas);
    $stmtVentas->execute($params);
    $ventas = $stmtVentas->fetchAll(PDO::FETCH_ASSOC);
}
?>
<form action="" method="POST">
    <label for="cliente">Cliente:</label>
    <select name="cliente_id" id="cliente" class="form-control">
        <option value="">Todos</option>
        <?php foreach ($clientes as $cliente): ?>
            <option value="<?php echo $cliente['id']; ?>" 
                <?php echo (isset($_POST['cliente_id']) && $_POST['cliente_id'] == $cliente['id']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($cliente['nombre']); ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <label for="producto">Tipo de Producto:</label>
    <select name="detalle_producto" id="producto" class="form-control">
        <option value="">Todos</option>
        <?php foreach ($productos as $producto): ?>
            <option value="<?php echo htmlspecialchars($producto['detalle_producto']); ?>" 
                <?php echo (isset($_POST['detalle_producto']) && $_POST['detalle_producto'] == $producto['detalle_producto']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($producto['detalle_producto']); ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <button type="submit" name="buscar" class="btn btn-primary">Buscar Ventas</button>
</form>

<?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buscar'])): ?>
    <?php if (!empty($ventas)): ?>
        <h3>Ventas <?php echo !empty($cliente_nombre) ? "de: " . htmlspecialchars($cliente_nombre) : ''; ?> 
        <?php echo !empty($producto_nombre) ? " - Producto: " . htmlspecialchars($producto_nombre) : ''; ?></h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Venta</th>
                    <th>Producto</th>
                    <th>Fecha</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ventas as $v): ?>
                    <tr>
                        <td><?php echo $v['id_venta']; ?></td>
                        <td><?php echo $v['detalle_producto']; ?></td>
                        <td><?php echo date("d/m/Y", strtotime($v['fechaV'])); ?></td>
                        <td><?php echo $v['cantidad']; ?></td>
                        <td>$<?php echo $v['total']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <form action="exportarVentasCSV.php" method="POST">
    <input type="hidden" name="cliente_id" value="<?php echo htmlspecialchars($cliente_id); ?>">
    <input type="hidden" name="cliente_nombre" value="<?php echo htmlspecialchars($cliente_nombre ?: 'Todos'); ?>">
    <input type="hidden" name="detalle_producto" value="<?php echo htmlspecialchars($producto_nombre); ?>">
    <input type="hidden" name="tipo_producto_texto" value="<?php echo htmlspecialchars($producto_nombre ?: 'Todos'); ?>">
    <button type="submit" class="btn btn-success">📄 Generar Reporte</button>
</form>

    <?php else: ?>
        <p class="alert alert-warning">No hay ventas registradas con estos filtros.</p>
    <?php endif; ?>
<?php endif; ?>
