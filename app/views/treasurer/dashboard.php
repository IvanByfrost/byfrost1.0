<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}

require_once ROOT . '/config.php';
require_once ROOT . '/app/library/SessionManager.php';

// Inicializar SessionManager
$sessionManager = new SessionManager();

// Verificar que el usuario esté logueado y sea tesorero
if (!isset($this->sessionManager) || !$this->sessionManager->isLoggedIn()) {
    header("Location: " . url . "?view=index&action=login");
    exit;
}

if (!$this->sessionManager->hasRole('treasurer')) {
    header("Location: " . url . "?view=unauthorized");
    exit;
}
?>

<script>
console.log("BASE_URL será configurada en dashFooter.php");

// Función de respaldo para loadView
window.safeLoadView = function(viewName) {
    console.log('safeLoadView llamado desde dashboard de tesorero con:', viewName);
    
    if (typeof loadView === 'function') {
        console.log('loadView disponible, ejecutando...');
        loadView(viewName);
    } else {
        console.error('loadView no está disponible, redirigiendo...');
        // Fallback: redirigir a la página
        const url = `${BASE_URL}?view=${viewName.replace('/', '&action=')}`;
        window.location.href = url;
    }
};
</script>

<div class="dashboard-container">
    <aside class="sidebar">
        <?php require_once __DIR__ . '/treasurerSidebar.php'; ?>
    </aside>
    
    <div id="mainContent" class="mainContent">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h1 class="h2 mb-4">Dashboard del Tesorero</h1>
                    
                    <!-- Tarjetas de resumen -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Empleados</h5>
                                    <h2 class="card-text">0</h2>
                                    <p class="card-text"><small>Empleados activos</small></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Nómina del Mes</h5>
                                    <h2 class="card-text">$0</h2>
                                    <p class="card-text"><small>Total a pagar</small></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Períodos Activos</h5>
                                    <h2 class="card-text">0</h2>
                                    <p class="card-text"><small>En proceso</small></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Reportes</h5>
                                    <h2 class="card-text">0</h2>
                                    <p class="card-text"><small>Generados este mes</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Acciones rápidas -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Acciones Rápidas</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <button class="btn btn-primary w-100" onclick="safeLoadView('payroll/employees')">
                                                <i class="fas fa-users"></i> Gestionar Empleados
                                            </button>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <button class="btn btn-success w-100" onclick="safeLoadView('payroll/periods')">
                                                <i class="fas fa-calendar"></i> Períodos de Nómina
                                            </button>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <button class="btn btn-warning w-100" onclick="safeLoadView('payroll/reports')">
                                                <i class="fas fa-chart-bar"></i> Generar Reportes
                                            </button>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <button class="btn btn-info w-100" onclick="safeLoadView('payroll/dashboard')">
                                                <i class="fas fa-tachometer-alt"></i> Dashboard Completo
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información del sistema -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Últimas Actividades</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">No hay actividades recientes</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Sistema de Nómina</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Estado:</strong> <span class="badge bg-success">Activo</span></p>
                                    <p><strong>Versión:</strong> 1.0</p>
                                    <p><strong>Última actualización:</strong> <?php echo date('d/m/Y H:i'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 