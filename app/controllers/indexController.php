<?php

class IndexController extends MainController{
    public function __construct($dbConn, $view)
    {
   //echo "Mi primer controlador";
   parent::__construct($dbConn, $view);      
    }
    public function Index() {
        $this->view->Render('Index', 'index');
    }
}