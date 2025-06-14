<?php
class ErrorController extends MainController {
    public function __construct(){
        parent::__construct();
    }
    public function Error ($url){
        $this->view->Render($this,"error",$url);
    }
}