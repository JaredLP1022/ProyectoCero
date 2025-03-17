<?php
include('partials/Head.php');
if ($_SESSION['rol'] != 'Administrador') {
        // Si no es administrador, redirigir a una página de acceso denegado o a la página principal
        header("Location: pages/access_denied.php"); // Puedes crear esta página si no la tienes
        exit;
    }
?>

<?php
include('partials/Header.php');
?>

<div class="container col-md-6">
        <?php
        include('partials/formRegistro.php');
        ?>
</div>
<?php
include('partials/Footer.php');
?>