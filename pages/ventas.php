<?php
    include("./bodyPage/HeadPages.php");

    if ($_SESSION['rol'] != 'Ventas' && $_SESSION['rol'] != 'Administrador') {
        // Redirigir a una página de acceso denegado o a la página principal
        header("Location: access_denied.php"); // Esta página la puedes crear
        exit;
    }
?>
<?php
    include("./bodyPage/HeaderPages.php");
?>
<div class="container mt-5">
<?php
    include("./partials/TablaVentas.php");
?>
</div>

<?php
    include("./bodyPage/footerAdmin.php");
?>