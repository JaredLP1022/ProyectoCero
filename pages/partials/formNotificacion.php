
<div class="container position-relative col-md-6">
    <h3>Enviar una notificación a los empleados de Ventas o Soporte Técnico de Horizontech</h3>
    <form id="notificacionesForm">
        <div class="mb-3">
            <label for="rol" class="form-label">Seleccionar Departamento</label>
            <select class="form-control" id="rol" name="rol" required>
                <option value="">Seleccionar Departamento</option>
                <option value="Ventas">Ventas</option>
                <option value="Soporte">Soporte Técnico</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="mensaje" class="form-label">Ingrese el mensaje</label>
            <textarea name="mensaje" class="form-control" id="mensaje" required></textarea>
        </div>
        <button type="submit" class="btn btn-lg btn-primary w-100">Enviar Aviso</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$("#notificacionesForm").submit(function (event) {
    event.preventDefault();
    let formData = $(this).serialize();
    $.post("procesar_notificacion.php", formData, function (response) {
    console.log("Respuesta del servidor antes de procesar:", response);

    if (response.success) {  
        Swal.fire({
            title: "Éxito",
            text: response.success,
            icon: "success",
            confirmButtonText: "OK"
        });
        $("#notificacionesForm")[0].reset();
    } else {
        Swal.fire({
            title: "Error",
            text: response.error || "Error desconocido",
            icon: "error",
            confirmButtonText: "OK"
        });
    }
}, "json");  

    
});

</script>