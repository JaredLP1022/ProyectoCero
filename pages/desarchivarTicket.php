<?php
include_once dirname(__DIR__) . '../config/db.php';

$pdo = new db();
$PDO = $pdo->conexion();

// Verificar si se envió correctamente el ID del ticket
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_ticket"])) {
    $id_ticket = $_POST["id_ticket"];

    try {
        // Obtener datos del ticket antes de archivarlo
        $query = "SELECT * FROM ticket WHERE id_ticket = ?";
        $stmt = $PDO->prepare($query);
        $stmt->execute([$id_ticket]);
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($ticket) {
            // Actualizar el estado del ticket a "Archivado"
            $query = "UPDATE ticket SET archivado = 'Desarchivado' WHERE id_ticket = ?";
            $stmt = $PDO->prepare($query);
            $stmt->execute([$id_ticket]);

            echo json_encode(["success" => true, "message" => "Ticket archivado correctamente"]);
        } else {
            echo json_encode(["success" => false, "message" => "Ticket no encontrado"]);
        }
    } catch (PDOException $e) {
        error_log("Error en la base de datos: " . $e->getMessage());
        echo json_encode(["success" => false, "message" => "Error al archivar el ticket"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Solicitud no válida"]);
}
?>
