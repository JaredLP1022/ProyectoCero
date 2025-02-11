<?php
include("C:/xampp/htdocs/ProyectoCero/config/db.php");

$pdo = new db();
$PDO = $pdo->conexion();

$comando = $PDO->query("SELECT cliente.nombre AS nombre_cliente, cliente.telefono AS telefono_cliente, cliente.direccion AS direccion_cliente, cliente.email AS email_cliente,  venta.fechaV AS fecha_Venta, venta.detalle_producto AS detalle_Venta, venta.cantidad AS cantidad_Venta, venta.precio AS precio_Venta, venta.total AS total_Venta, venta.estado AS estado_Venta FROM venta JOIN cliente ON venta.id_cliente= cliente.id");
$comando->execute();

$result = $comando->fetchAll(PDO::FETCH_ASSOC);

?>
