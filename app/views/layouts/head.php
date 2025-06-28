<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__, 3));
}
require_once ROOT . '/config.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Byfrost</title>
    <link rel="stylesheet" href="<?php echo url . app . rq ?>css\homeStyle.css">
    <link rel="stylesheet" href="<?php echo url . app . rq ?>css\loginstyle.css">
    <link rel="stylesheet" href="<?php echo url . app . rq ?>css\features.css">
    <link rel="stylesheet" href="<?php echo url . app . rq ?>css\slider.css">
    <link rel="stylesheet" href="<?php echo url . app . rq ?>css\contact.css">
    <link rel="stylesheet" href="<?php echo url . app . rq ?>css\errorstyle.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,200,0,0">
    <link rel="icon" type="image/x-icon" href="<?php echo url . app . rq ?>img\favicon.png">
</head>