<?php
class controllerCliente
{
    private $Model;
    public function __construct()
    {
        include("C:/xampp/htdocs/ProyectoCero/model/ModelCliente.php");

        $this->Model = new ModelCliente();

    }

    public function guardarUsuario($nombre, $email, $telefono, $direccion, $fechaReg)
    {
        $valor = $this->Model->agregarCliente($this->limpiarnombre($nombre), $this->limpiarcorreo($email), $this->limpiarPhone($telefono), $this->limpiardireccion($direccion), $this->limpiarFecha($fechaReg));
    }

    public function limpiarnombre($campo)
    {
        $campo = strip_tags($campo);
        $campo = filter_var($campo, FILTER_UNSAFE_RAW);
        $campo = htmlspecialchars($campo);
        return $campo;
    }
    public function limpiarcorreo($campo)
    {
        $campo = strip_tags($campo);
        $campo = filter_var($campo, FILTER_UNSAFE_RAW);
        $campo = htmlspecialchars($campo);
        return $campo;
    }
    public function limpiarPhone($campo)
    {
        $campo = strip_tags($campo);
        $campo = filter_var($campo, FILTER_UNSAFE_RAW);
        $campo = htmlspecialchars($campo);
        return $campo;
    }

    public function limpiardireccion($campo)
    {
        $campo = strip_tags($campo);
        $campo = filter_var($campo, FILTER_UNSAFE_RAW);
        $campo = htmlspecialchars($campo);
        return $campo;
    }
    public function limpiarFecha($campo)
    {
        $campo = strip_tags($campo);
        $campo = filter_var($campo, FILTER_UNSAFE_RAW);
        $campo = htmlspecialchars($campo);
        return $campo;
    }
    public function limpiarEstado($campo)
    {
        $campo = strip_tags($campo);
        $campo = filter_var($campo, FILTER_UNSAFE_RAW);
        $campo = htmlspecialchars($campo);
        return $campo;
    }
}
?>