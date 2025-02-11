<?php
    //incluimos la conexcion de la base de datos
    include("C:/xampp/htdocs/ProyectoCero/config/db.php");

    $pdo = new db();
    $PDO = $pdo->conexion();
    
    //vericamos que exista el id que se desea eliminar 
    if (isset($_GET['id'])) {
        //obtenemos el id a eliminar 
        $id = $_GET['id'];
        //ejecutamos la sentencia de sql para eliminar un elemento de la base de datos
        $comando = $PDO->query("DELETE FROM cliente WHERE id= $id");
        $comando->execute();
            
        header("Location: Clientes.php");
        
    }
?>
