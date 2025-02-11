<?php
include("C:/xampp/htdocs/ProyectoCero/config/db.php");

$pdo = new db();
$PDO = $pdo->conexion();

$comando = $PDO->query("SELECT * FROM producto");
$comando->execute();

$result = $comando->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="contenedorPanel">
  <div class="botonCss">
    <button title="Volver" class=" border-white botonCerrar ColorLetra" type="submit"
      onclick="location.href='Panel-administrador.php'">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-left-fill"
        viewBox="0 0 16 16">
        <path
          d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z" />
      </svg>
    </button>
  </div>
  <div class="tituloCss">
    <h4 class="text-center ColorLetra">Servicios y productos</h4>
  </div>
  <div class="botonCss">
    <button title="Home" class=" border-white botonCerrar ColorLetra" type="submit"
      onclick="location.href='Panel-Administrador.php'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
        fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
        <path
          d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z" />
      </svg></button>
  </div>
</div>
<hr class="bg-white">
<div class="container">
  <div class="row row-cols-1 row-cols-md-3 g-4">
    <?php
    foreach ($result as $row) {
      ?>
      <div class="col" >
        <div class="card bg-transparent border-white border h-100">
          <img src="./bodyPage/logo.png" class="card-img-top">
          <div class="card-body">
            <h5 class="card-title"><?php echo $row['description'] ?></h5>
            <p class="card-text">$<?php echo $row['price'] ?></p>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>
<br>