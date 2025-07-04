<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}

require_once ROOT . '/config.php';
require_once ROOT . '/app/library/SessionManager.php';

// Inicializar SessionManager
$sessionManager = new SessionManager();

// Verificar que el usuario esté logueado y tenga permisos
if (!isset($this->sessionManager) || !$this->sessionManager->isLoggedIn()) {
    header("Location: " . url . "?view=index&action=login");
    exit;
}

if (!$this->sessionManager->hasRole(['root', 'director', 'coordinator', 'treasurer'])) {
    header("Location: " . url . "?view=unauthorized");
    exit;
}
?>

<script>
console.log("BASE_URL será configurada en dashFooter.php");

// Función de respaldo para loadView
window.safeLoadView = function(viewName) {
    console.log('safeLoadView llamado desde dashboard de nómina con:', viewName);
    
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

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#" onclick="safeLoadView('payroll/dashboard')">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="safeLoadView('payroll/employees')">
                            <i class="fas fa-users"></i> Empleados
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="safeLoadView('payroll/periods')">
                            <i class="fas fa-calendar-alt"></i> Períodos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="safeLoadView('payroll/absences')">
                            <i class="fas fa-user-times"></i> Ausencias
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="safeLoadView('payroll/overtime')">
                            <i class="fas fa-clock"></i> Horas Extras
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="safeLoadView('payroll/bonuses')">
                            <i class="fas fa-gift"></i> Bonificaciones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="safeLoadView('payroll/reports')">
                            <i class="fas fa-chart-bar"></i> Reportes
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Contenido principal -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard de Nómina</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="safeLoadView('payroll/employees')">
                            <i class="fas fa-plus"></i> Nuevo Empleado
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="safeLoadView('payroll/periods')">
                            <i class="fas fa-calendar-plus"></i> Nuevo Período
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tarjetas de estadísticas -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Total Empleados</h5>
                                    <h2 class="card-text"><?php echo isset($total_employees) ? $total_employees : '0'; ?></h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Nómina del Mes</h5>
                                    <h2 class="card-text">$<?php echo isset($monthly_payroll) ? number_format($monthly_payroll, 0) : '0'; ?></h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-dollar-sign fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Períodos Activos</h5>
                                    <h2 class="card-text"><?php echo isset($active_periods) ? $active_periods : '0'; ?></h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-calendar-check fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Ausencias</h5>
                                    <h2 class="card-text"><?php echo isset($total_absences) ? $total_absences : '0'; ?></h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-user-times fa-2x"></i>
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
                            <div class="list-group list-group-flush">
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Sistema iniciado</h6>
                                        <small class="text-muted"><?php echo date('d/m/Y H:i'); ?></small>
                                    </div>
                                    <p class="mb-1">Dashboard de nómina cargado correctamente</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Estado del Sistema</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Estado:</strong> <span class="badge bg-success">Activo</span></p>
                            <p><strong>Versión:</strong> 1.0</p>
                            <p><strong>Última actualización:</strong> <?php echo date('d/m/Y H:i'); ?></p>
                            <p><strong>Usuarios autorizados:</strong> Root, Director, Coordinador, Tesorero</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php
// Incluir el footer del dashboard
include 'app/views/layouts/dashFooter.php';
?> 