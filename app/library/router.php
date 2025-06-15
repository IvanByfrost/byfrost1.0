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

        $this->parseUrl();
        $this->loadController();
        if (isset($_GET['error'])) {
            require_once 'Controllers/ErrorController.php';
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
        $controllerPath = "Controllers/{$this->controller}.php";
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

    protected function handleError($message)
    {
        require_once 'Controllers/errorController.php';
        $error = new ErrorController($this->dbConn, $this->view);
        $error->Error($message);
    }
}
