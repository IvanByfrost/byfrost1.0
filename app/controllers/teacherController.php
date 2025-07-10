<?php
require_once "app/models/teacherModel.php";

// Controlador para manejar las operaciones relacionadas con los profesores
class TeacherController extends MainController
{
    protected $teacherModel;
    // Constructor que inicializa el modelo de profesores
    public function __construct($dbConn)
    {
        parent::__construct($dbConn);
        $this->teacherModel = new TeacherModel();
    }
    public function dashboard()
    {
        $this->protectTeacher();
        $teachers = $this->teacherModel->getTeachers();
        $this->loadDashboardView('teacher/dashboard', ['teachers' => $teachers]);
    }

    // Función para crear un profesor
    public function createTeacher($data)
    {
        $this->protectTeacher();
        //Implementar la lógica para crear un profesor
        if (empty($data['name']) || empty($data['email'])) {
            $this->render('teacher/error', ['message' => 'Faltan campos obligatorios']);
            return;
        }
        $teacher = $this->teacherModel->createTeacher($data);
        if ($teacher) {
            $this->render('teacher/success', ['message' => 'Profesor creado exitosamente']);
        } else {
            $this->render('Error/error', ['message' => 'Error al crear el profesor']);
        }
    }
    // Función para consultar un profesor
    // Función para actualizar un profesor
    // Función para eliminar un profesor
    
    // Protección de acceso solo para profesores
    private function protectTeacher() {
        if (!isset($this->sessionManager) || !$this->sessionManager->isLoggedIn() || !$this->sessionManager->hasRole('teacher')) {
            header('Location: /?view=unauthorized');
            exit;
        }
    }

    /**
     * Carga una vista parcial vía AJAX para el módulo teacher
     */
    public function loadPartial()
    {
        $view = htmlspecialchars($_POST['view']) ?? htmlspecialchars($_GET['view']) ?? '';
        $action = htmlspecialchars($_POST['action']) ?? htmlspecialchars($_GET['action']) ?? 'index';
        $partialView = htmlspecialchars($_POST['partialView']) ?? htmlspecialchars($_GET['partialView']) ?? '';
        $force = isset(htmlspecialchars($_POST['force'])) || isset(htmlspecialchars($_GET['force']));

        if (!$this->isAjaxRequest() && !$force) {
            if (empty($partialView)) {
                echo '<div class="alert alert-warning">Vista no especificada. Use: ?view=teacher&action=loadPartial&partialView=vista</div>';
                return;
            }
            $viewPath = 'teacher/' . $partialView;
            $fullPath = ROOT . "/app/views/{$viewPath}.php";
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
        $viewPath = 'teacher/' . $partialView;
        $fullPath = ROOT . "/app/views/{$viewPath}.php";
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