<?php
require_once ROOT . '/app/models/attendanceModel.php';

$attendanceModel = new AttendanceModel($dbConn);
$weeklyStats = $attendanceModel->getWeeklyAttendanceStats();
$subjectStats = $attendanceModel->getAttendanceBySubject();
$lowAttendanceStudents = $attendanceModel->getStudentsWithLowAttendance(5);
?>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-chart-bar"></i> Detalles de Asistencia</h3>
    </div>
    <div class="card-body">
        <!-- Estadísticas por semana -->
        <div class="row mb-4">
            <div class="col-md-8">
                <h5><i class="fas fa-calendar-week"></i> Asistencia de la Última Semana</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Presentes</th>
                                <th>Total</th>
                                <th>Porcentaje</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($weeklyStats as $stat): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($stat['date'])) ?></td>
                                    <td><?= $stat['present_count'] ?></td>
                                    <td><?= $stat['total_students'] ?></td>
                                    <td>
                                        <span class="badge badge-<?= $stat['attendance_percentage'] >= 90 ? 'success' : ($stat['attendance_percentage'] >= 70 ? 'warning' : 'danger') ?>">
                                            <?= $stat['attendance_percentage'] ?>%
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($stat['attendance_percentage'] >= 90): ?>
                                            <i class="fas fa-check text-success"></i> Excelente
                                        <?php elseif ($stat['attendance_percentage'] >= 70): ?>
                                            <i class="fas fa-exclamation text-warning"></i> Buena
                                        <?php else: ?>
                                            <i class="fas fa-times text-danger"></i> Baja
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">
                <h5><i class="fas fa-exclamation-triangle"></i> Estudiantes con Baja Asistencia</h5>
                <div class="list-group">
                    <?php foreach ($lowAttendanceStudents as $student): ?>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0"><?= htmlspecialchars($student['student_name']) ?></h6>
                                    <small class="text-muted">Asistencia del mes</small>
                                </div>
                                <span class="badge badge-danger"><?= $student['attendance_percentage'] ?>%</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Estadísticas por materia -->
        <div class="row">
            <div class="col-12">
                <h5><i class="fas fa-book"></i> Asistencia por Materia (Hoy)</h5>
                <div class="row">
                    <?php foreach ($subjectStats as $subject): ?>
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h6 class="card-title"><?= htmlspecialchars($subject['subject_name']) ?></h6>
                                    <div class="progress mb-2">
                                        <div class="progress-bar bg-<?= $subject['attendance_percentage'] >= 90 ? 'success' : ($subject['attendance_percentage'] >= 70 ? 'warning' : 'danger') ?>" 
                                             style="width: <?= $subject['attendance_percentage'] ?>%">
                                        </div>
                                    </div>
                                    <p class="mb-0">
                                        <strong><?= $subject['attendance_percentage'] ?>%</strong>
                                        <br>
                                        <small class="text-muted">
                                            <?= $subject['present_count'] ?> de <?= $subject['total_students'] ?> estudiantes
                                        </small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.list-group-item {
    border-left: 4px solid #dc3545;
}

.progress {
    height: 8px;
    border-radius: 10px;
}

.badge {
    font-size: 0.8rem;
    padding: 5px 10px;
}

.table th {
    font-size: 0.9rem;
    font-weight: 600;
}

.table td {
    font-size: 0.9rem;
}
</style> 