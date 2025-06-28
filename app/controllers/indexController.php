<?php
require_once 'app/controllers/MainController.php';
class IndexController extends MainController{
    public function __construct($dbConn)
    {
   //echo "Mi primer controlador";
   parent::__construct($dbConn);      
    }
    public function Index() {
        $this->render('index', 'index');
    }
}