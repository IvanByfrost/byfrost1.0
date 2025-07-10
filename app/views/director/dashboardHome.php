<?php
// Vista de inicio del Dashboard del Director
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}

require_once ROOT . '/config.php';
require_once ROOT . '/app/library/SessionManager.php';

// Inicializar SessionManager
$sessionManager = new SessionManager();

// Verificar que el usuario esté logueado y sea director
if (!$sessionManager->isLoggedIn() || !$sessionManager->hasRole('director')) {
    echo '<div class="alert alert-danger">No tienes permisos para acceder a esta vista.</div>';
    return;
}
?>

<div class="container-fluid">
    <!-- Header del Dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Bienvenido al Sistema de Gestión</h2>
                    <p class="text-muted mb-0">Panel de control del director</p>
                </div>
                <div class="text-end">
                    <small class="text-muted"><?= date('d/m/Y H:i') ?></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas de acceso rápido -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-chart-line fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title">Vista General</h5>
                    <p class="card-text text-muted">Dashboard principal con KPIs y métricas</p>
                    <button class="btn btn-primary btn-sm" onclick="loadView('director/dashboardPartial')">
                        <i class="fas fa-arrow-right"></i> Acceder
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-bar-chart fa-3x text-success"></i>
                    </div>
                    <h5 class="card-title">Dashboard Completo</h5>
                    <p class="card-text text-muted">Vista completa con todos los widgets</p>
                    <button class="btn btn-success btn-sm" onclick="loadView('director/dashboard')">
                        <i class="fas fa-arrow-right"></i> Acceder
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-trending-up fa-3x text-info"></i>
                    </div>
                    <h5 class="card-title">Estadísticas</h5>
                    <p class="card-text text-muted">Análisis detallado de estudiantes</p>
                    <button class="btn btn-info btn-sm" onclick="loadView('studentStats/dashboard')">
                        <i class="fas fa-arrow-right"></i> Acceder
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-calculator fa-3x text-warning"></i>
                    </div>
                    <h5 class="card-title">Promedios</h5>
                    <p class="card-text text-muted">Gestión de promedios académicos</p>
                    <button class="btn btn-warning btn-sm" onclick="loadView('academicAverages')">
                        <i class="fas fa-arrow-right"></i> Acceder
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Acceso rápido a funciones principales -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-rocket"></i> Acceso Rápido</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-2">
                            <button class="btn btn-outline-primary w-100" onclick="loadView('school/createSchool')">
                                <i class="fas fa-plus"></i> Nuevo Colegio
                            </button>
                        </div>
                        <div class="col-6 mb-2">
                            <button class="btn btn-outline-success w-100" onclick="loadView('user/consultUser')">
                                <i class="fas fa-users"></i> Gestionar Usuarios
                            </button>
                        </div>
                        <div class="col-6 mb-2">
                            <button class="btn btn-outline-info w-100" onclick="loadView('payroll/dashboard')">
                                <i class="fas fa-dollar-sign"></i> Nómina
                            </button>
                        </div>
                        <div class="col-6 mb-2">
                            <button class="btn btn-outline-warning w-100" onclick="loadView('activity/dashboard')">
                                <i class="fas fa-tasks"></i> Actividades
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Resumen del Día</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h4 class="text-primary mb-1">1,247</h4>
                            <small class="text-muted">Estudiantes</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-success mb-1">89</h4>
                            <small class="text-muted">Profesores</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-info mb-1">95%</h4>
                            <small class="text-muted">Asistencia</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <h6 class="text-warning mb-1">8</h6>
                            <small class="text-muted">En Riesgo</small>
                        </div>
                        <div class="col-6">
                            <h6 class="text-danger mb-1">3</h6>
                            <small class="text-muted">Pendientes</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del sistema -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información del Sistema</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6><i class="fas fa-clock text-primary"></i> Última Actualización</h6>
                            <p class="text-muted"><?= date('d/m/Y H:i:s') ?></p>
                        </div>
                        <div class="col-md-4">
                            <h6><i class="fas fa-user text-success"></i> Usuario Activo</h6>
                            <p class="text-muted"><?= $_SESSION['user_name'] ?? 'Director' ?></p>
                        </div>
                        <div class="col-md-4">
                            <h6><i class="fas fa-shield-alt text-info"></i> Estado del Sistema</h6>
                            <p class="text-success"><i class="fas fa-check-circle"></i> Operativo</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT . '/app/views/layouts/dashFooter.php'; ?>

<script>
console.log('Vista de inicio del dashboard cargada');
</script> 