<?php
require_once 'app/models/DirectorModel.php';
require_once 'app/controllers/mainController.php';

class DirectorController extends MainController {
    protected $dbConn;
    protected $view;
    protected $directorModel;
    
    public function __construct($dbConn)
    {
        parent::__construct($dbConn);
        $this->dbConn = $dbConn;
        $this->directorModel = new DirectorModel();
    }

    // Acción por defecto: Lista de rectores
    public function directorList() {
        $this->protectDirector();
        $this->listAction();
    }

    // Dashboard del director
    public function dashboard() {
        $this->protectDirector();
        // Cargar la vista de inicio moderna del dashboard del director
        require_once ROOT . '/app/views/director/dashboard.php';
    }

    // Dashboard parcial del director (vista principal)
    public function dashboardPartial() {
        $this->protectDirector();
        // Cargar la vista parcial del dashboard del director
        require_once ROOT . '/app/views/director/dashboardPartial.php';
    }

    // Vista de inicio del dashboard del director
    public function dashboardHome() {
        $this->protectDirector();
        // Cargar la vista de inicio del dashboard del director
        require_once ROOT . '/app/views/director/dashboardHome.php';
    }

    // Menú principal del director
    public function menuDirector() {
        $this->protectDirector();
        $this->loadPartialView('director/menuDirector');
    }

    // Método por defecto para el router
    public function index() {
        $this->dashboard();
    }


    // Mostrar la lista de rectores
    public function listAction() {
        $this->protectDirector();
        $directors = $this->directorModel->getAllDirector();
        // Cargar la vista
        require_once app.views . 'director/directorLists.php';
    }

    // Mostrar el formulario para agregar un nuevo rector
    public function newDirector() {
        $this->protectDirector();
        // Cargar la vista del formulario
        require_once app.views . 'director/createDirector.php';
    }

    // Procesar la adición de un nuevo rector
    public function addDirector() {
        $this->protectDirector();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userName = $_POST['userName'] ?? '';
            $userLastName = $_POST['userLastName'] ?? '';
            $userEmail = $_POST['email'] ?? '';
            $userPassword = $_POST['password'] ?? ''; // La contraseña en texto plano
            $phoneUser = $_POST['phoneUser'] ?? null;

            // Validar que la contraseña no esté vacía
            if (empty($userPassword)) {
                echo "Error: La contraseña es obligatoria.";
                return;
            }

            if ($this->directorModel->createDirector($userName, $userLastName, $userEmail, $userPassword, $phoneUser)) {
                header('Location: /software_academico/rector/listar'); // Redirigir a la lista
                exit();
            } else {
                echo "Error al guardar el rector.";
            }
        } else {
            header('Location: /software_academico/rector/crear'); // Si no es POST, redirigir al formulario
            exit();
        }
    }

    // Mostrar el formulario para editar un rector existente
    public function editDirector() {
        $this->protectDirector();
        $id = $_GET['id'] ?? null; // Obtener ID de la URL
        if ($id) {
            $director = $this->directorModel->getDirectorById($id);
            if ($director) {
                require_once app.views. 'rector/editar.php';
            } else {
                echo "Rector no encontrado.";
            }
        } else {
            echo "ID de rector no proporcionado.";
        }
    }

    // Procesar la actualización de un rector
    public function directorUpdate() {
        $this->protectDirector();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_rector'] ?? null;
            $userName = $_POST['userName'] ?? '';
            $userLastName = $_POST['userLastName'] ?? '';
            $userEmail = $_POST['email'] ?? '';
            $phoneUser = $_POST['phoneUser'] ?? null;

            $userEmail = $_POST['email'] ?? '';
            if ($id && $this->directorModel->updateDirector($id, $userName, $userLastName, $userEmail, $phoneUser)) {
                header('Location: /software_academico/rector/listar');
                exit();
            } else {
                echo "Error al actualizar el rector.";
            }
        } else {
            header('Location: /software_academico/rector/listar'); // Redirigir si no es POST
            exit();
        }
    }

    // Eliminar un rector
    public function deleteDirector() {
        $this->protectDirector();
        $id = $_GET['id'] ?? null;
        if ($id) {
            if ($this->directorModel->deleteDirector($id)) {
                header('Location: /software_academico/rector/listar');
                exit();
            } else {
                echo "Error al eliminar el rector.";
            }
        } else {
            echo "ID de rector no proporcionado para eliminar.";
        }
    }

    // Protección de acceso solo para directores
    private function protectDirector() {
        if (!isset($this->sessionManager) || !$this->sessionManager->isLoggedIn() || !$this->sessionManager->hasAnyRole(['director', 'root'])) {
            header('Location: /?view=unauthorized');
            exit;
        }
    }

    /**
     * Carga una vista parcial vía AJAX para el módulo director
     */
    public function loadPartial()
    {
        $view = $_POST['view'] ?? $_GET['view'] ?? '';
        $action = $_POST['action'] ?? $_GET['action'] ?? 'index';
        $partialView = $_POST['partialView'] ?? $_GET['partialView'] ?? '';
        $force = isset($_POST['force']) || isset($_GET['force']);
        $debug = isset($_POST['debug']) || isset($_GET['debug']);

        // Debug: mostrar información
        if ($debug) {
            echo "DEBUG INFO:<br>";
            echo "view: " . htmlspecialchars($view) . "<br>";
            echo "action: " . htmlspecialchars($action) . "<br>";
            echo "partialView: " . htmlspecialchars($partialView) . "<br>";
            echo "force: " . ($force ? 'true' : 'false') . "<br>";
            echo "isAjaxRequest: " . ($this->isAjaxRequest() ? 'true' : 'false') . "<br>";
            echo "ROOT: " . ROOT . "<br>";
        }

        if (!$this->isAjaxRequest() && !$force) {
            if (empty($partialView)) {
                echo '<div class="alert alert-warning">Vista no especificada. Use: ?view=director&action=loadPartial&partialView=vista</div>';
                return;
            }
            $viewPath = 'director/' . $partialView;
            $fullPath = ROOT . "/app/views/{$viewPath}.php";
            
            if ($debug) {
                echo "viewPath: " . htmlspecialchars($viewPath) . "<br>";
                echo "fullPath: " . htmlspecialchars($fullPath) . "<br>";
                echo "file_exists: " . (file_exists($fullPath) ? 'true' : 'false') . "<br>";
            }
            
            if (!file_exists($fullPath)) {
                echo '<div class="alert alert-danger">Vista no encontrada: ' . htmlspecialchars($viewPath) . '</div>';
                return;
            }
            try {
                $this->loadPartialView($viewPath);
            } catch (Exception $e) {
                echo '<div class="alert alert-danger">Error al cargar la vista: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
            return;
        }
        if (empty($partialView)) {
            echo json_encode(['success' => false, 'message' => 'Vista no especificada']);
            return;
        }
        $viewPath = 'director/' . $partialView;
        $fullPath = ROOT . "/app/views/{$viewPath}.php";
        
        if ($debug) {
            echo "AJAX DEBUG INFO:<br>";
            echo "viewPath: " . htmlspecialchars($viewPath) . "<br>";
            echo "fullPath: " . htmlspecialchars($fullPath) . "<br>";
            echo "file_exists: " . (file_exists($fullPath) ? 'true' : 'false') . "<br>";
        }
        
        if (!file_exists($fullPath)) {
            echo json_encode(['success' => false, 'message' => "Vista no encontrada: {$viewPath}"]);
            return;
        }
        try {
            $this->loadPartialView($viewPath);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al cargar la vista: ' . $e->getMessage()]);
        }
    }
}
?>