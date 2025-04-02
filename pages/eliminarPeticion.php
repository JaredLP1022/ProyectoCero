<?php
include("C:/xampp/htdocs/ProyectoCero/config/db.php");

// Verificar si el parámetro ID está presente en la URL
if (isset($_GET['id'])) {
    $id_peticion = $_GET['id'];

    try {
        $pdo = new db();
        $PDO = $pdo->conexion();

        // Consulta para eliminar la petición con el ID proporcionado
        $sql = "DELETE  FROM peticiones WHERE id = :id_peticion";
        $stmt = $PDO->prepare($sql);
        $stmt->bindParam(':id_peticion', $id_peticion, PDO::PARAM_INT);
        $stmt->execute();

        // Redirigir de vuelta a la página de peticiones con un mensaje de éxito
        header("Location: tabla_peticiones.php?success=Petición eliminada correctamente.");
    } catch (PDOException $e) {
        // Si ocurre un error, redirigir con mensaje de error
        header("Location: tabla_peticiones.php?error=Error al eliminar la petición. Intente nuevamente.");
    }
} else {
    // Si no se pasa el ID, redirigir con mensaje de error
    header("Location: tabla_peticiones.php?error=ID no válido.");
}
?>
