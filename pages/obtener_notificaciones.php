<?php
session_start();
include("C:/xampp/htdocs/ProyectoCero/config/db.php");

try {
    $pdo = new db();
    $PDO = $pdo->conexion();

    // Obtener el rol del usuario desde la sesión
    if (!isset($_SESSION['rol'])) {
        echo json_encode(["error" => "Rol no proporcionado"]);
        exit;
    }
    $rol_usuario = $_SESSION['rol'];

    // Consulta SQL para obtener las notificaciones
    $sql = "SELECT mensaje, fecha_envio FROM notificaciones WHERE rol = :rol_usuario AND leido = 0 ORDER BY fecha_envio DESC LIMIT 5";
    $stmt = $PDO->prepare($sql);
    $stmt->bindParam(':rol_usuario', $rol_usuario, PDO::PARAM_STR);
    $stmt->execute();

    // Obtener los resultados
    $notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Verificar si hay notificaciones
    if (!$notificaciones) {
        $notificaciones = []; // Si no hay notificaciones, devolver un array vacío
    }

    // Devolver las notificaciones como un JSON
    echo json_encode($notificaciones);

} catch (PDOException $e) {
    echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);
}
?>
