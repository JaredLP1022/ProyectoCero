<?php
    include("controllers/controllerRegistro.php");

    $obj = new controladorRegistro();

    $nombre = $_POST["name"];
    $usuario = $_POST["username"];
    $contra = $_POST["password"];
    $contraConfi = $_POST["ConfirmPassword"];
    $error;

   if(empty($usuario) || empty($contra) || empty($contraConfi || empty($nombre))){
    $error .= "<p>Completa los campos</p>";
    header("Location:registroAdmin.php?error=".$error."&&Usuario=".$usuario."&&contraseña=".$contra."&&ConfirmarContraseña".$contraConfi);
   }else if($usuario && $contra && $contraConfi && $nombre){
        if($contra == $contraConfi){
            if($obj->guardarUsuario($usuario,$contra, $nombre) == false){
                $error = "<p>Usuario ya registrado</p>";
                header("Location:registroAdmin.php?error=".$error."&&Usuario=".$usuario."&&contraseña=".$contra."&&ConfirmarContraseña".$contraConfi);
            }else{
                header("Location:index.php");
            }
        }else{
            $error = "<p>Las contraseñas no coinciden</p>";
            header("Location:registroAdmin.php?error=".$error."&&Usuario=".$usuario."&&contraseña=".$contra."&&ConfirmarContraseña".$contraConfi);
        }
   }

?>