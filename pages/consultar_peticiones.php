<?php
include("./bodyPage/HeadPages.php");
?>
<?php
include("./bodyPage/HeaderPages.php");
if (empty($_SESSION['username'])) {
    header("Location:../index.php");
}
?>
<main>
    <div class="container mt-5">
        <?php
        include("./partials/TablaPeticiones.php");
        ?>
    </div>
</main>

<?php
include("./bodyPage/FooterPages.php");
?>
