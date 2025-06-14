<?php
class mainController
{
    //Conexión a la base de datos.
    protected $dbConn;
    protected $view;
    public function __construct($dbConn, $view)
    {
        $this->dbConn = $dbConn;
        $this->view = $view;
    }

    // Función para renderizar vistas
    protected function render($viewPath, $data = [])
    {
        extract($data); // convierte claves en variables (ej: $teachers)
        require "app/views/{$viewPath}.php";
    }
    protected function redirect($url) {

    }

    
}
