<?php
include_once __DIR__ . "/../config/db.php";

$pdo = new db();
$PDO = $pdo->conexion();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_ticket = isset($_POST["id_ticket"]) ? $_POST["id_ticket"] : null;

    if (!$id_ticket) {
        echo json_encode(["success" => false, "message" => "ID de ticket no recibido."]);
        exit();
    }

    try {
        $query = "DELETE FROM ticket WHERE id_ticket = ?";
        $stmt = $PDO->prepare($query);
        $stmt->execute([$id_ticket]);

        echo json_encode(["success" => true, "message" => "Ticket eliminado correctamente."]);
        exit();
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error al eliminar: " . $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(["success" => false, "message" => "Solicitud inválida."]);
    exit();
}
?>
