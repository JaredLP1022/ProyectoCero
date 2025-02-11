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
    <div class="tituloCss">
        <h4 class="text-center ColorLetra">Agregando Venta</h4>
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
<form action="AccionesClientes/GuardarVenta.php" method="POST" >
    <p>Datos Asociar cliente:</p>
     <div class="form-row row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="">ID cliente:</label>
                <input class="form-control" type="number" name="cliente_id" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="">Fecha:</label>
                <input class="form-control" type="date"  name="fecha_venta" required>
            </div>
        </div>
    </div>
    <hr class="bg-white">
    <p>Datos de la Venta:</p>
    <div class="form-row row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="">Detalle de los productos o Servicios:</label>
                <input class="form-control" type="text"  name="detalles_producto" required>
            </div>
        </div>
        
    </div>
    <br>
    <div class="form-row row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Cantidad</label>
                <input class="form-control" type="number"  name="cantidad" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Precio Unitario:</label>
                <input class="form-control" type="float"  name="precio_unitario" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Descuento:</label>
               <input class="form-control" type="float" name="descuento" required>
            </div>
        </div>
        
    </div>
    <hr class="bg-white">
   
        <div class="container">
            <button class="btn btn-primary btn-lg w-100" type="submit" name="save">Guardar Venta</button>
        </div>

</form>