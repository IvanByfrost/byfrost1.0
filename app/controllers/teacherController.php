<?php
require_once "mainController.php";
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
    public function dashTeacher()
    {
        $teachers = $this->teacherModel->getTeachers();
        $this->render('teacher/dashboard', ['teachers' => $teachers]);
    }
}
