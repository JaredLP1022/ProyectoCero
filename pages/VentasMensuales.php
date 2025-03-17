<?php
    include("./bodyPage/HeadPages.php");
?>
<?php
    include("./bodyPage/HeaderPages.php");
   
    if(empty($_SESSION['username'])){
        header("Location:../index.php");
        exit;
    }
    
?>
<div class="container mt-5">
<?php
    include("./partials/ReporteVentasMensuales.php");
?>
</div>
<br>
<?php

    include("./bodyPage/FooterPages.php");
?>