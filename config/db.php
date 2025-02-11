<?php
class db {
    private $host = "localhost";
    private $port = 33066;
    private $dbname = "sistema_clientes";
    private $user = "root";
    private $password = "";

    public function conexion() {
        try {
            // Crea la instancia PDO con la cadena de conexión
            $pdo = new PDO(
                "mysql:host={$this->host};port={$this->port};dbname={$this->dbname}",
                $this->user,
                $this->password
            );

            // Configura PDO para lanzar excepciones si hay errores
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        } catch (PDOException $e) {
            // En caso de error, muestra el mensaje y detiene la ejecución
            die("Error de conexión: " . $e->getMessage());
        }
    }
}
?>

