<?php
class controladorRegistro
{
    private $Model;
    public function __construct()
    {
        include("C:/xampp/htdocs/ProyectoCero/model/ModelRegistro.php");

        $this->Model = new ModelRegistro();

    }

    public function guardarUsuario($username, $password, $name)
    {
        $valor = $this->Model->agregarUsuario($this->limpiarUsername($username), $this->encriptadoContra($this->limpiarContraseña($password)), name: $this->limpiarName($name));
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
    public function encriptadoContra($contra)
    {
        return password_hash($contra, PASSWORD_BCRYPT);
    }

    public function verificarUser($username,$password){
        $keydb = $this->Model->ObtenerClave($username);
        return (password_verify($password,$keydb)) ? true : false;

    }
}
?>