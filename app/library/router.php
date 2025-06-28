<?php
class Router
{
    protected $controller = 'IndexController';
    protected $method = 'index';
    protected $params = [];

    protected $dbConn;
    protected $view;

    public function __construct($dbConn, $view = null)
    {
        $this->dbConn = $dbConn;
        $this->view = $view;

        // Verificar errores primero
        if (isset($_GET['error'])) {
            require_once ROOT.'/app/controllers/errorController.php';
            $error = new ErrorController($this->dbConn, $this->view);
            $error->Error($_GET['error']);
            return;
        }

        // Si hay un parámetro 'view', usar el routerView
        if (isset($_GET['view'])) {
            require_once ROOT . '/app/scripts/routerView.php';
            return;
        }

        // Si no hay parámetro 'url', puede ser una ruta no manejada por Apache
        if (!isset($_GET['url'])) {
            // Obtener la ruta de la URL actual
            $requestUri = $_SERVER['REQUEST_URI'] ?? '';
            $basePath = '/byfrost1.0/';
            
            if (strpos($requestUri, $basePath) === 0) {
                $path = substr($requestUri, strlen($basePath));
                $path = trim($path, '/');
                
                if (!empty($path)) {
                    $_GET['url'] = $path;
                }
            }
        }

        $this->parseUrl();
        $this->loadController();
    }

    protected function parseUrl()
    {
        $url = $_GET['url'] ?? 'Index/index';
        $url = filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL);
        $segments = explode('/', $url);

        if (!empty($segments[0])) {
            $this->controller = ucfirst($segments[0]) . 'Controller';
        }
        if (!empty($segments[1])) {
            $this->method = $segments[1];
        }
        if (count($segments) > 2) {
            $this->params = array_slice($segments, 2);
        }
    }

    protected function loadController()
    {
        $controllerPath = ROOT . "/app/controllers/{$this->controller}.php";
        if (file_exists($controllerPath)) {
            require_once $controllerPath;
            $controllerInstance = new $this->controller($this->dbConn, $this->view);

            if (method_exists($controllerInstance, $this->method)) {
                call_user_func_array([$controllerInstance, $this->method], $this->params);
            } else {
                $this->handleError("Método '{$this->method}' no encontrado.");
            }
        } else {
            // Si el controlador no existe, redirigir a 404
            $this->handleError("Controlador '{$this->controller}' no encontrado.");
        }
    }

    protected function handleError($message = 'Página no encontrada', $code = 404)
    {
        http_response_code($code);
        require_once ROOT . '/app/controllers/errorController.php';
    
        $error = new ErrorController($this->dbConn, $this->view);
    
        // Forzamos la vista de error por código (sin depender de $_GET)
        switch ($code) {
            case 400:
                $error->Error('400');
                break;
            case 500:
                $error->Error('500');
                break;
            case 404:
            default:
                $error->Error('404'); // Siempre usar '404' para errores de página no encontrada
                break;
        }
    }
}
