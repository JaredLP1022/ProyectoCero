<?php
include("C:/xampp/htdocs/ProyectoCero/config/db.php");

$pdo = new db();
$PDO = $pdo->conexion();
$error;

if (isset($_POST['save'])) {
    $nombre = $_POST['nombre'];
    $apellidoP = $_POST['apellidoP'];
    $apellidoS = $_POST['apellidoS'];
    $email = $_POST['email'];
    $telefono = $_POST['phone'];
    $calle = $_POST['calle'];
    $numCasa = $_POST['numCasa'];
    $codigoPostal = $_POST['codigoPostal'];
    $ciudad = $_POST['ciudad'];
    $estado = $_POST['estado'];
    $pais = $_POST['pais'];
    $fecha_registro = $_POST['fechaRegis'];

  
    $nombreC = $nombre." ".$apellidoP." ".$apellidoS; 
    $direccion = $calle.", ".$numCasa.", ".$codigoPostal.", ".$ciudad.", ".$estado.", ".$pais;
    $estadoC="Activo";
    $archivar = "Desarchivado";
    $fecha_modificacion = $fecha_registro;
    
    $query = "INSERT INTO cliente (nombre, email, telefono, direccion, fechaR, fechaM, estado, archivar)  VALUES(?,?,?,?,?,?,?,?)";

    $stmt = $PDO->prepare($query);
    $stmt->execute([$nombreC, $email, $telefono, $direccion, $fecha_registro, $fecha_modificacion, $estadoC, $archivar]);


    header("Location:../Clientes.php");
    

}else{
    $error ="<p>Los Campos estan vacios</p>";
    header("Location:../FormularioCliente.php?error=".$error);
}


?>