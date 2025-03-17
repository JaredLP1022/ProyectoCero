<?php
class controladorRegistro
{
    private $Model;

    public function __construct()
    {
        include("C:/xampp/htdocs/ProyectoCero/model/ModelRegistro.php");
        $this->Model = new ModelRegistro();
    }

    public function guardarUsuario($username, $password, $nombre, $email, $rol, $estado)
    {
        // Pasar todos los parámetros al modelo
        return $this->Model->agregarUsuario(
            $this->limpiarUsername($username),
            $this->encriptadoContra($this->limpiarContraseña($password)),
            $this->limpiarName($nombre),
            $this->limpiarEmail($email),
            $rol,
            $estado
        );
    }
    
    public function limpiarContraseña($campo)
    {
        $campo = strip_tags($campo);
        $campo = filter_var($campo, FILTER_UNSAFE_RAW);
        $campo = htmlspecialchars($campo);
        return $campo;
    }

    public function limpiarName($campo)
    {
        $campo = strip_tags($campo);
        $campo = filter_var($campo, FILTER_UNSAFE_RAW);
        $campo = htmlspecialchars($campo);
        return $campo;
    }

    public function limpiarUsername($campo)
    {
        $campo = strip_tags($campo);
        $campo = filter_var($campo, FILTER_UNSAFE_RAW);
        $campo = htmlspecialchars($campo);
        return $campo;
    }
    public function limpiarEmail($campo)
    {
        $campo = strip_tags($campo);
        $campo = filter_var($campo, FILTER_SANITIZE_EMAIL);
        $campo = htmlspecialchars($campo);
        return $campo;
    }
    
    public function encriptadoContra($contra)
    {
        return password_hash($contra, PASSWORD_BCRYPT);
    }

    public function verificarUser($username, $password)
{
    $keydb = $this->Model->ObtenerClave($username);
    
    // Verifica si la contraseña recuperada de la base de datos es válida
    var_dump($keydb); // Muestra el hash recuperado
    
    return ($keydb && password_verify($password, $keydb)) ? true : false;
}
}

?>