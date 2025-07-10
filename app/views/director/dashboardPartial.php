<?php
// Dashboard del Director - Versión Parcial para AJAX
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
                    <h2 class="mb-1">Dashboard del Director</h2>
                    <p class="text-muted mb-0">Panel de control y gestión integral del colegio</p>
                </div>
                <div class="text-end">
                    <small class="text-muted"><?= date('d/m/Y H:i') ?></small>
                </div>
            </div>
        </div>
    </div>

    <!-- KPIs -->
    <div class="row mb-4">
        <?php 
        $kpiPath = __DIR__ . '/components/kpiCards.php';
        if (file_exists($kpiPath)) {
            require_once $kpiPath;
        } else {
            echo '<div class="col-md-3"><div class="card"><div class="card-body"><h5>Estudiantes</h5><h3>1,230</h3></div></div></div>';
            echo '<div class="col-md-3"><div class="card"><div class="card-body"><h5>Profesores</h5><h3>45</h3></div></div></div>';
            echo '<div class="col-md-3"><div class="card"><div class="card-body"><h5>Cursos</h5><h3>12</h3></div></div></div>';
            echo '<div class="col-md-3"><div class="card"><div class="card-body"><h5>Asistencia</h5><h3>95%</h3></div></div></div>';
        }
        ?>
    </div>

    <!-- Widgets principales -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-line"></i> Asistencia del Día</h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <h2 class="text-success">95%</h2>
                        <p class="text-muted">Asistencia promedio</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-exclamation-triangle"></i> Estudiantes en Riesgo</h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <h2 class="text-warning">8</h2>
                        <p class="text-muted">Requieren atención</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secciones académica y administrativa -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-graduation-cap"></i> Actividad Académica</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success"></i> 15 tareas pendientes</li>
                        <li><i class="fas fa-clock text-warning"></i> 3 exámenes próximos</li>
                        <li><i class="fas fa-calendar text-info"></i> 2 eventos esta semana</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-cogs"></i> Gestión Administrativa</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><i class="fas fa-file-alt text-primary"></i> 5 reportes pendientes</li>
                        <li><i class="fas fa-users text-info"></i> 3 reuniones programadas</li>
                        <li><i class="fas fa-bell text-warning"></i> 2 notificaciones nuevas</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos y estadísticas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-bar"></i> Estadísticas Generales</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <h4 class="text-primary">1,230</h4>
                            <p class="text-muted">Total Estudiantes</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <h4 class="text-success">45</h4>
                            <p class="text-muted">Profesores Activos</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <h4 class="text-info">12</h4>
                            <p class="text-muted">Cursos Activos</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Otros widgets -->
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-calendar-alt"></i> Próximos Eventos</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><strong>15 Dic</strong> - Reunión de padres</li>
                        <li><strong>18 Dic</strong> - Exámenes finales</li>
                        <li><strong>20 Dic</strong> - Ceremonia de graduación</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-dollar-sign"></i> Pagos Pendientes</h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <h4 class="text-warning">$12,450</h4>
                        <p class="text-muted">Total pendiente</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-pie"></i> Rendimiento Académico</h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <h4 class="text-success">87%</h4>
                        <p class="text-muted">Promedio general</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Inicializar componentes específicos del dashboard
console.log('Dashboard parcial del director cargado');
</script> 