<?php
require_once ROOT . '/app/models/studentModel.php';

class studentController extends MainController
{
    protected $dbConn;
    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
    }

    //
    //
    //
}
