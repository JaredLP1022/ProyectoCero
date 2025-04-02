<?php
include("./bodyPage/HeadPages.php");

if ($_SESSION['rol'] != 'Ventas' && $_SESSION['rol'] != 'Administrador') {
    header("Location: access_denied.php");
    exit;
}

?>

<script type="text/javascript">
    // Función para redirigir al login después de 20 minutos de inactividad
    setTimeout(function() {
        window.location.href = '../login.php';  // Redirige al login
    }, 1200000);  // 1200000 ms = 20 minutos
</script>

<?php
    include("./bodyPage/HeaderPages.php");
?>
<div class="container mt-5">
<?php
    include("./partials/buscarReporteCliente.php");
?>
</div>

<?php
    include("./bodyPage/FooterPages.php");
?>