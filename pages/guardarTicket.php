<?php
include_once dirname(__DIR__) . '../config/db.php';

$pdo = new db();
$PDO = $pdo->conexion();

// Verificar si se envió el formulario correctamente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_venta = $_POST["id_venta"];
    $descripcion = trim($_POST["descripcion"]);
    $fecha = date("Y-m-d"); // Fecha actual
    $prioridad = $_POST["prioridad"];
    $estado = $_POST["estado"];

    try {
        // Insertar el ticket en la base de datos
        $query = "INSERT INTO ticket (id_venta, descripcionProblema, fecha, prioridad, estado) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $PDO->prepare($query);
        $stmt->execute([$id_venta, $descripcion, $fecha, $prioridad, $estado]);

        // ✅ Redirigir a tickets.php con un parámetro de éxito
        header("Location: tickets.php?success=1");
        exit();
    } catch (PDOException $e) {
        // ✅ Redirigir a tickets.php con un parámetro de error
        header("Location: tickets.php?error=1");
        exit();
    }
} else {
    // Redirección si se intenta acceder sin POST
    header("Location: tickets.php");
    exit();
}
?>
