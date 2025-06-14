<?php
class mainController
{
    protected $dbConn;
    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
    }

    // Función para renderizar vistas
    protected function render($viewPath, $data = [])
    {
        extract($data); // convierte claves en variables (ej: $teachers)
        require "app/views/{$viewPath}.php";
    }
}
