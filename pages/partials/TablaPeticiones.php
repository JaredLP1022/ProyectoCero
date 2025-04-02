<?php
include("C:/xampp/htdocs/ProyectoCero/config/db.php");

if (!isset($_SESSION['user_id'])) {
    echo "Debe iniciar sesión para consultar sus peticiones.";
    exit;
}

try {
    $pdo = new db();
    $PDO = $pdo->conexion();

    // Obtener el ID del usuario logeado
    $id_usuario = $_SESSION['user_id'];

    // Consulta para obtener las peticiones solo del usuario logeado
    $sql = "SELECT * FROM peticiones WHERE id_usuario = :id_usuario";
    $stmt = $PDO->prepare($sql);
    $stmt->bindParam(':id_usuario', $id_usuario); // Corregido de :usuario_id a :id_usuario
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

<!-- Estilos adicionales para la tabla -->
<style>
    /* Aseguramos que el fondo del tbody, tr y td sea transparente */
    #tablaPeticiones tbody {
        background-color: transparent !important;
    }

    #tablaPeticiones tbody tr {
        background-color: transparent !important;
    }

    #tablaPeticiones tbody td {
        background-color: transparent !important;
    }

    /* Si prefieres darle un color gris claro */
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
    <h2 class="text-center">Consulta de Peticiones</h2>

    <!-- Tabla para mostrar las peticiones -->
    <table class="table table-bordered border-white scroll" id="tablaPeticiones">
        <thead>
            <tr>
                <th>Petición</th>
                <th>Departamento</th>
                <th>Fecha de Creación</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Fecha Necesaria</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($peticiones as $peticion): ?>
                <tr>
                    <td><?php echo htmlspecialchars($peticion['peticion']); ?></td>
                    <td><?php echo htmlspecialchars($peticion['departamento']); ?></td>
                    <td><?php echo date("d/m/Y", strtotime($peticion['fecha_creacion'])); ?></td>
                    <td><?php echo htmlspecialchars($peticion['estado']); ?></td>
                    <td><?php echo htmlspecialchars($peticion['prioridad']); ?></td>
                    <td><?php echo date("d/m/Y", strtotime($peticion['fecha_necesita'])); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    // Inicializar DataTables con paginación y otras configuraciones
    $(document).ready(function() {
        $('#tablaPeticiones').DataTable({
            "paging": true, // Activar paginación
            "lengthChange": false, // Desactivar la opción de cambiar el número de registros por página
            "searching": true, // Activar búsqueda
            "ordering": true, // Activar ordenamiento
            "info": true, // Mostrar información sobre el total de registros
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "No se encontraron registros",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles",
                "infoFiltered": "(filtrado de _MAX_ registros totales)"
            }
        });
    });
</script>
