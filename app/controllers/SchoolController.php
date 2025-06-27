<?php
require_once "app/models/SchoolModel.php";
require_once 'app/controllers/MainController.php';
class SchoolController extends MainController {
    protected $schoolModel;

        public function __construct($dbConn, $view)
    {
        parent::__construct($dbConn, $view);
        $this->schoolModel = new SchoolModel();
    }

    
}
?>