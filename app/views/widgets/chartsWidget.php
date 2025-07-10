<?php
// Widget de gráficos estadísticos
require_once ROOT . '/app/models/activityStatsModel.php';

$statsModel = new ActivityStatsModel($dbConn);
$chartData = $statsModel->getChartData();
$subjectStats = $statsModel->getStatsBySubject();
$studentPerformance = $statsModel->getStudentPerformance();
?>

<div class="card charts-widget">
    <div class="card-header">
        <h5><i class="fas fa-chart-bar"></i> Estadísticas Académicas</h5>
        <div class="btn-group btn-group-sm" role="group">
            <button type="button" class="btn btn-outline-primary active" onclick="showChart('activities')">Actividades</button>
            <button type="button" class="btn btn-outline-success" onclick="showChart('grades')">Calificaciones</button>
            <button type="button" class="btn btn-outline-info" onclick="showChart('attendance')">Asistencia</button>
        </div>
    </div>
    <div class="card-body">
        <!-- Gráfico principal -->
        <div class="chart-container" style="position: relative; height: 300px;">
            <canvas id="mainChart"></canvas>
        </div>
        
        <!-- Estadísticas adicionales -->
        <div class="row mt-4">
            <div class="col-md-6">
                <h6><i class="fas fa-book"></i> Actividades por Materia</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Materia</th>
                                <th>Actividades</th>
                                <th>Promedio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($subjectStats, 0, 5) as $subject): ?>
                                <tr>
                                    <td><?= htmlspecialchars($subject['subject_name']) ?></td>
                                    <td><?= $subject['total_activities'] ?></td>
                                    <td>
                                        <span class="badge badge-<?= $subject['average_score'] >= 7 ? 'success' : ($subject['average_score'] >= 5 ? 'warning' : 'danger') ?>">
                                            <?= number_format($subject['average_score'], 1) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <h6><i class="fas fa-trophy"></i> Mejores Estudiantes</h6>
                <div class="list-group">
                    <?php foreach (array_slice($studentPerformance, 0, 5) as $student): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?= htmlspecialchars($student['student_name']) ?></strong>
                                <br>
                                <small class="text-muted"><?= $student['total_activities'] ?> actividades</small>
                            </div>
                            <span class="badge badge-success"><?= number_format($student['average_score'], 1) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Datos del gráfico
const chartData = {
    months: <?= json_encode($chartData['months']) ?>,
    activities: <?= json_encode($chartData['activities']) ?>,
    grades: <?= json_encode($chartData['grades']) ?>,
    attendance: <?= json_encode($chartData['attendance']) ?>
};

// Configuración del gráfico
let mainChart;
let currentChartType = 'activities';

function initChart() {
    const ctx = document.getElementById('mainChart').getContext('2d');
    
    mainChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.months,
            datasets: [{
                label: 'Actividades por Mes',
                data: chartData.activities,
                backgroundColor: '#007bff',
                borderColor: '#0056b3',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

function showChart(type) {
    // Actualizar botones
    document.querySelectorAll('.btn-group .btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    // Actualizar datos del gráfico
    let data, label, color;
    
    switch(type) {
        case 'activities':
            data = chartData.activities;
            label = 'Actividades por Mes';
            color = '#007bff';
            break;
        case 'grades':
            data = chartData.grades;
            label = 'Promedio de Calificaciones';
            color = '#28a745';
            break;
        case 'attendance':
            data = chartData.attendance;
            label = 'Porcentaje de Asistencia';
            color = '#17a2b8';
            break;
    }
    
    // Actualizar gráfico
    mainChart.data.datasets[0].data = data;
    mainChart.data.datasets[0].label = label;
    mainChart.data.datasets[0].backgroundColor = color;
    mainChart.data.datasets[0].borderColor = color;
    mainChart.update();
    
    currentChartType = type;
}

// Inicializar gráfico cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    initChart();
});
</script>

<style>
.charts-widget {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-radius: 10px;
}

.charts-widget .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px 10px 0 0;
    border: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}

.charts-widget .card-header h5 {
    margin: 0;
    font-size: 1.1rem;
}

.btn-group .btn {
    border-color: rgba(255,255,255,0.5);
    color: white;
}

.btn-group .btn:hover,
.btn-group .btn.active {
    background-color: rgba(255,255,255,0.2);
    border-color: white;
    color: white;
}

.chart-container {
    background: white;
    border-radius: 8px;
    padding: 10px;
}

.table th {
    font-size: 0.8rem;
    font-weight: 600;
}

.table td {
    font-size: 0.8rem;
}

.list-group-item {
    border-left: 4px solid #28a745;
    font-size: 0.9rem;
}

.badge {
    font-size: 0.8rem;
    padding: 5px 10px;
}

@media (max-width: 768px) {
    .charts-widget .card-header {
        flex-direction: column;
        gap: 10px;
    }
    
    .btn-group {
        width: 100%;
    }
    
    .btn-group .btn {
        flex: 1;
    }
}
</style> 