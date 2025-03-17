<?php

class ModelRegistro
{

    private $PDO;

    public function __construct()
    {
        include("C:/xampp/htdocs/ProyectoCero/config/db.php");
        $pdo = new db();
        $this->PDO = $pdo->conexion();

    }

    public function agregarUsuario($username, $password, $name, $email, $rol, $estado)
    {
        // Asumir que la base de datos ya está conectada y usar una consulta preparada
        $query = "INSERT INTO usuario (username, password, nombre, email, rol, estado) 
              VALUES (:username, :password, :nombre, :email, :rol, :estado)";

        $stmt = $this->PDO->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':nombre', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':rol', $rol);
        $stmt->bindParam(':estado', $estado);

        return $stmt->execute(); // Retorna true o false según el resultado
    }

    public function ObtenerClave($username)
{
    $statement = $this->PDO->prepare("SELECT password FROM usuario WHERE username = :username");
    $statement->bindParam(":username", $username);
    $statement->execute();
    $result = $statement->fetch();
    
    if ($result) {
        return $result['password'];
    } else {
        return false; // Usuario no encontrado
    }
}

}

?>