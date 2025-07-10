<?php require_once ROOT . '/app/views/layouts/dashHead.php'; ?>
<?php require_once ROOT . '/app/views/student/studentSidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="content-header">
            <h1><?php echo isset($task) ? 'Editar Tarea' : 'Nueva Tarea'; ?></h1>
            <p><?php echo isset($task) ? 'Modifica los datos de tu tarea' : 'Crea una nueva tarea académica'; ?></p>
        </div>

        <div class="content-card">
            <div class="card-body">
                <form method="POST" class="form-horizontal">
    <input type="hidden" name="csrf_token" value='<?= Validator::generateCSRFToken() ?>'>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="title">Título *</label>
                                <input type="text" class="form-control" id="title" name="title" 
                                       value="<?php echo htmlspecialchars($task['title'] ?? ''); ?>" 
                                       required>
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Descripción</label>
                                <textarea class="form-control" id="description" name="description" 
                                          rows="4"><?php echo htmlspecialchars($task['description'] ?? ''); ?></textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="due_date">Fecha Límite *</label>
                                <input type="date" class="form-control" id="due_date" name="due_date" 
                                       value="<?php echo $task['due_date'] ?? ''; ?>" 
                                       required>
                            </div>
                            
                            <div class="form-group">
                                <label for="status">Estado</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="pending" <?php echo ($task['status'] ?? '') === 'pending' ? 'selected' : ''; ?>>Pendiente</option>
                                    <option value="in-progress" <?php echo ($task['status'] ?? '') === 'in-progress' ? 'selected' : ''; ?>>En Progreso</option>
                                    <option value="completed" <?php echo ($task['status'] ?? '') === 'completed' ? 'selected' : ''; ?>>Completada</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <a href="?controller=student&action=listTasks&studentId=<?php echo $_SESSION['user_id']; ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo isset($task) ? 'Actualizar' : 'Crear'; ?>
                        </button>
                    </div>
                </form>
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

.card-body {
    padding: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    font-weight: 600;
    color: #555;
    margin-bottom: 8px;
    display: block;
}

.form-control {
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 10px 12px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

textarea.form-control {
    resize: vertical;
    min-height: 100px;
}

.form-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    padding-top: 20px;
    border-top: 1px solid #eee;
    margin-top: 20px;
}

.btn {
    padding: 10px 20px;
    border-radius: 5px;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-primary:hover {
    background: #5a6fd8;
    color: white;
    text-decoration: none;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
    color: white;
    text-decoration: none;
}

@media (max-width: 768px) {
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        justify-content: center;
    }
}
</style>

<?php require_once ROOT . '/app/views/layouts/dashFooter.php'; ?> 