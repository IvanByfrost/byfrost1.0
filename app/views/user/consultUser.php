<?php
// Variables iniciales
$message = $message ?? '';
$error = $error ?? '';
$success = $success ?? false;
$users = $users ?? [];
$search = $search ?? '';
$searchType = $searchType ?? '';
$roleType = $roleType ?? '';
$credentialType = $credentialType ?? '';
$credentialNumber = $credentialNumber ?? '';

require_once ROOT . '/app/library/SessionManager.php';
$sessionManager = new SessionManager();
$userRole = $sessionManager->getUserRole();

// Función para renderizar opciones de roles
if (!function_exists('renderRoleOptions')) {
    function renderRoleOptions($userRole, $selected = '') {
        $roles = [
            'student'    => 'Estudiante',
            'parent'     => 'Padre/Acudiente',
            'professor'  => 'Profesor',
            'coordinator'=> 'Coordinador',
            'director'   => 'Director/Rector',
            'treasurer'  => 'Tesorero',
            'root'       => 'Administrador',
            'no_role'    => 'Sin Rol'
        ];

        if ($userRole === 'director') {
            unset($roles['root'], $roles['director'], $roles['no_role']);
        }

        foreach ($roles as $value => $label) {
            $isSelected = ($selected === $value) ? 'selected' : '';
            echo "<option value=\"$value\" $isSelected>$label</option>";
        }
    }
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">

            <!-- Título y botones -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>Consulta de Usuarios</h2>
                    <p>Busca usuarios por documento, rol o nombre.</p>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-info" onclick="loadView('user/consultUser')">
                        <i class="fas fa-list"></i> Ver Todos
                    </button>
                    <button type="button" class="btn btn-success" onclick="loadView('user/assignRole')">
                        <i class="fas fa-plus"></i> Crear Nuevo Usuario
                    </button>
                </div>
            </div>

            <!-- Mensajes -->
            <?php if ($success && !empty($message)) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Formulario de búsqueda -->
            <form id="userSearchForm" class="mb-4" onsubmit="return searchUserAJAX(event)">
                <input type="hidden" name="csrf_token" value="<?= Validator::generateCSRFToken() ?>">
                <input type="hidden" name="view" value="user">
                <input type="hidden" name="action" value="consultUser">

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-search"></i> Buscar Usuario</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">

                            <!-- Tipo de búsqueda -->
                            <div class="col-md-3">
                                <label for="search_type" class="form-label">Tipo de Búsqueda</label>
                                <select class="form-control" id="search_type" name="search_type" onchange="toggleSearchFields()">
                                    <option value="">Seleccionar tipo</option>
                                    <option value="document" <?= $searchType === 'document' ? 'selected' : '' ?>>Por Documento</option>
                                    <option value="role" <?= $searchType === 'role' ? 'selected' : '' ?>>Por Rol</option>
                                    <option value="name" <?= $searchType === 'name' ? 'selected' : '' ?>>Por Nombre</option>
                                </select>
                            </div>

                            <!-- Documento -->
                            <div class="col-md-3" id="document_type_field" style="display: none;">
                                <label for="credential_type" class="form-label">Tipo de Documento</label>
                                <select class="form-control" id="credential_type" name="credential_type">
                                    <option value="">Seleccionar tipo</option>
                                    <option value="CC" <?= $credentialType === 'CC' ? 'selected' : '' ?>>Cédula de Ciudadanía</option>
                                    <option value="TI" <?= $credentialType === 'TI' ? 'selected' : '' ?>>Tarjeta de Identidad</option>
                                    <option value="CE" <?= $credentialType === 'CE' ? 'selected' : '' ?>>Cédula de Extranjería</option>
                                    <option value="PP" <?= $credentialType === 'PP' ? 'selected' : '' ?>>Pasaporte</option>
                                    <option value="RC" <?= $credentialType === 'RC' ? 'selected' : '' ?>>Registro Civil</option>
                                </select>
                            </div>

                            <div class="col-md-3" id="document_number_field" style="display: none;">
                                <label for="credential_number" class="form-label">Número de Documento</label>
                                <input type="text" class="form-control" id="credential_number" name="credential_number"
                                       value="<?= htmlspecialchars($credentialNumber) ?>" placeholder="Ingrese el número de documento">
                            </div>

                            <!-- Rol -->
                            <div class="col-md-3" id="role_type_field" style="display: none;">
                                <label for="role_type" class="form-label">Rol</label>
                                <select class="form-control" id="role_type" name="role_type">
                                    <option value="">Seleccionar rol</option>
                                    <?php renderRoleOptions($userRole, $roleType); ?>
                                </select>
                            </div>

                            <!-- Nombre -->
                            <div class="col-md-3" id="name_search_field" style="display: none;">
                                <label for="name_search" class="form-label">Nombre o Apellido</label>
                                <input type="text" class="form-control" id="name_search" name="name_search"
                                       value="<?= htmlspecialchars($search) ?>" placeholder="Ingrese nombre o apellido">
                            </div>

                            <!-- Botones -->
                            <div class="col-md-3 align-self-end">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-fill">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                    <button type="button" class="btn btn-secondary flex-fill" onclick="loadView('user/consultUser')">
                                        <i class="fas fa-list"></i> Ver Todos
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </form>

            <!-- Resultados -->
            <?php if (!empty($users)) : ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-users"></i> Usuarios Encontrados (<?= count($users) ?>)</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre Completo</th>
                                        <th>Documento</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th>Rol</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user) : ?>
                                        <tr>
                                            <td><?= htmlspecialchars($user['user_id']) ?></td>
                                            <td><strong><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></strong></td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?= htmlspecialchars($user['credential_type'] . ': ' . $user['credential_number']) ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($user['email'] ?? 'No especificado') ?></td>
                                            <td><?= htmlspecialchars($user['phone'] ?? 'No especificado') ?></td>
                                            <td>
                                                <?php if (!empty($user['user_role']) || !empty($user['role_type'])) : ?>
                                                    <span class="badge bg-primary">
                                                        <?= htmlspecialchars(ucfirst($user['user_role'] ?? $user['role_type'])) ?>
                                                    </span>
                                                <?php else : ?>
                                                    <span class="badge bg-secondary">Sin rol</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($user['is_active'])) : ?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php else : ?>
                                                    <span class="badge bg-danger">Inactivo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="loadView('user/view?id=<?= $user['user_id'] ?>')" title="Ver detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-warning" onclick="loadView('user/edit?id=<?= $user['user_id'] ?>')" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-info" onclick="loadView('user/viewRoleHistory?id=<?= $user['user_id'] ?>')" title="Historial de roles">
                                                        <i class="fas fa-history"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="loadView('user/changePassword?id=<?= $user['user_id'] ?>')" title="Cambiar contraseña">
                                                        <i class="fas fa-key"></i>
                                                    </button>
                                                    <?php if (!empty($user['is_active'])) : ?>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDeactivateUser(<?= $user['user_id'] ?>)" title="Desactivar">
                                                            <i class="fas fa-user-slash"></i>
                                                        </button>
                                                    <?php else : ?>
                                                        <button type="button" class="btn btn-sm btn-outline-success" onclick="confirmActivateUser(<?= $user['user_id'] ?>)" title="Activar">
                                                            <i class="fas fa-user-check"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDeleteUser(<?= $user['user_id'] ?>)" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php elseif (!empty($search) || !empty($searchType)) : ?>
                <div class="alert alert-warning">
                    <i class="fas fa-search"></i> No se encontraron usuarios con los criterios especificados.
                </div>
            <?php else : ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No hay usuarios registrados.
                    <button type="button" class="btn btn-link p-0" onclick="loadView('user/assignRole')">Crear el primer usuario</button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="/app/resources/js/dashboard.js"></script>
