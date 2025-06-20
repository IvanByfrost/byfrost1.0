<?php
require_once ROOT . '/app/models/subjectModel.php';

class subjectController extends MainController
{
    protected $dbConn;
    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
    }

    public function getAllSubjects() {
        $subjectModel = new subjectModel($this->dbConn);
        $subjects = $subjectModel->getAllSubjects();
        
        // Cargar la vista con los datos de las asignaturas
        require_once app.views . 'subject/subjectList.php';
    }

    
}
