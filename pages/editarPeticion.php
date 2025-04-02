<?php
include("C:/xampp/htdocs/ProyectoCero/config/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo = new db();
        $PDO = $pdo->conexion();

        // Verificar los datos recibidos en el formulario
        error_log(print_r($_POST, true)); // Esta línea imprimirá los datos de $_POST en el log

        // Recibir los datos del formulario
        $id = $_POST["id"];
        $estado = $_POST["estado"];
        $fecha_resolucion = $_POST["fecha_resolucion"];
        $respuesta_admin = $_POST["respuesta_admin"];

        // Verifica que los datos sean correctos
        if (empty($id) || empty($estado) || empty($fecha_resolucion) || empty($respuesta_admin)) {
            echo json_encode(["error" => "Faltan datos para actualizar"]);
            exit;
        }

        // Actualizar solo los campos requeridos
        $sql = "UPDATE peticiones SET 
                estado = :estado,
                fecha_resolucion = :fecha_resolucion,
                respuesta_admin = :respuesta_admin
                WHERE id = :id";
        
        $stmt = $PDO->prepare($sql);
        $stmt->bindParam(":estado", $estado);
        $stmt->bindParam(":fecha_resolucion", $fecha_resolucion);
        $stmt->bindParam(":respuesta_admin", $respuesta_admin);
        $stmt->bindParam(":id", $id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["error" => "No se pudo actualizar la petición"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Acceso no permitido"]);
}
?>
