<?php
session_start();
include "config/db.php";

$pdo = new db();
$PDO = $pdo->conexion();

$error = isset($_GET['error']) ? $_GET['error'] : '';
$success = isset($_GET['success']) ? $_GET['success'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $ip = $_SERVER['REMOTE_ADDR'];

    // 🔹 Clave secreta de reCAPTCHA v3
    $recaptcha_secret = "6LfW4_4qAAAAAJqyGZG5hhxDLA7uev0QIuljMDiK";

    // 🔹 Validar reCAPTCHA v3
    $recaptcha_response = $_POST['recaptcha_response'] ?? "";
    $recaptcha_url = "https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response&remoteip=$ip";
    
    $recaptcha_data = json_decode(file_get_contents($recaptcha_url), true);

    if (!$recaptcha_data["success"] || $recaptcha_data["score"] < 0.5) {
        $error = "Acceso denegado. Posible actividad sospechosa.";
        header("Location: error.php?error=" . urlencode($error));
        exit;
    }

    // 🔹 Validar campos vacíos
    if (empty($username) || empty($password)) {
        $error = "Ambos campos son obligatorios.";
        header("Location: error.php?error=" . urlencode($error));
        exit;
    }

    // 🔹 Buscar usuario en la base de datos
    try {
        $stmt = $PDO->prepare("SELECT * FROM usuario WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            // Verificar si la cuenta está bloqueada temporalmente
            if ($user['bloqueo_hasta'] && strtotime($user['bloqueo_hasta']) > time()) {
                $error = "Tu cuenta está bloqueada temporalmente. Intenta nuevamente más tarde.";
                header("Location: error.php?error=" . urlencode($error));
                exit;
            }

            // Verificar si la cuenta está activa
            if ($user['estado'] != 'Activo') {
                $error = "Tu cuenta está inactiva. Contacta al administrador.";
                header("Location: error.php?error=" . urlencode($error));
                exit;
            } else {
                // Verificar la contraseña
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['rol'] = $user['rol'];

                    // Resetear los intentos fallidos
                    $stmt = $PDO->prepare("UPDATE usuario SET intentos_fallidos = 0 WHERE username = :username");
                    $stmt->bindParam(':username', $username);
                    $stmt->execute();

                    $success = "Bienvenido, " . $user['username'] . "!";
                    header("Location: pages/Panel-Administrador.php");
                    exit;
                } else {
                    // Incrementar los intentos fallidos
                    $stmt = $PDO->prepare("UPDATE usuario SET intentos_fallidos = intentos_fallidos + 1 WHERE username = :username");
                    $stmt->bindParam(':username', $username);
                    $stmt->execute();

                    // Verificar si el usuario alcanzó 5 intentos fallidos
                    $stmt = $PDO->prepare("SELECT intentos_fallidos FROM usuario WHERE username = :username");
                    $stmt->bindParam(':username', $username);
                    $stmt->execute();
                    $user_data = $stmt->fetch();

                    if ($user_data['intentos_fallidos'] >= 5) {
                        // Bloquear la cuenta temporalmente por 5 minutos
                        $bloqueo_hasta = date('Y-m-d H:i:s', strtotime('+5 minutes'));
                        $stmt = $PDO->prepare("UPDATE usuario SET bloqueo_hasta = :bloqueo_hasta WHERE username = :username");
                        $stmt->bindParam(':bloqueo_hasta', $bloqueo_hasta);
                        $stmt->bindParam(':username', $username);
                        $stmt->execute();

                        $error = "Has excedido los intentos permitidos. Tu cuenta ha sido bloqueada temporalmente.";
                    } else {
                        $error = "Usuario o contraseña incorrectos.";
                    }
                    header("Location: error.php?error=" . urlencode($error));
                    exit;
                }
            }
        } else {
            $error = "Usuario o contraseña incorrectos.";
            header("Location: error.php?error=" . urlencode($error));
            exit;
        }
    } catch (PDOException $e) {
        $error = "Error en la base de datos.";
        header("Location: error.php?error=" . urlencode($error));
        exit;
    }
}
?>
