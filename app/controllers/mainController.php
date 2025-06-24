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
        // Debug temporal
        error_log("DEBUG render - folder: " . var_export($folder, true) . ", file: " . var_export($file, true));
        error_log("DEBUG render - folder type: " . gettype($folder) . ", file type: " . gettype($file));
        
        // Validar que folder y file sean strings
        if (!is_string($folder)) {
            error_log("Error: folder debe ser string, se recibió: " . gettype($folder));
            $folder = 'Error';
        }
        
        if (!is_string($file)) {
            error_log("Error: file debe ser string, se recibió: " . gettype($file));
            $file = 'error';
        }
        
        $viewPath = ROOT . "/app/views/{$folder}/{$file}.php";
        error_log("DEBUG render - viewPath: " . $viewPath);
    
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
