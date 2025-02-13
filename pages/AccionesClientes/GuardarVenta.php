<?php
include("C:/xampp/htdocs/ProyectoCero/config/db.php");

$pdo = new db();
$PDO = $pdo->conexion();

$idcliente = "";

if (isset($_POST["save"])) {
    $id_cliente = $_POST["cliente_id"];
    $fecha_venta = $_POST["fecha_venta"];
    $detalles_producto = trim($_POST["detalle_producto"]); // Usar trim() para eliminar espacios innecesarios
    $cantidad = $_POST["cantidad"];
    $precio_unitario = $_POST["precio_unitario"];
    $descuento = $_POST["descuento"];

    // Verificar si detalle_producto no está vacío
    if (empty($detalles_producto)) {
        die("Error: El detalle del producto no puede estar vacío.");
    }

    $subtotal_venta = $cantidad * $precio_unitario;
    $total = $subtotal_venta * ($descuento / 100); 
    $total_ventas = $subtotal_venta - $total;
    $estado_venta = "En proceso";
    $venta_archivada = "Desarchivado";

    // Obtener el correo del cliente de forma segura
    $querym = "SELECT email FROM cliente WHERE id = ?";
    $result = $PDO->prepare($querym);
    $result->execute([$id_cliente]); // Usamos el id_cliente correctamente

    if ($result->rowCount() > 0) {
        $email_cliente = $result->fetchColumn(); // Extraemos el correo del cliente

        // Incluir el archivo de confirmación (si es necesario)
        include('../ConfirmacionCompra.php'); // Asegúrate de que el archivo existe y está configurado correctamente
    } else {
        echo "Cliente no encontrado.";
        exit();
    }

    // Insertar la venta en la base de datos
    $query = "INSERT INTO venta (id_cliente, detalle_producto, fechaV, cantidad, precio, descuento, total, estado, archivada) 
              VALUES (?,?,?,?,?,?,?,?,?)"; 
    
    $stmt = $PDO->prepare($query);
    $resultado = $stmt->execute([$id_cliente, $detalles_producto, $fecha_venta, $cantidad, $precio_unitario, $descuento, $total_ventas, $estado_venta, $venta_archivada]);

    // Verificar si la inserción fue exitosa
    if ($resultado) {
        // Redirigir a la página de ventas
        header("Location:../ventas.php");
        exit();  // Asegúrate de terminar el script después de la redirección
    } else {
        echo "Hubo un error al guardar la venta.";
    }
}
?>
