<?php
class LoginController extends mainModel
{
    // Constructor de la clase 
    protected $dbConn;
    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
    }

    // Muestra el formulario de login
    public function index() {}

    // Procesa el formulario y redirige al dashboard según el rol
    public function auth() {}

    // Cierra la sesión y vuelve al login
    public function logout() {}
}
