<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__, 2));
}
require_once ROOT . '/config.php';
require_once ROOT . '/app/views/layouts/head.php';
?>
<header>
    <div class="main-header">
    <div class="logo-header">
        <a href="<?php echo url; ?>">
            <img src="<?php echo url . app . rq; ?>img/horizontal-logo.svg" alt="Byfrost Logo" width="200">
        </a>
    </div>

    <div class="menu-bar">
        <a href="<?php echo url . app . views; ?>index/plans.php" class="btn-menu">Planes</a>
        <a href="<?php echo url . app . views; ?>index/contact.php" class="btn-menu">Contáctanos</a>
        <a href="<?php echo url . app . views; ?>index/faq.php" class="btn-menu">FAQ</a>
        <a href="<?php echo url . app . views; ?>index/about.php" class="btn-menu">Quiénes somos</a>
    </div>

    <a href="<?php echo url . app . views; ?>index/login.php">
        <div class="login-bttn">
    <img src="<?php echo url . app . rq; ?>img/user-icon.svg" alt="User Icon" width="30"> 
    Iniciar sesión
        </div>
    </a>
</div>
</header>