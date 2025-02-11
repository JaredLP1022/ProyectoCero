<?php 

    class ModelCliente{

        private $PDO;

        public function __construct(){
            include("C:/xampp/htdocs/ProyectoCero/config/db.php");
            $pdo = new db();
            $this->PDO = $pdo->conexion();

        }

        public function agregarCliente($nombre,$email,$telefono,$direccion,$fechaReg){
            $statement = $this->PDO->prepare("INSERT INTO clientes (nombre, email, telefono, direccion, fecha_registro, fecha_modificacion, estado) values( :nombre, :email, :telefono, :direcion, :fecha_registro, :fecha_modificacion,:estado)");
            $statement->bindParam(":nombre",$nombre);
            $statement->bindParam(":email",$email);
            $statement->bindParam(":telefono",$telefono);
            $statement->bindParam(":direccion",$direccion);
            $statement->bindParam(":fecha_registro",$fechaReg);
            try {
                $statement->execute();
             
                return true;
            } catch (PDOException $e) {
                return false;
            }
        }
       
    }

?>