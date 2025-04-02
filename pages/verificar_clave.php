<?php
include_once dirname(__DIR__) . '../config/db.php';

$pdo = new db();
$PDO = $pdo->conexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $claveIngresada = $_POST['clave'];  // La clave que el usuario ingresa

    // Obtener el valor cifrado (hash) de la clave maestra de la tabla 'configuracion'
    $query = "SELECT valor FROM configuracion WHERE clave = 'AdministradorTech'";  // Cambié la clave a 'AdministradorTech'
    $stmt = $PDO->prepare($query);
    $stmt->execute();
    $claveBD = $stmt->fetchColumn();  // El valor almacenado es el hash

    // Verificar si la clave ingresada coincide con el hash almacenado en la base de datos
    if (password_verify($claveIngresada, $claveBD)) {
        echo json_encode(["success" => true]);  // La clave es correcta
    } else {
        echo json_encode(["success" => false]);  // La clave es incorrecta
    }
}
?>
