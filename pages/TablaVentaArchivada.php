<?php
    include("./bodyPage/HeadPages.php");
?>
<?php
    include("./bodyPage/HeaderPages.php");
    if(empty($_SESSION['username'])){
        header("Location:../index.php");
    }
?>
<div class="container mt-5">
<?php
    include("./partials/ventaArchivada.php");
?>
</div>

<?php
    include("./bodyPage/FooterPages.php");
?>