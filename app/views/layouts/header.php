<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}
require_once ROOT . '/app/config.php';
require_once ROOT . '/app/views/layouts/head.php';
?>
<header>
    <div class="main-header">
    <div class="logo-header">
        <a href="index.php">
            <img src="<?php echo url . rq ?>img\horizontal-logo.svg" alt="Byfrost Logo" width="200">
        </a>
    </div>

    <div class="menu-bar">
        <a href="plans.htm" class="btn-menu">Planes</a>
        <a href="contact.php" class="btn-menu">Contáctenos</a>
        <a href="faq.html" class="btn-menu">FAQ</a>
    </div>

    <a href="/byfrost1.0/app/views/index/login.php">
        <div class="login-bttn">
    <img src="<?php echo url . rq ?>img\user-icon.svg" alt="User Icon" width="30"> 
    Iniciar sesión
        </div>
    </a>
</div>
</header>