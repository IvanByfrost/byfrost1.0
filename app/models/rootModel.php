<?php
class RootModel extends MainModel
{
    //Constructor de la clase.
    protected $dbConn;
    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
    }
}
