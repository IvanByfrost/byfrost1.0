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
     * Renderiza una vista parcial sin header y footer
     * Útil para cargar contenido en dashboards o AJAX
     */
    protected function renderPartial($folder, $file = 'index', $data = [])
    {
        $viewPath = ROOT . "/app/views/{$folder}/{$file}.php";
        
        if (file_exists($viewPath)) {
            extract($data);
            require $viewPath;
        } else {
            echo "Vista parcial no encontrada: $viewPath";
        }
    }
    
    /**
     * Renderiza solo el contenido de la vista sin ningún layout
     * Retorna el contenido como string
     */
    protected function renderContent($folder, $file = 'index', $data = [])
    {
        $viewPath = ROOT . "/app/views/{$folder}/{$file}.php";
        
        if (file_exists($viewPath)) {
            extract($data);
            ob_start();
            require $viewPath;
            $content = ob_get_clean();
            return $content;
        } else {
            return "Vista no encontrada: $viewPath";
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

    /**
     * Carga una vista específica con datos
     * 
     * @param string $viewPath Ruta de la vista (ej: 'school/consultSchool')
     * @param array $data Datos a pasar a la vista
     * @return void
     */
    protected function loadView($viewPath, $data = [])
    {
        $viewPath = ROOT . "/app/views/{$viewPath}.php";
        
        if (file_exists($viewPath)) {
            extract($data);
            require ROOT . '/app/views/layouts/head.php';
            require ROOT . '/app/views/layouts/header.php';
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
     * Verifica si la petición es AJAX
     * 
     * @return bool
     */
    protected function isAjaxRequest()
    {
        // Método 1: Verificar HTTP_X_REQUESTED_WITH
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        }
        
        // Método 2: Verificar si es una petición fetch moderna
        if (!empty($_SERVER['HTTP_ACCEPT']) && 
            strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
            return true;
        }
        
        // Método 3: Verificar si hay parámetros específicos de AJAX
        if (isset($_POST['ajax']) || isset($_GET['ajax'])) {
            return true;
        }
        
        // Método 4: Verificar Content-Type para peticiones POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
            !empty($_SERVER['CONTENT_TYPE']) && 
            strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
            return true;
        }
        
        return false;
    }

    /**
     * Envía una respuesta JSON
     * 
     * @param bool $success Indica si la operación fue exitosa
     * @param string $message Mensaje de respuesta
     * @param array $data Datos adicionales
     * @return void
     */
    protected function sendJsonResponse($success, $message, $data = [])
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }

    /**
     * Carga una vista parcial sin header y footer
     * Útil para cargar contenido en dashboards o AJAX
     * 
     * @param string $viewPath Ruta de la vista (ej: 'school/consultSchool')
     * @param array $data Datos a pasar a la vista
     * @return void
     */
    protected function loadPartialView($viewPath, $data = [])
    {
        $viewPath = ROOT . "/app/views/{$viewPath}.php";
        
        if (file_exists($viewPath)) {
            extract($data);
            require $viewPath;
        } else {
            echo "Vista parcial no encontrada: $viewPath";
        }
    }
}
