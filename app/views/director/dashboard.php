<?php
error_log('DEBUG: Cargando app/views/director/dashboard.php');
echo '<!-- DEBUG: Cargando app/views/director/dashboard.php -->';
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

<script>
console.log("BASE_URL será configurada en dashFooter.php");

// Función de respaldo para loadView
window.safeLoadView = function(viewName) {
    console.log('safeLoadView llamado desde dashboard con:', viewName);
    
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
        <?php require_once __DIR__ . '/directorSidebar.php'; ?>
    </aside>
    
    <div id="mainContent" class="mainContent">
        <!-- Dashboard del Director -->
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="h2 mb-0">
                        <i class="fas fa-chart-line text-primary"></i>
                        Dashboard del Director
                    </h1>
                    <p class="text-muted">Panel de control y gestión integral del colegio</p>
                </div>
            </div>

            <!-- 1. SECCIÓN DE MÉTRICAS (KPIs) - DESPLEGABLE -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white" style="cursor: pointer;" onclick="toggleSection('metricsSection')">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-tachometer-alt"></i>
                                    Métricas Clave (KPIs)
                                </h5>
                                <i class="fas fa-chevron-down" id="metricsIcon"></i>
                            </div>
                        </div>
                        <div class="card-body" id="metricsSection">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <div class="kpi-card bg-success text-white p-3 rounded">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h3 class="mb-0" id="totalStudents">0</h3>
                                                <p class="mb-0">Total Estudiantes</p>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-user-graduate fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="kpi-card bg-info text-white p-3 rounded">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h3 class="mb-0" id="totalTeachers">0</h3>
                                                <p class="mb-0">Total Docentes</p>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-chalkboard-teacher fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="kpi-card bg-warning text-white p-3 rounded">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h3 class="mb-0" id="attendanceRate">0%</h3>
                                                <p class="mb-0">Asistencia Promedio</p>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-percentage fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="kpi-card bg-danger text-white p-3 rounded">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h3 class="mb-0" id="pendingTasks">0</h3>
                                                <p class="mb-0">Tareas Pendientes</p>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-tasks fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. SECCIÓN ACADÉMICA - DESPLEGABLE -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-success text-white" style="cursor: pointer;" onclick="toggleSection('academicSection')">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-graduation-cap"></i>
                                    Gestión Académica
                                </h5>
                                <i class="fas fa-chevron-down" id="academicIcon"></i>
                            </div>
                        </div>
                        <div class="card-body" id="academicSection">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 border-success">
                                        <div class="card-body text-center">
                                            <i class="fas fa-calendar-alt fa-3x text-success mb-3"></i>
                                            <h5 class="card-title">Actividades Académicas</h5>
                                            <p class="card-text">Gestiona eventos, actividades y calendario escolar</p>
                                            <button class="btn btn-success" onclick="loadView('activity/dashboard')">
                                                <i class="fas fa-plus"></i> Gestionar Actividades
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 border-info">
                                        <div class="card-body text-center">
                                            <i class="fas fa-clock fa-3x text-info mb-3"></i>
                                            <h5 class="card-title">Horarios</h5>
                                            <p class="card-text">Administra horarios de clases y eventos</p>
                                            <button class="btn btn-info" onclick="loadView('schedule/schedule')">
                                                <i class="fas fa-edit"></i> Gestionar Horarios
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 border-warning">
                                        <div class="card-body text-center">
                                            <i class="fas fa-graduation-cap fa-3x text-warning mb-3"></i>
                                            <h5 class="card-title">Historial Académico</h5>
                                            <p class="card-text">Consulta y gestiona el historial de estudiantes</p>
                                            <button class="btn btn-warning" onclick="loadView('student/academicHistory')">
                                                <i class="fas fa-search"></i> Ver Historial
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. SECCIÓN ADMINISTRATIVA - DESPLEGABLE -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white" style="cursor: pointer;" onclick="toggleSection('adminSection')">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-cogs"></i>
                                    Gestión Administrativa
                                </h5>
                                <i class="fas fa-chevron-down" id="adminIcon"></i>
                            </div>
                        </div>
                        <div class="card-body" id="adminSection">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <div class="card h-100 border-primary">
                                        <div class="card-body text-center">
                                            <i class="fas fa-school fa-3x text-primary mb-3"></i>
                                            <h6 class="card-title">Colegios</h6>
                                            <button class="btn btn-primary btn-sm" onclick="loadView('school/createSchool')">
                                                <i class="fas fa-plus"></i> Registrar
                                            </button>
                                            <button class="btn btn-outline-primary btn-sm" onclick="loadView('school/consultSchool')">
                                                <i class="fas fa-search"></i> Consultar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="card h-100 border-info">
                                        <div class="card-body text-center">
                                            <i class="fas fa-users fa-3x text-info mb-3"></i>
                                            <h6 class="card-title">Usuarios</h6>
                                            <button class="btn btn-info btn-sm" onclick="loadView('user/consultUser')">
                                                <i class="fas fa-users"></i> Gestionar
                                            </button>
                                            <button class="btn btn-outline-info btn-sm" onclick="loadView('user/assignRole')">
                                                <i class="fas fa-user-tag"></i> Roles
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="card h-100 border-success">
                                        <div class="card-body text-center">
                                            <i class="fas fa-money-bill-wave fa-3x text-success mb-3"></i>
                                            <h6 class="card-title">Nómina</h6>
                                            <button class="btn btn-success btn-sm" onclick="loadView('payroll/dashboard')">
                                                <i class="fas fa-chart-bar"></i> Dashboard
                                            </button>
                                            <button class="btn btn-outline-success btn-sm" onclick="loadView('payroll/employees')">
                                                <i class="fas fa-user-tie"></i> Empleados
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="card h-100 border-warning">
                                        <div class="card-body text-center">
                                            <i class="fas fa-file-alt fa-3x text-warning mb-3"></i>
                                            <h6 class="card-title">Reportes</h6>
                                            <button class="btn btn-warning btn-sm" onclick="loadView('director/editDirector')">
                                                <i class="fas fa-plus"></i> Crear
                                            </button>
                                            <button class="btn btn-outline-warning btn-sm" onclick="loadView('director/createDirector')">
                                                <i class="fas fa-search"></i> Consultar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. SECCIÓN DE COMUNICACIÓN - DESPLEGABLE -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-info text-white" style="cursor: pointer;" onclick="toggleSection('communicationSection')">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-comments"></i>
                                    Comunicación y Notificaciones
                                </h5>
                                <i class="fas fa-chevron-down" id="communicationIcon"></i>
                            </div>
                        </div>
                        <div class="card-body" id="communicationSection">
                            <!-- Banner de Anuncio Importante -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                                            <div>
                                                <h5 class="alert-heading mb-1">¡Anuncio Importante!</h5>
                                                <p class="mb-0">Reunión de padres programada para el próximo viernes 15 de marzo a las 6:00 PM. Todos los padres deben asistir.</p>
                                            </div>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Eventos del Mes -->
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 border-info">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0">
                                                <i class="fas fa-calendar-alt"></i>
                                                Eventos del Mes
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="list-group list-group-flush">
                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1">Reunión de Padres</h6>
                                                        <small class="text-muted">15 Mar - 6:00 PM</small>
                                                    </div>
                                                    <span class="badge bg-primary rounded-pill">Importante</span>
                                                </div>
                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1">Exámenes Finales</h6>
                                                        <small class="text-muted">20-25 Mar</small>
                                                    </div>
                                                    <span class="badge bg-warning rounded-pill">Académico</span>
                                                </div>
                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1">Festival de Arte</h6>
                                                        <small class="text-muted">28 Mar - 2:00 PM</small>
                                                    </div>
                                                    <span class="badge bg-success rounded-pill">Cultural</span>
                                                </div>
                                            </div>
                                            <button class="btn btn-info btn-sm w-100 mt-3" onclick="loadView('activity/dashboard')">
                                                <i class="fas fa-plus"></i> Agregar Evento
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Comunicaciones con Padres -->
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 border-success">
                                        <div class="card-header bg-success text-white">
                                            <h6 class="mb-0">
                                                <i class="fas fa-users"></i>
                                                Comunicaciones con Padres
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row text-center mb-3">
                                                <div class="col-6">
                                                    <div class="border-end">
                                                        <h4 class="text-success mb-0">247</h4>
                                                        <small class="text-muted">Mensajes Enviados</small>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <h4 class="text-info mb-0">89%</h4>
                                                    <small class="text-muted">Tasa de Lectura</small>
                                                </div>
                                            </div>
                                            <div class="progress mb-3">
                                                <div class="progress-bar bg-success" style="width: 89%"></div>
                                            </div>
                                            <div class="d-grid gap-2">
                                                <button class="btn btn-success btn-sm" onclick="loadView('director/createDirector')">
                                                    <i class="fas fa-envelope"></i> Nuevo Mensaje
                                                </button>
                                                <button class="btn btn-outline-success btn-sm" onclick="loadView('director/editDirector')">
                                                    <i class="fas fa-chart-bar"></i> Ver Estadísticas
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Notificaciones Recientes -->
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 border-warning">
                                        <div class="card-header bg-warning text-white">
                                            <h6 class="mb-0">
                                                <i class="fas fa-bell"></i>
                                                Notificaciones Recientes
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="list-group list-group-flush">
                                                <div class="list-group-item">
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <h6 class="mb-1">Nuevo estudiante registrado</h6>
                                                        <small class="text-muted">Hace 3 horas</small>
                                                    </div>
                                                    <p class="mb-1">María González se ha registrado en el sistema.</p>
                                                </div>
                                                <div class="list-group-item">
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <h6 class="mb-1">Reporte mensual listo</h6>
                                                        <small class="text-muted">Hace 1 día</small>
                                                    </div>
                                                    <p class="mb-1">El reporte de febrero está disponible.</p>
                                                </div>
                                                <div class="list-group-item">
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <h6 class="mb-1">Actualización de horarios</h6>
                                                        <small class="text-muted">Hace 2 días</small>
                                                    </div>
                                                    <p class="mb-1">Los horarios han sido actualizados.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. GRÁFICOS Y VISUALIZACIONES - DESPLEGABLE -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-warning text-white" style="cursor: pointer;" onclick="toggleSection('chartsSection')">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-chart-pie"></i>
                                    Gráficos y Visualizaciones
                                </h5>
                                <i class="fas fa-chevron-down" id="chartsIcon"></i>
                            </div>
                        </div>
                        <div class="card-body" id="chartsSection">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6 class="card-title">Asistencia por Mes</h6>
                                            <canvas id="attendanceChart" width="400" height="200"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6 class="card-title">Distribución de Estudiantes por Grado</h6>
                                            <canvas id="studentsChart" width="400" height="200"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">Rendimiento Académico - Últimos 6 Meses</h6>
                                            <canvas id="performanceChart" width="800" height="300"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts para gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Función para alternar secciones
function toggleSection(sectionId) {
    const section = document.getElementById(sectionId);
    const icon = document.getElementById(sectionId.replace('Section', 'Icon'));
    
    if (section.style.display === 'none') {
        section.style.display = 'block';
        icon.className = 'fas fa-chevron-down';
    } else {
        section.style.display = 'none';
        icon.className = 'fas fa-chevron-right';
    }
}

// Cargar datos de KPIs
function loadKPIs() {
    // Simular datos - en producción estos vendrían de la base de datos
    document.getElementById('totalStudents').textContent = '1,247';
    document.getElementById('totalTeachers').textContent = '89';
    document.getElementById('attendanceRate').textContent = '94.2%';
    document.getElementById('pendingTasks').textContent = '12';
}

// Gráfico de asistencia
function createAttendanceChart() {
    const ctx = document.getElementById('attendanceChart');
    if (!ctx) return;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            datasets: [{
                label: 'Asistencia (%)',
                data: [92, 94, 91, 95, 93, 94],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
}

// Gráfico de distribución de estudiantes
function createStudentsChart() {
    const ctx = document.getElementById('studentsChart');
    if (!ctx) return;
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Primaria', 'Secundaria', 'Bachillerato'],
            datasets: [{
                data: [450, 380, 417],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
                ]
            }]
        },
        options: {
            responsive: true
        }
    });
}

// Gráfico de rendimiento académico
function createPerformanceChart() {
    const ctx = document.getElementById('performanceChart');
    if (!ctx) return;
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            datasets: [{
                label: 'Promedio General',
                data: [85, 87, 86, 89, 88, 90],
                backgroundColor: 'rgba(54, 162, 235, 0.8)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    loadKPIs();
    createAttendanceChart();
    createStudentsChart();
    createPerformanceChart();
});
</script>

<?php
require_once __DIR__ . '/../layouts/dashFooter.php';
?>