<?php
class MainController
{
    //Conexión a la base de datos.
    protected $dbConn;
    protected $view;
    protected $sessionManager;
    public function __construct($dbConn, $view = null)
    {
        $this->dbConn = $dbConn;
        $this->view = $view;
        
        // Incluir SessionManager explícitamente
        require_once __DIR__ . '/../library/SessionManager.php';
        $this->sessionManager = new SessionManager();
    }

    // Función para renderizar vistas
    protected function render($folder, $file = 'index', $data = [])
    {
        // Debug temporal
        //error_log("DEBUG render - folder: " . var_export($folder, true) . ", file: " . var_export($file, true));
        //error_log("DEBUG render - folder type: " . gettype($folder) . ", file type: " . gettype($file));
        
        // Validar que folder y file sean strings
        if (!is_string($folder)) {
            error_log("Error: folder debe ser string, se recibió: " . gettype($folder));
            $folder = 'Error';
        }
        
        if (!is_string($file)) {
            //error_log("Error: file debe ser string, se recibió: " . gettype($file));
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
            // Redirigir a la página de error 404
            http_response_code(404);
            require_once ROOT . '/app/controllers/errorController.php';
            $error = new ErrorController($this->dbConn);
            $error->Error('404');
        }
    }
    
    /**
     * Redirige a una URL específica
     * 
     * @param string $url URL de destino
     * @return void
     */
    protected function redirect($url) 
    {
        if (!headers_sent()) {
            header("Location: " . $url);
            exit();
        } else {
            echo "<script>window.location.href = '" . htmlspecialchars($url) . "';</script>";
            echo "<noscript><meta http-equiv='refresh' content='0;url=" . htmlspecialchars($url) . "'></noscript>";
        }
    }
}
