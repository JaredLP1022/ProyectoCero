<?php
// Incluir la conexión a la base de datos
include("C:/xampp/htdocs/ProyectoCero/config/db.php");

// Comprobar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $user = $_POST['user'];
    $estado = $_POST['estado'];

    // Convertir el estado en "Activo" o "Inactivo" según el valor enviado
    if ($estado === "activo") {
        $estado = "Activo";  // Convertir "activo" a "Activo"
    } elseif ($estado === "inactivo") {
        $estado = "Inactivo";  // Convertir "inactivo" a "Inactivo"
    }

    // Conectar a la base de datos
    $pdo = new db();
    $PDO = $pdo->conexion();

    // Validar los datos recibidos (usuario y estado)
    if (!empty($user) && in_array($estado, ['Activo', 'Inactivo'])) {
        try {
            // Preparar la consulta para actualizar el estado del usuario
            $stmt = $PDO->prepare("UPDATE usuario SET estado = :estado WHERE username = :user");
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':user', $user);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Si la actualización fue exitosa, redirigir con un mensaje de éxito
                header("Location:../../registroAdmin.php");
                exit;
            } else {
                // Si ocurrió un error en la actualización, redirigir con un mensaje de error
                header("Location: alguna_pagina.php?error=No se pudo actualizar el estado de la cuenta.");
                exit;
            }
        } catch (PDOException $e) {
            // Si hay un error con la base de datos, mostrar un mensaje de error
            header("Location: alguna_pagina.php?error=Error en la base de datos: " . $e->getMessage());
            exit;
        }
    } else {
        // Si los datos no son válidos, redirigir con un mensaje de error
        header("Location: alguna_pagina.php?error=Datos inválidos.");
        exit;
    }
} else {
    // Si el formulario no fue enviado correctamente, redirigir con un mensaje de error
    header("Location: alguna_pagina.php?error=Acción no permitida.");
    exit;
}
