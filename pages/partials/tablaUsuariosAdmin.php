<?php
include_once dirname(__DIR__) . '../../config/db.php';

$pdo = new db();
$PDO = $pdo->conexion();

// Obtener todos los usuarios
$query = "SELECT nombre, username, email, rol, estado, bloqueo_hasta FROM usuario";
$stmt = $PDO->prepare($query);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Función para convertir la fecha al formato legible dd-mm-yyyy hh:mm
function formatearFechaLegible($fecha)
{
    if (!$fecha) {
        return "Usuario sin bloqueo"; // Si no tiene fecha, mostramos este texto
    }

    $fechaObj = new DateTime($fecha); // Convertir a objeto DateTime
    return $fechaObj->format('d-m-Y H:i'); // Formato dd-mm-yyyy hh:mm
}

?>


<style>
    /* Aseguramos que el fondo del tbody, tr y td sea transparente */
    #tablaUsuarios tbody {
        background-color: transparent !important;
    }

    #tablaUsuarios tbody tr {
        background-color: transparent !important;
    }

    #tablaUsuarios tbody td {
        background-color: transparent !important;
    }

    /* Si prefieres darle un color gris claro */
    #tablaUsuarios tbody {
        background-color: #transparent !important;
        /* Gris claro */
    }

    #tablaUsuarios tbody tr {
        background-color: #transparent !important;
    }

    #tablaUsuarios tbody td {
        background-color: transparent !important;
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<div class="container my-3">
    <button title="Agregar Usuario" class="btn btn-secondary" onclick="location.href='nuevoUsuario.php'"><svg
            xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus"
            viewBox="0 0 16 16">
            <path
                d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
            <path fill-rule="evenodd"
                d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5" />
        </svg></button>
    <button class="btn btn-secondary" onclick="location.href='RegistrarClave.php'">Clave Maestra</button>
</div>

<table class="table table-bordered border-white scroll" id="tablaUsuarios">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Username</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Estado</th>
            <th>Tiempo de Bloqueo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                <td><?php echo htmlspecialchars($usuario['username']); ?></td>
                <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                <td><?php echo htmlspecialchars($usuario['rol']); ?></td>
                <td><?php echo htmlspecialchars($usuario['estado']); ?></td>
                <td>
                    <?php echo formatearFechaLegible($usuario['bloqueo_hasta']); ?>
                </td>
                <td>
                    <button class="btn btn-warning btn-sm editar-usuario" data-bs-toggle="modal"
                        data-bs-target="#modalEditarUsuario" data-username="<?php echo $usuario['username']; ?>">
                        ✏️ Editar
                    </button>
                    <button class="btn btn-danger btn-sm eliminar-usuario"
                        data-username="<?php echo $usuario['username']; ?>">
                        🗑️ Eliminar
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<!-- Modal para editar usuario -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarUsuarioLabel">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarUsuario">
                    <input type="hidden" id="editUsername" name="username">

                    <div class="mb-3">
                        <label for="editNombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="editNombre" name="nombre" required>
                    </div>

                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="editRol" class="form-label">Rol</label>
                        <select class="form-control" id="editRol" name="rol">
                            <option value="Administrador">Administrador</option>
                            <option value="Ventas">Ventas</option>
                            <option value="Soporte">Soporte</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editEstado" class="form-label">Estado</label>
                        <select class="form-control" id="editEstado" name="estado">
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editBloqueoHasta" class="form-label">Tiempo de Bloqueo</label>
                        <input type="datetime-local" class="form-control" id="editBloqueoHasta" name="bloqueo_hasta">
                        <small id="bloqueoTexto" class="form-text text-muted"></small>
                        <button type="button" class="btn btn-warning mt-2" id="eliminarBloqueo"
                            style="display:none;">Eliminar Tiempo de Bloqueo</button>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="guardarCambiosUsuario">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ingresar la clave maestra -->
<div class="modal fade" id="modalClaveMaestra" tabindex="-1" aria-labelledby="modalClaveLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalClaveLabel">Confirmación de Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Ingrese la clave maestra para confirmar la eliminación del usuario:</p>
                <input type="password" id="claveMaestra" class="form-control" placeholder="Clave maestra">
                <small class="text-danger d-none" id="errorClave">Clave incorrecta</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmarEliminar">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        var table = $('#tablaUsuarios').DataTable({
            "paging": true,       // Activa la paginación
            "searching": true,    // Activa el campo de búsqueda
            "ordering": true,     // Permite ordenar las columnas
            "info": true,         // Muestra información de la tabla
            "lengthMenu": [4],    // Número de registros por página
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

        var usuarioAEliminar = ""; // Guardamos el usuario que se quiere eliminar

        // Evento al hacer clic en el botón eliminar
        $(document).on("click", ".eliminar-usuario", function () {
            usuarioAEliminar = $(this).data("username");
            $("#claveMaestra").val(""); // Limpiar el campo de clave
            $("#errorClave").addClass("d-none"); // Ocultar error
            $("#modalClaveMaestra").modal("show");
        });

        // Evento para confirmar la eliminación con la clave
        $("#confirmarEliminar").click(function () {
            var claveMaestra = $("#claveMaestra").val().trim();

            if (claveMaestra === "") {
                $("#errorClave").text("Debe ingresar la clave").removeClass("d-none");
                return;
            }

            $.ajax({
                url: "verificar_clave.php",
                type: "POST",
                data: { clave: claveMaestra },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        // Clave correcta, proceder con la eliminación
                        eliminarUsuario(usuarioAEliminar);
                    } else {
                        $("#errorClave").text("Clave incorrecta").removeClass("d-none");
                    }
                },
                error: function () {
                    Swal.fire("Error", "Hubo un problema con la validación.", "error");
                }
            });
        });

        function eliminarUsuario(username) {
            $.ajax({
                url: "eliminar_usuario.php",
                type: "POST",
                data: { username: username },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Eliminado",
                            text: response.message,
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        });
                        table.row($(`button[data-username="${username}"]`).parents('tr')).remove().draw();
                        $("#modalClaveMaestra").modal("hide");
                    } else {
                        Swal.fire("Error", response.message, "error");
                    }
                },
                error: function () {
                    Swal.fire("Error", "No se pudo eliminar el usuario.", "error");
                }
            });
        }

        // Evento para editar usuario
        $(document).on("click", ".editar-usuario", function () {
            // Obtener el username desde el atributo data-username
            var username = $(this).data("username");

            $.ajax({
                url: "obtener_usuario.php", // El archivo PHP que obtendrá los datos
                type: "POST",
                data: { username: username },
                dataType: "json",
                success: function (data) {
                    if (data.error) {
                        Swal.fire("Error", data.error, "error");
                        return;
                    }

                    // Asignamos los valores del usuario en los campos del modal
                    $("#editUsername").val(data.username);
                    $("#editNombre").val(data.nombre);
                    $("#editEmail").val(data.email);
                    $("#editRol").val(data.rol);
                    $("#editEstado").val(data.estado);

                    // Verificar si hay fecha de bloqueo
                    if (data.bloqueo_hasta) {
                        // Si tiene fecha de bloqueo, mostramos el campo con la fecha
                        var bloqueoHasta = new Date(data.bloqueo_hasta);
                        $("#editBloqueoHasta").val(bloqueoHasta.toISOString().slice(0, 16)); // Formato ISO sin segundos
                        $("#eliminarBloqueo").show(); // Mostrar el botón para eliminar
                    } else {
                        // Si no tiene fecha de bloqueo, ocultamos el botón para eliminar
                        $("#editBloqueoHasta").val("");
                        $("#eliminarBloqueo").hide();
                        $("#editBloqueoHasta").prop("disabled", true); // Deshabilitar el campo
                    }

                    // Abrir el modal
                    $("#modalEditarUsuario").modal("show");
                },
                error: function () {
                    Swal.fire("Error", "Hubo un problema al cargar los datos del usuario.", "error");
                }
            });
        });

        // Cuando se haga clic en "Eliminar Tiempo de Bloqueo"
        $("#eliminarBloqueo").click(function () {
            $("#editBloqueoHasta").val(""); // Limpiar el campo de fecha
            $("#editBloqueoHasta").prop("disabled", true); // Deshabilitar el campo
            $("#eliminarBloqueo").hide(); // Ocultar el botón de eliminar

            // Establecer el campo bloqueo_hasta como null
            $("#editBloqueoHasta").data("bloqueo_hasta", null);  // Marcamos que no hay valor
        });

        // Función para convertir "dd-mm-yyyy hh:mm" a "yyyy-mm-dd HH:MM:SS"
        function convertirAFormatoSQL(fechaStr) {
            if (!fechaStr) return null;

            let partes = fechaStr.split(" ");
            let fecha = partes[0].split("-");
            let hora = partes[1].split(":");

            let anio = fecha[2];
            let mes = fecha[1];
            let dia = fecha[0];

            let horas = hora[0];
            let minutos = hora[1];

            return `${anio}-${mes}-${dia} ${horas}:${minutos}:00`;
        }

        // Guardar cambios en usuario
        $("#guardarCambiosUsuario").click(function () {
            // Verifica si el campo de bloqueo está deshabilitado o vacío, y en tal caso, asigna null
            let bloqueoInput = $("#editBloqueoHasta").prop("disabled") || $("#editBloqueoHasta").val().trim() === ""
                ? null  // Si el campo está deshabilitado o vacío, enviamos null
                : convertirAFormatoSQL($("#editBloqueoHasta").val());  // Si tiene valor, lo convertimos

            let formData = {
                username: $("#editUsername").val(),
                nombre: $("#editNombre").val(),
                email: $("#editEmail").val(),
                rol: $("#editRol").val(),
                estado: $("#editEstado").val(),
                bloqueo_hasta: bloqueoInput  // Enviar null si no hay fecha
            };

            $.ajax({
                url: "editar_usuario.php",
                type: "POST",
                data: formData,
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        Swal.fire("Éxito", "Usuario actualizado correctamente.", "success").then(() => location.reload());
                    } else {
                        Swal.fire("Error", response.message, "error");
                    }
                },
                error: function () {
                    Swal.fire("Error", "No se pudo actualizar el usuario.", "error");
                }
            });
        });

    });

</script>