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
}