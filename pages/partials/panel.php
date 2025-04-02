<?php
$rol = $_SESSION['rol']; // Asegúrate de que la sesión tiene este valor
if (!isset($_SESSION['rol'])) {
    echo "Error: No tienes permisos para ver esta página.";
    exit;
}
$rol_usuario = $_SESSION['rol']; // Guardamos el rol del usuario actual
?>

<style>
   #notificacionesContent .dropdown-item {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    max-width: 250px;
    white-space: normal;
    word-wrap: break-word;
    overflow: hidden;
    text-overflow: ellipsis;
    padding: 8px;
}

#notificacionesContent .dropdown-item p {
    margin: 0;
    word-wrap: break-word;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
<div class="contenedorPanel d-flex align-items-center justify-content-between">
    <div class="tituloCss">
        <h4 class="text-center ColorLetra">Panel Horizontech</h4>
    </div>
    <?php if ($rol == 'Ventas' || $rol == 'Soporte'): ?>
    <!-- Contenedor de Notificaciones -->
    <div class="botonCss position-relative me-3">
        <button class="border-white botonCerrar ColorLetra position-relative" type="button" id="notificacionBtn">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" 
                 class="bi bi-bell ColorLetra" viewBox="0 0 16 16">
                <path d="M8 16a2 2 0 0 0 1.985-1.75H6.015A2 2 0 0 0 8 16zM8 1a4 4 0 0 1 4 4v2.5c0 .628.41 1.255.865 1.745.188.203.285.455.285.705v.5H2v-.5c0-.25.097-.502.285-.705C2.59 8.755 3 8.128 3 7.5V5a4 4 0 0 1 4-4z"/>
            </svg>
            <!-- Contador de notificaciones -->
            <span id="notificacionContador" class="position-absolute badge rounded-pill bg-danger"
                  style="top: 0; right: 0; transform: translate(50%, -50%); font-size: 12px; padding: 5px 7px; min-width: 18px; display: none;">
                0
            </span>
        </button>

        <!-- Dropdown de Notificaciones -->
        <div id="notificacionLista" class="dropdown-menu dropdown-menu-end" style="display: none; max-width: 300px;">
            <h6 class="dropdown-header">Notificaciones</h6>
            <div id="notificacionesContent">
                <p class="text-muted text-center">No hay notificaciones</p>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <!-- Botón de Logout -->
    <div class="botonCss">
        <button class="border-white botonCerrar ColorLetra" type="submit" onclick="location.href='../logout.php'">
            Logout
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                 class="bi bi-box-arrow-in-right ColorLetra" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                      d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z"/>
                <path fill-rule="evenodd"
                      d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
            </svg>
        </button>
    </div>
</div>



<hr class="bg-white">
<br>
<div class="row row-cols-3 row-cols-md-3 g-4">
    <div class="col">
        <div class="card bg-transparent border-white">
            <div class="card-body">
                <h5 class="card-title ColorLetra">Gestion de clientes</h5>
                <div class="card-body ColorLetra">
                    <p class="ColorLetra">Aqui se pueden editar, agregar clientes, ver los clientes que se tienen archivados</p>
                    <button class="btn btn-secondary btn-lg w-100 border-white" type="submit"
                        onclick="location.href='clientes.php'">Ir a clientes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card bg-transparent border-white">
            <div class="card-body">
                <h5 class="card-title ColorLetra">Gestion de Ventas</h5>
                <div class="card-body ColorLetra">
                    <p class="ColorLetra">Gestionar ventas, ver las ventas, cambiar el estado de las ventas</p>
                    <button class="btn btn-secondary btn-lg w-100 border-white" type="submit"
                        onclick="location.href='ventas.php'">Ir a Ventas</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card bg-transparent border-white">
            <div class="card-body">
                <h5 class="card-title ColorLetra">Catalgo de Prodcutos</h5>
                <div class="card-body ColorLetra">
                <div class="card-body ColorLetra">
                   <p>Se muestran los productos y servicios</p>
                   <button class="btn btn-secondary btn-lg w-100 border-white" type="submit"onclick="location.href='ProductosVista.php'">Productos</button>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card bg-transparent border-white">

            <div class="card-body">
                <h5 class="card-title ColorLetra">Soporte y Atención al Cliente</h5>
                <div class="card-body ColorLetra">
                <div class="card-body ColorLetra">
                   <p>Soporte y Atención al Cliente</p>
                   <button class="btn btn-secondary btn-lg w-100 border-white" type="submit"
                        onclick="location.href='Tickets.php'">Soporte y Atención al Cliente</button>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card bg-transparent border-white">

            <div class="card-body">
                <h5 class="card-title ColorLetra">”Análisis de Datos y Reportes</h5>
                <div class="card-body ColorLetra">
                <div class="card-body ColorLetra">
                   <p>Análisis de Datos y Reportes</p>
                   <button class="btn btn-secondary btn-lg w-100 border-white" type="submit"
                        onclick="location.href='PanelAnalisisDatos.php'">”Análisis de Datos y Reportes</button>
                </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col">
        <div class="card bg-transparent border-white">
            <div class="card-body">
                <h5 class="card-title ColorLetra">Administrar Usuarios</h5>
                <div class="card-body ColorLetra">
                <div class="card-body ColorLetra">
                   <p>Se agregaran usuarios con roles menores </p>
                   <button class="btn btn-secondary btn-lg w-100 border-white" type="submit"
                        onclick="location.href='TablaUsuarios.php'">Administracion de usuarios</button>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    function cargarNotificaciones() {
    $.ajax({
        url: 'obtener_notificaciones.php',
        method: 'POST',
        data: { rol: "<?php echo $rol_usuario; ?>" }, // Enviamos el rol
        dataType: 'json',
        success: function (data) {
            console.log("Datos recibidos:", data); // Para depuración
            let contador = data.length;
            $("#notificacionContador").text(contador).toggle(contador > 0);

            let notificacionesHTML = "";
            if (contador > 0) {
                data.forEach(n => {
                    notificacionesHTML += `<div class="dropdown-item">
                        <small class="text-muted">${n.fecha_envio}</small>
                        <p class="mb-1">${n.mensaje}</p>
                        <hr>
                    </div>`;
                });
            } else {
                notificacionesHTML = `<p class="text-muted text-center">No hay notificaciones</p>`;
            }
            $("#notificacionesContent").html(notificacionesHTML);
        }
    });
}


    // Cargar notificaciones al cargar la página
    cargarNotificaciones();

    // Mostrar/Ocultar menú al hacer clic en la campana
    $("#notificacionBtn").click(function () {
        $("#notificacionLista").toggle();
    });

    // Refrescar notificaciones cada 30 segundos
    setInterval(cargarNotificaciones, 30000);
});
</script>
