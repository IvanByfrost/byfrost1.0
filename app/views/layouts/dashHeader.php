<?php
// Define the ROOT constant if it is not already defined
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}

require_once ROOT . '/config.php';
require_once ROOT . '/app/library/SessionManager.php';

// Inicializar SessionManager
$sessionManager = new SessionManager();

// Obtener datos del usuario actual
$currentUser = $sessionManager->getCurrentUser();
$userName = $currentUser['full_name'] ?: 'Usuario';
$userRole = $currentUser['role'] ?: 'Usuario';

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
            <a href="<?php echo url . app ?>processes/outProcess.php" style="
            display: block;
            padding: 8px 10px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
          ">Cerrar sesión</a>
        </div>
    </div>
</header>