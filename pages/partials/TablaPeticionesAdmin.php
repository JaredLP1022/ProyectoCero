<?php
include("C:/xampp/htdocs/ProyectoCero/config/db.php");

try {
    $pdo = new db();
    $PDO = $pdo->conexion();

    // Consulta para obtener todas las peticiones con los datos del usuario
    $sql = "SELECT p.*, u.nombre AS usuario_nombre, u.email AS usuario_email 
            FROM peticiones p
            JOIN usuario u ON p.id_usuario = u.id";
    $stmt = $PDO->prepare($sql);
    $stmt->execute();

    // Obtener los resultados
    $peticiones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error en la base de datos: " . $e->getMessage();
}
?>

<!-- Incluye jQuery y DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Estilos adicionales para la tabla -->
<style>
    #tablaPeticiones tbody {
        background-color: transparent !important;
    }

    #tablaPeticiones tbody tr {
        background-color: transparent !important;
    }

    #tablaPeticiones tbody td {
        background-color: transparent !important;
    }
</style>

<div class="container mt-5">
    <h2 class="text-center">Consulta de Peticiones (Administrador)</h2>

    <!-- Tabla para mostrar las peticiones -->
    <table class="table table-bordered border-white scroll" id="tablaPeticiones">
        <thead>
            <tr>
                <th>Petición</th>
                <th>Usuario</th>
                <th>Correo</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Fecha de Creación</th>
                <th>Fecha Necesaria</th>
                <th>Acciones</th> 
            </tr>
        </thead>
        <tbody>
            <?php foreach ($peticiones as $peticion): ?>
                <tr>
                    <td><?php echo htmlspecialchars($peticion['peticion']); ?></td>
                    <td><?php echo htmlspecialchars($peticion['usuario_nombre']); ?></td>
                    <td><?php echo htmlspecialchars($peticion['usuario_email']); ?></td>
                    <td><?php echo htmlspecialchars($peticion['estado']); ?></td>
                    <td><?php echo htmlspecialchars($peticion['prioridad']); ?></td>
                    <td><?php echo date("d/m/Y", strtotime($peticion['fecha_creacion'])); ?></td>
                    <td><?php echo date("d/m/Y", strtotime($peticion['fecha_necesita'])); ?></td>
                    <td>
                        <button class="btn btn-primary btn-sm editar-peticion" data-bs-toggle="modal" data-bs-target="#modalEditar" data-id="<?php echo $peticion['id']; ?>">✏️ Editar</button>
                        <button class="btn btn-danger btn-sm eliminar-peticion" data-id="<?php echo $peticion['id']; ?>">🗑️ Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Enlace para redirigir a otra página -->
    <div class="text-center mt-3">
        <a href="nuevaPagina.php" class="btn btn-info">Ir a otra página</a>
    </div>
</div>

<!-- Modal para editar peticiones -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar Petición</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario para editar peticion -->
                <form id="formEditarPeticion">
                    <div class="mb-3">
                        <label for="id" class="form-label">ID Petición</label>
                        <input type="text" id="id" name="id" class="form-control" readonly> <!-- Campo visible para el ID -->
                    </div>

                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="">Seleccione el estado</option>
                            <option value="aceptada">Aceptada</option>
                            <option value="rechazada">Rechazada</option>
                            <option value="finalizada">Finalizada</option>
                            <option value="en proceso">En Proceso</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_resolucion" class="form-label">Fecha de Resolución</label>
                        <input type="date" class="form-control" id="fecha_resolucion" name="fecha_resolucion" required>
                    </div>

                    <div class="mb-3">
                        <label for="respuesta_admin" class="form-label">Respuesta del Administrador</label>
                        <textarea class="form-control" id="respuesta_admin" name="respuesta_admin" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="guardarCambios">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
    // Inicializar DataTables con paginación y otras configuraciones
    $('#tablaPeticiones').DataTable({
        "paging": true,
        "lengthChange": true, // Permitir cambiar el número de registros por página
        "pageLength": 3, // Número inicial de registros por página
        "lengthMenu": [3, 6, 10], // Opciones para cambiar la cantidad de registros
        "searching": true,
        "ordering": true,
        "info": true,
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "zeroRecords": "No se encontraron registros",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros disponibles",
            "infoFiltered": "(filtrado de _MAX_ registros totales)"
        }
    });



        // Evento para abrir el modal de edición
        $(".editar-peticion").click(function () {
            let id = $(this).data("id");
            console.log("ID: ", id); // Verifica que el ID se está obteniendo correctamente

            // Obtener otros valores de la fila para editar
            let estado = $(this).data("estado");
            let fecha_resolucion = $(this).data("fecha_resolucion");
            let respuesta = $(this).data("respuesta");

            // Rellenar los campos del modal con los datos correspondientes
            $("#id").val(id); // Mostrar el ID en el input
            $("#estado").val(estado); // Seleccionar el estado que viene desde la base de datos
            $("#fecha_resolucion").val(fecha_resolucion);
            $("#respuesta_admin").val(respuesta);
        });

        // Evento para guardar cambios en la petición
        $("#guardarCambios").click(function () {
            console.log($("#formEditarPeticion").serialize()); // Ver los datos que se envían
            $.ajax({
                url: "editarPeticion.php",
                type: "POST",
                data: $("#formEditarPeticion").serialize(),
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Éxito",
                            text: "Petición actualizada correctamente",
                            icon: "success"
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire("Error", response.error, "error");
                    }
                },
                error: function (xhr, status, error) {
    console.log("Error: ", error); // Detalle del error
    console.log("Status: ", status); // Estado de la respuesta
    console.log("XHR: ", xhr); // Objeto XHR completo
    console.log("Response Text: ", xhr.responseText); // Ver la respuesta completa
    Swal.fire("Error", "No se pudo conectar con el servidor", "error");
}
            });
        });

        // Funcionalidad para eliminar una petición
        $(".eliminar-peticion").click(function () {
            let id = $(this).data("id");

            Swal.fire({
                title: "¿Estás seguro?",
                text: "Esta acción eliminará la petición permanentemente",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sí, eliminarla"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "eliminarPeticion.php",
                        type: "POST",
                        data: { id: id },
                        dataType: "json",
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Éxito",
                                    text: "Petición eliminada correctamente",
                                    icon: "success"
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire("Error", response.error, "error");
                            }
                        },
                        error: function () {
                            Swal.fire("Error", "No se pudo conectar con el servidor", "error");
                        }
                    });
                }
            });
        });
    });
</script>
