<?php
function getConnection() {
    try {
        $db = new PDO("mysql:host=localhost;dbname=baldur-test", 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->exec("SET NAMES utf8");
        date_default_timezone_set('America/Bogota');
        return $db;
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}