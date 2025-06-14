<?php
$server = "localhost";
$user = "root";
$passw = "";
$datab = "baldur_test";
date_default_timezone_set('America/Bogota');

$conexion = mysqli_connect( $server, $user, $passw ) or die ("Problemas con la Base de datos, contactar al desarollador");
$db = mysqli_select_db( $conection, $datab ) or die ( "Error con la base de datos registrar la configuración" );

if (!mysqli_set_charset($conection, "utf8")) {
    printf("Error cargando el conjunto de caracteres utf8: %s\n", mysqli_error($conexion));
    exit();
} else {}