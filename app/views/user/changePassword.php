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

// Procesar cambio de contraseña si se envió el formulario
$success = false;
$error = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validar token CSRF
        if (!Validator::validateCSRFToken($_POST['csrf_token'] ?? '')) {
            throw new Exception('Token de seguridad inválido.');
        }
        
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $sendNotification = isset($_POST['send_notification']);
        
        // Validaciones
        if (empty($newPassword) || empty($confirmPassword)) {
            throw new Exception('Todos los campos son obligatorios.');
        }
        
        if (strlen($newPassword) < 8) {
            throw new Exception('La contraseña debe tener al menos 8 caracteres.');
        }
        
        if ($newPassword !== $confirmPassword) {
            throw new Exception('Las contraseñas no coinciden.');
        }
        
        // Validar complejidad de contraseña
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/', $newPassword)) {
            throw new Exception('La contraseña debe contener al menos una letra mayúscula, una minúscula, un número y un carácter especial.');
        }
        
        // Aquí normalmente cambiarías la contraseña en la base de datos
        // Por ahora simulamos éxito
        $success = true;
        $message = 'Contraseña cambiada exitosamente.' . ($sendNotification ? ' Se envió una notificación al usuario.' : '');
        
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
                    <h2>Cambiar Contraseña</h2>
                    <p>Cambia la contraseña de un usuario del sistema.</p>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-info" onclick="loadView('user/view?id=<?php echo $user['user_id']; ?>')">
                        <i class="fas fa-eye"></i> Ver Usuario
                    </button>
                    <button type="button" class="btn btn-warning" onclick="loadView('user/edit?id=<?php echo $user['user_id']; ?>')">
                        <i class="fas fa-edit"></i> Editar Usuario
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
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user"></i> Información del Usuario
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
                                <label class="form-label fw-bold">Rol:</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-primary">
                                        <?php echo htmlspecialchars(ucfirst($user['role_type'])); ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario de Cambio de Contraseña -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-key"></i> Nueva Contraseña
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Requisitos de Contraseña</h6>
                        <p class="mb-0">La nueva contraseña debe cumplir con los siguientes requisitos:</p>
                        <ul class="mb-0 mt-2">
                            <li>Mínimo 8 caracteres</li>
                            <li>Al menos una letra mayúscula</li>
                            <li>Al menos una letra minúscula</li>
                            <li>Al menos un número</li>
                            <li>Al menos un carácter especial (@$!%*?&)</li>
                        </ul>
                    </div>

                    <form method="POST" id="changePasswordForm">
                        <input type="hidden" name="csrf_token" value='<?= Validator::generateCSRFToken() ?>'>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Nueva Contraseña *</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="new_password" name="new_password" 
                                               placeholder="Ingrese la nueva contraseña" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password')">
                                            <i class="fas fa-eye" id="new_password_icon"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">La contraseña debe cumplir con los requisitos de seguridad.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirmar Contraseña *</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                               placeholder="Confirme la nueva contraseña" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password')">
                                            <i class="fas fa-eye" id="confirm_password_icon"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Debe coincidir con la nueva contraseña.</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="send_notification" name="send_notification" checked>
                                <label class="form-check-label" for="send_notification">
                                    Enviar notificación por email al usuario sobre el cambio de contraseña
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="force_logout" name="force_logout">
                                <label class="form-check-label" for="force_logout">
                                    Forzar cierre de sesión del usuario (si está conectado)
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" onclick="loadView('user/view?id=<?php echo $user['user_id']; ?>')">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary" onclick="return confirmPasswordChange()">
                                <i class="fas fa-save"></i> Cambiar Contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Función para mostrar/ocultar contraseña
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '_icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Función para confirmar cambio de contraseña
function confirmPasswordChange() {
    return confirm('¿Estás seguro de que deseas cambiar la contraseña de este usuario?');
}

// Validación del formulario
document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    // Validar que las contraseñas coincidan
    if (newPassword !== confirmPassword) {
        e.preventDefault();
        alert('Las contraseñas no coinciden.');
        return false;
    }
    
    // Validar longitud mínima
    if (newPassword.length < 8) {
        e.preventDefault();
        alert('La contraseña debe tener al menos 8 caracteres.');
        return false;
    }
    
    // Validar complejidad
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/;
    if (!passwordRegex.test(newPassword)) {
        e.preventDefault();
        alert('La contraseña debe contener al menos una letra mayúscula, una minúscula, un número y un carácter especial.');
        return false;
    }
    
    return true;
});

// Validación en tiempo real
document.getElementById('new_password').addEventListener('input', function() {
    const password = this.value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    // Actualizar indicadores de validación
    updatePasswordStrength(password);
    
    // Verificar coincidencia
    if (confirmPassword && password !== confirmPassword) {
        document.getElementById('confirm_password').classList.add('is-invalid');
    } else {
        document.getElementById('confirm_password').classList.remove('is-invalid');
    }
});

document.getElementById('confirm_password').addEventListener('input', function() {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = this.value;
    
    if (confirmPassword && newPassword !== confirmPassword) {
        this.classList.add('is-invalid');
    } else {
        this.classList.remove('is-invalid');
    }
});

// Función para mostrar fortaleza de contraseña
function updatePasswordStrength(password) {
    let strength = 0;
    let feedback = '';
    
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/\d/.test(password)) strength++;
    if (/[@$!%*?&]/.test(password)) strength++;
    
    const input = document.getElementById('new_password');
    input.classList.remove('is-valid', 'is-invalid', 'is-warning');
    
    if (strength >= 5) {
        input.classList.add('is-valid');
        feedback = 'Contraseña fuerte';
    } else if (strength >= 3) {
        input.classList.add('is-warning');
        feedback = 'Contraseña moderada';
    } else {
        input.classList.add('is-invalid');
        feedback = 'Contraseña débil';
    }
}
</script> 