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

<script>


// Función de respaldo para loadView
window.loadView = function(viewName) {
    console.log('loadView llamado desde reportes con:', viewName);
    
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
        <!-- Contenido principal -->
        <main class="col-12 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-4 pb-3 mb-4 border-bottom">
                <div>
                    <h1 class="h2 mb-0">Reportes de Nómina</h1>
                    <p class="text-muted mb-0">Genera reportes detallados del sistema de nómina</p>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-success" onclick="exportReport()">
                            <i class="fas fa-file-excel"></i> Exportar Excel
                        </button>
                        <button type="button" class="btn btn-danger" onclick="exportPDF()">
                            <i class="fas fa-file-pdf"></i> Exportar PDF
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="loadView('payroll/dashboard')">
                            <i class="fas fa-arrow-left"></i> Volver al Dashboard
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filtros de reporte -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Configuración del Reporte</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="#" class="row g-3">
                        <div class="col-md-3">
                            <label for="report_type" class="form-label">Tipo de Reporte</label>
                            <select class="form-select" id="report_type" name="report_type" onchange="updateReportOptions()">
                                <option value="payroll_summary">Resumen de Nómina</option>
                                <option value="employee_details">Detalles por Empleado</option>
                                <option value="department_summary">Resumen por Departamento</option>
                                <option value="absences_report">Reporte de Ausencias</option>
                                <option value="overtime_report">Reporte de Horas Extras</option>
                                <option value="bonuses_report">Reporte de Bonificaciones</option>
                                <option value="cost_analysis">Análisis de Costos</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="period" class="form-label">Período</label>
                            <select class="form-select" id="period" name="period">
                                <option value="">Seleccionar período</option>
                                <!-- Aquí se cargarían los períodos dinámicamente -->
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="department" class="form-label">Departamento</label>
                            <select class="form-select" id="department" name="department">
                                <option value="">Todos los departamentos</option>
                                <option value="Administración">Administración</option>
                                <option value="Académico">Académico</option>
                                <option value="Financiero">Financiero</option>
                                <option value="Recursos Humanos">Recursos Humanos</option>
                                <option value="Tecnología">Tecnología</option>
                                <option value="Mantenimiento">Mantenimiento</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="date_range" class="form-label">Rango de Fechas</label>
                            <input type="text" class="form-control" id="date_range" name="date_range" placeholder="Seleccionar fechas">
                        </div>
                        
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search"></i> Generar Reporte
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="window.location.reload()">
                                <i class="fas fa-times"></i> Limpiar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Resumen ejecutivo -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Total Nómina</h5>
                                    <h2 class="card-text">$<?php echo isset($total_payroll) ? number_format($total_payroll, 0) : '0'; ?></h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-dollar-sign fa-2x"></i>
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
                                    <h5 class="card-title">Empleados Activos</h5>
                                    <h2 class="card-text"><?php echo isset($active_employees) ? $active_employees : '0'; ?></h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-users fa-2x"></i>
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
                                    <h5 class="card-title">Horas Extras</h5>
                                    <h2 class="card-text">$<?php echo isset($total_overtime) ? number_format($total_overtime, 0) : '0'; ?></h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x"></i>
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
                                    <h5 class="card-title">Bonificaciones</h5>
                                    <h2 class="card-text">$<?php echo isset($total_bonuses) ? number_format($total_bonuses, 0) : '0'; ?></h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-gift fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido del reporte -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Resultados del Reporte</h6>
                </div>
                <div class="card-body">
                    <?php if (isset($report_data) && !empty($report_data)): ?>
                        <!-- Aquí se mostraría el contenido específico del reporte según el tipo -->
                        <div id="reportContent">
                            <!-- El contenido se cargará dinámicamente según el tipo de reporte -->
                            <div class="text-center py-5">
                                <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Selecciona un tipo de reporte y genera los resultados</h5>
                                <p class="text-muted">Los datos se mostrarán aquí una vez que generes el reporte</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay datos de reporte disponibles</h5>
                            <p class="text-muted">Configura los filtros y genera un reporte para ver los resultados</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Gráficos y estadísticas -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">Distribución por Departamento</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="departmentChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">Evolución Mensual</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="monthlyChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once ROOT . '/app/views/layouts/dashFooter.php'; ?>

<script>
// Función para actualizar opciones según el tipo de reporte
function updateReportOptions() {
    const reportType = document.getElementById('report_type').value;
    const departmentSelect = document.getElementById('department');
    const periodSelect = document.getElementById('period');
    
    // Mostrar/ocultar campos según el tipo de reporte
    switch(reportType) {
        case 'department_summary':
            departmentSelect.style.display = 'none';
            break;
        case 'employee_details':
            departmentSelect.style.display = 'block';
            break;
        default:
            departmentSelect.style.display = 'block';
    }
}

// Función para exportar a Excel
function exportReport() {
    const reportType = document.getElementById('report_type').value;
    if (!reportType) {
        alert('Por favor selecciona un tipo de reporte');
        return;
    }
    
    // Aquí se implementaría la lógica de exportación a Excel
    alert('Función de exportación a Excel en desarrollo');
}

// Función para exportar a PDF
function exportPDF() {
    const reportType = document.getElementById('report_type').value;
    if (!reportType) {
        alert('Por favor selecciona un tipo de reporte');
        return;
    }
    
    // Aquí se implementaría la lógica de exportación a PDF
    alert('Función de exportación a PDF en desarrollo');
}

// Inicializar gráficos si Chart.js está disponible
if (typeof Chart !== 'undefined') {
    // Gráfico de departamentos
    const departmentCtx = document.getElementById('departmentChart').getContext('2d');
    new Chart(departmentCtx, {
        type: 'doughnut',
        data: {
            labels: ['Administración', 'Académico', 'Financiero', 'Recursos Humanos', 'Tecnología', 'Mantenimiento'],
            datasets: [{
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                    '#FF9F40'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Gráfico mensual
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            datasets: [{
                label: 'Nómina Total',
                data: [65000, 59000, 80000, 81000, 56000, 55000],
                borderColor: '#36A2EB',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Inicializar date range picker si está disponible
if (typeof $.fn.daterangepicker !== 'undefined') {
    $('#date_range').daterangepicker({
        locale: {
            format: 'DD/MM/YYYY',
            applyLabel: 'Aplicar',
            cancelLabel: 'Cancelar',
            fromLabel: 'Desde',
            toLabel: 'Hasta'
        }
    });
}
</script> 