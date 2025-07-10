<?php
require_once 'app/views/layouts/dashHeader.php';
?>

<div class="student-stats-dashboard">
    <div class="container-fluid">
        <!-- Header del dashboard -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2><i class="fas fa-chart-bar"></i> Estadísticas de Estudiantes</h2>
                        <p class="text-muted">Análisis detallado del rendimiento y distribución estudiantil</p>
                    </div>
                    <div class="dashboard-actions">
                        <button class="btn btn-outline-primary" onclick="exportToExcel()">
                            <i class="fas fa-file-excel"></i> Exportar Excel
                        </button>
                        <button class="btn btn-outline-secondary" onclick="generatePDF()">
                            <i class="fas fa-file-pdf"></i> Generar PDF
                        </button>
                        <button class="btn btn-success" onclick="refreshData()">
                            <i class="fas fa-sync-alt"></i> Actualizar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Métricas principales -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="metric-card total">
                    <div class="metric-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="metric-content">
                        <h3><?php echo number_format($stats['total_students']); ?></h3>
                        <p>Total Estudiantes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card active">
                    <div class="metric-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="metric-content">
                        <h3><?php echo number_format($stats['active_students']); ?></h3>
                        <p>Estudiantes Activos</p>
                        <small><?php echo $stats['active_percentage']; ?>% del total</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card inactive">
                    <div class="metric-icon">
                        <i class="fas fa-user-times"></i>
                    </div>
                    <div class="metric-content">
                        <h3><?php echo number_format($stats['inactive_students']); ?></h3>
                        <p>Estudiantes Inactivos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card growth">
                    <div class="metric-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="metric-content">
                        <h3><?php echo count($monthlyGrowth); ?></h3>
                        <p>Meses de Datos</p>
                        <small>Últimos 12 meses</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos principales -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="chart-card">
                    <h5><i class="fas fa-venus-mars"></i> Distribución por Género</h5>
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="genderChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-card">
                    <h5><i class="fas fa-birthday-cake"></i> Distribución por Edad</h5>
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="ageChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico de crecimiento mensual -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="chart-card">
                    <h5><i class="fas fa-chart-line"></i> Crecimiento Mensual</h5>
                    <div class="chart-container" style="position: relative; height: 200px;">
                        <canvas id="growthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tablas de estudiantes -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="table-card">
                    <h5><i class="fas fa-star"></i> Mejor Rendimiento</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Estudiante</th>
                                    <th>Promedio</th>
                                    <th>Actividades</th>
                                    <th>Última Actividad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($topStudents as $student): ?>
                                <tr>
                                    <td>
                                        <div class="student-info">
                                            <strong><?php echo htmlspecialchars($student['student_name']); ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-success"><?php echo $student['average_score']; ?></span>
                                    </td>
                                    <td><?php echo $student['total_activities']; ?></td>
                                    <td>
                                        <small><?php echo date('d/m/Y', strtotime($student['last_activity'])); ?></small>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="table-card">
                    <h5><i class="fas fa-exclamation-triangle"></i> Necesitan Atención</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Estudiante</th>
                                    <th>Promedio</th>
                                    <th>Asistencias</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($attentionStudents as $student): ?>
                                <tr class="<?php echo $student['average_score'] < 6 ? 'table-warning' : ''; ?>">
                                    <td>
                                        <div class="student-info">
                                            <strong><?php echo htmlspecialchars($student['student_name']); ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $student['average_score'] < 6 ? 'bg-danger' : 'bg-warning'; ?>">
                                            <?php echo $student['average_score']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo $student['attendance_count']; ?></td>
                                    <td>
                                        <?php if ($student['average_score'] < 6): ?>
                                            <span class="badge bg-danger">Bajo Rendimiento</span>
                                        <?php elseif ($student['attendance_count'] < 5): ?>
                                            <span class="badge bg-warning">Baja Asistencia</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estudiantes recientes -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="table-card">
                    <h5><i class="fas fa-clock"></i> Estudiantes Recientes</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Estudiante</th>
                                    <th>Email</th>
                                    <th>Fecha de Registro</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentStudents as $student): ?>
                                <tr>
                                    <td>
                                        <div class="student-info">
                                            <strong><?php echo htmlspecialchars($student['student_name']); ?></strong>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($student['created_at'])); ?></td>
                                    <td>
                                        <span class="badge <?php echo $student['is_active'] ? 'bg-success' : 'bg-secondary'; ?>">
                                            <?php echo $student['is_active'] ? 'Activo' : 'Inactivo'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewStudent(<?php echo $student['student_id'] ?? 0; ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.student-stats-dashboard {
    padding: 20px;
    background: #f8f9fa;
    min-height: 100vh;
}

.dashboard-actions {
    display: flex;
    gap: 10px;
}

.metric-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    transition: transform 0.2s ease;
}

.metric-card:hover {
    transform: translateY(-2px);
}

.metric-card.total {
    border-left: 4px solid #28a745;
}

.metric-card.active {
    border-left: 4px solid #007bff;
}

.metric-card.inactive {
    border-left: 4px solid #dc3545;
}

.metric-card.growth {
    border-left: 4px solid #ffc107;
}

.metric-icon {
    margin-right: 15px;
    font-size: 2rem;
    color: #007bff;
}

.metric-content h3 {
    margin: 0;
    font-size: 2rem;
    font-weight: 700;
    color: #333;
}

.metric-content p {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
}

.metric-content small {
    color: #999;
    font-size: 0.8rem;
}

.chart-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.chart-card h5 {
    margin: 0 0 20px 0;
    color: #333;
    font-weight: 600;
}

.chart-card h5 i {
    margin-right: 8px;
    color: #007bff;
}

.table-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.table-card h5 {
    margin: 0 0 20px 0;
    color: #333;
    font-weight: 600;
}

.table-card h5 i {
    margin-right: 8px;
    color: #007bff;
}

.student-info {
    display: flex;
    flex-direction: column;
}

.student-info strong {
    color: #333;
}

@media (max-width: 768px) {
    .dashboard-actions {
        flex-direction: column;
    }
    
    .metric-card {
        margin-bottom: 15px;
    }
}
</style>

<script>
// Datos para los gráficos
const genderData = <?php echo json_encode($genderStats); ?>;
const ageData = <?php echo json_encode($ageStats); ?>;
const growthData = <?php echo json_encode($monthlyGrowth); ?>;

// Gráfico de género
const genderCtx = document.getElementById('genderChart').getContext('2d');
new Chart(genderCtx, {
    type: 'doughnut',
    data: {
        labels: genderData.map(item => item.gender),
        datasets: [{
            data: genderData.map(item => item.total),
            backgroundColor: [
                '#007bff',
                '#28a745',
                '#ffc107',
                '#dc3545'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    usePointStyle: true
                }
            }
        }
    }
});

// Gráfico de edad
const ageCtx = document.getElementById('ageChart').getContext('2d');
new Chart(ageCtx, {
    type: 'bar',
    data: {
        labels: ageData.map(item => item.age_group),
        datasets: [{
            label: 'Total',
            data: ageData.map(item => item.total),
            backgroundColor: '#007bff',
            borderColor: '#0056b3',
            borderWidth: 1
        }, {
            label: 'Activos',
            data: ageData.map(item => item.active),
            backgroundColor: '#28a745',
            borderColor: '#1e7e34',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                position: 'top'
            }
        }
    }
});

// Gráfico de crecimiento
const growthCtx = document.getElementById('growthChart').getContext('2d');
new Chart(growthCtx, {
    type: 'line',
    data: {
        labels: growthData.map(item => {
            const date = new Date(item.month + '-01');
            return date.toLocaleDateString('es-ES', { month: 'short', year: 'numeric' });
        }),
        datasets: [{
            label: 'Nuevos Estudiantes',
            data: growthData.map(item => item.new_students),
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
            borderWidth: 2,
            fill: true
        }, {
            label: 'Estudiantes Activos',
            data: growthData.map(item => item.active_new),
            borderColor: '#28a745',
            backgroundColor: 'rgba(40, 167, 69, 0.1)',
            borderWidth: 2,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                position: 'top'
            }
        }
    }
});

// Funciones de utilidad
function refreshData() {
    location.reload();
}

function exportToExcel() {
    window.open('<?php echo url; ?>?view=studentStats&action=exportExcel', '_blank');
}

function generatePDF() {
    window.open('<?php echo url; ?>?view=studentStats&action=generateReport', '_blank');
}

function viewStudent(studentId) {
    if (studentId > 0) {
        loadView('student/view&id=' + studentId);
    }
}
</script>

<?php require_once 'app/views/layouts/dashFooter.php'; ?> 