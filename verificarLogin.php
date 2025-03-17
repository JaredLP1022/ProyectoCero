<?php
include "config/db.php";

$pdo = new db();
$PDO = $pdo->conexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $error = "";

    // Validar que los campos no estén vacíos
    if (empty($username) || empty($password)) {
        $error .= "Ambos campos son obligatorios.<br>";
    }

    // Si hay error, redirigir con mensaje
    if (!empty($error)) {
        header("Location: login.php?error=" . urlencode($error));
        exit;
    }

    // Buscar al usuario en la base de datos
    try {
        $stmt = $PDO->prepare("SELECT * FROM usuario WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch();

            // Verificar la contraseña
            if (password_verify($password, $user['password'])) {
                // Si la contraseña es correcta, iniciar sesión
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['rol'] = $user['rol'];
                
                // Redirigir al panel de usuario
                header("Location: pages/Panel-Administrador.php");
                exit;
            } else {
                $error = "La contraseña es incorrecta.";
            }
        } else {
            $error = "Usuario no encontrado.";
        }
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }

    // Si hubo error, redirigir con mensaje
    if (!empty($error)) {
        header("Location: login.php?error=" . urlencode($error));
        exit;
    }
}
?>
