<?php
// Dashboard del Director - Versión Modular y Simplificada
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}

require_once ROOT . '/config.php';
require_once ROOT . '/app/library/SessionManager.php';

// Inicializar SessionManager
$sessionManager = new SessionManager();

// Verificar que el usuario esté logueado y sea director
if (!$sessionManager->isLoggedIn() || !$sessionManager->hasRole('director')) {
    header("Location: " . url . "?view=index&action=login");
    exit;
}

require_once ROOT . '/app/views/layouts/dashHeader.php';
?>
<link rel="stylesheet" href="<?= url . app . rq ?>css/dashboard.css">
<div class="dashboard-container">
    <aside class="sidebar">
        <?php require_once __DIR__ . '/directorSidebar.php'; ?>
    </aside>
    <main class="mainContent" id="mainContent">
        <?php require_once __DIR__ . '/dashboardHome.php'; ?>
    </main>
</div>
