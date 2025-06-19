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
        $this->teacherModel = new TeacherModel($dbConn);
    }
    public function showDashTeacher()
    {
        $teachers = $this->teacherModel->getTeachers();
        $this->render('teacher/dashboard', ['teachers' => $teachers]);
    }

    // Función para crear un profesor
    public function createTeacher($data)
    {
        //Implementar la lógica para crear un profesor
        if (empty($data['name']) || empty($data['email'])) {
            $this->render('teacher/error', ['message' => 'Faltan campos obligatorios']);
            return;
        }
        $teacher = $this->teacherModel->createTeacher($data);
        if ($teacher) {
            $this->render('teacher/success', ['message' => 'Profesor creado exitosamente']);
        } else {
            $this->render('teacher/error', ['message' => 'Error al crear el profesor']);
        }
    }
    // Función para consultar un profesor
    // Función para actualizar un profesor
    // Función para eliminar un profesor
}
