<?php require_once 'app/views/layouts/head.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-tasks me-2"></i>Gestión de Actividades
                    </h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createActivityModal">
                        <i class="fas fa-plus me-2"></i>Nueva Actividad
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="activitiesTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Profesor</th>
                                    <th>Grupo</th>
                                    <th>Puntaje Máx.</th>
                                    <th>Fecha Límite</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($activities)): ?>
                                    <?php foreach ($activities as $activity): ?>
                                        <tr>
                                            <td><?= $activity['activity_id'] ?></td>
                                            <td>
                                                <strong><?= htmlspecialchars($activity['activity_name']) ?></strong>
                                                <?php if (!empty($activity['description'])): ?>
                                                    <br><small class="text-muted"><?= htmlspecialchars(substr($activity['description'], 0, 50)) ?>...</small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-info"><?= htmlspecialchars($activity['activity_type']) ?></span>
                                            </td>
                                            <td><?= htmlspecialchars($activity['first_name'] . ' ' . $activity['last_name']) ?></td>
                                            <td>
                                                <?= htmlspecialchars($activity['grade_name'] . ' ' . $activity['group_name']) ?>
                                                <br><small class="text-muted"><?= htmlspecialchars($activity['school_name']) ?></small>
                                            </td>
                                            <td>
                                                <span class="badge bg-success"><?= $activity['max_score'] ?></span>
                                            </td>
                                            <td>
                                                <?php 
                                                $dueDate = new DateTime($activity['due_date']);
                                                $now = new DateTime();
                                                $isOverdue = $dueDate < $now;
                                                $isToday = $dueDate->format('Y-m-d') === $now->format('Y-m-d');
                                                ?>
                                                <span class="badge <?= $isOverdue ? 'bg-danger' : ($isToday ? 'bg-warning' : 'bg-secondary') ?>">
                                                    <?= $dueDate->format('d/m/Y H:i') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($isOverdue): ?>
                                                    <span class="badge bg-danger">Vencida</span>
                                                <?php elseif ($isToday): ?>
                                                    <span class="badge bg-warning">Hoy</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">Pendiente</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                                            onclick="viewActivity(<?= $activity['activity_id'] ?>)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-warning" 
                                                            onclick="editActivity(<?= $activity['activity_id'] ?>)">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            onclick="deleteActivity(<?= $activity['activity_id'] ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>No hay actividades registradas</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear actividad -->
<div class="modal fade" id="createActivityModal" tabindex="-1" aria-labelledby="createActivityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createActivityModalLabel">
                    <i class="fas fa-plus me-2"></i>Nueva Actividad
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createActivityForm">
    <input type="hidden" name="csrf_token" value='<?= Validator::generateCSRFToken() ?>'>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="activity_name" class="form-label">Nombre de la Actividad *</label>
                                <input type="text" class="form-control" id="activity_name" name="activity_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="activity_type_id" class="form-label">Tipo de Actividad *</label>
                                <select class="form-select" id="activity_type_id" name="activity_type_id" required>
                                    <option value="">Seleccionar tipo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="class_group_id" class="form-label">Grupo *</label>
                                <select class="form-select" id="class_group_id" name="class_group_id" required>
                                    <option value="">Seleccionar grupo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="term_id" class="form-label">Período Académico *</label>
                                <select class="form-select" id="term_id" name="term_id" required>
                                    <option value="">Seleccionar período</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="professor_subject_id" class="form-label">Materia *</label>
                                <select class="form-select" id="professor_subject_id" name="professor_subject_id" required>
                                    <option value="">Seleccionar materia</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_score" class="form-label">Puntaje Máximo *</label>
                                <input type="number" class="form-control" id="max_score" name="max_score" min="0" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="due_date" class="form-label">Fecha Límite *</label>
                                <input type="datetime-local" class="form-control" id="due_date" name="due_date" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Crear Actividad
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para ver actividad -->
<div class="modal fade" id="viewActivityModal" tabindex="-1" aria-labelledby="viewActivityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewActivityModalLabel">
                    <i class="fas fa-eye me-2"></i>Detalles de la Actividad
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewActivityContent">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?> 