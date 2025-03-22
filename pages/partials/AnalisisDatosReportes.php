<div class="contenedorPanel">
    <div class="botonCss">
        <button title="Volver" class=" border-white botonCerrar ColorLetra" type="submit"
            onclick="location.href='Panel-administrador.php'">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path
                    d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z" />
            </svg>
        </button>
    </div>
    <div class="tituloCss">
        <h4 class="text-center ColorLetra">Analisis de datos y reportes de cada area</h4>
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
<hr class="bg-white">
<br>
<div class="row row-cols-3 row-cols-md-3 g-4">
    <div class="col">
        <div class="card bg-transparent border-white h-100">
            <div class="card-body">
                <h5 class="card-title ColorLetra">Generar reporte de ventas mensuales</h5>
                <div class="card-body ColorLetra">
                    <p class="ColorLetra">Como Administrador,
                        quiero poder generar un
                        reporte de ventas
                        mensuales para analizar el
                        rendimiento de las ventas
                        durante un período
                        específico.
                    </p>
                    <button class="btn btn-secondary btn-lg w-100 border-white ColorLetra" type="submit"
                        onclick="location.href='VentasMensuales.php'">Reporte Por Mes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card bg-transparent border-white h-100">
            <div class="card-body">
                <h5 class="card-title ColorLetra">Generar reporte de soporte técnico</h5>
                <div class="card-body ColorLetra">
                    <p class="ColorLetra">Como Usuario de Soporte,
                        quiero poder generar un
                        reporte de todas las
                        incidencias registradas
                        durante un período
                        específico, para analizar el
                        desempeño del equipo de
                        soporte.</p>
                    <button class="btn btn-secondary btn-lg w-100 border-white ColorLetra" type="submit"
                        onclick="location.href='ReporteSoporteT.php'">Reporte Soporte Tecnico</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card bg-transparent border-white h-100">
            <div class="card-body">
                <h5 class="card-title ColorLetra">Visualizar estadísticas de ventas por producto</h5>
                <div class="card-body ColorLetra">
                    <div class="card-body ColorLetra">
                        <p>Como Administrador,
                            quiero poder visualizar
                            estadísticas de ventas por
                            producto, para identificar
                            los productos más
                            vendidos y tomar
                            decisiones estratégica</p>
                        <button class="btn btn-secondary btn-lg w-100 border-white ColorLetra" type="submit"
                            onclick="location.href='ventaProductos.php'">Estadiscas de productos y servicios</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card bg-transparent border-white h-100">

            <div class="card-body">
                <h5 class="card-title ColorLetra">Exportar reporte de soporte en formato PDF</h5>
                <div class="card-body ColorLetra">
                    <div class="card-body ColorLetra">
                        <p>Como Usuario de Soporte,
                            quiero poder exportar un
                            reporte de soporte técnico
                            en formato PDF para
                            compartirlo fácilmente con
                            otros departamentos.</p>
                        <button class="btn btn-secondary btn-lg w-100 border-white ColorLetra" type="submit"
                            onclick="location.href='ReporteClientesR.php'">”Análisis de Datos y Reportes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card bg-transparent border-white h-100">
            <div class="card-body">
                <h5 class="card-title ColorLetra">Generar reporte de clientes activos por región</h5>
                <div class="card-body ColorLetra">
                    <div class="card-body ColorLetra">
                        <p>Como Usuario de Ventas,
                            quiero poder generar un
                            reporte de los clientes
                            activos clasificados por
                            región, para analizar la distribución geográfica de
                            la base de clientes </p>
                        <button class="btn btn-secondary btn-lg w-100 border-white ColorLetra" type="submit"
                            onclick="location.href='BuscarClientesF.php'">Agregar Usuario</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card bg-transparent border-white h-100">
            <div class="card-body">
                <h5 class="card-title ColorLetra">Exportar reporte de ingresos por producto en Exce</h5>
                <div class="card-body ColorLetra">
                    <div class="card-body ColorLetra">
                        <p>Como Administrador,
                            quiero poder exportar un
                            reporte de ingresos
                            generados por cada
                            producto en formato
                            Excel, para facilitar el
                            análisis financiero </p>
                        <button class="btn btn-secondary btn-lg w-100 border-white ColorLetra" type="submit"
                            onclick="location.href='../registroAdmin.php'">Agregar Usuario</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card bg-transparent border-white h-100">
            <div class="card-body">
                <h5 class="card-title ColorLetra">Generar reporte de clientes por fecha de registro</h5>
                <div class="card-body ColorLetra">
                    <div class="card-body ColorLetra">
                        <p>Como Usuario de Ventas,
                            quiero poder generar un
                            reporte de clientes
                            clasificados por fecha de
                            registro, para analizar el
                            crecimiento de la base de
                            clientes.
                        </p>
                        <button class="btn btn-secondary btn-lg w-100 border-white ColorLetra" type="submit"
                            onclick="location.href='../registroAdmin.php'">Agregar Usuario</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card bg-transparent border-white h-100">
            <div class="card-body">
                <h5 class="card-title ColorLetra">Visualizar estadísticas de ventas por región</h5>
                <div class="card-body ColorLetra">
                    <div class="card-body ColorLetra">
                        <p>Como Administrador,
                            quiero poder visualizar
                            estadísticas de ventas
                            clasificadas por región,
                            para identificar las áreas
                            geográficas con mejor
                            rendimiento.</p>
                        <button class="btn btn-secondary btn-lg w-100 border-white ColorLetra" type="submit"
                            onclick="location.href='../registroAdmin.php'">Agregar Usuario</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>