<?php
// Vista principal de promedios académicos
?>

<div class="container-fluid">
    <!-- Header de la página -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-chart-line text-primary"></i>
                        Promedios Académicos
                    </h1>
                    <p class="text-muted">Análisis completo del rendimiento académico</p>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary" onclick="exportToPDF()">
                        <i class="fas fa-file-pdf"></i> Exportar PDF
                    </button>
                    <button type="button" class="btn btn-outline-success" onclick="exportToExcel()">
                        <i class="fas fa-file-excel"></i> Exportar Excel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas generales -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Promedio General
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($generalStats['promedio_general'], 2) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Tasa de Aprobación
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $generalStats['tasa_aprobacion_general'] ?>%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Calificaciones
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($generalStats['total_calificaciones']) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Estudiantes Activos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($generalStats['total_estudiantes']) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="row">
        <!-- Tabla de promedios por período -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar"></i> Promedios por Período Académico
                    </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                            <a class="dropdown-item" href="#" onclick="refreshData()">Actualizar</a>
                            <a class="dropdown-item" href="#" onclick="toggleChart()">Alternar Gráfico</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($termAverages)) : ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Período</th>
                                        <th>Promedio</th>
                                        <th>Total Calificaciones</th>
                                        <th>Rango</th>
                                        <th>Aprobación</th>
                                        <th>Tasa Aprobación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($termAverages as $term) : ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($term['academic_term_name']) ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $term['promedio'] >= 3.0 ? 'success' : 'warning' ?> fs-6">
                                                    <?= $term['promedio'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-muted"><?= $term['total_calificaciones'] ?> calificaciones</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= $term['nota_minima'] ?> - <?= $term['nota_maxima'] ?>
                                                </small>
                                            </td>
                                            <td>
                                                <span class="text-success"><?= $term['aprobados'] ?></span> / 
                                                <span class="text-danger"><?= $term['reprobados'] ?></span>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-<?= $term['tasa_aprobacion'] >= 80 ? 'success' : ($term['tasa_aprobacion'] >= 60 ? 'warning' : 'danger') ?>" 
                                                         style="width: <?= $term['tasa_aprobacion'] ?>%">
                                                        <?= $term['tasa_aprobacion'] ?>%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Gráfico de tendencias -->
                        <div class="mt-4">
                            <h6 class="font-weight-bold text-primary">
                                <i class="fas fa-chart-line"></i> Tendencia de Promedios
                            </h6>
                            <canvas id="averagesChart" width="400" height="200"></canvas>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            No hay datos de calificaciones disponibles para mostrar promedios por período.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar con información adicional -->
        <div class="col-lg-4">
            <!-- Mejores estudiantes -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-trophy"></i> Mejores Estudiantes
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($topStudents)) : ?>
                        <div class="list-group list-group-flush">
                            <?php foreach (array_slice($topStudents, 0, 5) as $index => $student) : ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="font-weight-bold">
                                            <?= htmlspecialchars($student['student_name']) ?>
                                        </div>
                                        <small class="text-muted">
                                            <?= htmlspecialchars($student['academic_term_name']) ?>
                                        </small>
                                    </div>
                                    <span class="badge badge-success badge-pill">
                                        <?= $student['promedio'] ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-3">
                            <a href="<?= $GLOBALS['url'] ?>academicAverages/topStudents" class="btn btn-sm btn-outline-success">
                                Ver todos los estudiantes
                            </a>
                        </div>
                    <?php else : ?>
                        <div class="text-center text-muted">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <p>No hay datos de estudiantes disponibles</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Enlaces rápidos -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-link"></i> Enlaces Rápidos
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="<?= $GLOBALS['url'] ?>academicAverages/subjects" class="list-group-item list-group-item-action">
                            <i class="fas fa-book text-primary"></i> Promedios por Asignatura
                        </a>
                        <a href="<?= $GLOBALS['url'] ?>academicAverages/professors" class="list-group-item list-group-item-action">
                            <i class="fas fa-chalkboard-teacher text-success"></i> Promedios por Profesor
                        </a>
                        <a href="<?= $GLOBALS['url'] ?>academicAverages/topStudents" class="list-group-item list-group-item-action">
                            <i class="fas fa-trophy text-warning"></i> Mejores Estudiantes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts para la funcionalidad -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configurar gráfico de promedios
    const ctx = document.getElementById('averagesChart');
    if (ctx) {
        const rows = document.querySelectorAll('tbody tr');
        const labels = [];
        const data = [];
        
        rows.forEach((row) => {
            const period = row.cells[0].textContent.trim();
            const average = parseFloat(row.cells[1].textContent.trim());
            
            labels.push(period);
            data.push(average);
        });
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Promedio por Período',
                    data: data,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Promedio: ' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
});

// Funciones de utilidad
function refreshData() {
    location.reload();
}

function toggleChart() {
    const chart = document.getElementById('averagesChart');
    if (chart) {
        chart.style.display = chart.style.display === 'none' ? 'block' : 'none';
    }
}

function exportToPDF() {
    // Implementar exportación a PDF
    alert('Función de exportación a PDF en desarrollo');
}

function exportToExcel() {
    // Implementar exportación a Excel
    alert('Función de exportación a Excel en desarrollo');
}
</script>

<style>
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 0.5rem;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 0.5rem 0.5rem 0 0 !important;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.875rem;
}

.badge {
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
}

.progress {
    border-radius: 1rem;
    background-color: #e9ecef;
}

.progress-bar {
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 600;
}

.list-group-item {
    border: none;
    border-bottom: 1px solid #e3e6f0;
}

.list-group-item:last-child {
    border-bottom: none;
}

canvas {
    border-radius: 0.5rem;
    background: white;
    padding: 1rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
</style> 