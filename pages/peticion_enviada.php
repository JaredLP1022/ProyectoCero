<?php
// Iniciamos la sesión para obtener los mensajes de éxito o error
session_start();

// Obtenemos el mensaje de éxito o error desde la URL
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';

// Definir la página a la que redirigir después de cerrar la alerta
$redirectUrl = "PanelAnalisisDatos.php"; // Cambia esto a la página que prefieras

$redirectUrl2 = "peticionesAdmin.php"; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petición Enviada</title>
    <!-- Incluir SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>

<script>
    // Verificar si hay un mensaje de éxito y mostrar SweetAlert con redirección
    <?php if ($success): ?>
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '<?php echo htmlspecialchars($success); ?>',
            confirmButtonText: 'Aceptar',
            willClose: () => {
                window.location.href = "<?php echo $redirectUrl; ?>";
            }
        });
    <?php endif; ?>

    // Verificar si hay un mensaje de error y mostrar SweetAlert con redirección
    <?php if ($error): ?>
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: '<?php echo htmlspecialchars($error); ?>',
            confirmButtonText: 'Aceptar',
            willClose: () => {
                window.location.href = "<?php echo $redirectUrl2; ?>";
            }
        });
    <?php endif; ?>
</script>

</body>
</html>
