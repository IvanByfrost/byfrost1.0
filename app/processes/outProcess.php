<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)));
}
require_once ROOT . '/app/scripts/connection.php';
require_once ROOT . '/app/models/userModel.php';

$dbConn = getConnection(); // usa tu método de conexión
$controller = new LoginController($dbConn);
$controller->logout();