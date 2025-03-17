<?php
include_once dirname(__DIR__) . '../config/db.php';

$pdo = new db();
$PDO = $pdo->conexion();

// Verificar si se envió el formulario correctamente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $id_venta = $_POST["id_venta"] ?? null;
    $descripcion = trim($_POST["descripcionProblema"] ?? ""); // CORREGIDO
    $fecha = date("Y-m-d"); // Fecha actual
    $prioridad = $_POST["prioridad"] ?? null;
    $estado = $_POST["estado"] ?? null;

    // Validar que no haya campos vacíos
    if (empty($id_venta) || empty($descripcion) || empty($prioridad) || empty($estado)) {
        die("Error: Todos los campos son obligatorios.");
    }

    try {
        // Insertar el ticket en la base de datos
        $query = "INSERT INTO ticket (id_venta, descripcionProblema, fecha, prioridad, estado) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $PDO->prepare($query);
        $stmt->execute([$id_venta, $descripcion, $fecha, $prioridad, $estado]);

        // ✅ Redirigir a tickets.php con éxito
        header("Location: tickets.php?success=1");
        exit();
    } catch (PDOException $e) {
        // ✅ Mostrar error antes de redirigir
        echo "Error SQL: " . $e->getMessage();
        exit();
    }
} else {
    // Redirección si se intenta acceder sin POST
    header("Location: tickets.php");
    exit();
}
?>
