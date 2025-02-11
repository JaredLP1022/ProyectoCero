<?php
    include("./bodyPage/HeadPages.php");
?>
<?php
    include("./bodyPage/HeaderPages.php");
    if(empty($_SESSION['username'])){
        header("Location:../index.php");
    }
?>
<div class="container mt-5 col-md-6">
<?php
    include("./AccionesClientes/MostrarCliente.php");
?>
</div>

<?php
    include("./bodyPage/FooterPages.php");
?>