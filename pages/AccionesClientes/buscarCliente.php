<?php 
include("C:/xampp/htdocs/ProyectoCero/config/db.php");
$pdo = new db();
$PDO = $pdo->conexion();


// Código para buscar cliente por nombre
if (isset($_POST['buscar'])) {
    $nombre = trim($_POST["name"]);
    $apellido1 = trim($_POST["apellido1"]);
    $apellidos = trim($_POST["apellidos"]);
    $nombre_buscar = $nombre." ".$apellido1." ".$apellidos;

    $comando = $PDO->query("SELECT * FROM cliente WHERE nombre= $nombre_buscar");
    

}
?>