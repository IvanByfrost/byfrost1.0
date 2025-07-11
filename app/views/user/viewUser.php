<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}
require_once ROOT . '/config.php';
require_once ROOT . '/app/library/SessionManager.php';
require_once ROOT . '/app/library/Validator.php';

// Verificar que el usuario esté logueado
$sessionManager = new SessionManager();
if (!$sessionManager->isLoggedIn()) {
    header("Location: " . url . "?view=index&action=login");
    exit;
}

// Obtener datos del usuario desde la base de datos
$userId = htmlspecialchars($_GET['id'] ?? '');
if (!$userId) {
    header("Location: " . url . "?view=user&action=consultUser");
    exit;
}

// Cargar datos del usuario desde el modelo
require_once ROOT . '/app/scripts/connection.php';
$dbConn = getConnection();
require_once ROOT . '/app/models/UserModel.php';
$userModel = new UserModel($dbConn);

try {
    $user = $userModel->getUser($userId);
    if (!$user) {
        header("Location: " . url . "?view=user&action=consultUser");
        exit;
    }
    
    // Obtener historial de roles
    $roleHistory = $userModel->getRoleHistory($userId);
    
} catch (Exception $e) {
    error_log("Error cargando usuario: " . $e->getMessage());
    header("Location: " . url . "?view=user&action=consultUser");
    exit;
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>Detalles del Usuario</h2>
                    <p>Información completa del usuario seleccionado.</p>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-warning" onclick="loadView('user/edit?id=<?php echo $user['user_id']; ?>')">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button type="button" class="btn btn-info" onclick="loadView('user/viewRoleHistory?id=<?php echo $user['user_id']; ?>')">
                        <i class="fas fa-history"></i> Historial de Roles
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="loadView('user/consultUser')">
                        <i class="fas fa-arrow-left"></i> Volver
                    </button>
                </div>
            </div>

            <!-- Información del Usuario -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-user"></i> Información Personal
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Nombre Completo:</label>
                                        <p class="form-control-plaintext"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Email:</label>
                                        <p class="form-control-plaintext"><?php echo htmlspecialchars($user['email']); ?></p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Teléfono:</label>
                                        <p class="form-control-plaintext"><?php echo htmlspecialchars($user['phone']); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Documento:</label>
                                        <p class="form-control-plaintext">
                                            <span class="badge bg-info">
                                                <?php echo htmlspecialchars($user['credential_type'] . ': ' . $user['credential_number']); ?>
                                            </span>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Fecha de Nacimiento:</label>
                                        <p class="form-control-plaintext"><?php echo htmlspecialchars(date('d/m/Y', strtotime($user['date_of_birth']))); ?></p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Dirección:</label>
                                        <p class="form-control-plaintext"><?php echo htmlspecialchars($user['address']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle"></i> Estado y Rol
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Estado:</label>
                                <p>
                                    <?php if ($user['is_active']): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactivo</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Rol Actual:</label>
                                <p>
                                    <span class="badge bg-primary">
                                        <?php echo htmlspecialchars(ucfirst($user['role_type'])); ?>
                                    </span>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Fecha de Registro:</label>
                                <p class="form-control-plaintext"><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($user['created_at']))); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones Rápidas -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-cogs"></i> Acciones
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <?php if ($user['is_active']): ?>
                                    <button type="button" class="btn btn-outline-danger" onclick="confirmDeactivateUser(<?php echo $user['user_id']; ?>)">
                                        <i class="fas fa-user-slash"></i> Inactivar Usuario
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-outline-success" onclick="confirmActivateUser(<?php echo $user['user_id']; ?>)">
                                        <i class="fas fa-user-check"></i> Activar Usuario
                                    </button>
                                <?php endif; ?>
                                <button type="button" class="btn btn-outline-warning" onclick="loadView('user/changePassword?id=<?php echo $user['user_id']; ?>')">
                                    <i class="fas fa-key"></i> Cambiar Contraseña
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historial de Roles -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history"></i> Historial de Roles
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Rol</th>
                                    <th>Estado</th>
                                    <th>Fecha de Asignación</th>
                                    <th>Fecha de Desactivación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($roleHistory as $role): ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">
                                                <?php echo htmlspecialchars(ucfirst($role['role_type'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($role['is_active']): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($role['created_at']))); ?></td>
                                        <td>
                                            <?php if (!$role['is_active']): ?>
                                                <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($role['updated_at'] ?? $role['created_at']))); ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Función para confirmar desactivación de usuario
function confirmDeactivateUser(userId) {
    if (confirm('¿Estás seguro de que deseas inactivar este usuario? El usuario no podrá acceder al sistema.')) {
        // Aquí implementarías la lógica para desactivar el usuario
        loadView('user/deactivate?id=' + userId);
    }
}

// Función para confirmar activación de usuario
function confirmActivateUser(userId) {
    if (confirm('¿Estás seguro de que deseas activar este usuario? El usuario podrá acceder al sistema nuevamente.')) {
        // Aquí implementarías la lógica para activar el usuario
        loadView('user/activate?id=' + userId);
    }
}
</script> 