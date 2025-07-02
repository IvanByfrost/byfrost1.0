<?php
require_once __DIR__ . '/../../library/SessionManager.php';
$sessionManager = new SessionManager();
$userRole = $sessionManager->getUserRole();
// Verificar si hay mensajes o errores
$error = $error ?? '';
$success = $success ?? '';
$message = $message ?? '';
$users = $users ?? [];
$roles = $roles ?? [];
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2>Asignar Roles por Documento</h2>
            <p>Busca un usuario por documento y asígnale un rol específico.</p>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Formulario de búsqueda -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Buscar Usuario</h5>
                </div>
                <div class="card-body">
                    <form id="searchUserForm" method="POST" action="#" onsubmit="return false;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="credential_type">Tipo de Documento</label>
                                    <select class="form-control" id="credential_type" name="credential_type" required>
                                        <option value="">Seleccionar tipo</option>
                                        <option value="CC">Cédula de Ciudadanía</option>
                                        <option value="TI">Tarjeta de Identidad</option>
                                        <option value="CE">Cédula de Extranjería</option>
                                        <option value="PP">Pasaporte</option>
                                        <option value="RC">Registro Civil</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="credential_number">Número de Documento</label>
                                    <input type="text" class="form-control" id="credential_number" name="credential_number" 
                                           placeholder="Ingrese el número de documento" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Resultados de búsqueda -->
            <div class="card" id="searchResultsCard" style="display: none;">
                <div class="card-header">
                    <h5 class="card-title">Usuarios Encontrados</h5>
                </div>
                <div class="card-body" id="searchResultsContainer">
                    <!-- Se llenará via AJAX -->
                </div>
            </div>

            <!-- Lista de usuarios sin rol -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title">Usuarios Sin Rol Asignado</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="usersWithoutRoleTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre Completo</th>
                                    <th>Documento</th>
                                    <th>Email</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Se llenará via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para asignar rol -->
<div class="modal fade" id="assignRoleModal" tabindex="-1" aria-labelledby="assignRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignRoleModalLabel">Asignar Rol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="assignRoleForm" onsubmit="return false;">
                    <input type="hidden" id="modal_user_id" name="user_id">
                    
                    <div class="mb-3">
                        <label for="modal_user_name" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="modal_user_name" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_current_role" class="form-label">Rol Actual</label>
                        <input type="text" class="form-control" id="modal_current_role" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_role_type" class="form-label">Nuevo Rol *</label>
                        <select class="form-control" id="modal_role_type" name="role_type" required>
                            <option value="">Seleccionar rol</option>
                            <?php if ($userRole === 'director'): ?>
                                <option value="student">Estudiante</option>
                                <option value="parent">Padre/Acudiente</option>
                                <option value="professor">Profesor</option>
                                <option value="coordinator">Coordinador</option>
                                <option value="treasurer">Tesorero</option>
                            <?php elseif ($userRole === 'coordinator'): ?>
                                <option value="student">Estudiante</option>
                            <?php else: ?>
                                <option value="student">Estudiante</option>
                                <option value="parent">Padre/Acudiente</option>
                                <option value="professor">Profesor</option>
                                <option value="coordinator">Coordinador</option>
                                <option value="director">Director/Rector</option>
                                <option value="treasurer">Tesorero</option>
                                <option value="root">Administrador</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="assignRole()">
                    <i class="fas fa-save"></i> Asignar Rol
                </button>
            </div>
        </div>
    </div>
</div>
