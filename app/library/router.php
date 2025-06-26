<?php
class Router
{
    protected $controller = 'IndexController';
    protected $method = 'index';
    protected $params = [];

    protected $dbConn;
    protected $view;

    public function __construct($dbConn, $view)
    {
        $this->dbConn = $dbConn;
        $this->view = $view;

        // Si hay un parámetro 'view', usar el routerView
        if (isset($_GET['view'])) {
            require_once ROOT . '/app/scripts/routerView.php';
            return;
        }

        $this->parseUrl();
        $this->loadController();
        if (isset($_GET['error'])) {
            require_once ROOT.'/app/controllers/errorController.php';
            $error = new ErrorController($this->dbConn, $this->view);
            $error->Error($_GET['error']);
            return;
        }
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
                $error->Error($message); // mensaje personalizado o código
                break;
        }
    }
}
