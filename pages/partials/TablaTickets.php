<?php
include_once dirname(__DIR__) . '../../config/db.php';

$pdo = new db();
$PDO = $pdo->conexion();

// Obtener todos los tickets con el nombre del cliente
$query = "SELECT t.id_ticket, v.id_venta, v.detalle_producto, t.fecha, t.prioridad, t.estado, c.nombre AS cliente 
          FROM ticket t
          JOIN venta v ON t.id_venta = v.id_venta
          JOIN cliente c ON v.id_cliente = c.id";
$stmt = $PDO->prepare($query);
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    </style>
</head>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Botón para agregar nuevo ticket -->
<div class="container my-3">
    <button class="btn btn-success" onclick="location.href='formAgregarTicket.php'">➕ Agregar Ticket</button>
</div>

<!-- Tabla de tickets -->
<div class="row py-3">
    <div class="col">
        <table class="table table-bordered border-white scroll" id="tablaTickets">
            <thead>
                <tr>
                    <th class="ColorLetra">Cliente</th>
                    <th class="ColorLetra">Descripción del Producto/Servicio</th>
                    <th class="ColorLetra">Fecha de Registro</th>
                    <th class="ColorLetra">Prioridad</th>
                    <th class="ColorLetra">Estado</th>
                    <th class="ColorLetra">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ticket['cliente']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['detalle_producto']); ?></td>
                        <td><?php echo date("d/m/Y", strtotime($ticket['fecha'])); ?></td>
                        <td><?php echo htmlspecialchars($ticket['prioridad']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['estado']); ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm editar-ticket" data-bs-toggle="modal"
                                data-bs-target="#modalEditarTicket" data-id="<?php echo $ticket['id_ticket']; ?>"
                                data-prioridad="<?php echo $ticket['prioridad']; ?>"
                                data-estado="<?php echo $ticket['estado']; ?>">
                                ✏️ Editar
                            </button>
                            <!-- Botón Eliminar -->
                            <button class="btn btn-danger btn-sm eliminar-ticket"
                                data-id="<?php echo $ticket['id_ticket']; ?>">
                                🗑️ Eliminar
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

         <!-- Filtros -->
         <div class="row mb-3">
            <div class="col-md-4">
                <label for="filterEstado">Filtrar por Estado:</label>
                <select id="filterEstado" class="form-control">
                    <option value="">Todos</option>
                    <option value="En proceso">En proceso</option>
                    <option value="Cerrado">Cerrado</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="filterPrioridad">Filtrar por Prioridad:</label>
                <select id="filterPrioridad" class="form-control">
                    <option value="">Todos</option>
                    <option value="Baja">Baja</option>
                    <option value="Media">Media</option>
                    <option value="Alta">Alta</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="filterFechaCreacion">Filtrar por Fecha de Creación:</label>
                <input type="date" id="filterFechaCreacion" class="form-control">
            </div>
        </div>
    </div>
</div>

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
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        // Llenar el modal con los datos correctos
        $(".editar-ticket").click(function () {
            $("#id_ticket").val($(this).data("id"));
            $("#estado").val($(this).data("estado"));
            $("#prioridad").val($(this).data("prioridad"));
        });

        // Enviar los datos del formulario con AJAX
        $("#formEditarTicket").submit(function (event) {
            event.preventDefault();

            $.ajax({
                url: "editarTicket.php",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        Swal.fire("Éxito", response.message, "success").then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire("Error", response.message, "error");
                    }
                },
                error: function () {
                    Swal.fire("Error", "Hubo un problema con la solicitud.", "error");
                }
            });
        });
    });

    $(document).ready(function () {
        $(".eliminar-ticket").click(function () {
            const id_ticket = $(this).data("id");

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
                        data: { id_ticket: id_ticket },
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
                            Swal.fire("Error", "Hubo un problema con la solicitud.", "error");
                        }
                    });
                }
            });
        });
    });


</script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<!-- jQuery y DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        // Inicializar DataTable y configurar opciones
        var table = $('#tablaTickets').DataTable({
            "pageLength": 5, // Mostrar 5 registros por página
            "lengthMenu": [[5, 10], [5, 10]],
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "No se encontraron resultados",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty": "No hay tickets registrados",
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

        // Filtrar por Estado
        $('#filterEstado').on('change', function () {
            table.column(4).search(this.value).draw(); // Columna 4 = Estado
        });

        // Filtrar por Prioridad
        $('#filterPrioridad').on('change', function () {
            table.column(3).search(this.value).draw(); // Columna 3 = Prioridad
        });

        // Filtrar por fecha de creación
        $("#filterFechaCreacion").change(function () {
            var filterDate = this.value;
            if (filterDate) {
                table.column(2).search(filterDate).draw(); // Filtra por la fecha en la columna 2 (Fecha de Registro)
            } else {
                table.column(2).search('').draw(); // Si no hay filtro, muestra todas las fechas
            }
        });
    });

</script>