<?php
session_start();

  // Establece el tiempo de inactividad máximo en segundos (20 minutos)
  $tiempo_inactividad = 20 * 60;

  // Verifica si la variable de sesión de la última actividad existe
  if (isset($_SESSION['ultimoAcceso'])) {
      $tiempo_transcurrido = time() - $_SESSION['ultimoAcceso'];

      // Si el tiempo de inactividad es mayor al tiempo máximo, cierra la sesión
      if ($tiempo_transcurrido > $tiempo_inactividad) {
          session_unset();  // Elimina todas las variables de sesión
          session_destroy(); // Destruye la sesión
          header("Location: ../index.php"); // Redirige al login
          exit();
      }
  }

  // Actualiza la última actividad
  $_SESSION['ultimoAcceso'] = time();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="bodyPage/stylesPages.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>SWRGC</title>
</head>

<body class="bg-secondary" ></body>