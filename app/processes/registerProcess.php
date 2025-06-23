<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)));
}

require_once ROOT . '/app/scripts/connection.php';
require_once ROOT . '/app/controllers/registerController.php';

$dbConn = getConnection();

$controller = new registerController($dbConn);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['subject'] === 'register') {
    $controller->registerUser(); 
}