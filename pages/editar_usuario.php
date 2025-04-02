<?php
include_once dirname(__DIR__) . '../config/db.php';

$pdo = new db();
$PDO = $pdo->conexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];
    $estado = $_POST['estado'];
    $bloqueo_hasta = ($_POST['bloqueo_hasta'] === "null") ? null : $_POST['bloqueo_hasta']; // Aquí verificamos que 'null' sea tratado correctamente

    $query = "UPDATE usuario SET nombre = :nombre, email = :email, rol = :rol, estado = :estado, bloqueo_hasta = NULL, intentos_fallidos = 0 WHERE username = :username";
    $stmt = $PDO->prepare($query);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':rol', $rol);
    $stmt->bindParam(':estado', $estado);
  
    $stmt->bindParam(':username', $username);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Usuario actualizado correctamente",
            "username" => $username,
            "nombre" => $nombre,
            "email" => $email,
            "rol" => $rol,
            "estado" => $estado,
       
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al actualizar el usuario"]);
    }
}
?>
