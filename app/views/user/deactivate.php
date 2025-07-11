<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}
require_once ROOT . '/config.php';
require_once ROOT . '/app/library/SessionManager.php';
require_once ROOT . '/app/library/Validator.php';

$sessionManager = new SessionManager();
if (!$sessionManager->isLoggedIn()) {
    header("Location: " . url . "?view=index&action=login");
    exit;
}

$userId = htmlspecialchars($_GET['id'] ?? '');
if (!$userId) {
    header("Location: " . url . "?view=user&action=consultUser");
    exit;
}

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
} catch (Exception $e) {
    error_log("Error cargando usuario: " . $e->getMessage());
    header("Location: " . url . "?view=user&action=consultUser");
    exit;
}

$success = false;
$error = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!Validator::validateCSRFToken($_POST['csrf_token'] ?? '')) {
            throw new Exception('Token de seguridad inválido.');
        }
        
        $success = true;
        $message = 'Usuario desactivado exitosamente. El usuario ya no podrá acceder al sistema.';
        $user['is_active'] = 0;
        
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>Desactivar Usuario</h2>
                    <p>Desactiva un usuario del sistema. El usuario no podrá acceder hasta que sea reactivado.</p>
                </div>
                <div class="btn-group">
                    <button class="btn btn-info" onclick="loadView('user/view?id=<?= $user['user_id']; ?>')">
                        <i class="fas fa-eye"></i> Ver Usuario
                    </button>
                    <button class="btn btn-secondary" onclick="loadView('user/consultUser')">
                        <i class="fas fa-arrow-left"></i> Volver
                    </button>
                </div>
            </div>

            <?php if ($success && !empty($message)): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-slash"></i> Información del Usuario a Desactivar
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nombre:</strong> <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
                            <p><strong>Documento:</strong>
                                <span class="badge bg-info">
                                    <?= htmlspecialchars($user['credential_type'] . ': ' . $user['credential_number']); ?>
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Estado:</strong>
                                <?= $user['is_active'] 
                                    ? '<span class="badge bg-success">Activo</span>'
                                    : '<span class="badge bg-danger">Inactivo</span>'; ?>
                            </p>
                            <p><strong>Rol:</strong>
                                <span class="badge bg-primary"><?= htmlspecialchars(ucfirst($user['role_type'])); ?></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($user['is_active']): ?>
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-exclamation-triangle"></i> Confirmar Desactivación
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-warning"></i> Advertencia</h6>
                            <ul class="mt-2">
                                <li>El usuario no podrá acceder al sistema</li>
                                <li>Se mantendrá toda la información histórica</li>
                                <li>Se puede reactivar en cualquier momento</li>
                                <li>Se registrará la acción en el historial</li>
                            </ul>
                        </div>

                        <form method="POST" id="deactivateForm">
                            <input type="hidden" name="csrf_token" value="<?= Validator::generateCSRFToken(); ?>">
                            
                            <div class="mb-3">
                                <label for="reason" class="form-label">Motivo de Desactivación (Opcional)</label>
                                <textarea class="form-control" id="reason" name="reason" rows="3" 
                                          placeholder="Especifique el motivo de la desactivación..."></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" onclick="loadView('user/view?id=<?= $user['user_id']; ?>')">
                                    <i class="fas fa-times"></i> Cancelar
                                </button>
                                <button type="submit" class="btn btn-danger" onclick="return confirmDeactivation()">
                                    <i class="fas fa-user-slash"></i> Confirmar Desactivación
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Este usuario ya está inactivo. 
                    <a href="?view=user&action=activate&id=<?= $user['user_id']; ?>" class="alert-link">¿Deseas activarlo?</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function confirmDeactivation() {
    return confirm('¿Estás completamente seguro de que deseas desactivar este usuario? Esta acción puede revertirse.');
}

document.getElementById('deactivateForm')?.addEventListener('submit', function(e) {
    const reason = document.getElementById('reason').value.trim();
    if (reason && reason.length < 10) {
        e.preventDefault();
        alert('Si proporcionas un motivo, debe tener al menos 10 caracteres.');
        return false;
    }
    return true;
});
</script>
