<?php
include_once dirname(__DIR__) . '../../config/db.php';

$pdo = new db();
$PDO = $pdo->conexion();

// Obtener ventas para el select
$queryVentas = $PDO->query("SELECT v.id_venta, c.nombre AS cliente, v.detalle_producto FROM venta v INNER JOIN cliente c ON v.id_cliente = c.id");
$queryVentas->execute();
$ventas = $queryVentas->fetchAll(PDO::FETCH_ASSOC);

// Obtener todos los problemas
$query_problemas = "SELECT id_problema, descripcionProblem FROM problema";
$stmt_problemas = $PDO->prepare($query_problemas);
$stmt_problemas->execute();
$problemas = $stmt_problemas->fetchAll(PDO::FETCH_ASSOC);

?>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<div class="contenedorPanel">
    <div class="botonCss">
        <button title="Volver" class="border-white botonCerrar ColorLetra" type="button" onclick="location.href='tickets.php'">
            ⬅️ Volver a Tickets
        </button>
    </div>
    <div class="tituloCss">
        <h4 class="text-center ColorLetra">Agregar Nuevo Ticket</h4>
    </div>
</div>

<hr class="bg-white">

<form id="formAgregarTicket" action="guardarTicket.php" method="POST">
    <p>Detalles del Ticket:</p>
    <div class="form-row row">
        <!-- Selección de Venta -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="venta">Venta Asociada:</label>
                <select class="form-control" name="id_venta" required>
                    <option value="">Selecciona una venta</option>
                    <?php foreach ($ventas as $venta): ?>
                        <option value="<?php echo $venta['id_venta']; ?>">
                            <?php echo $venta['cliente'] . " - " . $venta['detalle_producto']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Fecha (automática y deshabilitada) -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="fecha">Fecha:</label>
                <input class="form-control" type="date" name="fecha" value="<?php echo date('Y-m-d'); ?>" readonly required>
            </div>
        </div>
    </div>

    <div class="form-row row">
        <!-- Descripción del problema -->
        <div class="col-md-12">
            <div class="form-group">
                <label for="descripcion">Descripción del Problema:</label>
                <select class="form-control" name="descripcionProblema" id="descripcionProblema">

                            <?php foreach ($problemas as $problema): ?>
                                <option value="<?php echo $problema['id_problema']; ?>">
                                    <?php echo htmlspecialchars($problema['descripcionProblem']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
            </div>
        </div>
    </div>

    <div class="form-row row">
        <!-- Prioridad -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="prioridad">Prioridad:</label>
                <select class="form-control" name="prioridad" required>
                    <option value="Baja">Baja</option>
                    <option value="Media">Media</option>
                    <option value="Alta">Alta</option>
                </select>
            </div>
        </div>

        <!-- Estado -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="estado">Estado:</label>
                <select class="form-control" name="estado" required>
                    <option value="En proceso">En proceso</option>
                    <option value="Cerrado">Cerrado</option>
                </select>
            </div>
        </div>
    </div>

    <br>
    <div class="container">
        <button class="btn btn-primary btn-lg w-100" type="button" onclick="confirmarGuardado()">Guardar Ticket</button>
    </div>
</form>

<script>
function confirmarGuardado() {
    Swal.fire({
        title: '¿Guardar Ticket?',
        text: "Se registrará un nuevo ticket en el sistema.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, guardar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("formAgregarTicket").submit();
        }
    });
}
</script>
