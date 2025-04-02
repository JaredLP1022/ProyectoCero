<?php  
include_once dirname(__DIR__) . '../../config/db.php';  

$pdo = new db();  
$PDO = $pdo->conexion();  

// Obtener filtros del formulario  
$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : null;  
$anio = isset($_GET['anio']) ? (int)$_GET['anio'] : null;  
$producto_id = isset($_GET['producto']) ? (int)$_GET['producto'] : null;  

// Consultar los productos disponibles para el filtro
$query_productos = "SELECT id, description FROM producto";
$stmt_productos = $PDO->prepare($query_productos);
$stmt_productos->execute();
$productos_filtro = $stmt_productos->fetchAll(PDO::FETCH_ASSOC);

// Consulta base con JOIN para relacionar venta y producto
$query = "SELECT v.detalle_producto, SUM(v.cantidad) as total_vendido, SUM(v.total) as ingresos_totales, SUM(v.descuento) as total_descuentos
          FROM venta v
          JOIN producto p ON v.detalle_producto = p.description
          WHERE 1=1";  

// Filtrar por mes, año y producto si están definidos  
if ($mes) {  
    $query .= " AND MONTH(v.fechaV) = :mes";  
}  
if ($anio) {  
    $query .= " AND YEAR(v.fechaV) = :anio";  
}  
if ($producto_id) {  
    $query .= " AND p.id = :producto_id";  
}  

$query .= " GROUP BY v.detalle_producto ORDER BY total_vendido DESC";  

$stmt = $PDO->prepare($query);  

// Asignar parámetros si están establecidos  
if ($mes) {  
    $stmt->bindParam(':mes', $mes, PDO::PARAM_INT);  
}  
if ($anio) {  
    $stmt->bindParam(':anio', $anio, PDO::PARAM_INT);  
}  
if ($producto_id) {  
    $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);  
}  

$stmt->execute();  
$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);  

// Preparar datos para gráficos  
$productos = [];  
$ventas_cantidades = [];  
$ventas_ingresos = [];  
$ventas_descuentos = [];  // Array para almacenar los descuentos
$total_ingresos = 0;  
$total_descuentos = 0;  // Inicialización de total_descuentos
$producto_mas_vendido = null;  
$cantidad_mas_vendida = 0;  

foreach ($ventas as $venta) {  
    $total_ingresos += $venta['ingresos_totales'];  
    $total_descuentos += $venta['total_descuentos'];  // Acumulando el total de descuentos
    if ($venta['total_vendido'] > $cantidad_mas_vendida) {  
        $cantidad_mas_vendida = $venta['total_vendido'];  
        $producto_mas_vendido = $venta['detalle_producto'];  
    }  
    $productos[] = htmlspecialchars($venta['detalle_producto']);  
    $ventas_cantidades[] = $venta['total_vendido'];  
    $ventas_ingresos[] = $venta['ingresos_totales'];  
    $ventas_descuentos[] = $venta['total_descuentos'];  // Almacenamos los descuentos en el array
}  
?>   
<style>
    select, input[type="text"], input[type="date"], input[type="number"] {
        background: transparent;
        border: 2px solid white; /* Borde blanco */
        color: white; /* Color del texto */
        padding: 5px;
        border-radius: 5px;
    }

    select option {
        background: black; /* Fondo negro para opciones */
        color: white;
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">  

<div class="container my-3">  
    <h3 class="text-center fw-bold">Reporte de Ventas por Producto</h3>  
    <form method="GET">  
    <select name="mes">  
    <option value="">Mes</option>  
    <?php  
    $meses = [
        1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril", 5 => "Mayo", 6 => "Junio",
        7 => "Julio", 8 => "Agosto", 9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre"
    ];  

    foreach ($meses as $num => $nombre) {  
        echo "<option value='$num'>$nombre</option>";  
    }  
    ?>  
</select>  

        <select name="anio">  
            <option value="">Año</option>  
            <?php for ($y = date('Y'); $y >= 2000; $y--) { echo "<option value='$y'>$y</option>"; } ?>  
        </select>  
          <!-- Filtro para seleccionar un producto desde la tabla 'producto' -->
        <select name="producto">
            <option value="">Selecciona un Producto</option>
            <?php foreach ($productos_filtro as $producto): ?>
                <option value="<?php echo $producto['id']; ?>" <?php echo ($producto_id == $producto['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($producto['description']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filtrar</button>  
    </form>  

    <table class="table table-bordered" id="tablaVentas">  
        <thead>  
            <tr>  
                <th>Producto</th>  
                <th>Cantidad Vendida</th>  
                <th>Ingresos Totales</th>  
            </tr>  
        </thead>  
        <tbody>  
            <?php foreach ($ventas as $venta): ?>  
                <tr>  
                    <td><?php echo htmlspecialchars($venta['detalle_producto']); ?></td>  
                    <td><?php echo number_format($venta['total_vendido']); ?></td>  
                    <td><?php echo '$' . number_format($venta['ingresos_totales'], 2); ?></td>  
                </tr>  
            <?php endforeach; ?>  
        </tbody>  
    </table>  
</div>  

<button id="toggleStats" class="btn btn-primary my-3">Ver Estadísticas</button>

<div id="estadisticas" class="container my-4 p-4 border rounded" style="display: none;">
    <h4>Total Ganado: $<?php echo number_format($total_ingresos, 2); ?> MXN</h4>
    <h4>Total Descuento Aplicado: $<?php echo number_format($total_descuentos, 2); ?> MXN</h4>
    <h4>Producto Más Vendido: <?php echo htmlspecialchars($producto_mas_vendido); ?> (<?php echo $cantidad_mas_vendida; ?> vendidos)</h4>   

    <div class="row">  
        <div class="col-md-6">  
            <canvas id="graficoBarras"></canvas>  
        </div>  
        <div class="col-md-6">  
            <canvas id="graficoPastel"></canvas>  
        </div>  
    </div> 
    
    <form action="exportar_excel.php" method="post">
    <button class="btn btn-primary" type="submit">Exportar a Excel</button>
</form>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>  
<script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>  
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>  
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>  
<script>  
document.getElementById("toggleStats").addEventListener("click", function() {
    var estadisticas = document.getElementById("estadisticas");
    if (estadisticas.style.display === "none") {
        estadisticas.style.display = "block";
        this.textContent = "Ocultar Estadísticas";
    } else {
        estadisticas.style.display = "none";
        this.textContent = "Ver Estadísticas";
    }
});

$(document).ready(function() {
    $('#tablaVentas').DataTable({
        language: {
            "sEmptyTable": "No hay datos disponibles en la tabla",
            "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
            "sInfoEmpty": "Mostrando 0 a 0 de 0 entradas",
            "sInfoFiltered": "(filtrado de _MAX_ entradas totales)",
            "sLengthMenu": "Mostrar _MENU_ entradas",
            "sLoadingRecords": "Cargando...",
            "sProcessing": "Procesando...",
            "sSearch": "Buscar:",
            "sZeroRecords": "No se encontraron resultados",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": activar para ordenar la columna de manera descendente"
            }
        }
    });
});  

var ctxBarras = document.getElementById('graficoBarras').getContext('2d');  
var graficoBarras = new Chart(ctxBarras, {  
    type: 'bar',  
    data: {  
        labels: <?php echo json_encode($productos); ?>,  
        datasets: [{  
            label: 'Cantidad Vendida',  
            data: <?php echo json_encode($ventas_cantidades); ?>,  
            backgroundColor: '#007bff'  
        }]  
    },  
    options: {  
        responsive: true,  
        plugins: {  
            legend: {  
                labels: {  
                    color: 'white' // Color blanco para la leyenda  
                }  
            },  
            tooltip: {  
                titleColor: 'white',  
                bodyColor: 'white'  
            }  
        },  
        scales: {  
            x: {  
                ticks: { color: 'white' } // Color blanco para etiquetas del eje X  
            },  
            y: {  
                ticks: { color: 'white' } // Color blanco para etiquetas del eje Y  
            }  
        }  
    }  
});  

var ctxPastel = document.getElementById('graficoPastel').getContext('2d');  
var graficoPastel = new Chart(ctxPastel, {  
    type: 'pie',  
    data: {  
        labels: <?php echo json_encode($productos); ?>,  
        datasets: [{  
            data: <?php echo json_encode($ventas_ingresos); ?>,  
            backgroundColor: ['#007bff', '#0056b3', '#003366', '#3399ff', '#66b3ff']  
        }]  
    },  
    options: {  
        responsive: true,  
        plugins: {  
            legend: {  
                labels: {  
                    color: 'white' // Color blanco para la leyenda  
                }  
            },  
            tooltip: {  
                titleColor: 'white',  
                bodyColor: 'white'  
            }  
        }  
    }  
});  
</script>
