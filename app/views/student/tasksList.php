<?php require_once ROOT . '/app/views/layouts/dashHead.php'; ?>
<?php require_once ROOT . '/app/views/student/studentSidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="content-header">
            <div class="header-content">
                <div>
                    <h1>Mis Tareas</h1>
                    <p>Gestiona tus tareas académicas</p>
                </div>
                <a href="?controller=student&action=createTask&studentId=<?php echo $_SESSION['user_id']; ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva Tarea
                </a>
            </div>
        </div>

        <div class="content-card">
            <div class="card-body">
                <?php if (!empty($tasks)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Descripción</th>
                                    <th>Fecha Límite</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tasks as $task): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($task['title']); ?></td>
                                        <td><?php echo htmlspecialchars(substr($task['description'], 0, 100)) . (strlen($task['description']) > 100 ? '...' : ''); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($task['due_date'])); ?></td>
                                        <td>
                                            <span class="status-badge <?php echo $task['status']; ?>">
                                                <?php echo ucfirst($task['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="?controller=student&action=editTask&taskId=<?php echo $task['id']; ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="?controller=student&action=deleteTask&taskId=<?php echo $task['id']; ?>" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de que quieres eliminar esta tarea?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-tasks"></i>
                        <h3>No hay tareas</h3>
                        <p>No tienes tareas registradas. Crea una nueva tarea para comenzar.</p>
                        <a href="?controller=student&action=createTask&studentId=<?php echo $_SESSION['user_id']; ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Crear Primera Tarea
                        </a>
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

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-content h1 {
    margin: 0;
    color: #333;
    font-size: 24px;
    font-weight: 600;
}

.header-content p {
    margin: 5px 0 0 0;
    color: #666;
}

.content-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
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

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.pending {
    background: #fff3cd;
    color: #856404;
}

.status-badge.in-progress {
    background: #cce5ff;
    color: #004085;
}

.status-badge.completed {
    background: #d4edda;
    color: #155724;
}

.action-buttons {
    display: flex;
    gap: 5px;
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
    margin: 0 0 20px 0;
}

@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .table-responsive {
        font-size: 14px;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 2px;
    }
}
</style>

<?php require_once ROOT . '/app/views/layouts/dashFooter.php'; ?> 