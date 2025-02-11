<?php
include('partials/Head.php');
if(!empty($_SESSION['username'])){
    header("Location:pages/Panel-Administrador.php");
}
?>

<?php
include('partials/Header.php');
?>

<div class="container col-md-3">
    <?php
    include('partials/formlogin.php');
    ?>
</div>
<?php
include('partials/Footer.php');
?>