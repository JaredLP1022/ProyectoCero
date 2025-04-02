<?php
include_once dirname(__DIR__) . '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = new db();
    $PDO = $pdo->conexion();

    $username = $_POST['username'] ?? '';

    if (!empty($username)) {
        $query = "DELETE FROM usuario WHERE username = :username";
        $stmt = $PDO->prepare($query);
        $stmt->bindParam(':username', $username);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Usuario eliminado correctamente"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al eliminar el usuario"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "No se proporcionó un usuario"]);
    }
}
?>
