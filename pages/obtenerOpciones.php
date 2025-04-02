<?php
include("C:/xampp/htdocs/ProyectoCero/config/db.php");

try {
    $pdo = new db();
    $PDO = $pdo->conexion();

    // Consulta para obtener los estados de las peticiones
    $sql_estado = "SELECT  estado FROM peticiones";
    $stmt_estado = $PDO->prepare($sql_estado);
    $stmt_estado->execute();
    $estados = $stmt_estado->fetchAll(PDO::FETCH_ASSOC);


    // Verificamos si las consultas están devolviendo datos
    if (!$estados) {
        $estados = []; // En caso de que no se encuentren estados, asignamos un array vacío
    }

    // Devolver las opciones como un JSON
    echo json_encode([
        "estados" => $estados
    ]);
} catch (PDOException $e) {
    echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);
}
?>
