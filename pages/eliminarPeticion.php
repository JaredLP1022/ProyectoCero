<?php
include("C:/xampp/htdocs/ProyectoCero/config/db.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id_peticion = $_POST['id'];

    try {
        $pdo = new db();
        $PDO = $pdo->conexion();

        $sql = "DELETE FROM peticiones WHERE id = :id_peticion";
        $stmt = $PDO->prepare($sql);
        $stmt->bindParam(':id_peticion', $id_peticion, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "La petición no fue encontrada o ya fue eliminada."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "error" => "Error de base de datos: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Solicitud inválida."]);
}
?>
