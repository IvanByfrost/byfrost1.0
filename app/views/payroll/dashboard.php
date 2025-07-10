<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}

require_once ROOT . '/config.php';
require_once ROOT . '/app/library/SessionManager.php';

// Inicializar SessionManager
$sessionManager = new SessionManager();

// Verificar que el usuario esté logueado y tenga permisos
if (!$sessionManager->isLoggedIn()) {
    header("Location: " . url . "?view=index&action=login");
    exit;
}

if (!$sessionManager->hasRole(['root', 'director', 'coordinator', 'treasurer'])) {
    header("Location: " . url . "?view=unauthorized");
    exit;
}
?>

<script src="<?php echo url . app . rq ?>js/payrollDashboard.js"></script>

<div class="container-fluid">
    <div class="row">
        <!-- Contenido principal -->
        <main class="col-12 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-4 pb-3 mb-4 border-bottom">
                <div>
                    <h1 class="h2 mb-0">Dashboard de Nómina</h1>
                    <p class="text-muted mb-0">Sistema de gestión de nómina y recursos humanos</p>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-primary" onclick="loadView('payroll/employees')">
                            <i class="fas fa-plus"></i> Nuevo Empleado
                        </button>
                        <button type="button" class="btn btn-success" onclick="loadView('payroll/periods')">
                            <i class="fas fa-calendar-plus"></i> Nuevo Período
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tarjetas de estadísticas -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-gradient rounded-circle p-3">
                                        <i class="fas fa-users text-white fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-title text-muted mb-1">Total Empleados</h6>
                                    <h3 class="mb-0 fw-bold"><?php echo isset($total_employees) ? $total_employees : '0'; ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-success bg-gradient rounded-circle p-3">
                                        <i class="fas fa-dollar-sign text-white fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-title text-muted mb-1">Nómina del Mes</h6>
                                    <h3 class="mb-0 fw-bold">$<?php echo isset($monthly_payroll) ? number_format($monthly_payroll, 0) : '0'; ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning bg-gradient rounded-circle p-3">
                                        <i class="fas fa-calendar-check text-white fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-title text-muted mb-1">Períodos Activos</h6>
                                    <h3 class="mb-0 fw-bold"><?php echo isset($active_periods) ? $active_periods : '0'; ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-info bg-gradient rounded-circle p-3">
                                        <i class="fas fa-user-times text-white fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-title text-muted mb-1">Ausencias</h6>
                                    <h3 class="mb-0 fw-bold"><?php echo isset($total_absences) ? $total_absences : '0'; ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones rápidas -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0 fw-bold">Acciones Rápidas</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-2 col-sm-4 col-6">
                                    <div class="d-grid">
                                        <button class="btn btn-outline-primary btn-lg h-100" onclick="loadView('payroll/employees')">
                                            <i class="fas fa-users fa-2x mb-2"></i><br>
                                            <span class="fw-bold">Empleados</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4 col-6">
                                    <div class="d-grid">
                                        <button class="btn btn-outline-success btn-lg h-100" onclick="loadView('payroll/periods')">
                                            <i class="fas fa-calendar fa-2x mb-2"></i><br>
                                            <span class="fw-bold">Períodos</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4 col-6">
                                    <div class="d-grid">
                                        <button class="btn btn-outline-warning btn-lg h-100" onclick="loadView('payroll/absences')">
                                            <i class="fas fa-user-times fa-2x mb-2"></i><br>
                                            <span class="fw-bold">Ausencias</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4 col-6">
                                    <div class="d-grid">
                                        <button class="btn btn-outline-info btn-lg h-100" onclick="loadView('payroll/overtime')">
                                            <i class="fas fa-clock fa-2x mb-2"></i><br>
                                            <span class="fw-bold">Horas Extras</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4 col-6">
                                    <div class="d-grid">
                                        <button class="btn btn-outline-secondary btn-lg h-100" onclick="loadView('payroll/bonuses')">
                                            <i class="fas fa-gift fa-2x mb-2"></i><br>
                                            <span class="fw-bold">Bonificaciones</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4 col-6">
                                    <div class="d-grid">
                                        <button class="btn btn-outline-dark btn-lg h-100" onclick="loadView('payroll/reports')">
                                            <i class="fas fa-chart-bar fa-2x mb-2"></i><br>
                                            <span class="fw-bold">Reportes</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del sistema -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0 fw-bold">Últimas Actividades</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item border-0 px-0">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1 fw-bold">Sistema iniciado</h6>
                                            <p class="mb-0 text-muted">Dashboard de nómina cargado correctamente</p>
                                        </div>
                                        <small class="text-muted"><?php echo date('d/m/Y H:i'); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0 fw-bold">Estado del Sistema</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">Estado:</span>
                                        <span class="badge bg-success">Activo</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">Versión:</span>
                                        <span>1.0</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">Última actualización:</span>
                                        <span><?php echo date('d/m/Y H:i'); ?></span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">Usuarios autorizados:</span>
                                        <span>Root, Director, Coordinador, Tesorero</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once ROOT . '/app/views/layouts/dashFooter.php'; ?>