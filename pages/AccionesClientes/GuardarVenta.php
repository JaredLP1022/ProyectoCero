<?php
include("C:/xampp/htdocs/ProyectoCero/config/db.php");

$pdo = new db();
$PDO = $pdo->conexion();

$idcliente = "";

if(isset($_POST["save"])){
    $id_cliente = $_POST["cliente_id"];
    $fecha_venta = $_POST["fecha_venta"];
    $detalles_producto = $_POST["detalles_producto"];
    $cantidad = $_POST["cantidad"];
    $precio_unitario = $_POST["precio_unitario"];
    $descuento = $_POST["descuento"];


    $subtotal_venta = $cantidad * $precio_unitario;
    $total = $subtotal_venta * ($descuento / 100); 
    $total_ventas = $subtotal_venta - $total;
    $estado_venta = "En proceso";
    $venta_archivada = "Desarchivado";

    $querym = "SELECT email from cliente where id = '$idcliente'";
    $result = $PDO->prepare($querym);
    $result->execute();

    $email_cliente = $result;

    include('../ConfirmacionCompra.php');


    $query = "INSERT INTO venta (id_cliente, detalle_producto, fechaV, cantidad, precio, descuento, total, estado, archivada) VALUES (?,?,?,?,?,?,?,?,?)"; 
    
    $stmt = $PDO->prepare($query);
    $stmt->execute([$id_cliente, $detalles_producto, $fecha_venta, $cantidad, $precio_unitario, $descuento, $total_ventas, $estado_venta, $venta_archivada]);

    
    header("Location:../ventas.php");


}




?>
