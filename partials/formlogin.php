<?php if(!empty($_GET["error"])):?>
    <<div class="alert alert-danger">
        <?= !empty($_GET["error"]) ? $_GET["error"] : ""?>
    </div>
<?php endif;?>

<div class="container position-relative">
    <form class="container formLog " action="verificarLogin.php" method="POST">
        <h2 class="inicioLog">Inicio de sesion</h2>
        <label>Usuario</label>
        <input type="text" name="username" placeholder="Usuario" required>
        <br>
        <label> Contrase&#241;a</label>
        <input type="password" name="password" placeholder="**************" required>
        <br>
        <button class="btn btn-primary btn-lg" id="boton" type="submit" name="btningresar">Iniciar
            sesion</button>
    </form>
</div>