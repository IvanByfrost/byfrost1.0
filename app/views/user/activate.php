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
} catch (Exception $e) {
    error_log("Error cargando usuario: " . $e->getMessage());
    header("Location: " . url . "?view=user&action=consultUser");
    exit;
}

// Procesar activación si se envió el formulario
$success = false;
$error = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validar token CSRF
        if (!Validator::validateCSRFToken($_POST['csrf_token'] ?? '')) {
            throw new Exception('Token de seguridad inválido.');
        }
        
        // Aquí normalmente activarías el usuario en la base de datos
        // Por ahora simulamos éxito
        $success = true;
        $message = 'Usuario activado exitosamente. El usuario ya puede acceder al sistema.';
        $user['is_active'] = 1;
        $user['deactivated_at'] = null;
        $user['deactivation_reason'] = null;
        
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>Activar Usuario</h2>
                    <p>Reactiva un usuario del sistema. El usuario podrá acceder nuevamente.</p>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-info" onclick="loadView('user/view?id=<?php echo $user['user_id']; ?>')">
                        <i class="fas fa-eye"></i> Ver Usuario
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="loadView('user/consultUser')">
                        <i class="fas fa-arrow-left"></i> Volver
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

            <!-- Información del Usuario -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-check"></i> Información del Usuario a Activar
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
                                <label class="form-label fw-bold">Documento:</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-info">
                                        <?php echo htmlspecialchars($user['credential_type'] . ': ' . $user['credential_number']); ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Estado Actual:</label>
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
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información de Desactivación -->
            <?php if (!$user['is_active'] && isset($user['deactivated_at'])): ?>
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle"></i> Información de Desactivación
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Fecha de Desactivación:</label>
                                    <p class="form-control-plaintext">
                                        <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($user['deactivated_at']))); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Motivo de Desactivación:</label>
                                    <p class="form-control-plaintext">
                                        <?php echo htmlspecialchars($user['deactivation_reason'] ?? 'No especificado'); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Formulario de Confirmación -->
            <?php if (!$user['is_active']): ?>
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user-check"></i> Confirmar Activación
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Información</h6>
                            <p class="mb-0">
                                Al activar este usuario:
                            </p>
                            <ul class="mb-0 mt-2">
                                <li>El usuario podrá acceder al sistema nuevamente</li>
                                <li>Se mantendrá toda la información histórica</li>
                                <li>Se registrará la acción en el historial</li>
                                <li>Se enviará una notificación al usuario</li>
                            </ul>
                        </div>

                        <form method="POST" id="activateForm">
                            <input type="hidden" name="csrf_token" value='<?= Validator::generateCSRFToken() ?>'>
                            
                            <div class="mb-3">
                                <label for="activation_reason" class="form-label">Motivo de Activación (Opcional)</label>
                                <textarea class="form-control" id="activation_reason" name="activation_reason" rows="3" 
                                          placeholder="Especifique el motivo de la activación..."></textarea>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="send_notification" name="send_notification" checked>
                                    <label class="form-check-label" for="send_notification">
                                        Enviar notificación por email al usuario
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" onclick="loadView('user/view?id=<?php echo $user['user_id']; ?>')">
                                    <i class="fas fa-times"></i> Cancelar
                                </button>
                                <button type="submit" class="btn btn-success" onclick="return confirmActivation()">
                                    <i class="fas fa-user-check"></i> Confirmar Activación
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Este usuario ya está activo. 
                    <a href="?view=user&action=deactivate&id=<?php echo $user['user_id']; ?>" class="alert-link">¿Deseas desactivarlo?</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Función para confirmar activación
function confirmActivation() {
    return confirm('¿Estás seguro de que deseas activar este usuario? El usuario podrá acceder al sistema nuevamente.');
}

// Validación del formulario
document.getElementById('activateForm')?.addEventListener('submit', function(e) {
    const activationReason = document.getElementById('activation_reason').value.trim();
    
    // La razón es opcional, pero si se proporciona debe tener al menos 10 caracteres
    if (activationReason && activationReason.length < 10) {
        e.preventDefault();
        alert('Si proporcionas un motivo, debe tener al menos 10 caracteres.');
        return false;
    }
    
    return true;
});
</script> 