<?php
include_once dirname(__DIR__) . '../../config/db.php';

$pdo = new db();
$PDO = $pdo->conexion();

// Obtener lista de países
$paises_query = "SELECT nombre FROM pais";
$paises_stmt = $PDO->query($paises_query);
$paises_lista = $paises_stmt->fetchAll(PDO::FETCH_COLUMN);

// Obtener datos de clientes
$clientes_query = "SELECT nombre, direccion FROM cliente WHERE estado = 'Activo'";
$clientes_stmt = $PDO->prepare($clientes_query);
$clientes_stmt->execute();
$clientes = $clientes_stmt->fetchAll(PDO::FETCH_ASSOC);

// Función para separar dirección y país
function separarDireccion($direccion, $paises_lista)
{
    foreach ($paises_lista as $pais) {
        if (strpos($direccion, $pais) !== false) {
            return [
                'direccion' => trim(str_replace($pais, '', $direccion)),
                'pais' => $pais
            ];
        }
    }
    return ['direccion' => $direccion, 'pais' => 'Desconocido'];
}
?>

<!-- Filtros de búsqueda -->
<div class="row mb-6">
    <div class="col-md-4">
        <select id="filtroPais" class="form-select">
            <option value="">Selecciona un País</option>
            <!-- Los países se cargarán dinámicamente aquí -->
        </select>
    </div>
    <div class="col-md-4">
        <button id="btnFiltrar" class="btn btn-primary">Filtrar</button>
    </div>
</div>

<table id="tablaClientes" class="table table-bordered border-white">
    <thead>
        <tr>
            <th>Nombre del cliente</th>
            <th>Dirección</th>
            <th>País / Región</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($clientes as $row):
            $direccionSeparada = separarDireccion($row['direccion'], $paises_lista);
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                <td><?php echo htmlspecialchars($direccionSeparada['direccion']); ?></td>
                <td><?php echo htmlspecialchars($direccionSeparada['pais']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Estilos de DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<!-- Incluir SweetAlert2 desde CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        var table = $('#tablaClientes').DataTable({
            "pageLength": 10,
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "No se encontraron resultados",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles",
                "infoFiltered": "(filtrado de _MAX_ registros en total)",
                "search": "Buscar:",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            }
        });

        // Obtener países únicos de la tabla
        var paises = [];
        table.rows().every(function() {
            var data = this.data();
            var pais = data[2]; // Cambia el índice si es necesario para apuntar a la columna correcta

            if (paises.indexOf(pais) === -1 && pais) {
                paises.push(pais);
            }
        });

        // Agregar los países al filtro
        paises.forEach(function(pais) {
            $('#filtroPais').append('<option value="' + pais + '">' + pais + '</option>');
        });

        // Filtrar la tabla al hacer clic en el botón "Filtrar"
        $('#btnFiltrar').on('click', function() {
            var filtroPais = $('#filtroPais').val().toLowerCase(); // Obtener valor del filtro de país

            // Filtrar las filas de la tabla
            table.rows().every(function() {
                var data = this.data();
                var pais = data[2] ? data[2].toLowerCase() : '';  // Asegurarse de que data[2] no sea undefined

                // Verifica si las filas coinciden con los filtros
                if (filtroPais === '' || pais.includes(filtroPais)) {
                    this.node().style.display = ''; // Muestra la fila
                } else {
                    this.node().style.display = 'none'; // Oculta la fila
                }
            });
        });
    });
</script>
