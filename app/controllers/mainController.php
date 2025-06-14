<?php
class mainController {
    protected $dbConn;
        public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
    }
}
?>