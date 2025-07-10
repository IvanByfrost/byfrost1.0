<?php
// Widget de estadísticas de asistencia
require_once ROOT . '/app/models/attendanceModel.php';

// Obtener conexión a la base de datos
require_once ROOT . '/app/scripts/connection.php';
$dbConn = getConnection();

$attendanceModel = new AttendanceModel($dbConn);
$stats = $attendanceModel->getTodayAttendanceStats();
?>

<div class="card attendance-widget">
    <div class="card-header">
        <h5><i class="fas fa-calendar-days"></i> Asistencia Promedio</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="attendance-stat">
                    <div class="stat-icon bg-primary">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="stat-content">
                        <h6>Hoy</h6>
                        <h3 class="text-primary"><?= number_format($stats['attendance_today'] * 100, 1) ?>%</h3>
                        <small class="text-muted">
                            <?= $stats['present_today'] ?> de <?= $stats['total_students'] ?> estudiantes
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="attendance-stat">
                    <div class="stat-icon bg-success">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-content">
                        <h6>Este Mes</h6>
                        <h3 class="text-success"><?= number_format($stats['attendance_month'] * 100, 1) ?>%</h3>
                        <small class="text-muted">
                            <?= $stats['present_month'] ?> de <?= $stats['total_students'] ?> estudiantes
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Barra de progreso -->
        <div class="progress mt-3" style="height: 8px;">
            <div class="progress-bar bg-primary" 
                 style="width: <?= $stats['attendance_today'] * 100 ?>%"
                 title="Asistencia del día">
            </div>
        </div>
        
        <!-- Indicadores de estado -->
        <div class="mt-3">
            <?php if ($stats['attendance_today'] >= 0.9): ?>
                <span class="badge badge-success"><i class="fas fa-check"></i> Excelente</span>
            <?php elseif ($stats['attendance_today'] >= 0.7): ?>
                <span class="badge badge-warning"><i class="fas fa-exclamation"></i> Buena</span>
            <?php else: ?>
                <span class="badge badge-danger"><i class="fas fa-times"></i> Baja</span>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.attendance-widget {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-radius: 10px;
}

.attendance-widget .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px 10px 0 0;
    border: none;
}

.attendance-widget .card-header h5 {
    margin: 0;
    font-size: 1.1rem;
}

.attendance-stat {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    color: white;
}

.stat-content h6 {
    margin: 0;
    font-size: 0.9rem;
    color: #6c757d;
}

.stat-content h3 {
    margin: 5px 0;
    font-weight: bold;
}

.stat-content small {
    font-size: 0.8rem;
}

.progress {
    border-radius: 10px;
    background-color: #e9ecef;
}

.progress-bar {
    border-radius: 10px;
    transition: width 0.6s ease;
}

.badge {
    font-size: 0.8rem;
    padding: 5px 10px;
}

@media (max-width: 768px) {
    .attendance-stat {
        margin-bottom: 20px;
    }
    
    .stat-icon {
        width: 40px;
        height: 40px;
        margin-right: 10px;
    }
    
    .stat-content h3 {
        font-size: 1.5rem;
    }
}
</style> 