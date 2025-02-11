<?php
include("C:/xampp/htdocs/ProyectoCero/config/db.php");

$pdo = new db();
$PDO = $pdo->conexion();


// Código para archivar cliente (mover de la tabla 'clientes' a 'clientes_archivo')
if (isset($_GET['id'])) {
    $cliente_id = $_GET['id'];
   
    $comando = $PDO->query("UPDATE cliente set estado = 'Activo',  archivar = 'Desarchivado'  WHERE id = $cliente_id");
    $comando->execute();

    header("Location:clientes.php");

}

?>