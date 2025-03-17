<?php
include_once dirname(__DIR__) . '../../config/db.php';

// Conexión a la base de datos
$pdo = new db();
$PDO = $pdo->conexion();

// Variables iniciales
$totalIngresos = 0;
$masVendido = null;
$menosVendido = null;
$clienteMasComprador = null;  // Cliente que más ha comprado
$mes = $anio = null;
$productos = [];
$colores = [];

// Comprobamos si se ha enviado el formulario con el año y mes
if (isset($_POST['anio']) && isset($_POST['mes'])) {
    $mes = $_POST['mes']; // Mes (ej. '03')
    $anio = $_POST['anio']; // Año (ej. '2025')

    // Consultar el total de ingresos
    $sqlIngresos = "SELECT SUM(total) AS total_ingresos FROM venta WHERE YEAR(fechaV) = :anio AND MONTH(fechaV) = :mes";
    $stmtIngresos = $PDO->prepare($sqlIngresos);
    $stmtIngresos->bindParam(':anio', $anio);
    $stmtIngresos->bindParam(':mes', $mes);
    $stmtIngresos->execute();
    $totalIngresos = $stmtIngresos->fetch()['total_ingresos'];

    // Consultar el artículo más vendido
    $sqlMasVendido = "SELECT detalle_producto, SUM(cantidad) AS total_cantidad FROM venta WHERE YEAR(fechaV) = :anio AND MONTH(fechaV) = :mes GROUP BY detalle_producto ORDER BY total_cantidad DESC LIMIT 1";
    $stmtMasVendido = $PDO->prepare($sqlMasVendido);
    $stmtMasVendido->bindParam(':anio', $anio);
    $stmtMasVendido->bindParam(':mes', $mes);
    $stmtMasVendido->execute();
    $masVendido = $stmtMasVendido->fetch();

    // Consultar el artículo menos vendido
    $sqlMenosVendido = "SELECT detalle_producto, SUM(cantidad) AS total_cantidad FROM venta WHERE YEAR(fechaV) = :anio AND MONTH(fechaV) = :mes GROUP BY detalle_producto ORDER BY total_cantidad ASC LIMIT 1";
    $stmtMenosVendido = $PDO->prepare($sqlMenosVendido);
    $stmtMenosVendido->bindParam(':anio', $anio);
    $stmtMenosVendido->bindParam(':mes', $mes);
    $stmtMenosVendido->execute();
    $menosVendido = $stmtMenosVendido->fetch();

    // Consultar el cliente que más ha comprado
    $sqlClienteMasComprador = "SELECT v.id_cliente, c.nombre, SUM(v.total) AS total_comprado
                                FROM venta v
                                JOIN cliente c ON v.id_cliente = c.id
                                WHERE YEAR(v.fechaV) = :anio AND MONTH(v.fechaV) = :mes
                                GROUP BY v.id_cliente
                                ORDER BY total_comprado DESC LIMIT 1";
    $stmtClienteMasComprador = $PDO->prepare($sqlClienteMasComprador);
    $stmtClienteMasComprador->bindParam(':anio', $anio);
    $stmtClienteMasComprador->bindParam(':mes', $mes);
    $stmtClienteMasComprador->execute();
    $clienteMasComprador = $stmtClienteMasComprador->fetch();

    // Consultar todos los productos vendidos en ese mes
    $sqlProductos = "SELECT detalle_producto, SUM(cantidad) AS total_cantidad FROM venta WHERE YEAR(fechaV) = :anio AND MONTH(fechaV) = :mes GROUP BY detalle_producto";
    $stmtProductos = $PDO->prepare($sqlProductos);
    $stmtProductos->bindParam(':anio', $anio);
    $stmtProductos->bindParam(':mes', $mes);
    $stmtProductos->execute();
    $productos = $stmtProductos->fetchAll(PDO::FETCH_ASSOC);

    // Generar colores aleatorios para cada producto
    foreach ($productos as $producto) {
        // Generar un color único en formato rgb
        $colores[] = 'rgb(' . rand(0, 255) . ',' . rand(0, 255) . ',' . rand(0, 255) . ')';
    }
}
?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script> <!-- jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.20/jspdf.plugin.autotable.min.js"></script>


<div class="contenedorPanel">
    <div class="botonCss">
        <button title="Volver" class=" border-white botonCerrar ColorLetra" type="submit"
            onclick="location.href='PanelAnalisisDatos.php'">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path
                    d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z" />
            </svg>
        </button>
    </div>
    <div class="tituloCss">
        <h4 class="text-center ColorLetra">Generar reporte de ventas por mes</h4>
    </div>
    <div class="botonCss">
        <button title="Home" class=" border-white botonCerrar ColorLetra" type="submit"
            onclick="location.href='Panel-Administrador.php'"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                height="16" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                <path
                    d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z" />
            </svg></button>
    </div>

</div>
<hr>
<br>

<div class="report-container">
        <h2>Ingrese en el formulario la fecha del reporte de mes que desea generar</h2>

        <!-- Formulario para seleccionar año y mes -->
        <form method="POST" action="">
            <label for="anio">Año:</label>
            <select name="anio" id="anio" required>
                <option value="">Seleccionar Año</option>
                <?php
                // Obtener los años disponibles desde la base de datos
                $sqlAnios = "SELECT DISTINCT YEAR(fechaV) AS anio FROM venta ORDER BY anio DESC";
                $stmtAnios = $PDO->prepare($sqlAnios);
                $stmtAnios->execute();
                while ($row = $stmtAnios->fetch()) {
                    $selected = ($row['anio'] == $anio) ? 'selected' : '';
                    echo "<option value='{$row['anio']}' {$selected}>{$row['anio']}</option>";
                }
                ?>
            </select>

            <label for="mes">Mes:</label>
            <select name="mes" id="mes" required>
                <option value="">Seleccionar Mes</option>
                <option value="01" <?= (isset($mes) && $mes == '01') ? 'selected' : ''; ?>>Enero</option>
                <option value="02" <?= (isset($mes) && $mes == '02') ? 'selected' : ''; ?>>Febrero</option>
                <option value="03" <?= (isset($mes) && $mes == '03') ? 'selected' : ''; ?>>Marzo</option>
                <option value="04" <?= (isset($mes) && $mes == '04') ? 'selected' : ''; ?>>Abril</option>
                <option value="05" <?= (isset($mes) && $mes == '05') ? 'selected' : ''; ?>>Mayo</option>
                <option value="06" <?= (isset($mes) && $mes == '06') ? 'selected' : ''; ?>>Junio</option>
                <option value="07" <?= (isset($mes) && $mes == '07') ? 'selected' : ''; ?>>Julio</option>
                <option value="08" <?= (isset($mes) && $mes == '08') ? 'selected' : ''; ?>>Agosto</option>
                <option value="09" <?= (isset($mes) && $mes == '09') ? 'selected' : ''; ?>>Septiembre</option>
                <option value="10" <?= (isset($mes) && $mes == '10') ? 'selected' : ''; ?>>Octubre</option>
                <option value="11" <?= (isset($mes) && $mes == '11') ? 'selected' : ''; ?>>Noviembre</option>
                <option value="12" <?= (isset($mes) && $mes == '12') ? 'selected' : ''; ?>>Diciembre</option>
            </select>

            <button type="submit">Generar Reporte</button>
        </form>
<br>
      <div class="container row ">
      <?php if (isset($mes) && isset($anio)): ?>
           <div class="form-goup col-xl-6">
             <!-- Mostrar los datos del reporte -->
             <h3>Reporte de Ventas - <?= $mes ?>/<?= $anio ?></h3>
            <p><strong>Total de Ingresos: $</strong><?=$totalIngresos ?> MXN</p>
            <p><strong>Artículo Más Vendido:</strong> <?= $masVendido['detalle_producto'] ?> (<?= $masVendido['total_cantidad'] ?> unidades)</p>
            <p><strong>Artículo Menos Vendido:</strong> <?= $menosVendido['detalle_producto'] ?> (<?= $menosVendido['total_cantidad'] ?> unidades)</p>
 <!-- Mostrar el cliente que más ha comprado -->
 <?php if ($clienteMasComprador): ?>
                <p><strong>Cliente que Más Ha Comprado:</strong> <?= $clienteMasComprador['nombre'] ?> (Total Comprado: <?= $clienteMasComprador['total_comprado'] ?> MXN)</p>
            <?php endif; ?>

           </div>

          <div class="form-group col-xl-6">
              <!-- Gráfico de las ventas -->
              <canvas id="ventasChart"></canvas>

          </div>
           <div class="d-grip gap-2 col-6 mx-auto">
             <!-- Botón para exportar a PDF -->
             <button class="btn btn-success" id="exportPdf" onclick="generatePDF()">Exportar a PDF</button>
           </div>
        <?php endif; ?>
      </div>
    </div>

    <script>
        <?php if (isset($mes) && isset($anio)): ?>
            // Preparar datos para el gráfico
            const productos = <?php echo json_encode(array_column($productos, 'detalle_producto')); ?>;
            const cantidades = <?php echo json_encode(array_column($productos, 'total_cantidad')); ?>;
            const colores = <?php echo json_encode($colores); ?>;

            // Generación del gráfico con los productos y cantidades
            const ctx = document.getElementById('ventasChart').getContext('2d');
            const ventasChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: productos, // Productos desde la base de datos
                    datasets: [{
                        label: 'Ventas por Producto',
                        data: cantidades, // Cantidades desde la base de datos
                        backgroundColor: colores, // Colores únicos para cada barra
                        borderColor: colores,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: 'white' // Cambiar color de los valores del eje Y
                            }
                        },
                        x: {
                            ticks: {
                                color: 'white' // Cambiar color de las etiquetas del eje X
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: 'white' // Cambiar color de la leyenda
                            }
                        }
                    }
                }
            });
        <?php endif; ?>

          // Función para exportar el reporte a PDF
    function generatePDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        // Añadir un título al reporte
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(20);
        doc.text('Reporte de Ventas', 105, 20, null, null, 'center');

        // Añadir una línea de separación
        doc.setLineWidth(0.5);
        doc.line(10, 25, 200, 25);

        // Información de la fecha
        doc.setFontSize(12);
        doc.setFont('helvetica', 'normal');
        doc.text('Fecha: ' + new Date().toLocaleDateString(), 150, 30, null, null, 'right');
        
        // Espacio para el total de ingresos
        doc.text('Total de Ingresos: $' + '<?= $totalIngresos ?>' + ' MXN', 20, 40);
        
        // Espacio para el artículo más vendido
        doc.text('Artículo Más Vendido: ' + '<?= $masVendido['detalle_producto'] ?>', 20, 50);
        
        // Espacio para el artículo menos vendido
        doc.text('Artículo Menos Vendido: ' + '<?= $menosVendido['detalle_producto'] ?>', 20, 60);

        // Espacio para el cliente que más ha comprado
        doc.text('Cliente que Más Ha Comprado: ' + '<?= $clienteMasComprador['nombre'] ?>', 20, 70);
        
        // Agregar el detalle de ventas por producto
        let yPosition = 90;
        doc.setFont('helvetica', 'bold');
        doc.text('Detalles de Ventas por Producto', 20, yPosition);
        yPosition += 10;
        
        doc.setFont('helvetica', 'normal');
        
        // Generar tabla con los productos y sus cantidades
        doc.autoTable({
            startY: yPosition,
            head: [['Producto', 'Cantidad Vendida']],
            body: <?php echo json_encode(array_map(function($producto) {
                return [$producto['detalle_producto'], $producto['total_cantidad']];
            }, $productos)); ?>,
            theme: 'grid',
            headStyles: {
                fillColor: [0, 0, 0],  // Color de fondo de los encabezados
                textColor: [255, 255, 255],  // Color de texto de los encabezados
                fontSize: 12,
                font: 'helvetica',
                halign: 'center'
            },
            bodyStyles: {
                fontSize: 10,
                font: 'helvetica',
                halign: 'center'
            },
            columnStyles: {
                0: { cellWidth: 100 },
                1: { cellWidth: 50 }
            },
            margin: { top: 10, left: 20, right: 20 },
        });

        // Añadir pie de página
        doc.setFontSize(8);
        doc.text('Generado por Sistema de Ventas - ProyectoCero', 105, doc.internal.pageSize.height - 10, null, null, 'center');

        // Guardar el archivo PDF
        doc.save('reporte-ventas.pdf');
    }
    </script>