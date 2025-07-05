<?php require_once ROOT . '/app/views/layouts/dashHead.php'; ?>
<?php require_once ROOT . '/app/views/student/studentSidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Header del Dashboard -->
        <div class="dashboard-header">
            <h1>Dashboard del Estudiante</h1>
            <p>Bienvenido, <?php echo htmlspecialchars($student['name'] ?? 'Estudiante'); ?></p>
        </div>

        <!-- Tarjetas de Estadísticas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $tasksCount; ?></h3>
                        <p>Tareas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $worksCount; ?></h3>
                        <p>Trabajos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $activitiesCount; ?></h3>
                        <p>Actividades</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $reportsCount; ?></h3>
                        <p>Reportes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="row">
            <!-- Tareas Pendientes -->
            <div class="col-md-6">
                <div class="content-card">
                    <div class="card-header">
                        <h5><i class="fas fa-clock"></i> Tareas Pendientes</h5>
                        <a href="?controller=student&action=listTasks&studentId=<?php echo $_SESSION['user_id']; ?>" class="btn btn-sm btn-primary">Ver Todas</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($pendingTasks)): ?>
                            <div class="task-list">
                                <?php foreach ($pendingTasks as $task): ?>
                                    <div class="task-item">
                                        <div class="task-info">
                                            <h6><?php echo htmlspecialchars($task['title']); ?></h6>
                                            <p><?php echo htmlspecialchars($task['description']); ?></p>
                                            <small class="text-muted">Fecha límite: <?php echo date('d/m/Y', strtotime($task['due_date'])); ?></small>
                                        </div>
                                        <div class="task-actions">
                                            <a href="?controller=student&action=editTask&taskId=<?php echo $task['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No hay tareas pendientes</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Trabajos Recientes -->
            <div class="col-md-6">
                <div class="content-card">
                    <div class="card-header">
                        <h5><i class="fas fa-file-alt"></i> Trabajos Recientes</h5>
                        <a href="?controller=student&action=listWorks&studentId=<?php echo $_SESSION['user_id']; ?>" class="btn btn-sm btn-primary">Ver Todos</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentWorks)): ?>
                            <div class="work-list">
                                <?php foreach ($recentWorks as $work): ?>
                                    <div class="work-item">
                                        <div class="work-info">
                                            <h6><?php echo htmlspecialchars($work['title']); ?></h6>
                                            <p><?php echo htmlspecialchars($work['description']); ?></p>
                                            <small class="text-muted">Fecha: <?php echo date('d/m/Y', strtotime($work['created_at'])); ?></small>
                                        </div>
                                        <div class="work-actions">
                                            <a href="?controller=student&action=editWork&workId=<?php echo $work['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No hay trabajos recientes</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="content-card">
                    <div class="card-header">
                        <h5><i class="fas fa-bolt"></i> Acciones Rápidas</h5>
                    </div>
                    <div class="card-body">
                        <div class="quick-actions">
                            <a href="?controller=student&action=createTask&studentId=<?php echo $_SESSION['user_id']; ?>" class="quick-action-btn">
                                <i class="fas fa-plus"></i>
                                <span>Nueva Tarea</span>
                            </a>
                            <a href="?controller=student&action=createWork&studentId=<?php echo $_SESSION['user_id']; ?>" class="quick-action-btn">
                                <i class="fas fa-file-plus"></i>
                                <span>Nuevo Trabajo</span>
                            </a>
                            <a href="?controller=student&action=createActivity&studentId=<?php echo $_SESSION['user_id']; ?>" class="quick-action-btn">
                                <i class="fas fa-calendar-plus"></i>
                                <span>Nueva Actividad</span>
                            </a>
                            <a href="?controller=student&action=createReport&studentId=<?php echo $_SESSION['user_id']; ?>" class="quick-action-btn">
                                <i class="fas fa-chart-line"></i>
                                <span>Nuevo Reporte</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.main-content {
    margin-left: 250px;
    padding: 20px;
    min-height: 100vh;
    background: #f8f9fa;
}

.dashboard-header {
    background: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.dashboard-header h1 {
    margin: 0;
    color: #333;
    font-size: 24px;
    font-weight: 600;
}

.dashboard-header p {
    margin: 5px 0 0 0;
    color: #666;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    font-size: 24px;
    color: white;
}

.stat-card:nth-child(1) .stat-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-card:nth-child(2) .stat-icon {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.stat-card:nth-child(3) .stat-icon {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.stat-card:nth-child(4) .stat-icon {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.stat-content h3 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    color: #333;
}

.stat-content p {
    margin: 5px 0 0 0;
    color: #666;
    font-size: 14px;
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
    display: flex;
    justify-content: space-between;
    align-items: center;
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

.task-list, .work-list {
    max-height: 300px;
    overflow-y: auto;
}

.task-item, .work-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #eee;
}

.task-item:last-child, .work-item:last-child {
    border-bottom: none;
}

.task-info h6, .work-info h6 {
    margin: 0 0 5px 0;
    color: #333;
    font-weight: 600;
}

.task-info p, .work-info p {
    margin: 0 0 5px 0;
    color: #666;
    font-size: 14px;
}

.quick-actions {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.quick-action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
    background: #f8f9fa;
    border: 2px dashed #ddd;
    border-radius: 10px;
    text-decoration: none;
    color: #666;
    transition: all 0.3s ease;
    min-width: 120px;
}

.quick-action-btn:hover {
    background: #667eea;
    border-color: #667eea;
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
}

.quick-action-btn i {
    font-size: 24px;
    margin-bottom: 8px;
}

.quick-action-btn span {
    font-size: 12px;
    font-weight: 500;
    text-align: center;
}

/* Responsive */
@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
        padding: 10px;
    }
    
    .quick-actions {
        justify-content: center;
    }
    
    .stat-card {
        margin-bottom: 15px;
    }
}
</style>

<?php require_once ROOT . '/app/views/layouts/dashFooter.php'; ?> 