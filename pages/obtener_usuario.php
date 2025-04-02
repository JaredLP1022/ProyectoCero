<?php
include_once dirname(__DIR__) . '../config/db.php';

$pdo = new db();
$PDO = $pdo->conexion();

if (isset($_POST['username'])) {
    // Obtener el username enviado desde el frontend
    $username = $_POST['username'];

    // Consulta para obtener los datos del usuario
    $query = "SELECT username, nombre, email, rol, estado, bloqueo_hasta FROM usuario WHERE username = :username";
    $stmt = $PDO->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Convertir la fecha de bloqueo a un formato legible, si existe
        $usuario['bloqueo_hasta'] = $usuario['bloqueo_hasta'] ? (new DateTime($usuario['bloqueo_hasta']))->format('Y-m-d H:i:s') : null;

        // Retornar los datos del usuario en formato JSON
        echo json_encode($usuario);
    } else {
        echo json_encode(['error' => 'Usuario no encontrado']);
    }
} else {
    echo json_encode(['error' => 'No se ha enviado el username']);
}
?>
