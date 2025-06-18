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
    protected function render($folder, $file = 'index', $data = [])
    {
        $viewPath = ROOT . "/app/views/{$folder}/{$file}.php";
    
        if (file_exists($viewPath)) {
            extract($data);
            require ROOT . '/app/views/layouts/head.php';
            require ROOT . '/app/views/layouts/header.php'; // opcional
            require $viewPath;
            require ROOT . '/app/views/layouts/footer.php';
        } else {
            http_response_code(500);
            echo "Vista no encontrada: {$folder}/{$file}.php";
        }
    }
    protected function redirect($url) {

    }

    
}
