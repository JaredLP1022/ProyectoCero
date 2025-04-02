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

<div class="contenedorPanel">
    <div class="botonCss">
        <button title="Volver" class=" border-white botonCerrar ColorLetra" type="submit"
            onclick="location.href='ventas.php'">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path
                    d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z" />
            </svg>
        </button>
    </div>
    <br>
    <div class="tituloCss">
        <h4 class="text-center ColorLetra">Generando Reporte de clientes</h4>
    </div>
    <div class="botonCss">
        <button title="Home" class=" border-white botonCerrar ColorLetra" type="submit"
            onclick="location.href='Panel-Administrador.php'"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                height="16" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                <path
                    d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z" />
            </svg></button>
    </div>
<br>
</div>

<form action="AccionesClientes/reporteAccionC.php" method="POST" class="mt-4" >
    <label for="">Generar reporte entre las fechas seleccionadas</label>
    <br>
    <hr class="bg-white">
    <div class="form-row row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="">Ingresa la primera fecha de tu busqueda</label>
                <input type="date" name="fechaPrimera" required>
            </div>
        </div>
        <div class="col-md-6">
            <label for="">Ingresa la segunda fecha de tu busqueda</label>
            <input type="date" name="fechaSegunda" required>
        </div>
    </div>
    <br>
    <div class="container">
        <button class="btn btn-primary btn-lg w-100" type="submit" name="genera">Hacer Reporte de Ventas</button>
    </div>

</form>
