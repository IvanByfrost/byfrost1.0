<?php
require_once './modelo/CoordinadorModelo.php';

class CoordinadorController {
    private $modelo;

    public function __construct() {
        $this->modelo = new CoordinadorModelo();
    }

    public function mostrarPanel() {
        $datos = $this->modelo->obtenerDatos();
        require './vista/index.php';
    }
}
