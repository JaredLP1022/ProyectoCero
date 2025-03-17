<?php
include_once dirname(__DIR__) . '../../config/db.php';

$pdo = new db();
$PDO = $pdo->conexion();

// Obtener todos los tickets con los datos requeridos
$query = "SELECT t.id_ticket, v.id_venta, v.detalle_producto, t.fecha, t.prioridad, t.estado, t.fecha_resolucion, 
                 c.nombre AS cliente, p.descripcionProblem AS descripcionProblema
          FROM ticket t
          JOIN venta v ON t.id_venta = v.id_venta
          JOIN cliente c ON v.id_cliente = c.id
          LEFT JOIN problema p ON t.descripcionProblema = p.id_problema
          WHERE archivado = 'Desarchivado';";
$stmt = $PDO->prepare($query);
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Contadores para el gráfico
$tickets_resueltos = 0;
$tickets_no_resueltos = 0;
$ultimo_tiempo_resolucion = 'N/A';

foreach ($tickets as $ticket) {
    if ($ticket['estado'] === 'Cerrado') {
        $tickets_resueltos++;
        if ($ticket['fecha_resolucion']) {
            $fechaInicio = new DateTime($ticket['fecha']);
            $fechaFin = new DateTime($ticket['fecha_resolucion']);
            $intervalo = $fechaInicio->diff($fechaFin);
            $ultimo_tiempo_resolucion = $intervalo->days . ' días';
        }
    } else {
        $tickets_no_resueltos++;
    }
}
?>

<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
</head>

<!-- Texto descriptivo -->
<div class="container my-3">
    <p class="text-center fw-bold">Se muestran los tickets de los clientes, con los días que tardó en darse la resolución. Debajo de la tabla se muestra un gráfico con los tickets resueltos y no resueltos para visualizar el porcentaje de cada estado.</p>
</div>

<!-- Tabla de tickets -->
<div class="row py-3">
<table class="table table-bordered border-white" id="tablaTickets">
    <thead>
        <tr>
            <th>Cliente</th>
            <th>Descripción del Producto/Servicio</th>
            <th>Descripción del Problema</th>
            <th>Fecha de Registro</th>
            <th>Fecha de Resolución</th>
            <th>Tiempo de Resolución</th>
            <th>Prioridad</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tickets as $ticket): ?>
            <tr>
                <td><?php echo htmlspecialchars($ticket['cliente']); ?></td>
                <td><?php echo htmlspecialchars($ticket['detalle_producto']); ?></td>
                <td><?php echo htmlspecialchars($ticket['descripcionProblema']); ?></td>
                <td><?php echo date("d/m/Y", strtotime($ticket['fecha'])); ?></td>
                <td>
                    <?php 
                        echo $ticket['fecha_resolucion'] ? date("d/m/Y", strtotime($ticket['fecha_resolucion'])) : 'Pendiente';
                    ?>
                </td>
                <td>
                    <?php 
                        if ($ticket['fecha_resolucion']) {
                            $fechaInicio = new DateTime($ticket['fecha']);
                            $fechaFin = new DateTime($ticket['fecha_resolucion']);
                            $intervalo = $fechaInicio->diff($fechaFin);
                            echo $intervalo->days . ' días';
                        } else {
                            echo 'En proceso';
                        }
                    ?>
                </td>
                <td><?php echo htmlspecialchars($ticket['prioridad']); ?></td>
                <td><?php echo htmlspecialchars($ticket['estado']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

<!-- Contenedor del gráfico -->
<div class="container my-4 p-4 border rounded" style="color: rgb(0, 0, 20);">
    <div class="row">
        <!-- Información de los tickets -->
        <div class="col-md-6">
            <h5>Tickets Pendientes: <?php echo $tickets_no_resueltos; ?></h5>
            <h5>Tickets Resueltos: <?php echo $tickets_resueltos; ?></h5>
            <h5>Días en resolver el último ticket: <?php echo $ultimo_tiempo_resolucion; ?></h5>
            <a href="generarReporteST.php" class="btn text-white mt-3" style="background-color: rgb(0, 0, 20);">📄 Exportar a PDF</a>
        </div>
        <!-- Gráfico -->
        <div class="col-md-6 text-center">
            <canvas id="graficoTickets" style="max-width: 300px; max-height: 300px;"></canvas>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#tablaTickets').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "language": {
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                },
                "search": "Buscar:",
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty": "No hay registros disponibles",
                "zeroRecords": "No se encontraron resultados"
            }
        });
    });

    var ctx = document.getElementById('graficoTickets').getContext('2d');
    var graficoTickets = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Resueltos', 'No Resueltos'],
            datasets: [{
                data: [<?php echo $tickets_resueltos; ?>, <?php echo $tickets_no_resueltos; ?>],
                backgroundColor: ['#007bff', '#0056b3']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        color: 'white' 
                    }
                }
            }
        }
    });
</script>