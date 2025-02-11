<?php
    include("controllers/controllerRegistro.php");
    session_start();
    $obj = new controladorRegistro();

    $usuario = $obj->limpiarName($_POST["username"]);
    $contra = $obj->limpiarContraseña($_POST["password"]);
    $error;
    $bandera = $obj->verificarUser($usuario,$contra);
    if($bandera){
        $_SESSION["username"] = $usuario;
        header("Location:pages/Panel-Administrador.php");
    }else{
        $error = "<p>Las claves de acceso no coinciden</p>";
        header("Location:index.php?error=".$error);
    }
    

?>