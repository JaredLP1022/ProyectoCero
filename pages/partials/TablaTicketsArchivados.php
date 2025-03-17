<?php
include_once dirname(__DIR__) . '../../config/db.php';

$pdo = new db();
$PDO = $pdo->conexion();

// Obtener todos los tickets archivados
$query = "SELECT t.id_ticket, v.id_venta, v.detalle_producto, t.fecha, t.prioridad, t.estado, 
                 c.nombre AS cliente, p.id_problema, p.descripcionProblem AS descripcionProblema
          FROM ticket t
          JOIN venta v ON t.id_venta = v.id_venta
          JOIN cliente c ON v.id_cliente = c.id
          LEFT JOIN problema p ON t.descripcionProblema = p.id_problema
          WHERE archivado = 'Archivado';";
$stmt = $PDO->prepare($query);
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener todos los problemas
$query_problemas = "SELECT id_problema, descripcionProblem FROM problema";
$stmt_problemas = $PDO->prepare($query_problemas);
$stmt_problemas->execute();
$problemas = $stmt_problemas->fetchAll(PDO::FETCH_ASSOC);
?>

<head>
    <style>
        /* Eliminar el fondo de los inputs de los filtros */
        #filterEstado,
        #filterPrioridad {
            background-color: transparent !important;
            border-color: #ced4da !important;
            color: #495057;
        }

        #filterEstado option,
        #filterPrioridad option {
            background-color: transparent !important;
        }

        /* Para los filtros de fecha */
        #filterFechaCreacion {
            background-color: transparent !important;
            border-color: #ced4da !important;
            color: #495057;
        }

        /* Aseguramos que el fondo del tbody, tr y td sea transparente */
        #tablaTickets tbody {
            background-color: transparent !important;
        }

        #tablaTickets tbody tr {
            background-color: transparent !important;
        }

        #tablaTickets tbody td {
            background-color: transparent !important;
        }

        /* Si prefieres darle un color gris claro */
        #tablaTickets tbody {
            background-color: #transparent !important;
            /* Gris claro */
        }

        #tablaTickets tbody tr {
            background-color: #transparent !important;
        }

        #tablaTickets tbody td {
            background-color: transparent !important;
        }
    </style>
</head>

<!-- Botón para agregar nuevo ticket -->
<div class="container my-3">
    <button class="btn btn-success" onclick="location.href='nuevoTicket.php'">➕ Agregar Ticket</button>
    <a href="reporteTickets.php" class="btn btn-info btn-sm">📄 Generar Reporte</a>
</div>

<!-- Tabla de tickets archivados -->
<div class="row py-3">
    <div class="col">
        <table class="table table-bordered border-white scroll" id="tablaTickets">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Descripción del Producto/Servicio</th>
                    <th>Descripción del Problema</th>
                    <th>Fecha de Registro</th>
                    <th>Prioridad</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ticket['cliente']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['detalle_producto']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['descripcionProblema']); ?></td>
                        <td><?php echo date("d/m/Y", strtotime($ticket['fecha'])); ?></td>
                        <td><?php echo htmlspecialchars($ticket['prioridad']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['estado']); ?></td>
                        <td>
                        

                            <button class="btn btn-danger btn-sm eliminar-ticket" data-id="<?php echo $ticket['id_ticket']; ?>">🗑️
                                Eliminar</button>
                            <?php if ($ticket['estado'] === 'Cerrado'): ?>
                                <button class="btn btn-success" onclick="desarchivarTicket(<?php echo $ticket['id_ticket']; ?>)">🔄
                                    Desarchivar</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<br>

<!-- Modal -->
<div id="modalEditarTicket" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarTicket">
                    <input type="hidden" id="id_ticket" name="id_ticket">

                    <div class="form-group">
                        <label>Estado:</label>
                        <select class="form-control" name="estado" id="estado">
                            <option value="En proceso">En proceso</option>
                            <option value="Cerrado">Cerrado</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Prioridad:</label>
                        <select class="form-control" name="prioridad" id="prioridad">
                            <option value="Baja">Baja</option>
                            <option value="Media">Media</option>
                            <option value="Alta">Alta</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="descripcionProblema">Descripción del Problema:</label>
                        <select class="form-control" name="descripcionProblema" id="descripcionProblema">
                            <?php foreach ($problemas as $problema): ?>
                                <option value="<?php echo $problema['id_problema']; ?>">
                                    <?php echo htmlspecialchars($problema['descripcionProblem']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Incluir jQuery primero -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Luego incluir Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Luego incluir DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        var table = $('#tablaTickets').DataTable({
            "paging": true,       // Activa la paginación
            "searching": true,    // Activa el campo de búsqueda
            "ordering": true,     // Permite ordenar las columnas
            "info": true,         // Muestra información de la tabla
            "lengthMenu": [5, 10], // Número de registros por página
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "No se encontraron resultados",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles",
                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                "search": "Buscar:",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            }
        });
    });

    function desarchivarTicket(id_ticket) {
    Swal.fire({
        title: "¿Desarchivar Ticket?",
        text: "Este ticket será restaurado a la tabla principal.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, desarchivar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("desarchivarTicket.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "id_ticket=" + id_ticket
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire("Restaurado", "El ticket ha sido desarchivado correctamente.", "success")
                        .then(() => location.reload());
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            })
            .catch(error => {
                Swal.fire("Error", "No se pudo desarchivar el ticket.", "error");
            });
        }
    });
}


    $(document).on("click", ".eliminar-ticket", function () {
    var ticketId = $(this).data("id");
    Swal.fire({
        title: "¿Estás seguro?",
        text: "Esta acción no se puede deshacer.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "eliminarTicket.php",
                type: "POST",
                data: { id_ticket: ticketId },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        Swal.fire("Eliminado", response.message, "success").then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire("Error", response.message, "error");
                    }
                },
                error: function () {
                    Swal.fire("Error", "No se pudo eliminar el ticket.", "error");
                }
            });
        }
    });
});

</script>
