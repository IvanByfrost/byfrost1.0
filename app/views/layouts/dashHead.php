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
    <title>Byfrost - Dashboard<?php echo isset($userRole) && $userRole ? " de " . ucfirst($userRole) : ""; ?></title>
    <link rel="stylesheet" href="<?php echo url . app . rq ?>css/homeStyle.css">
    <link rel="stylesheet" href="<?php echo url . app . rq ?>css/headMasterLists.css">
    <link rel="stylesheet" href="<?php echo url . app . rq ?>css/dashboard.css">
    <link rel="stylesheet" href="<?php echo url . app . rq ?>css/headMaster.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,200,0,0">
    <link rel="icon" type="image/x-icon" href="<?php echo url . app . rq ?>img/favicon.png">
</head>