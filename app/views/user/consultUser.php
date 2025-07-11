<?php
// Verificar si hay mensajes o errores
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

if (!function_exists('renderRoleOptions')) {
    function renderRoleOptions($userRole, $selected = '') {
        $roles = [
            'student' => 'Estudiante',
            'parent' => 'Padre/Acudiente',
            'professor' => 'Profesor',
            'coordinator' => 'Coordinador',
            'director' => 'Director/Rector',
            'treasurer' => 'Tesorero',
            'root' => 'Administrador',
            'no_role' => 'Sin Rol'
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>Consulta de Usuarios</h2>
                    <p>Busca usuarios por documento, rol o nombre.</p>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-info" onclick="loadView('user/consultUser')">
                        <i class="fas fa-list"></i> Ver Todos
                    </button>
                    <button type="button" class="btn btn-success" onclick="loadView('user/assignRole')">
                        <i class="fas fa-plus"></i> Crear Nuevo Usuario
                    </button>
                </div>
            </div>
            
            <!-- Mensajes -->
            <?php if ($success && !empty($message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($message); ?>
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
            <form id="userSearchForm" class="mb-4" onsubmit="return searchUserAJAX(event);">
                <input type="hidden" name="csrf_token" value='<?= Validator::generateCSRFToken() ?>'>
                <input type="hidden" name="view" value="user">
                <input type="hidden" name="action" value="consultUser">
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-search"></i> Buscar Usuario
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="search_type">Tipo de Búsqueda</label>
                                    <select class="form-control" id="search_type" name="search_type" onchange="toggleSearchFields()">
                                        <option value="">Seleccionar tipo</option>
                                        <option value="document" <?= $searchType === 'document' ? 'selected' : '' ?>>Por Documento</option>
                                        <option value="role" <?= $searchType === 'role' ? 'selected' : '' ?>>Por Rol</option>
                                        <option value="name" <?= $searchType === 'name' ? 'selected' : '' ?>>Por Nombre</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Campos para búsqueda por documento -->
                            <div class="col-md-3" id="document_type_field" style="display: none;">
                                <div class="form-group">
                                    <label for="credential_type">Tipo de Documento</label>
                                    <select class="form-control" id="credential_type" name="credential_type">
                                        <option value="">Seleccionar tipo</option>
                                        <option value="CC" <?= $credentialType === 'CC' ? 'selected' : '' ?>>Cédula de Ciudadanía</option>
                                        <option value="TI" <?= $credentialType === 'TI' ? 'selected' : '' ?>>Tarjeta de Identidad</option>
                                        <option value="CE" <?= $credentialType === 'CE' ? 'selected' : '' ?>>Cédula de Extranjería</option>
                                        <option value="PP" <?= $credentialType === 'PP' ? 'selected' : '' ?>>Pasaporte</option>
                                        <option value="RC" <?= $credentialType === 'RC' ? 'selected' : '' ?>>Registro Civil</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Campo para número de documento -->
                            <div class="col-md-3" id="document_number_field" style="display: none;">
                                <div class="form-group">
                                    <label for="credential_number">Número de Documento</label>
                                    <input type="text" class="form-control" id="credential_number" name="credential_number"
                                           placeholder="Ingrese el número de documento" 
                                           value="<?php echo htmlspecialchars($credentialNumber); ?>">
                                </div>
                            </div>
                            
                            <!-- Campo para búsqueda por rol -->
                            <div class="col-md-3" id="role_type_field" style="display: none;">
                                <div class="form-group">
                                    <label for="role_type">Rol</label>
                                    <select class="form-control" id="role_type" name="role_type">
                                        <option value="">Seleccionar rol</option>
                                        <?php renderRoleOptions($userRole, $roleType); ?>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Campo para búsqueda por nombre -->
                            <div class="col-md-3" id="name_search_field" style="display: none;">
                                <div class="form-group">
                                    <label for="name_search">Nombre o Apellido</label>
                                    <input type="text" class="form-control" id="name_search" name="name_search"
                                           placeholder="Ingrese nombre o apellido" 
                                           value="<?php echo htmlspecialchars($search); ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary flex-fill">
                                            <i class="fas fa-search"></i> Buscar
                                        </button>
                                        <button type="button" class="btn btn-secondary" onclick="loadView('user/consultUser')">
                                            <i class="fas fa-list"></i> Ver Todos
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Resultados -->
            <?php if (!empty($users)): ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-users"></i> 
                            Usuarios Encontrados (<?php echo count($users); ?>)
                        </h5>
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
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?php echo htmlspecialchars($user['credential_type'] . ': ' . $user['credential_number']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars($user['email'] ?? 'No especificado'); ?></td>
                                            <td><?php echo htmlspecialchars($user['phone'] ?? 'No especificado'); ?></td>
                                            <td>
                                                <?php if (!empty($user['user_role']) || !empty($user['role_type'])): ?>
                                                    <span class="badge bg-primary">
                                                        <?php echo htmlspecialchars(ucfirst($user['user_role'] ?? $user['role_type'])); ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Sin rol</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (isset($user['is_active']) && $user['is_active']): ?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactivo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                                            onclick="loadView('user/view?id=<?php echo $user['user_id']; ?>')" 
                                                            title="Ver detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-warning" 
                                                            onclick="loadView('user/edit?id=<?php echo $user['user_id']; ?>')" 
                                                            title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                                            onclick="loadView('user/viewRoleHistory?id=<?php echo $user['user_id']; ?>')" 
                                                            title="Historial de roles">
                                                        <i class="fas fa-history"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                            onclick="loadView('user/changePassword?id=<?php echo $user['user_id']; ?>')" 
                                                            title="Cambiar contraseña">
                                                        <i class="fas fa-key"></i>
                                                    </button>
                                                    <?php if (isset($user['is_active']) && $user['is_active']): ?>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                                onclick="confirmDeactivateUser(<?php echo $user['user_id']; ?>)" 
                                                                title="Desactivar usuario">
                                                            <i class="fas fa-user-slash"></i>
                                                        </button>
                                                    <?php else: ?>
                                                        <button type="button" class="btn btn-sm btn-outline-success" 
                                                                onclick="confirmActivateUser(<?php echo $user['user_id']; ?>)" 
                                                                title="Activar usuario">
                                                            <i class="fas fa-user-check"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            onclick="confirmDeleteUser(<?php echo $user['user_id']; ?>)" 
                                                            title="Eliminar">
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
            <?php elseif (!empty($search) || !empty($searchType)): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-search"></i> 
                    No se encontraron usuarios con los criterios especificados.
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    No hay usuarios registrados. <button type="button" class="btn btn-link p-0" onclick="loadView('user/assignRole')">Crear el primer usuario</button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="/app/resources/js/dashboard.js"></script>
<script>

// Función para búsqueda AJAX
function searchUserAJAX(e) {
    e.preventDefault();
    
    const searchType = document.getElementById('search_type').value;
    let searchData = {};
    
    // Validación específica para cada tipo de búsqueda
    switch(searchType) {
        case 'document':
            const credentialType = document.getElementById('credential_type').value;
            const credentialNumber = document.getElementById('credential_number').value;
            
            if (!credentialType || !credentialNumber) {
                alert('Por favor, selecciona el tipo de documento e ingresa el número.');
                return false;
            }
            
            searchData = {
                search_type: 'document',
                credential_type: credentialType,
                credential_number: credentialNumber
            };
            break;
            
        case 'role':
            const roleType = document.getElementById('role_type').value;
            
            if (!roleType) {
                alert('Por favor, selecciona un rol.');
                return false;
            }
            
            searchData = {
                search_type: 'role',
                role_type: roleType
            };
            break;
            
        case 'name':
            const nameSearch = document.getElementById('name_search').value.trim();
            
            if (!nameSearch) {
                alert('Por favor, ingresa un nombre para buscar.');
                return false;
            }
            
            searchData = {
                search_type: 'name',
                name_search: nameSearch
            };
            break;
            
        default:
            alert('Por favor, selecciona un tipo de búsqueda.');
            return false;
    }
    
    // Construir URL con parámetros
    const params = new URLSearchParams(searchData);
    
    // Usar loadView si está disponible, sino redirigir
    if (typeof loadView === 'function') {
        loadView('user/consultUser?' + params.toString());
    } else {
        const url = `${window.location.origin}${window.location.pathname}?view=user&action=consultUser&${params.toString()}`;
        window.location.href = url;
    }
    
    return false;
}

// Función para confirmar eliminación de usuario
function confirmDeleteUser(userId) {
    if (confirm('¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.')) {
        if (typeof loadView === 'function') {
            loadView('user/delete?id=' + userId);
        } else {
            const url = `${window.location.origin}${window.location.pathname}?view=user&action=delete&id=${userId}`;
            window.location.href = url;
        }
    }
}

// Función para confirmar desactivación de usuario
function confirmDeactivateUser(userId) {
    if (confirm('¿Estás seguro de que deseas desactivar este usuario? El usuario no podrá acceder al sistema.')) {
        loadView('user/deactivate?id=' + userId);
    }
}

// Función para confirmar activación de usuario
function confirmActivateUser(userId) {
    if (confirm('¿Estás seguro de que deseas activar este usuario? El usuario podrá acceder al sistema nuevamente.')) {
        loadView('user/activate?id=' + userId);
    }
}

// Inicializar campos al cargar la página
// Eliminar la función inline toggleSearchFields y su llamada en DOMContentLoaded
</script>