<?php
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

include("C:/xampp/htdocs/ProyectoCero/config/db.php");

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo = new db();
        $PDO = $pdo->conexion();

        if (!isset($_POST['rol']) || !isset($_POST['mensaje'])) {
            $response["error"] = "Faltan datos en la solicitud";
            echo json_encode($response);
            exit;
        }

        $rol = trim($_POST['rol']);
        $mensaje = trim($_POST['mensaje']);

        if (!in_array($rol, ['Ventas', 'Soporte'])) {
            $response["error"] = "Rol no válido";
            echo json_encode($response);
            exit;
        }

        if (empty($mensaje)) {
            $response["error"] = "El mensaje no puede estar vacío";
            echo json_encode($response);
            exit;
        }

        $sql = "INSERT INTO notificaciones (rol, mensaje, fecha_envio, leido) VALUES (?, ?, NOW(), 0)";
        $stmt = $PDO->prepare($sql);
        $stmt->execute([$rol, $mensaje]);

        $response["success"] = "Notificación enviada correctamente";
        echo json_encode($response);
        exit;
    } catch (PDOException $e) {
        $response["error"] = "Error en la base de datos";
        echo json_encode($response);
        exit;
    }
}

exit;
?>
