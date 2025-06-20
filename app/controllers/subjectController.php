<?php
require_once ROOT . '/app/models/subjectModel.php';

class subjectController extends MainController
{
    protected $dbConn;
    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
    }

    
}
