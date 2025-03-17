<?php
include "config/db.php";


$pdo = new db();
$PDO = $pdo->conexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir datos del formulario
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'Ventas';  // O lo puedes cambiar según el formulario
    $status = 'Activo';  // O lo puedes cambiar según el formulario

    $error = "";

    // Validar campos vacíos
    if (empty($name) || empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error .= "Todos los campos son obligatorios.<br>";
    }

    // Validar que las contraseñas coincidan
    if ($password !== $confirm_password) {
        $error .= "Las contraseñas no coinciden.<br>";
    }

    // Validar la longitud de la contraseña
    if (strlen($password) < 8) {
        $error .= "La contraseña debe tener al menos 8 caracteres.<br>";
    }

    // Validar formato del correo
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error .= "Correo electrónico no válido.<br>";
    }

    // Si hubo errores, mostrar mensaje de error
    if ($error) {
        echo $error;
        exit;
    }

    // Encriptar la contraseña
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Conexión a la base de datos
    try {


        // Verificar si el usuario ya existe
        $stmt = $PDO->prepare("SELECT COUNT(*) FROM usuario WHERE username = :username OR email = :email");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            echo "El nombre de usuario o el correo electrónico ya están registrados.";
            exit;
        }

        // Insertar el nuevo usuario
        $stmt = $PDO->prepare("INSERT INTO usuario (username,nombre, email, password, rol, estado) VALUES (:username, :name, :email, :password, :rol, :estado)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':rol', $role);
        $stmt->bindParam(':estado', $status);
       

        if ($stmt->execute()) {
            header("Location: pages/Panel-Administrador.php");
            exit;
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>