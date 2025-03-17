<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($_GET['success'])): ?>
    <script>
        Swal.fire({
            icon: "success",
            title: "¡Registro exitoso!",
            text: "El usuario ha sido creado correctamente.",
            confirmButtonText: "Aceptar"
        });
    </script>
<?php endif; ?>


<div class="contenedorPanel">
    <div class="tituloCss" >
    <h4 class="text-center ColorLetra">Panel Administrador</h4>
    </div>
    <div class="botonCss">
    <button class=" border-white botonCerrar ColorLetra" type="submit" onclick="location.href='../logout.php'">Logout<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
            class="bi bi-box-arrow-in-right ColorLetra" viewBox="0 0 16 16">
            <path fill-rule="evenodd"
                d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z" />
            <path fill-rule="evenodd"
                d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
        </svg></button>
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
                <h5 class="card-title ColorLetra">Agregar Usuarios</h5>
                <div class="card-body ColorLetra">
                <div class="card-body ColorLetra">
                   <p>Se agregaran usuarios con roles menores </p>
                   <button class="btn btn-secondary btn-lg w-100 border-white" type="submit"
                        onclick="location.href='../registroAdmin.php'">Agregar Usuario</button>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>