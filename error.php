<?php
// Obtener el mensaje de error desde la URL (si está presente)
$error_message = isset($_GET['error']) ? $_GET['error'] : 'Ocurrió un error desconocido.';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <!-- Incluir SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.16/dist/sweetalert2.min.css">
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.16/dist/sweetalert2.all.min.js"></script>
    <script>
        // Mostrar el mensaje de error usando SweetAlert
        Swal.fire({
            icon: 'error',
            title: '¡Ups! Algo salió mal',
            text: '<?php echo htmlspecialchars($error_message); ?>',
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            // Al hacer clic en "Aceptar", redirige a index.php
            if (result.isConfirmed) {
                window.location.href = "index.php";
            }
        });
    </script>
</body>
</html>


