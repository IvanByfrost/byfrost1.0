<?php
require_once 'app/models/DirectorModel.php';
require_once 'app/controllers/MainController.php';

class DirectorController extends MainController {
    protected $directorModel;
    
    public function __construct($dbConn)
    {
        // Asegurar que session_start() solo se llame una vez y antes de cualquier salida
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct($dbConn);
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
        try {
            // Obtener estadísticas para el dashboard
            $stats = $this->getDashboardStats();
            
            // Cargar la vista del dashboard
            $this->loadDashboardView('director/dashboard', [
                'stats' => $stats,
                'currentUser' => $this->sessionManager->getCurrentUser()
            ]);
        } catch (Exception $e) {
            ErrorHandler::logError("Error en dashboard del director: " . $e->getMessage());
            $this->loadView('Error/500', ['error' => $e->getMessage()]);
        }
    }

    // Dashboard parcial del director (vista principal)
    public function dashboardPartial() {
        $this->protectDirector();
        $this->loadPartialView('director/dashboardPartial');
    }

    // Vista de inicio del dashboard del director
    public function dashboardHome() {
        $this->protectDirector();
        $this->loadPartialView('director/dashboardHome');
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
        try {
            $directors = $this->directorModel->getAllDirector();
            $this->loadView('director/directorLists', ['directors' => $directors]);
        } catch (Exception $e) {
            ErrorHandler::logError("Error en listAction: " . $e->getMessage());
            $this->loadView('Error/500', ['error' => $e->getMessage()]);
        }
    }

    // Mostrar el formulario para agregar un nuevo rector
    public function newDirector() {
        $this->protectDirector();
        $this->loadView('director/createDirector');
    }

    // Procesar la adición de un nuevo rector
    public function addDirector() {
        $this->protectDirector();
        
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validar datos requeridos
                $requiredFields = ['userName', 'userLastName', 'email', 'password'];
                foreach ($requiredFields as $field) {
                    if (empty($_POST[$field])) {
                        throw new Exception("El campo $field es obligatorio");
                    }
                }
                
                $userName = htmlspecialchars($_POST['userName']);
                $userLastName = htmlspecialchars($_POST['userLastName']);
                $userEmail = htmlspecialchars($_POST['email']);
                $userPassword = $_POST['password']; // No hashear aquí, el modelo se encarga
                $phoneUser = htmlspecialchars($_POST['phoneUser'] ?? '');

                if ($this->directorModel->createDirector($userName, $userLastName, $userEmail, $userPassword, $phoneUser)) {
                    $_SESSION['success'] = 'Director creado exitosamente';
                    $this->redirect(url . '?view=director&action=listAction');
                } else {
                    throw new Exception('Error al crear el director');
                }
            } else {
                $this->loadView('director/createDirector');
            }
        } catch (Exception $e) {
            ErrorHandler::logError("Error en addDirector: " . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
            $this->loadView('director/createDirector');
        }
    }

    // Mostrar el formulario para editar un rector existente
    public function editDirector() {
        $this->protectDirector();
        
        try {
            $directorId = htmlspecialchars($_GET['id'] ?? '');
            if (empty($directorId)) {
                throw new Exception('ID de director no proporcionado');
            }
            
            $director = $this->directorModel->getDirectorById($directorId);
            if (!$director) {
                throw new Exception('Director no encontrado');
            }
            
            $this->loadView('director/editDirector', ['director' => $director]);
        } catch (Exception $e) {
            ErrorHandler::logError("Error en editDirector: " . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
            $this->redirect(url . '?view=director&action=listAction');
        }
    }

    // Procesar la actualización de un rector
    public function directorUpdate() {
        $this->protectDirector();
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            $directorId = htmlspecialchars($_POST['director_id'] ?? '');
            if (empty($directorId)) {
                throw new Exception('ID de director no proporcionado');
            }
            
            $userName = htmlspecialchars($_POST['userName'] ?? '');
            $userLastName = htmlspecialchars($_POST['userLastName'] ?? '');
            $userEmail = htmlspecialchars($_POST['email'] ?? '');
            $phoneUser = htmlspecialchars($_POST['phoneUser'] ?? '');
            
            // Validar campos requeridos
            if (empty($userName) || empty($userLastName) || empty($userEmail)) {
                throw new Exception('Los campos nombre, apellido y email son obligatorios');
            }
            
            if ($this->directorModel->updateDirector($directorId, $userName, $userLastName, $userEmail, $phoneUser)) {
                $_SESSION['success'] = 'Director actualizado exitosamente';
            } else {
                throw new Exception('Error al actualizar el director');
            }
            
            $this->redirect(url . '?view=director&action=listAction');
        } catch (Exception $e) {
            ErrorHandler::logError("Error en directorUpdate: " . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
            $this->redirect(url . '?view=director&action=listAction');
        }
    }

    // Eliminar un rector
    public function deleteDirector() {
        $this->protectDirector();
        
        try {
            $directorId = htmlspecialchars($_GET['id'] ?? '');
            if (empty($directorId)) {
                throw new Exception('ID de director no proporcionado');
            }
            
            if ($this->directorModel->deleteDirector($directorId)) {
                $_SESSION['success'] = 'Director eliminado exitosamente';
            } else {
                throw new Exception('Error al eliminar el director');
            }
            
            $this->redirect(url . '?view=director&action=listAction');
        } catch (Exception $e) {
            ErrorHandler::logError("Error en deleteDirector: " . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
            $this->redirect(url . '?view=director&action=listAction');
        }
    }

    /**
     * Protege las acciones del director
     */
    private function protectDirector() {
        if (!$this->sessionManager->isLoggedIn()) {
            $this->redirect(url . '?view=index&action=login');
        }
        
        if (!$this->sessionManager->hasRole(['director', 'root'])) {
            $this->loadView('Error/403');
        }
    }

    /**
     * Obtener estadísticas para el dashboard
     */
    private function getDashboardStats() {
        try {
            $stats = [
                'total_schools' => 0,
                'total_teachers' => 0,
                'total_students' => 0,
                'total_activities' => 0
            ];
            
            // Aquí puedes agregar las consultas reales a la base de datos
            // Por ahora usamos valores por defecto
            
            return $stats;
        } catch (Exception $e) {
            ErrorHandler::logError("Error obteniendo estadísticas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Carga una vista parcial
     */
    public function loadPartial() {
        $this->protectDirector();
        $view = htmlspecialchars($_GET['partial'] ?? '');
        
        if (empty($view)) {
            echo "Vista parcial no especificada";
            return;
        }
        
        $this->loadPartialView("director/$view");
    }
}
?>