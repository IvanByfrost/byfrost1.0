<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}
require_once ROOT . '/app/config.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Byfrost</title>
    <link rel="stylesheet" href="<?php echo url . rq ?>css\homeStyle.css">
    <link rel="stylesheet" href="<?php echo url . rq ?>css\loginstyle.css">
    <!--<link rel="stylesheet" href="<?php echo url . rq ?>css\header.css">
    <link rel="stylesheet" href="<?php echo url . rq ?>css\footer.css">
    <link rel="stylesheet" href="<?php echo url . rq ?>css\home.css">-->
    <link rel="stylesheet" href="<?php echo url . rq ?>css\features.css">
    <link rel="stylesheet" href="<?php echo url . rq ?>css\slider.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,200,0,0">
</head>