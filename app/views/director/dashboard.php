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
        <div class="container-fluid">
            <!-- KPIs -->
            <div class="row mb-4">
                <?php require_once __DIR__ . '/components/kpiCards.php'; ?>
            </div>
            <!-- Widgets principales -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <?php require_once ROOT . '/app/views/widgets/attendanceWidget.php'; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <?php require_once ROOT . '/app/views/layouts/studentRiskWidget.php'; ?>
                </div>
            </div>
            <!-- Secciones académica y administrativa -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <?php require_once __DIR__ . '/components/academicSection.php'; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <?php require_once __DIR__ . '/components/adminSection.php'; ?>
                </div>
            </div>
            <!-- Gráficos y estadísticas -->
            <div class="row mb-4">
                <div class="col-12">
                    <?php require_once ROOT . '/app/views/widgets/chartsWidget.php'; ?>
                </div>
            </div>
            <!-- Otros widgets -->
            <div class="row">
                <div class="col-md-4 mb-3">
                    <?php require_once ROOT . '/app/views/layouts/upcomingEventsWidget.php'; ?>
                </div>
                <div class="col-md-4 mb-3">
                    <?php require_once ROOT . '/app/views/layouts/paymentWidget.php'; ?>
                </div>
                <div class="col-md-4 mb-3">
                    <?php require_once ROOT . '/app/views/layouts/academicStatsWidget.php'; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Cargar Chart.js primero -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Cargar dashboard.js -->
<script src="<?= url . app . rq ?>js/dashboard.js"></script>

<!-- Debug y verificación -->
<script>
console.log('Dashboard cargado, verificando JavaScript...');

// Verificar después de un breve delay
setTimeout(function() {
    console.log('=== VERIFICACIÓN FINAL ===');
    console.log('loadView:', typeof window.loadView);
    console.log('safeLoadView:', typeof window.safeLoadView);
    
    if (typeof window.loadView === 'function') {
        console.log('✅ loadView está disponible y funcionando');
        // Test simple
        console.log('Probando loadView con consultUser...');
        window.loadView('consultUser');
    } else {
        console.log('❌ loadView NO está disponible');
    }
    
    console.log('=== FIN VERIFICACIÓN ===');
}, 2000);
</script>

