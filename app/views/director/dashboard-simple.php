<?php
// Dashboard del Director - Versión Simplificada y Modular
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}

require_once ROOT . '/config.php';
require_once ROOT . '/app/library/SessionManager.php';

// Inicializar SessionManager
$sessionManager = new SessionManager();

// Verificar que el usuario esté logueado y sea director
if (!$sessionManager->isLoggedIn()) {
    header("Location: " . url . "?view=index&action=login");
    exit;
}

if (!$sessionManager->hasRole('director')) {
    header("Location: " . url . "?view=unauthorized");
    exit;
}

require_once ROOT . '/app/views/layouts/dashHeader.php';
?>

<div class="dashboard-container">
    <aside class="sidebar">
        <?php require_once __DIR__ . '/directorSidebar.php'; ?>
    </aside>
    
    <div id="mainContent" class="mainContent">
        <div class="container-fluid">
            <!-- Header del Dashboard -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1">Dashboard del Director</h2>
                            <p class="text-muted mb-0">Panel de control y gestión integral del colegio</p>
                        </div>
                        <div class="text-end">
                            <small class="text-muted"><?= date('d/m/Y H:i') ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPIs Principales -->
            <div class="row mb-4">
                <?php require_once __DIR__ . '/components/kpiCards.php'; ?>
            </div>

            <!-- Widgets Principales -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <?php require_once ROOT . '/app/views/widgets/attendanceWidget.php'; ?>
                </div>
                <div class="col-md-6">
                    <?php require_once ROOT . '/app/views/widgets/studentRiskWidget.php'; ?>
                </div>
            </div>

            <!-- Secciones Modulares -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <?php require_once __DIR__ . '/components/academicSection.php'; ?>
                </div>
                <div class="col-md-6 mb-4">
                    <?php require_once __DIR__ . '/components/adminSection.php'; ?>
                </div>
            </div>

            <!-- Widgets de Gráficos -->
            <div class="row mb-4">
                <div class="col-12">
                    <?php require_once ROOT . '/app/views/widgets/chartsWidget.php'; ?>
                </div>
            </div>

            <!-- Widgets Adicionales -->
            <div class="row">
                <div class="col-md-4 mb-4">
                    <?php require_once ROOT . '/app/views/layouts/upcomingEventsWidget.php'; ?>
                </div>
                <div class="col-md-4 mb-4">
                    <?php require_once ROOT . '/app/views/layouts/paymentWidget.php'; ?>
                </div>
                <div class="col-md-4 mb-4">
                    <?php require_once ROOT . '/app/views/layouts/academicStatsWidget.php'; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Modular -->
<script src="<?= url . app . rq ?>js/dashboard.js"></script> 