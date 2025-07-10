<?php
require_once 'app/models/academicStatsModel.php';
$academicStatsModel = new AcademicStatsModel($dbConn);

// Función para obtener color según el rango de calificación
function getScoreColor($scoreRange) {
    $colors = [
        'Excelente (4.5-5.0)' => '#28a745',
        'Bueno (3.5-4.4)' => '#17a2b8',
        'Aceptable (3.0-3.4)' => '#ffc107',
        'Deficiente (2.0-2.9)' => '#fd7e14',
        'Insuficiente (0.0-1.9)' => '#dc3545'
    ];
    return $colors[$scoreRange] ?? '#6c757d';
}

// Obtener datos académicos
$generalStats = $academicStatsModel->getGeneralStats();
$averagesByTerm = $academicStatsModel->getAveragesByTerm();
$averagesBySubject = $academicStatsModel->getAveragesBySubject();
$topStudents = $academicStatsModel->getTopStudents(5);
$topSubjects = $academicStatsModel->getTopSubjects(5);
$scoreDistribution = $academicStatsModel->getScoreDistribution();
$termComparison = $academicStatsModel->getTermComparison();
?>

<div class="academic-stats-widget">
    <div class="widget-header">
        <h3><i class="fas fa-chart-line"></i> Estadísticas Académicas</h3>
        <div class="widget-actions">
            <button class="btn btn-sm btn-outline-primary" onclick="refreshAcademicStats()">
                <i class="fas fa-sync-alt"></i>
            </button>
            <button class="btn btn-sm btn-outline-success" onclick="exportAcademicReport()">
                <i class="fas fa-download"></i>
            </button>
        </div>
    </div>

    <!-- Métricas principales -->
    <div class="academic-metrics">
        <div class="metric-card global">
            <div class="metric-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="metric-content">
                <h4><?php echo number_format($generalStats['global_average'] ?? 0, 2); ?></h4>
                <p>Promedio Global</p>
                <small><?php echo number_format($generalStats['total_scores'] ?? 0); ?> calificaciones</small>
            </div>
        </div>

        <div class="metric-card students">
            <div class="metric-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="metric-content">
                <h4><?php echo number_format($generalStats['total_students'] ?? 0); ?></h4>
                <p>Estudiantes</p>
                <small><?php echo number_format($generalStats['total_activities'] ?? 0); ?> actividades</small>
            </div>
        </div>

        <div class="metric-card passing">
            <div class="metric-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="metric-content">
                <h4><?php echo $generalStats['global_pass_rate'] ?? 0; ?>%</h4>
                <p>Tasa de Aprobación</p>
                <small><?php echo number_format($generalStats['passing_scores'] ?? 0); ?> aprobados</small>
            </div>
        </div>

        <div class="metric-card range">
            <div class="metric-icon">
                <i class="fas fa-chart-bar"></i>
            </div>
            <div class="metric-content">
                <h4><?php echo number_format($generalStats['highest_score'] ?? 0, 1); ?></h4>
                <p>Mejor Calificación</p>
                <small>Rango: <?php echo number_format($generalStats['lowest_score'] ?? 0, 1); ?> - <?php echo number_format($generalStats['highest_score'] ?? 0, 1); ?></small>
            </div>
        </div>
    </div>

    <!-- Promedios por período -->
    <div class="term-averages">
        <h5><i class="fas fa-calendar-alt"></i> Promedios por Período</h5>
        <div class="term-cards">
            <?php if (empty($averagesByTerm)): ?>
                <div class="no-data">
                    <i class="fas fa-info-circle"></i>
                    <span>No hay datos de períodos académicos</span>
                </div>
            <?php else: ?>
                <?php foreach ($averagesByTerm as $term): ?>
                <div class="term-card">
                    <div class="term-header">
                        <h6><?php echo htmlspecialchars($term['term_name']); ?></h6>
                        <span class="term-score"><?php echo number_format($term['average_score'], 2); ?></span>
                    </div>
                    <div class="term-stats">
                        <div class="stat-item">
                            <span class="label">Calificaciones:</span>
                            <span class="value"><?php echo number_format($term['total_scores']); ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="label">Aprobados:</span>
                            <span class="value success"><?php echo number_format($term['passing_scores']); ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="label">Reprobados:</span>
                            <span class="value danger"><?php echo number_format($term['failing_scores']); ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="label">Tasa de aprobación:</span>
                            <span class="value"><?php echo $term['pass_rate']; ?>%</span>
                        </div>
                    </div>
                    <div class="term-actions">
                        <button class="btn btn-sm btn-outline-primary" onclick="viewTermDetails(<?php echo $term['term_id']; ?>)">
                            <i class="fas fa-eye"></i> Ver Detalles
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Mejores estudiantes -->
    <div class="top-students">
        <h5><i class="fas fa-trophy"></i> Mejores Estudiantes</h5>
        <div class="students-list">
            <?php if (empty($topStudents)): ?>
                <div class="no-data">
                    <i class="fas fa-info-circle"></i>
                    <span>No hay datos de estudiantes</span>
                </div>
            <?php else: ?>
                <?php foreach ($topStudents as $index => $student): ?>
                <div class="student-item">
                    <div class="student-rank">
                        <span class="rank-number">#<?php echo $index + 1; ?></span>
                    </div>
                    <div class="student-info">
                        <strong><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></strong>
                        <span class="stats"><?php echo $student['total_scores']; ?> calificaciones</span>
                    </div>
                    <div class="student-score">
                        <span class="score"><?php echo number_format($student['average_score'], 2); ?></span>
                        <small>Promedio general</small>
                    </div>
                    <div class="student-actions">
                        <button class="btn btn-sm btn-outline-primary" onclick="viewStudentDetails(<?php echo $student['user_id']; ?>)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Mejores asignaturas -->
    <div class="top-subjects">
        <h5><i class="fas fa-book"></i> Asignaturas con Mejor Rendimiento</h5>
        <div class="subjects-list">
            <?php if (empty($topSubjects)): ?>
                <div class="no-data">
                    <i class="fas fa-info-circle"></i>
                    <span>No hay datos de asignaturas</span>
                </div>
            <?php else: ?>
                <?php foreach ($topSubjects as $index => $subject): ?>
                <div class="subject-item">
                    <div class="subject-rank">
                        <span class="rank-number">#<?php echo $index + 1; ?></span>
                    </div>
                    <div class="subject-info">
                        <strong><?php echo htmlspecialchars($subject['subject_name']); ?></strong>
                        <span class="stats"><?php echo $subject['total_scores']; ?> calificaciones</span>
                    </div>
                    <div class="subject-score">
                        <span class="score"><?php echo number_format($subject['average_score'], 2); ?></span>
                        <small><?php echo $subject['pass_rate']; ?>% aprobación</small>
                    </div>
                    <div class="subject-actions">
                        <button class="btn btn-sm btn-outline-primary" onclick="viewSubjectDetails(<?php echo $subject['subject_id']; ?>)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Distribución de calificaciones -->
    <div class="score-distribution">
        <h5><i class="fas fa-chart-pie"></i> Distribución de Calificaciones</h5>
        <div class="distribution-chart">
            <?php if (empty($scoreDistribution)): ?>
                <div class="no-data">
                    <i class="fas fa-info-circle"></i>
                    <span>No hay datos de distribución</span>
                </div>
            <?php else: ?>
                <div class="distribution-bars">
                    <?php foreach ($scoreDistribution as $distribution): ?>
                    <div class="distribution-bar">
                        <div class="bar-label">
                            <span class="range-name"><?php echo htmlspecialchars($distribution['score_range']); ?></span>
                            <span class="range-count"><?php echo $distribution['count']; ?> estudiantes</span>
                        </div>
                        <div class="bar-container">
                            <div class="bar-fill" style="width: <?php echo $distribution['percentage']; ?>%; background-color: <?php echo getScoreColor($distribution['score_range']); ?>;"></div>
                        </div>
                        <div class="bar-percentage">
                            <span><?php echo $distribution['percentage']; ?>%</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Comparación entre períodos -->
    <div class="term-comparison">
        <h5><i class="fas fa-chart-line"></i> Comparación entre Períodos</h5>
        <div class="comparison-chart">
            <?php if (empty($termComparison)): ?>
                <div class="no-data">
                    <i class="fas fa-info-circle"></i>
                    <span>No hay datos de comparación</span>
                </div>
            <?php else: ?>
                <div class="comparison-bars">
                    <?php foreach ($termComparison as $term): ?>
                    <div class="comparison-item">
                        <div class="term-info">
                            <h6><?php echo htmlspecialchars($term['term_name']); ?></h6>
                            <span class="term-stats">
                                <?php echo $term['total_scores']; ?> calificaciones
                            </span>
                        </div>
                        <div class="term-metrics">
                            <div class="metric">
                                <span class="label">Promedio:</span>
                                <span class="value"><?php echo number_format($term['average_score'], 2); ?></span>
                            </div>
                            <div class="metric">
                                <span class="label">Aprobación:</span>
                                <span class="value success"><?php echo $term['pass_rate']; ?>%</span>
                            </div>
                            <div class="metric">
                                <span class="label">Desviación:</span>
                                <span class="value"><?php echo number_format($term['standard_deviation'], 2); ?></span>
                            </div>
                        </div>
                        <div class="term-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="viewTermComparison(<?php echo $term['term_id']; ?>)">
                                <i class="fas fa-chart-line"></i> Ver Gráfico
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="quick-actions">
        <button class="btn btn-primary" onclick="viewFullReport()">
            <i class="fas fa-file-alt"></i> Ver Reporte Completo
        </button>
        <button class="btn btn-success" onclick="exportAcademicReport()">
            <i class="fas fa-download"></i> Exportar Reporte
        </button>
        <button class="btn btn-info" onclick="viewTrends()">
            <i class="fas fa-chart-line"></i> Ver Tendencias
        </button>
        <button class="btn btn-warning" onclick="viewAnalytics()">
            <i class="fas fa-analytics"></i> Análisis Avanzado
        </button>
    </div>
</div>

<style>
.academic-stats-widget {
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

.widget-actions {
    display: flex;
    gap: 8px;
}

.academic-metrics {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 25px;
}

.metric-card {
    display: flex;
    align-items: center;
    padding: 15px;
    border-radius: 8px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    transition: transform 0.2s ease;
}

.metric-card:hover {
    transform: translateY(-2px);
}

.metric-card.global {
    border-left: 4px solid #007bff;
}

.metric-card.students {
    border-left: 4px solid #28a745;
}

.metric-card.passing {
    border-left: 4px solid #ffc107;
}

.metric-card.range {
    border-left: 4px solid #dc3545;
}

.metric-icon {
    margin-right: 15px;
    font-size: 1.5rem;
    color: #007bff;
}

.metric-content h4 {
    margin: 0;
    font-size: 1.5rem;
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

.term-averages, .top-students, .top-subjects, .score-distribution {
    margin-bottom: 25px;
}

.term-averages h5, .top-students h5, .top-subjects h5, .score-distribution h5 {
    margin: 0 0 15px 0;
    color: #333;
    font-weight: 600;
    font-size: 1rem;
}

.term-averages h5 i, .top-students h5 i, .top-subjects h5 i, .score-distribution h5 i {
    margin-right: 8px;
}

.term-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 15px;
}

.term-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    border-left: 4px solid #007bff;
    transition: all 0.2s ease;
}

.term-card:hover {
    background: #e9ecef;
    transform: translateY(-2px);
}

.term-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.term-header h6 {
    margin: 0;
    color: #333;
    font-weight: 600;
}

.term-score {
    font-size: 1.2rem;
    font-weight: 700;
    color: #007bff;
}

.term-stats {
    margin-bottom: 10px;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
    font-size: 0.85rem;
}

.stat-item .label {
    color: #666;
}

.stat-item .value {
    font-weight: 600;
    color: #333;
}

.stat-item .value.success {
    color: #28a745;
}

.stat-item .value.danger {
    color: #dc3545;
}

.term-actions {
    text-align: center;
}

.students-list, .subjects-list {
    max-height: 300px;
    overflow-y: auto;
}

.student-item, .subject-item {
    display: flex;
    align-items: center;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 8px;
    background: #f8f9fa;
    border-left: 4px solid #28a745;
    transition: all 0.2s ease;
}

.student-item:hover, .subject-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.student-rank, .subject-rank {
    margin-right: 15px;
}

.rank-number {
    display: inline-block;
    width: 30px;
    height: 30px;
    background: #007bff;
    color: white;
    border-radius: 50%;
    text-align: center;
    line-height: 30px;
    font-weight: 600;
    font-size: 0.9rem;
}

.student-info, .subject-info {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.student-info strong, .subject-info strong {
    color: #333;
    font-weight: 600;
}

.student-info .term, .subject-info .stats {
    font-size: 0.85rem;
    color: #666;
}

.student-score, .subject-score {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 0 15px;
}

.student-score .score, .subject-score .score {
    font-weight: 600;
    color: #333;
    font-size: 1.1rem;
}

.student-score small, .subject-score small {
    font-size: 0.8rem;
    color: #666;
}

.student-actions, .subject-actions {
    margin-left: 15px;
}

.no-data {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 30px;
    color: #999;
    font-style: italic;
}

.no-data i {
    margin-right: 8px;
    font-size: 1.2rem;
}

.score-distribution {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
}

.distribution-chart {
    margin-bottom: 20px;
    text-align: center;
}

.distribution-bars {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.distribution-bar {
    display: flex;
    align-items: center;
    padding: 8px 15px;
    border-radius: 6px;
    background: #e9ecef;
    border: 1px solid #dee2e6;
}

.bar-label {
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.85rem;
    color: #333;
}

.range-name {
    font-weight: 600;
}

.range-count {
    color: #666;
    font-size: 0.75rem;
}

.bar-container {
    width: 100%;
    height: 10px;
    background: #e9ecef;
    border-radius: 5px;
    margin: 0 10px;
    overflow: hidden;
}

.bar-fill {
    height: 100%;
    border-radius: 5px;
    transition: width 0.3s ease-in-out;
}

.bar-percentage {
    font-size: 0.85rem;
    color: #333;
    font-weight: 600;
    min-width: 50px;
    text-align: right;
}

.term-comparison {
    margin-bottom: 25px;
}

.comparison-chart {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
}

.comparison-bars {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 15px;
}

.comparison-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    border-radius: 8px;
    background: #e9ecef;
    border-left: 4px solid #17a2b8;
    transition: all 0.2s ease;
}

.comparison-item:hover {
    background: #dee2e6;
    transform: translateX(5px);
}

.term-info {
    flex: 1;
    margin-right: 15px;
}

.term-info h6 {
    margin: 0 0 5px 0;
    color: #333;
    font-weight: 600;
}

.term-info .term-stats {
    font-size: 0.85rem;
    color: #666;
}

.term-metrics {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 5px;
}

.metric {
    display: flex;
    align-items: center;
    font-size: 0.85rem;
}

.metric .label {
    color: #666;
    margin-right: 8px;
}

.metric .value {
    font-weight: 600;
    color: #333;
}

.metric .value.success {
    color: #28a745;
}

.term-actions {
    text-align: center;
}

.quick-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
    padding-top: 15px;
    border-top: 1px solid #f0f0f0;
}

@media (max-width: 768px) {
    .academic-metrics {
        grid-template-columns: 1fr;
    }
    
    .term-cards {
        grid-template-columns: 1fr;
    }
    
    .student-item, .subject-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .student-score, .subject-score {
        align-items: flex-start;
        margin: 0;
    }
    
    .quick-actions {
        flex-direction: column;
    }
    
    .distribution-bars {
        flex-direction: column;
    }
    
    .comparison-bars {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Función para obtener color según el rango de calificación
function getScoreColor(scoreRange) {
    const colors = {
        'Excelente (4.5-5.0)': '#28a745',
        'Bueno (3.5-4.4)': '#17a2b8',
        'Aceptable (3.0-3.4)': '#ffc107',
        'Deficiente (2.0-2.9)': '#fd7e14',
        'Insuficiente (0.0-1.9)': '#dc3545'
    };
    return colors[scoreRange] || '#6c757d';
}

// Función para refrescar estadísticas académicas
function refreshAcademicStats() {
    location.reload();
}

// Función para exportar reporte académico
function exportAcademicReport() {
    window.open('<?php echo url; ?>?view=academic&action=export', '_blank');
}

// Función para ver detalles del período
function viewTermDetails(termId) {
    if (termId > 0) {
        loadView('academic/termDetails&id=' + termId);
    }
}

// Función para ver detalles del estudiante
function viewStudentDetails(studentId) {
    if (studentId > 0) {
        loadView('student/view&id=' + studentId);
    }
}

// Función para ver detalles de la asignatura
function viewSubjectDetails(subjectId) {
    if (subjectId > 0) {
        loadView('subject/view&id=' + subjectId);
    }
}

// Función para ver reporte académico completo
function viewFullReport() {
    loadView('academic/report');
}

// Función para ver tendencias de calificaciones
function viewTrends() {
    loadView('academic/trends');
}

// Función para comparar asignaturas
function viewSubjectComparison() {
    loadView('academic/subjectComparison');
}

// Función para ver ranking de estudiantes
function viewStudentRanking() {
    loadView('academic/studentRanking');
}

// Función para ver comparación de períodos
function viewTermComparison(termId) {
    if (termId > 0) {
        loadView('academic/termComparison&id=' + termId);
    }
}

// Función para ver análisis avanzado
function viewAnalytics() {
    loadView('academic/advancedAnalytics');
}

// Gráfico de distribución de calificaciones
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('scoreDistributionChart').getContext('2d');
    
    const distributionData = <?php echo json_encode($scoreDistribution); ?>;
    
    const chart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: distributionData.map(item => item.score_range),
            datasets: [{
                data: distributionData.map(item => item.count),
                backgroundColor: distributionData.map(item => getScoreColor(item.score_range)),
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script> 