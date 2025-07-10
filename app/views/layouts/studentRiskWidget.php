<?php
require_once 'app/models/studentRiskModel.php';
$studentRisk = new StudentRiskModel($dbConn);

// Obtener datos de riesgo
$riskStats = $studentRisk->getRiskStatistics();
$gradeRiskStudents = $studentRisk->getStudentsAtRiskByGrades(3.0);
$attendanceRiskStudents = $studentRisk->getStudentsAtRiskByAttendance(3, 1);
$combinedRiskStudents = $studentRisk->getStudentsAtCombinedRisk(3.0, 3);
?>

<div class="student-risk-widget">
    <div class="widget-header">
        <h3><i class="fas fa-exclamation-triangle text-warning"></i> Alertas de Riesgo Académico</h3>
        <div class="widget-actions">
            <button class="btn btn-sm btn-outline-warning" onclick="refreshRiskData()">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>

    <!-- Métricas de riesgo -->
    <div class="risk-metrics">
        <div class="risk-card critical">
            <div class="risk-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="risk-content">
                <h4><?php echo $riskStats['low_grades_count'] ?? 0; ?></h4>
                <p>Bajo Rendimiento</p>
                <small>Promedio < 3.0</small>
            </div>
        </div>

        <div class="risk-card warning">
            <div class="risk-icon">
                <i class="fas fa-calendar-times"></i>
            </div>
            <div class="risk-content">
                <h4><?php echo $riskStats['high_absences_count'] ?? 0; ?></h4>
                <p>Baja Asistencia</p>
                <small>> 3 faltas/mes</small>
            </div>
        </div>

        <div class="risk-card danger">
            <div class="risk-icon">
                <i class="fas fa-percentage"></i>
            </div>
            <div class="risk-content">
                <h4><?php echo $riskStats['risk_percentage'] ?? 0; ?>%</h4>
                <p>En Riesgo</p>
                <small>Total estudiantes</small>
            </div>
        </div>
    </div>

    <!-- Alertas específicas -->
    <div class="risk-alerts">
        <!-- Estudiantes con bajo rendimiento -->
        <div class="alert-section">
            <h5><i class="fas fa-graduation-cap text-danger"></i> Bajo Rendimiento Académico</h5>
            <div class="alert-list">
                <?php if (empty($gradeRiskStudents)): ?>
                    <div class="no-alerts">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>No hay estudiantes con bajo rendimiento</span>
                    </div>
                <?php else: ?>
                    <?php foreach ($gradeRiskStudents as $student): ?>
                    <div class="alert-item grade-risk">
                        <div class="student-info">
                            <strong><?php echo htmlspecialchars($student['student_name']); ?></strong>
                            <span class="email"><?php echo htmlspecialchars($student['email']); ?></span>
                        </div>
                        <div class="risk-details">
                            <span class="badge bg-danger">Promedio: <?php echo $student['average_score']; ?></span>
                            <small><?php echo $student['total_activities']; ?> actividades</small>
                        </div>
                        <div class="alert-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="viewStudentDetails(<?php echo $student['student_id']; ?>)">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-warning" onclick="generateReport(<?php echo $student['student_id']; ?>)">
                                <i class="fas fa-file-alt"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Estudiantes con faltas excesivas -->
        <div class="alert-section">
            <h5><i class="fas fa-calendar-times text-warning"></i> Baja Asistencia</h5>
            <div class="alert-list">
                <?php if (empty($attendanceRiskStudents)): ?>
                    <div class="no-alerts">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>No hay estudiantes con problemas de asistencia</span>
                    </div>
                <?php else: ?>
                    <?php foreach ($attendanceRiskStudents as $student): ?>
                    <div class="alert-item attendance-risk">
                        <div class="student-info">
                            <strong><?php echo htmlspecialchars($student['student_name']); ?></strong>
                            <span class="email"><?php echo htmlspecialchars($student['email']); ?></span>
                        </div>
                        <div class="risk-details">
                            <span class="badge bg-warning">Faltas: <?php echo $student['absences']; ?></span>
                            <small><?php echo $student['tardies']; ?> tardanzas</small>
                        </div>
                        <div class="alert-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="viewStudentDetails(<?php echo $student['student_id']; ?>)">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-warning" onclick="generateReport(<?php echo $student['student_id']; ?>)">
                                <i class="fas fa-file-alt"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Estudiantes con riesgo combinado -->
        <div class="alert-section">
            <h5><i class="fas fa-exclamation-triangle text-danger"></i> Riesgo Combinado</h5>
            <div class="alert-list">
                <?php if (empty($combinedRiskStudents)): ?>
                    <div class="no-alerts">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>No hay estudiantes con riesgo combinado</span>
                    </div>
                <?php else: ?>
                    <?php foreach ($combinedRiskStudents as $student): ?>
                    <div class="alert-item combined-risk">
                        <div class="student-info">
                            <strong><?php echo htmlspecialchars($student['student_name']); ?></strong>
                            <span class="email"><?php echo htmlspecialchars($student['email']); ?></span>
                        </div>
                        <div class="risk-details">
                            <span class="badge bg-danger"><?php echo $student['risk_type']; ?></span>
                            <small>Promedio: <?php echo $student['average_score']; ?> | Faltas: <?php echo $student['absences']; ?></small>
                        </div>
                        <div class="alert-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="viewStudentDetails(<?php echo $student['student_id']; ?>)">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="generateUrgentReport(<?php echo $student['student_id']; ?>)">
                                <i class="fas fa-exclamation"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="quick-actions">
        <button class="btn btn-warning" onclick="exportRiskReport()">
            <i class="fas fa-download"></i> Exportar Reporte
        </button>
        <button class="btn btn-info" onclick="viewRiskTrends()">
            <i class="fas fa-chart-line"></i> Ver Tendencias
        </button>
        <button class="btn btn-success" onclick="generateInterventionPlan()">
            <i class="fas fa-clipboard-list"></i> Plan de Intervención
        </button>
    </div>
</div>

<style>
.student-risk-widget {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 20px;
    border-left: 4px solid #ffc107;
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

.risk-metrics {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 25px;
}

.risk-card {
    display: flex;
    align-items: center;
    padding: 15px;
    border-radius: 8px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    transition: transform 0.2s ease;
}

.risk-card:hover {
    transform: translateY(-2px);
}

.risk-card.critical {
    border-left: 4px solid #dc3545;
}

.risk-card.warning {
    border-left: 4px solid #ffc107;
}

.risk-card.danger {
    border-left: 4px solid #fd7e14;
}

.risk-icon {
    margin-right: 15px;
    font-size: 1.5rem;
    color: #dc3545;
}

.risk-content h4 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
}

.risk-content p {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
}

.risk-content small {
    color: #999;
    font-size: 0.8rem;
}

.risk-alerts {
    margin-bottom: 20px;
}

.alert-section {
    margin-bottom: 20px;
}

.alert-section h5 {
    margin: 0 0 15px 0;
    color: #333;
    font-weight: 600;
    font-size: 1rem;
}

.alert-section h5 i {
    margin-right: 8px;
}

.alert-list {
    max-height: 300px;
    overflow-y: auto;
}

.alert-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 8px;
    background: #f8f9fa;
    border-left: 4px solid #ffc107;
    transition: all 0.2s ease;
}

.alert-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.alert-item.grade-risk {
    border-left-color: #dc3545;
    background: rgba(220, 53, 69, 0.05);
}

.alert-item.attendance-risk {
    border-left-color: #ffc107;
    background: rgba(255, 193, 7, 0.05);
}

.alert-item.combined-risk {
    border-left-color: #fd7e14;
    background: rgba(253, 126, 20, 0.05);
}

.student-info {
    display: flex;
    flex-direction: column;
    flex: 1;
}

.student-info strong {
    color: #333;
    font-weight: 600;
}

.student-info .email {
    font-size: 0.85rem;
    color: #666;
}

.risk-details {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 0 15px;
}

.risk-details small {
    color: #666;
    font-size: 0.8rem;
    margin-top: 2px;
}

.alert-actions {
    display: flex;
    gap: 5px;
}

.no-alerts {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    color: #28a745;
    font-style: italic;
}

.no-alerts i {
    margin-right: 8px;
    font-size: 1.2rem;
}

.quick-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
    padding-top: 15px;
    border-top: 1px solid #f0f0f0;
}

@media (max-width: 768px) {
    .risk-metrics {
        grid-template-columns: 1fr;
    }
    
    .alert-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .risk-details {
        align-items: flex-start;
        margin: 0;
    }
    
    .quick-actions {
        flex-direction: column;
    }
}
</style>

<script>
// Función para refrescar datos de riesgo
function refreshRiskData() {
    location.reload();
}

// Función para ver detalles del estudiante
function viewStudentDetails(studentId) {
    if (studentId > 0) {
        loadView('student/view&id=' + studentId);
    }
}

// Función para generar reporte individual
function generateReport(studentId) {
    if (studentId > 0) {
        window.open('<?php echo url; ?>?view=studentRisk&action=generateReport&id=' + studentId, '_blank');
    }
}

// Función para generar reporte urgente
function generateUrgentReport(studentId) {
    if (studentId > 0) {
        if (confirm('¿Generar reporte urgente para este estudiante?')) {
            window.open('<?php echo url; ?>?view=studentRisk&action=generateUrgentReport&id=' + studentId, '_blank');
        }
    }
}

// Función para exportar reporte general
function exportRiskReport() {
    window.open('<?php echo url; ?>?view=studentRisk&action=exportReport', '_blank');
}

// Función para ver tendencias
function viewRiskTrends() {
    loadView('studentRisk/trends');
}

// Función para generar plan de intervención
function generateInterventionPlan() {
    loadView('studentRisk/intervention');
}
</script> 