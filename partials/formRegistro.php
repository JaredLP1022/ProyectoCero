<?php if(!empty($_GET["error"])):?>
    <<div class="alert alert-danger">
        <?= !empty($_GET["error"]) ? $_GET["error"] : ""?>
    </div>
<?php endif;?>
<div class="container position-relative h-auto">
    
    <form class="container registroJ" action="store.php" method="POST">
        <h2 class="inicioLog">Registro de Usuario</h2>
        <label>Nombre del usuario administrador</label>
        <input type="text" name="name" placeholder="Nombre" >
        <br>
        <label>Usuario</label>
        <input type="text" name="username" placeholder="Usuario" >
        <br>
        <label> Contrase&#241;a</label>
        <input type="password" name="password" placeholder="**************" >
        <br>
        <label>Repetir Contrase&#241;a</label>
        <input type="password" name="ConfirmPassword" placeholder="**************" >
        <br>
        <button class="btn btn-primary btn-lg" id="boton" type="submit" name="btningresar">Registrar</button>
    </form>
</div>