<?php
session_start();

include("C:/xampp/htdocs/ProyectoCero/config/db.php");

// Asegurarnos de que el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recogemos los datos del formulario
    $descripcion = $_POST['descripcion'];
    $prioridad = $_POST['prioridad'];
    $departamento = $_POST['departamento'];
    $fecha_necesaria = $_POST['fecha_necesaria']; // Corrección del nombre del campo
    $id_usuario = $_SESSION['user_id']; // Obtener el ID del usuario desde la sesión

    // Fecha actual de creación
    $fecha_creacion = date('Y-m-d H:i:s'); // Formato de fecha y hora

    // Estado de la petición (siempre "En proceso" al crear la petición)
    $estado = "En proceso";

    // Conexión a la base de datos
    try {
        $pdo = new db();
        $PDO = $pdo->conexion();

        // Insertar la nueva petición en la base de datos
        $sql = "INSERT INTO peticiones (peticion, prioridad, departamento, fecha_necesita, fecha_creacion, estado, id_usuario) 
                VALUES (:peticion, :prioridad, :departamento, :fecha_necesaria, :fecha_creacion, :estado, :id_usuario)";
        
        $stmt = $PDO->prepare($sql);
        $stmt->bindParam(':peticion', $descripcion);  // Corrección: ahora coincide con la consulta
        $stmt->bindParam(':prioridad', $prioridad);
        $stmt->bindParam(':departamento', $departamento);
        $stmt->bindParam(':fecha_necesaria', $fecha_necesaria);  // Corrección del nombre
        $stmt->bindParam(':fecha_creacion', $fecha_creacion);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':id_usuario', $id_usuario);

        // Ejecutamos la consulta
        $stmt->execute();

        // Si la inserción es exitosa, redirigimos a peticion_enviada.php con éxito
        header("Location: peticion_enviada.php?success=Petición enviada correctamente.");
        exit();
    } catch (PDOException $e) {
        // Si hay error en la base de datos, redirigimos con mensaje de error
        die("Error en la base de datos: " . $e->getMessage());
    
    }
}
?>
