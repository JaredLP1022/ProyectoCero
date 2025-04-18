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

// Obtener tipos de problema
$queryProblemas = "SELECT id_problema, descripcionProblem FROM problema";
$stmtProblemas = $PDO->prepare($queryProblemas);
$stmtProblemas->execute();
$problemas = $stmtProblemas->fetchAll(PDO::FETCH_ASSOC);

// Manejar la búsqueda de tickets si se envió el formulario
$tickets = [];
$cliente_nombre = "";
$problema_descripcion = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buscar'])) {
    $cliente_id = $_POST['cliente_id'];

    // Verificar si el campo 'problema_id' está presente en el POST
    if (isset($_POST['problema_id']) && !empty($_POST['problema_id'])) {
        $problema_id = $_POST['problema_id'];

        // Obtener descripción del tipo de problema
        $queryProblema = "SELECT descripcionProblem FROM problema WHERE id_problema = ?";
        $stmtProblema = $PDO->prepare($queryProblema);
        $stmtProblema->execute([$problema_id]);
        $problema_descripcion = $stmtProblema->fetchColumn();
    } else {
        $problema_id = null;
        $problema_descripcion = null;
    }

    // Si el cliente fue seleccionado, obtener su nombre
    if (!empty($cliente_id)) {
        $queryCliente = "SELECT nombre FROM cliente WHERE id = ?";
        $stmtCliente = $PDO->prepare($queryCliente);
        $stmtCliente->execute([$cliente_id]);
        $cliente_nombre = $stmtCliente->fetchColumn();
    }

    // Consulta para obtener los tickets filtrados por cliente y tipo de problema
    $queryTickets = "SELECT t.id_ticket, v.detalle_producto, t.fecha, t.prioridad, t.estado, p.descripcionProblem AS descripcionProblema
                     FROM ticket t
                     JOIN venta v ON t.id_venta = v.id_venta
                     LEFT JOIN problema p ON t.descripcionProblema = p.id_problema";
    
    // Si 'cliente_id' está especificado, agregar el filtro
    if (!empty($cliente_id)) {
        $queryTickets .= " WHERE v.id_cliente = ?";
        // Si también se seleccionó un tipo de problema, agregar el filtro
        if (!empty($problema_id)) {
            $queryTickets .= " AND t.descripcionProblema = ?";
        }
    } else {
        // Si no se seleccionó cliente, solo filtrar por problema (si es necesario)
        if (!empty($problema_id)) {
            $queryTickets .= " WHERE t.descripcionProblema = ?";
        }
    }

    $stmtTickets = $PDO->prepare($queryTickets);
    if (!empty($cliente_id) && !empty($problema_id)) {
        $stmtTickets->execute([$cliente_id, $problema_id]);
    } elseif (!empty($cliente_id)) {
        $stmtTickets->execute([$cliente_id]);
    } elseif (!empty($problema_id)) {
        $stmtTickets->execute([$problema_id]);
    } else {
        $stmtTickets->execute();
    }

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

    <label for="problema">Tipo de Problema:</label>
    <select name="problema_id" id="problema" class="form-control">
        <option value="">Selecciona un tipo de problema (opcional)</option>
        <?php foreach ($problemas as $problema): ?>
            <option value="<?php echo $problema['id_problema']; ?>" 
                <?php echo (isset($_POST['problema_id']) && $_POST['problema_id'] == $problema['id_problema']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($problema['descripcionProblem']); ?>
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
        <?php echo $problema_descripcion ? "- Tipo de Problema: " . htmlspecialchars($problema_descripcion) : ''; ?></h3>
        <table class="table table-bordered border-white">
            <thead>
                <tr>
                    <th>ID Ticket</th>
                    <th>Producto/Servicio</th>
                    <th>Descripcion del problema</th>
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
                        <td><?php echo htmlspecialchars($ticket['descripcionProblema']); ?></td>
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
            <input type="hidden" name="problema_id" value="<?php echo htmlspecialchars($problema_id); ?>">
            <input type="hidden" name="problema_descripcion" value="<?php echo htmlspecialchars($problema_descripcion); ?>">
            <button type="submit" class="btn btn-success">📄 Generar Reporte</button>
        </form>

    <?php else: ?>
        <p class="alert alert-warning">No hay tickets registrados para este cliente o tipo de problema.</p>
    <?php endif; ?>
<?php endif; ?>
</div>
</body>
</html>
<?php
    include("./bodyPage/FooterPages.php");
?>
