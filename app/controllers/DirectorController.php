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
        // Cargar la vista completa del dashboard del director (con layouts y menú)
        require_once ROOT . '/app/views/director/dashboard.php';
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
}
?>