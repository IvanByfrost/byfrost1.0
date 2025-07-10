<?php
require_once 'app/models/studentStatsModel.php';
$studentStats = new StudentStatsModel($dbConn);
$stats = $studentStats->getStudentStats();
$genderStats = $studentStats->getStudentStatsByGender();
$ageStats = $studentStats->getStudentStatsByAge();
$recentStudents = $studentStats->getRecentStudents(5);
$topStudents = $studentStats->getTopPerformingStudents(5);
$attentionStudents = $studentStats->getStudentsNeedingAttention(5);
?>

<div class="student-stats-widget">
    <div class="widget-header">
        <h3><i class="fas fa-users"></i> Estadísticas de Estudiantes</h3>
        <div class="widget-actions">
            <button class="btn btn-sm btn-outline-primary" onclick="refreshStudentStats()">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>

    <!-- Métricas principales -->
    <div class="stats-cards">
        <div class="stat-card total">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h4><?php echo number_format($stats['total_students']); ?></h4>
                <p>Total Estudiantes</p>
            </div>
        </div>

        <div class="stat-card active">
            <div class="stat-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-content">
                <h4><?php echo number_format($stats['active_students']); ?></h4>
                <p>Activos (<?php echo $stats['active_percentage']; ?>%)</p>
            </div>
        </div>

        <div class="stat-card inactive">
            <div class="stat-icon">
                <i class="fas fa-user-times"></i>
            </div>
            <div class="stat-content">
                <h4><?php echo number_format($stats['inactive_students']); ?></h4>
                <p>Inactivos</p>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="charts-container">
        <div class="chart-section">
            <h5>Distribución por Género</h5>
            <div class="chart-container" style="position: relative; height: 300px;">
                <canvas id="genderChart"></canvas>
            </div>
        </div>

        <div class="chart-section">
            <h5>Distribución por Edad</h5>
            <div class="chart-container" style="position: relative; height: 300px;">
                <canvas id="ageChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Listas de estudiantes -->
    <div class="students-lists">
        <div class="list-section">
            <h5><i class="fas fa-star"></i> Mejor Rendimiento</h5>
            <div class="student-list">
                <?php foreach ($topStudents as $student): ?>
                <div class="student-item">
                    <div class="student-info">
                        <span class="student-name"><?php echo htmlspecialchars($student['student_name']); ?></span>
                        <span class="student-score">Promedio: <?php echo $student['average_score']; ?></span>
                    </div>
                    <div class="student-activity">
                        <small><?php echo $student['total_activities']; ?> actividades</small>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="list-section">
            <h5><i class="fas fa-exclamation-triangle"></i> Necesitan Atención</h5>
            <div class="student-list">
                <?php foreach ($attentionStudents as $student): ?>
                <div class="student-item attention">
                    <div class="student-info">
                        <span class="student-name"><?php echo htmlspecialchars($student['student_name']); ?></span>
                        <span class="student-score">Promedio: <?php echo $student['average_score']; ?></span>
                    </div>
                    <div class="student-activity">
                        <small><?php echo $student['attendance_count']; ?> asistencias</small>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Estudiantes recientes -->
    <div class="recent-students">
        <h5><i class="fas fa-clock"></i> Estudiantes Recientes</h5>
        <div class="recent-list">
            <?php foreach ($recentStudents as $student): ?>
            <div class="recent-item">
                <div class="student-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="student-details">
                    <span class="student-name"><?php echo htmlspecialchars($student['student_name']); ?></span>
                    <span class="student-email"><?php echo htmlspecialchars($student['email']); ?></span>
                    <small class="student-date"><?php echo date('d/m/Y', strtotime($student['created_at'])); ?></small>
                </div>
                <div class="student-status">
                    <span class="status-badge <?php echo $student['is_active'] ? 'active' : 'inactive'; ?>">
                        <?php echo $student['is_active'] ? 'Activo' : 'Inactivo'; ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
.student-stats-widget {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 20px;
}

.widget-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.widget-header h3 {
    margin: 0;
    color: #333;
    font-size: 1.2rem;
    font-weight: 600;
}

.widget-header h3 i {
    margin-right: 8px;
    color: #007bff;
}

.stats-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 25px;
}

.stat-card {
    display: flex;
    align-items: center;
    padding: 15px;
    border-radius: 8px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-left: 4px solid #007bff;
    transition: transform 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-card.total {
    border-left-color: #28a745;
}

.stat-card.active {
    border-left-color: #007bff;
}

.stat-card.inactive {
    border-left-color: #dc3545;
}

.stat-icon {
    margin-right: 15px;
    font-size: 1.5rem;
    color: #007bff;
}

.stat-content h4 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
}

.stat-content p {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
}

.charts-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.chart-section {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
}

.chart-section h5 {
    margin: 0 0 15px 0;
    color: #333;
    font-size: 1rem;
}

.students-lists {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.list-section {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
}

.list-section h5 {
    margin: 0 0 15px 0;
    color: #333;
    font-size: 1rem;
}

.list-section h5 i {
    margin-right: 8px;
    color: #007bff;
}

.student-list {
    max-height: 200px;
    overflow-y: auto;
}

.student-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #e9ecef;
}

.student-item:last-child {
    border-bottom: none;
}

.student-item.attention {
    background: rgba(255, 193, 7, 0.1);
    border-radius: 4px;
    padding: 8px;
    margin: 2px 0;
}

.student-info {
    display: flex;
    flex-direction: column;
}

.student-name {
    font-weight: 500;
    color: #333;
}

.student-score {
    font-size: 0.85rem;
    color: #666;
}

.student-activity {
    text-align: right;
}

.recent-students {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
}

.recent-students h5 {
    margin: 0 0 15px 0;
    color: #333;
    font-size: 1rem;
}

.recent-students h5 i {
    margin-right: 8px;
    color: #007bff;
}

.recent-list {
    max-height: 250px;
    overflow-y: auto;
}

.recent-item {
    display: flex;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #e9ecef;
}

.recent-item:last-child {
    border-bottom: none;
}

.student-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #007bff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-right: 12px;
}

.student-details {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.student-details .student-name {
    font-weight: 500;
    color: #333;
}

.student-details .student-email {
    font-size: 0.85rem;
    color: #666;
}

.student-details .student-date {
    font-size: 0.8rem;
    color: #999;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-badge.active {
    background: #d4edda;
    color: #155724;
}

.status-badge.inactive {
    background: #f8d7da;
    color: #721c24;
}

@media (max-width: 768px) {
    .stats-cards {
        grid-template-columns: 1fr;
    }
    
    .charts-container {
        grid-template-columns: 1fr;
    }
    
    .students-lists {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Datos para los gráficos
const genderData = <?php echo json_encode($genderStats); ?>;
const ageData = <?php echo json_encode($ageStats); ?>;

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

// Función para refrescar estadísticas
function refreshStudentStats() {
    // Aquí puedes agregar lógica AJAX para refrescar los datos
    location.reload();
}
</script> 