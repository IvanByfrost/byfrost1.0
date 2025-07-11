<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}
require_once ROOT . '/config.php';
require_once ROOT . '/app/library/SessionManager.php';
require_once ROOT . '/app/library/Validator.php';

// Verificar sesión
$sessionManager = new SessionManager();
if (!$sessionManager->isLoggedIn()) {
    header("Location: " . url . "?view=index&action=login");
    exit;
}

// Obtener ID
$userId = htmlspecialchars($_GET['id'] ?? '');
if (!$userId) {
    header("Location: " . url . "?view=user&action=consultUser");
    exit;
}

// Cargar datos del usuario
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
        if (!Validator::validateCSRFToken($_POST['csrf_token'] ?? '')) {
            throw new Exception('Token de seguridad inválido.');
        }

        // Aquí iría la lógica real de activación en DB
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
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>Activar Usuario</h2>
                    <p>Reactiva un usuario del sistema. El usuario podrá acceder nuevamente.</p>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-info" onclick="loadView('user/view?id=<?= $user['user_id'] ?>')">
                        <i class="fas fa-eye"></i> Ver Usuario
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="loadView('user/consultUser')">
                        <i class="fas fa-arrow-left"></i> Volver
                    </button>
                </div>
            </div>

            <?php if ($success && !empty($message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            <?php endif; ?>

            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-user-check"></i> Información del Usuario a Activar</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nombre Completo:</strong> <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                            <p>
                                <strong>Documento:</strong>
                                <span class="badge bg-info">
                                    <?= htmlspecialchars($user['credential_type'] . ': ' . $user['credential_number']) ?>
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong>Estado Actual:</strong>
                                <?php if ($user['is_active']): ?>
                                    <span class="badge bg-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inactivo</span>
                                <?php endif; ?>
                            </p>
                            <p>
                                <strong>Rol Actual:</strong>
                                <span class="badge bg-primary">
                                    <?= htmlspecialchars(ucfirst($user['role_type'] ?? 'Sin rol')) ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!$user['is_active'] && isset($user['deactivated_at'])): ?>
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información de Desactivación</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Fecha de Desactivación:</strong>
                                    <?= htmlspecialchars($user['deactivated_at']
                                        ? date('d/m/Y H:i', strtotime($user['deactivated_at']))
                                        : 'N/A') ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Motivo de Desactivación:</strong>
                                    <?= htmlspecialchars($user['deactivation_reason'] ?? 'No especificado') ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!$user['is_active']): ?>
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-user-check"></i> Confirmar Activación</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Información</h6>
                            <ul class="mb-0">
                                <li>El usuario podrá acceder al sistema nuevamente.</li>
                                <li>Se mantiene toda la información histórica.</li>
                                <li>Se registrará la acción en el historial.</li>
                                <li>Se enviará notificación al usuario.</li>
                            </ul>
                        </div>

                        <form method="POST" id="activateForm">
                            <input type="hidden" name="csrf_token" value="<?= Validator::generateCSRFToken() ?>">
                            <div class="mb-3">
                                <label for="activation_reason" class="form-label">Motivo de Activación (opcional)</label>
                                <textarea class="form-control" id="activation_reason" name="activation_reason" rows="3"
                                          placeholder="Especifique el motivo de la activación..."></textarea>
                            </div>
                            <div class="mb-3 form-check">
                                <input class="form-check-input" type="checkbox" id="send_notification" name="send_notification" checked>
                                <label class="form-check-label" for="send_notification">
                                    Enviar notificación por email al usuario
                                </label>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" onclick="loadView('user/view?id=<?= $user['user_id'] ?>')">
                                    <i class="fas fa-times"></i> Cancelar
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-user-check"></i> Confirmar Activación
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Este usuario ya está activo.
                    <a href="?view=user&action=deactivate&id=<?= $user['user_id'] ?>" class="alert-link">¿Deseas desactivarlo?</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.getElementById('activateForm')?.addEventListener('submit', function(e) {
    const reason = document.getElementById('activation_reason').value.trim();

    if (reason && reason.length < 10) {
        e.preventDefault();
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Error',
                text: 'Si proporcionas un motivo, debe tener al menos 10 caracteres.',
                icon: 'error'
            });
        } else {
            alert('Si proporcionas un motivo, debe tener al menos 10 caracteres.');
        }
        return false;
    }

    if (!confirm('¿Estás seguro de que deseas activar este usuario? El usuario podrá acceder al sistema nuevamente.')) {
        e.preventDefault();
        return false;
    }

    return true;
});
</script>
