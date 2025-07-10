<?php require_once 'app/views/layouts/head.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-plus me-2"></i>Crear Nueva Actividad
                    </h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($missingData)): ?>
                        <div class="alert alert-warning" role="alert">
                            <h5 class="alert-heading">
                                <i class="fas fa-exclamation-triangle me-2"></i>Datos faltantes
                            </h5>
                            <p>Para crear actividades, necesitas tener configurados los siguientes elementos:</p>
                            <ul class="mb-0">
                                <?php foreach ($missingData as $data): ?>
                                    <li><strong><?= ucfirst($data) ?></strong></li>
                                <?php endforeach; ?>
                            </ul>
                            <hr>
                            <p class="mb-0">
                                <small>Contacta al administrador del sistema para configurar estos datos.</small>
                            </p>
                        </div>
                    <?php endif; ?>
                    
                    <form id="createActivityForm" method="POST" action="activity/createActivity">
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
                                        <?php foreach ($activityTypes as $type): ?>
                                            <option value="<?= $type['activity_type_id'] ?>"><?= htmlspecialchars($type['type_name']) ?></option>
                                        <?php endforeach; ?>
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
                                        <?php foreach ($classGroups as $group): ?>
                                            <option value="<?= $group['class_group_id'] ?>">
                                                <?= htmlspecialchars($group['grade_name'] . ' ' . $group['group_name'] . ' - ' . $group['school_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="term_id" class="form-label">Período Académico *</label>
                                    <select class="form-select" id="term_id" name="term_id" required>
                                        <option value="">Seleccionar período</option>
                                        <?php foreach ($academicTerms as $term): ?>
                                            <option value="<?= $term['term_id'] ?>">
                                                <?= htmlspecialchars($term['term_name'] . ' (' . $term['school_year'] . ')') ?>
                                            </option>
                                        <?php endforeach; ?>
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
                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Describe los detalles de la actividad..."></textarea>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="activity/showDashboard" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Volver
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Crear Actividad
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?> 