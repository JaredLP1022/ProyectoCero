<?php
include("C:/xampp/htdocs/ProyectoCero/config/db.php");
$pdo = new db();
$PDO = $pdo->conexion();

// Obtener la lista de clientes
$query = $PDO->query("SELECT id, nombre FROM cliente ORDER BY nombre ASC");
$clientes = $query->fetchAll(PDO::FETCH_ASSOC);

// Verificar si hay resultados
if (empty($clientes)) {
    echo "<script>alert('No hay clientes registrados en la base de datos');</script>";
}
?>


<?php if (isset($_GET['mensaje'])): ?>
    <script>
        // Verifica si el mensaje es de éxito o error
        var mensaje = "<?php echo $_GET['mensaje']; ?>";
        if (mensaje === "success") {
            Swal.fire({
                title: '¡Éxito!',
                text: 'El reporte se generó correctamente.',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        } else if (mensaje === "error") {
            Swal.fire({
                title: '¡Error!',
                text: 'No se encontraron resultados para el reporte.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    </script>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<h2>Buscar Reporte por Cliente</h2>
<form action="exportarProductosCliente.php" method="POST">
    <label for="clienteID">Selecciona un Cliente:</label>
    <select name="clienteID" required>
        <option value="">-- Seleccione --</option>
        <?php foreach ($clientes as $cliente): ?>
            <option value="<?= $cliente['id'] ?>"><?= htmlspecialchars($cliente['nombre']) ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" name="genera">Generar Reporte</button>
</form>