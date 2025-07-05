<?php require_once ROOT . '/app/views/layouts/dashHead.php'; ?>
<?php require_once ROOT . '/app/views/student/studentSidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="content-header">
            <h1>Información Académica</h1>
            <p>Historial y datos académicos del estudiante</p>
        </div>

        <div class="content-card">
            <div class="card-header">
                <h5><i class="fas fa-graduation-cap"></i> Historial Académico</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($academic)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Período</th>
                                    <th>Materia</th>
                                    <th>Calificación</th>
                                    <th>Estado</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($academic as $record): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($record['period'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($record['subject'] ?? ''); ?></td>
                                        <td>
                                            <span class="grade-badge <?php echo $this->getGradeClass($record['grade'] ?? 0); ?>">
                                                <?php echo htmlspecialchars($record['grade'] ?? 'N/A'); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-badge <?php echo $record['status'] ?? ''; ?>">
                                                <?php echo ucfirst($record['status'] ?? 'N/A'); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($record['observations'] ?? ''); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-graduation-cap"></i>
                        <h3>No hay información académica</h3>
                        <p>Aún no se ha registrado información académica para este estudiante.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.content-header {
    background: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.content-header h1 {
    margin: 0;
    color: #333;
    font-size: 24px;
    font-weight: 600;
}

.content-header p {
    margin: 5px 0 0 0;
    color: #666;
}

.content-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.card-header {
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
}

.card-header h5 {
    margin: 0;
    color: #333;
    font-weight: 600;
}

.card-header i {
    margin-right: 8px;
    color: #667eea;
}

.card-body {
    padding: 20px;
}

.table {
    margin-bottom: 0;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #555;
    background: #f8f9fa;
}

.table td {
    vertical-align: middle;
}

.grade-badge {
    padding: 4px 8px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 600;
    text-align: center;
    min-width: 40px;
    display: inline-block;
}

.grade-badge.excellent {
    background: #d4edda;
    color: #155724;
}

.grade-badge.good {
    background: #cce5ff;
    color: #004085;
}

.grade-badge.average {
    background: #fff3cd;
    color: #856404;
}

.grade-badge.poor {
    background: #f8d7da;
    color: #721c24;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.approved {
    background: #d4edda;
    color: #155724;
}

.status-badge.pending {
    background: #fff3cd;
    color: #856404;
}

.status-badge.failed {
    background: #f8d7da;
    color: #721c24;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #666;
}

.empty-state i {
    font-size: 48px;
    color: #ddd;
    margin-bottom: 20px;
}

.empty-state h3 {
    margin: 0 0 10px 0;
    color: #333;
}

.empty-state p {
    margin: 0;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 14px;
    }
}
</style>

<?php require_once ROOT . '/app/views/layouts/dashFooter.php'; ?> 