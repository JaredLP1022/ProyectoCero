<?php
include("C:/xampp/htdocs/ProyectoCero/config/db.php"); // Tu archivo de conexión a la base de datos
$pdo = new db();
$PDO = $pdo->conexion();
$result = [];

// Verificamos si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha_inicio = $_POST["fecha_inicio"];
    $fecha_fin = $_POST["fecha_fin"];

    // Consulta SQL para obtener los clientes dentro del rango de fechas (sin LIMIT)
    $comando = $PDO->prepare("SELECT * FROM cliente WHERE fechaR BETWEEN :fecha_inicio AND :fecha_fin");
    $comando->bindParam(':fecha_inicio', $fecha_inicio);
    $comando->bindParam(':fecha_fin', $fecha_fin);
    $comando->execute();

    $result = $comando->fetchAll(PDO::FETCH_ASSOC);
}
?>
<h3>Generar reporte de clientes por fecha de registro</h3>
<br>
<form id="formBuscarClientes" method="POST" action="BuscarClientesF.php" class="row mb-4">
    <div class="col-md-4">
        <label for="fecha_inicio" class="form-label text-white">Fecha de inicio:</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio"
            class="form-control bg-transparent text-white border-white" required>
    </div>
    <div class="col-md-4">
        <label for="fecha_fin" class="form-label text-white">Fecha de fin:</label>
        <input type="date" id="fecha_fin" name="fecha_fin" class="form-control bg-transparent text-white border-white"
            required>
    </div>
    <div class="col-md-4 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">Buscar</button>
    </div>
</form>
<hr>
<br>
<!-- Mostrar resultados de la búsqueda -->
<div class="container row">
    <div class="col-md-6">
        <h2>Resultados de la Búsqueda</h2>
    </div>
    <div class="col-md-6"> <button id="descargarPDF" class="btn btn-success">Descargar Reporte en PDF</button></div>
</div>
<table id="tablaClientes" class="table table-bordered border-white scroll">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Dirección</th>
            <th>Fecha Registro</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($result as $row) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                <td><?php echo htmlspecialchars($row['direccion']); ?></td>
                <td><?php echo htmlspecialchars($row['fechaR']); ?></td>
                <td><?php echo htmlspecialchars($row['estado']); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<!-- Incluir jsPDF y autoTable -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<!-- Incluir CSS de DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">

<!-- Incluir jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Incluir JS de DataTables -->
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<!-- Incluir SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        $('#tablaClientes').DataTable({
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "No se encontraron resultados",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles",
                "infoFiltered": "(filtrado de _MAX_ registros en total)",
                "search": "Buscar:",
                "paginate": {
                    "first": "Primera",
                    "last": "Última",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "pageLength": 5,  // Número de resultados por página
            "lengthChange": false,  // Desactivar la opción de cambiar el número de registros por página
        });
    });

    // Almacenar las fechas seleccionadas en sessionStorage
    document.getElementById("formBuscarClientes").addEventListener("submit", function (event) {
        let fechaInicio = document.getElementById("fecha_inicio").value;
        let fechaFin = document.getElementById("fecha_fin").value;

        if (!fechaInicio || !fechaFin) {
            // Usar SweetAlert2 para mostrar un mensaje
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: 'Por favor, selecciona ambas fechas.',
                confirmButtonColor: '#3085d6',
                background: '#333',
                color: '#fff'
            });
            event.preventDefault();
            return;
        }

        if (fechaInicio > fechaFin) {
            // Usar SweetAlert2 para mostrar un mensaje
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: 'La fecha de inicio no puede ser mayor que la fecha de fin.',
                confirmButtonColor: '#3085d6',
                background: '#333',
                color: '#fff'
            });
            event.preventDefault();
        } else {
            // Guardar las fechas en sessionStorage para usarlas después
            sessionStorage.setItem('fechaInicio', fechaInicio);
            sessionStorage.setItem('fechaFin', fechaFin);
        }
    });

    document.getElementById('descargarPDF').addEventListener('click', function () {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ orientation: "landscape" });

    // Obtener la instancia de DataTables
    let table = $('#tablaClientes').DataTable();

    // Obtener TODOS los datos de DataTables sin importar la paginación
    let allData = table.rows().data().toArray();

    if (allData.length === 0) {
        alert("No hay datos para generar el PDF.");
        return;
    }

    let data = [];

    // Recuperar las fechas de sessionStorage
    let fechaInicio = sessionStorage.getItem('fechaInicio') || "No especificado";
    let fechaFin = sessionStorage.getItem('fechaFin') || "No especificado";

    // Función para formatear la fecha en "dd de mes de yyyy"
    function formatDate(dateStr) {
        if (!dateStr) return "No especificado";

        // Convertir la fecha a un objeto Date
        const date = new Date(dateStr);

        // Ajustar la fecha para que no se vea afectada por la zona horaria
        date.setMinutes(date.getMinutes() + date.getTimezoneOffset()); // Ajusta según la zona horaria local

        // Formato dd de mes de yyyy
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return date.toLocaleDateString('es-ES', options);
    }

    let fechaInicioFormateada = formatDate(fechaInicio);
    let fechaFinFormateada = formatDate(fechaFin);

    // Extraer datos de TODAS las filas
    allData.forEach((row, index) => {
        let rowData = [
            index + 1, // Número de fila
            row[0] || 'N/A', // Nombre
            row[1] || 'N/A', // Email
            row[2] || 'N/A', // Teléfono
            row[3] || 'N/A', // Dirección
            formatDate(row[4]) || 'N/A', // Fecha Registro formateada
            row[5] || 'N/A'  // Estado
        ];
        data.push(rowData);
    });

    // Agregar título
    doc.setFontSize(16);
    doc.text('Reporte de Clientes', 14, 10);

    // Mostrar rango de fechas en el PDF
    doc.setFontSize(12);
    doc.text(`Fecha de inicio: ${fechaInicioFormateada}`, 14, 20);
    doc.text(`Fecha de fin: ${fechaFinFormateada}`, 14, 27);

    // Generar tabla con autoTable
    doc.autoTable({
        startY: 35,
        head: [['#', 'Nombre', 'Email', 'Teléfono', 'Dirección', 'Fecha Registro', 'Estado']],
        body: data,
        theme: 'grid',
        styles: { fontSize: 10 },
        headStyles: { fillColor: [0, 123, 255], textColor: 255 },
        columnStyles: { 0: { cellWidth: 10 }, 3: { cellWidth: 40 } }, // Ajuste de columnas
    });

    // Descargar PDF
    doc.save('reporte_clientes.pdf');
});

</script>