<?php
function getConnection() {

    $host = 'localhost';
    $dbName = 'baldur-test';
    $user = 'root';
    $pass = ''; 

    $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
        PDO::ATTR_EMULATE_PREPARES   => false,             
    ];

    try {
        $db = new PDO($dsn, $user, $pass, $options);
                
        date_default_timezone_set('America/Bogota'); 

        return $db;
    } catch (PDOException $e) {

        die("Error de conexión: " . $e->getMessage());
    }
}