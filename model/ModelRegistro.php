<?php 

    class ModelRegistro{

        private $PDO;

        public function __construct(){
            include("C:/xampp/htdocs/ProyectoCero/config/db.php");
            $pdo = new db();
            $this->PDO = $pdo->conexion();

        }

        public function agregarUsuario($username,$password,$name){
            $statement = $this->PDO->prepare("INSERT INTO usuario values(null, :username, :password, :nombre)");
            $statement->bindParam(":username",$username);
            $statement->bindParam(":password",$password);
            $statement->bindParam(":nombre",$name);
            try {
                $statement->execute();
                return true;
            } catch (PDOException $e) {
                return false;
            }
        }
        public function ObtenerClave($username){
            $statement = $this->PDO->prepare("SELECT password from usuario WHERE username = :username");
            $statement->bindParam(":username",$username);
            return ($statement->execute()) ? $statement->fetch()['password'] : false;
        }
    }

?>