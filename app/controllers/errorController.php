<?php
class ErrorController extends MainController {
    public function __construct($dbConn, $view) {
        parent::__construct($dbConn, $view);
    }

    public function Error ($mensaje){
        $this->view->Render('Error', 'error', ['mensaje' => $mensaje]);
    }

        public function errormsg($msg = "Error desconocido") {
        echo "<h1>$msg</h1>";
    }
}