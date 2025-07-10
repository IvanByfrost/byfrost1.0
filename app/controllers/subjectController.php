<?php
require_once ROOT . '/app/models/subjectModel.php';

class subjectController extends MainController
{
    protected $dbConn;
    public function __construct($dbConn)
    {
        // Asegurar que session_start() solo se llame una vez y antes de cualquier salida
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->dbConn = $dbConn;
    }

    public function getAllSubjects() {
        $subjectModel = new subjectModel();
        $subjects = $subjectModel->getAllSubjects();
        
        // Cargar la vista con los datos de las asignaturas
        require_once app.views . 'subject/subjectList.php';
    }

    
}
