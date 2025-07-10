<?php
require_once ROOT . '/app/models/activityStatsModel.php';

$statsModel = new ActivityStatsModel($dbConn);
$chartData = $statsModel->getChartData();
$subjectStats = $statsModel->getStatsBySubject();
$studentPerformance = $statsModel->getStudentPerformance();
$activitiesByMonth = $statsModel->getActivitiesByMonth();
$gradesByMonth = $statsModel->getGradesByMonth();
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-chart-line"></i> Reportes Académicos</h2>
            <p class="text-muted">Análisis detallado del rendimiento académico</p>
        </div>
    </div>

    <!-- Gráficos principales -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-bar"></i> Estadísticas por Mes</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 400px;">
                        <canvas id="mainChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-pie-chart"></i> Distribución por Materia</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 400px;">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tablas de estadísticas -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-trophy"></i> Top 10 Estudiantes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Estudiante</th>
                                    <th>Actividades</th>
                                    <th>Promedio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($studentPerformance as $index => $student): ?>
                                    <tr>
                                        <td>
                                            <?php if ($index < 3): ?>
                                                <span class="badge badge-warning"><?= $index + 1 ?></span>
                                            <?php else: ?>
                                                <?= $index + 1 ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($student['student_name']) ?></td>
                                        <td><?= $student['total_activities'] ?></td>
                                        <td>
                                            <span class="badge badge-<?= $student['average_score'] >= 8 ? 'success' : ($student['average_score'] >= 6 ? 'warning' : 'danger') ?>">
                                                <?= number_format($student['average_score'], 1) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-book"></i> Rendimiento por Materia</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Materia</th>
                                    <th>Actividades</th>
                                    <th>Promedio</th>
                                    <th>Estudiantes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($subjectStats as $subject): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($subject['subject_name']) ?></td>
                                        <td><?= $subject['total_activities'] ?></td>
                                        <td>
                                            <span class="badge badge-<?= $subject['average_score'] >= 7 ? 'success' : ($subject['average_score'] >= 5 ? 'warning' : 'danger') ?>">
                                                <?= number_format($subject['average_score'], 1) ?>
                                            </span>
                                        </td>
                                        <td><?= $subject['unique_students'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen de métricas -->
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3><?= array_sum($chartData['activities']) ?></h3>
                    <p>Total Actividades</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3><?= number_format(array_sum($chartData['grades']) / count(array_filter($chartData['grades'])), 1) ?></h3>
                    <p>Promedio General</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3><?= number_format(array_sum($chartData['attendance']) / count(array_filter($chartData['attendance'])), 1) ?>%</h3>
                    <p>Asistencia Promedio</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h3><?= count($subjectStats) ?></h3>
                    <p>Materias Activas</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Datos para los gráficos
const chartData = {
    months: <?= json_encode($chartData['months']) ?>,
    activities: <?= json_encode($chartData['activities']) ?>,
    grades: <?= json_encode($chartData['grades']) ?>,
    attendance: <?= json_encode($chartData['attendance']) ?>
};

const subjectData = {
    labels: <?= json_encode(array_column($subjectStats, 'subject_name')) ?>,
    data: <?= json_encode(array_column($subjectStats, 'total_activities')) ?>
};

// Gráfico principal (línea múltiple)
const mainCtx = document.getElementById('mainChart').getContext('2d');
new Chart(mainCtx, {
    type: 'line',
    data: {
        labels: chartData.months,
        datasets: [
            {
                label: 'Actividades',
                data: chartData.activities,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4
            },
            {
                label: 'Promedio Calificaciones',
                data: chartData.grades,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4
            },
            {
                label: 'Asistencia (%)',
                data: chartData.attendance,
                borderColor: '#17a2b8',
                backgroundColor: 'rgba(23, 162, 184, 0.1)',
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
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

// Gráfico de pastel
const pieCtx = document.getElementById('pieChart').getContext('2d');
new Chart(pieCtx, {
    type: 'doughnut',
    data: {
        labels: subjectData.labels,
        datasets: [{
            data: subjectData.data,
            backgroundColor: [
                '#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1',
                '#fd7e14', '#20c997', '#e83e8c', '#6c757d', '#17a2b8'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>

<style>
.card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-radius: 10px;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px 10px 0 0;
    border: none;
}

.chart-container {
    background: white;
    border-radius: 8px;
    padding: 10px;
}

.table th {
    font-size: 0.9rem;
    font-weight: 600;
}

.badge {
    font-size: 0.8rem;
    padding: 5px 10px;
}
</style> 