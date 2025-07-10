<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}
require_once ROOT . '/config.php';

$userRole = $_SESSION["ByFrost_role"] ?? '';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Byfrost - Sistema de Gestión Educativa</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="<?php echo url . app . rq ?>css/dashboard.css">
    <link rel="stylesheet" href="<?php echo url . app . rq ?>css/sidebar.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo url . app . rq ?>img/favicon.ico">
    
    <style>
        /* Estilos adicionales para mejorar la experiencia */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        
        /* Mejorar la legibilidad */
        .mainContent {
            font-size: 14px;
            line-height: 1.6;
        }
        
        /* Estilos para cards */
        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        
        /* Estilos para botones */
        .btn {
            border-radius: 6px;
            font-weight: 500;
        }
        
        /* Estilos para alertas */
        .alert {
            border-radius: 8px;
            border: none;
        }
    </style>
</head>
<body>