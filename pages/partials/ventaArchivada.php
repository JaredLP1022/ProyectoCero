<?php
include("C:/xampp/htdocs/ProyectoCero/config/db.php");

$pdo = new db();
$PDO = $pdo->conexion();

$comando = $PDO->query("SELECT * FROM venta WHERE archivada= 'Archivado'");
$comando->execute();

$result = $comando->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="contenedorPanel">
    <div class="botonCss">
        <button class=" border-white botonCerrar ColorLetra" type="submit" onclick="location.href='ventas.php'">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path
                    d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z" />
            </svg>
        </button>
    </div>
    <div class="tituloCss">
        <h4 class="text-center ColorLetra">Ventas Archivadas</h4>
    </div>
    <div class="botonCss">
        <button class=" border-white botonCerrar ColorLetra" type="submit"
            onclick="location.href='Panel-Administrador.php'">Panel<svg xmlns="http://www.w3.org/2000/svg" width="16"
                height="16" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                <path
                    d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z" />
            </svg></button>
    </div>

</div>
<hr class="bg-white">
<div class="row py-3">
    <div class="col">
        <table class="table table-bordered border-white">
            <thead>
                <tr>
                    <th class="ColorLetra">Cliente</th>
                    <th class="ColorLetra">Detalle Producto</th>
                    <th class="ColorLetra">Cantidad</th>
                    <th class="ColorLetra">Precio Unitario</th>
                    <th class="ColorLetra">Total Venta</th>
                    <th class="ColorLetra">Estado de la venta</th>
                    <th class="ColorLetra">Fecha de Venta</th>
                    <th class="ColorLetra">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($result as $row) {
                    ?>
                    <tr>
                        <td>
                            <?php
                            $sql = $PDO->query("SELECT nombre FROM cliente WHERE id = $row[id_cliente]");
                            $sql->execute();

                            $cliente = $sql->fetchColumn();
                            echo $cliente
                                ?>

                        </td>
                        <td><?php echo $row['detalle_producto'] ?></td>
                        <td><?php echo $row['cantidad'] ?></td>
                        <td><?php echo $row['precio'] ?></td>
                        <td><?php echo $row['total'] ?></td>
                        <td><?php echo $row['estado'] ?></td>
                        <td><?php echo str_replace('-', '/', date('d-m-y', strtotime($row['fechaV']))) ?></td>

                        <td>
                            <button title="Eliminar Venta Archivada" onclick="location.href='eliminarventa.php?id=<?php echo $row['id_venta'] ?>'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-trash-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                                </svg></button>
                            <button title="Archivar" class=" "
                                onclick="location.href='Desarchivarventa.php?id=<?php echo $row['id_venta'] ?>'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-archive" viewBox="0 0 16 16">
                                    <path
                                        d="M0 2a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 12.5V5a1 1 0 0 1-1-1zm2 3v7.5A1.5 1.5 0 0 0 3.5 14h9a1.5 1.5 0 0 0 1.5-1.5V5zm13-3H1v2h14zM5 7.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5" />
                                </svg>
                            </button>
                        </td>

                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>