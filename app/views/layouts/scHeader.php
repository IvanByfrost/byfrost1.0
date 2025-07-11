<?php
$userName = $_SESSION["ByFrost_userName"] ?? '';
$userRole = $_SESSION["ByFrost_role"] ?? '';

// Define the ROOT constant if it is not already defined
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}
require_once ROOT . '/config.php';
require_once ROOT . '/app/views/layouts/dashHead.php';
?>

<header>
    <div class="main-header">
        <a href="#">
            <img src="<?php echo url . app . rq ?>img/horizontal-logo.svg" alt="Byfrost Logo" width="200">
        </a>
    </div>

    <div class="user-menu">
        <div class="user-menu-trigger" onclick="toggleUserMenu()">
            <img src="<?php echo url . app . rq ?>img/user-photo.png" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%; cursor: pointer;">
        </div>
        <div class="user-menu-container">
            <div style="text-align: center; padding: 10px;">
                <strong><?php echo htmlspecialchars($userName); ?></strong><br>
                <small><?php echo ucfirst($userRole); ?></small>
            </div>
            <hr>
            <form action="<?= url . app ?>processes/outProcess.php" method="post" style="margin: 0; padding: 10px;">
                <input type="hidden" name="csrf_token" value="<?= Validator::generateCSRFToken() ?>">
                <button type="submit" class="logout-btn" style="width: 100%; background: #e3342f; color: #fff; border: none; padding: 8px 0; border-radius: 5px; font-size: 1rem; cursor: pointer; font-weight: bold; margin-top: 8px; transition: background 0.2s; text-align: center;">Cerrar sesión</button>
            </form>
        </div>
    </div>
</header>