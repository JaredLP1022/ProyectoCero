<?php
include("C:/xampp/htdocs/ProyectoCero/config/db.php");

$pdo = new db();
$PDO = $pdo->conexion();


// Código para archivar cliente (mover de la tabla 'clientes' a 'clientes_archivo')
if (isset($_GET['id'])) {
    $id = $_GET['id'];
   
    $comando = $PDO->query("UPDATE venta set estado = 'Completada',  archivada = 'Desarchivado'  WHERE id_venta = $id");
    $comando->execute();

    header("Location:ventas.php");

}

?>