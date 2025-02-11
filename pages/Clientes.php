<?php
    include("./bodyPage/HeadPages.php");
?>
<?php
    include("./bodyPage/HeaderPages.php");
    if(empty($_SESSION['username'])){
        header("Location:../index.php");
    }
?>

<div class="container mt-3">
<?php
    include("./partials/TablaClientes.php");
?>
</div>


<?php
    include("./bodyPage/footerAdmin.php");
?>