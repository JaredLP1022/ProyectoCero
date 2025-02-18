<?php
include_once __DIR__ . "/../config/db.php";

$pdo = new db();
$PDO = $pdo->conexion();

// Validar que los datos existan antes de actualizar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_ticket = isset($_POST["id_ticket"]) ? $_POST["id_ticket"] : null;
    $estado = isset($_POST["estado"]) ? $_POST["estado"] : null;
    $prioridad = isset($_POST["prioridad"]) ? $_POST["prioridad"] : null;

    if (!$id_ticket || !$estado || !$prioridad) {
        echo json_encode(["success" => false, "message" => "Faltan datos obligatorios."]);
        exit();
    }

    try {
        $query = "UPDATE ticket SET estado = ?, prioridad = ? WHERE id_ticket = ?";
        $stmt = $PDO->prepare($query);
        $stmt->execute([$estado, $prioridad, $id_ticket]);

        echo json_encode(["success" => true, "message" => "Ticket actualizado correctamente."]);
        exit();
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error al actualizar: " . $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(["success" => false, "message" => "Solicitud inválida."]);
    exit();
}
?>
