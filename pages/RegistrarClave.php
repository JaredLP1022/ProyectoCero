<?php
    include("./bodyPage/HeadPages.php");

if ($_SESSION['rol'] != 'Administrador') {
    header("Location: access_denied.php");
    exit;
}
?>

<?php
    include("./bodyPage/HeaderPages.php");
?>
<div class="container mt-5">
<?php
    include("guardarClaveMaestra.php");
?>
</div>

<?php
    include("./bodyPage/FooterPages.php");
?>