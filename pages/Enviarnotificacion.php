<?php
include("./bodyPage/HeadPages.php");
if (empty($_SESSION['username'])) {
    header("Location:../index.php");
}
?>
<?php
include("./bodyPage/HeaderPages.php");
?>
<main>
    <div class="container mt-5">
        <?php
        include("./partials/formNotificacion.php");
        ?>
    </div>
</main>

<?php
include("./bodyPage/FooterPages.php");
?>
