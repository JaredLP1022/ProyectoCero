<?php
    session_start(); // Asegúrate de que esta línea esté al inicio del archivo
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Denegado</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <script>
        // Mostrar la alerta de acceso denegado
        Swal.fire({
            icon: 'error',
            title: 'Acceso Denegado',
            text: 'No tienes permisos para acceder a esta página.',
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            // Redirigir al panel de administrador cuando se presiona el botón de aceptar
            if (result.isConfirmed) {
                window.location.href = 'Panel-Administrador.php'; // Redirige al panel de administrador
            }
        });
    </script>
</body>
</html>
