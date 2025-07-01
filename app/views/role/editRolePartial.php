<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">
                        <?php if (isset($permissions) && !empty($permissions)): ?>
                            Editar permisos para el rol: <strong><?= htmlspecialchars($permissions['role_type']) ?></strong>
                        <?php else: ?>
                            Seleccionar Rol para Editar Permisos
                        <?php endif; ?>
                    </h2>
                </div>
                <div class="card-body">
                    <?php if (isset($permissions) && !empty($permissions)): ?>
                        <!-- Formulario de edición de permisos -->
                        <form method="post" action="<?= url ?>?controller=role&action=update">
                            <input type="hidden" name="role_type" value="<?= htmlspecialchars($permissions['role_type']) ?>">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Permisos disponibles:</h5>
                                    
                                    <div class="form-check mb-3">
                                        <input type="checkbox" class="form-check-input" id="can_create" name="can_create" 
                                               <?= isset($permissions['can_create']) && $permissions['can_create'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="can_create">
                                            <strong>Crear</strong> - Puede crear nuevos registros
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-3">
                                        <input type="checkbox" class="form-check-input" id="can_read" name="can_read" 
                                               <?= isset($permissions['can_read']) && $permissions['can_read'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="can_read">
                                            <strong>Leer</strong> - Puede ver información
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input type="checkbox" class="form-check-input" id="can_update" name="can_update" 
                                               <?= isset($permissions['can_update']) && $permissions['can_update'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="can_update">
                                            <strong>Actualizar</strong> - Puede modificar registros existentes
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-3">
                                        <input type="checkbox" class="form-check-input" id="can_delete" name="can_delete" 
                                               <?= isset($permissions['can_delete']) && $permissions['can_delete'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="can_delete">
                                            <strong>Eliminar</strong> - Puede eliminar registros
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar Permisos
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="loadView('role/index')">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </button>
                            </div>
                        </form>
                        
                    <?php else: ?>
                        <!-- Selector de rol -->
                        <form method="get" action="">
                            <input type="hidden" name="controller" value="role">
                            <input type="hidden" name="action" value="edit">
                            
                            <div class="mb-3">
                                <label for="role_type" class="form-label">Selecciona el rol que deseas editar:</label>
                                <select name="role_type" id="role_type" class="form-select" required>
                                    <option value="">-- Selecciona un rol --</option>
                                    <option value="student" <?= (isset($_GET['role_type']) && $_GET['role_type'] === 'student') ? 'selected' : '' ?>>Estudiante</option>
                                    <option value="parent" <?= (isset($_GET['role_type']) && $_GET['role_type'] === 'parent') ? 'selected' : '' ?>>Padre/Acudiente</option>
                                    <option value="professor" <?= (isset($_GET['role_type']) && $_GET['role_type'] === 'professor') ? 'selected' : '' ?>>Profesor</option>
                                    <option value="coordinator" <?= (isset($_GET['role_type']) && $_GET['role_type'] === 'coordinator') ? 'selected' : '' ?>>Coordinador</option>
                                    <option value="director" <?= (isset($_GET['role_type']) && $_GET['role_type'] === 'director') ? 'selected' : '' ?>>Director/Rector</option>
                                    <option value="treasurer" <?= (isset($_GET['role_type']) && $_GET['role_type'] === 'treasurer') ? 'selected' : '' ?>>Tesorero</option>
                                    <option value="root" <?= (isset($_GET['role_type']) && $_GET['role_type'] === 'root') ? 'selected' : '' ?>>Administrador</option>
                                </select>
                            </div>
                            
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Cargar Permisos
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="loadView('role/index')">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div> 