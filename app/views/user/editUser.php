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

// Procesar formulario si se envió
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = false;
    $message = '';
    $error = '';
    
    try {
        // Validar datos del formulario
        $firstName = htmlspecialchars($_POST['first_name'] ?? '');
        $lastName = htmlspecialchars($_POST['last_name'] ?? '');
        $email = htmlspecialchars($_POST['email'] ?? '');
        $phone = htmlspecialchars($_POST['phone'] ?? '');
        $address = htmlspecialchars($_POST['address'] ?? '');
        $dateOfBirth = htmlspecialchars($_POST['date_of_birth'] ?? '');
        $credentialType = htmlspecialchars($_POST['credential_type'] ?? '');
        $credentialNumber = htmlspecialchars($_POST['credential_number'] ?? '');
        
        // Validaciones básicas
        if (empty($firstName) || empty($lastName) || empty($email)) {
            throw new Exception('Los campos nombre, apellido y email son obligatorios.');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('El formato del email no es válido.');
        }
        
        // Aquí normalmente guardarías los datos en la base de datos
        // Por ahora simulamos éxito
        $success = true;
        $message = 'Usuario actualizado exitosamente.';
        
        // Actualizar datos del usuario para mostrar en el formulario
        $user['first_name'] = $firstName;
        $user['last_name'] = $lastName;
        $user['email'] = $email;
        $user['phone'] = $phone;
        $user['address'] = $address;
        $user['date_of_birth'] = $dateOfBirth;
        $user['credential_type'] = $credentialType;
        $user['credential_number'] = $credentialNumber;
        
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
                    <h2>Editar Usuario</h2>
                    <p>Modifica la información del usuario seleccionado.</p>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-info" onclick="loadView('user/view?id=<?php echo $user['user_id']; ?>')">
                        <i class="fas fa-eye"></i> Ver Detalles
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="loadView('user/consultUser')">
                        <i class="fas fa-arrow-left"></i> Volver
                    </button>
                </div>
            </div>

            <!-- Mensajes -->
            <?php if (isset($success) && $success && !empty($message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($error) && !empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Formulario de Edición -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit"></i> Información del Usuario
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" id="editUserForm">
                        <input type="hidden" name="csrf_token" value='<?= Validator::generateCSRFToken() ?>'>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">Nombre *</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Apellido *</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                           value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="<?php echo htmlspecialchars($user['phone']); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="credential_type" class="form-label">Tipo de Documento</label>
                                    <select class="form-control" id="credential_type" name="credential_type">
                                        <option value="CC" <?= $user['credential_type'] === 'CC' ? 'selected' : '' ?>>Cédula de Ciudadanía</option>
                                        <option value="TI" <?= $user['credential_type'] === 'TI' ? 'selected' : '' ?>>Tarjeta de Identidad</option>
                                        <option value="CE" <?= $user['credential_type'] === 'CE' ? 'selected' : '' ?>>Cédula de Extranjería</option>
                                        <option value="PP" <?= $user['credential_type'] === 'PP' ? 'selected' : '' ?>>Pasaporte</option>
                                        <option value="RC" <?= $user['credential_type'] === 'RC' ? 'selected' : '' ?>>Registro Civil</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="credential_number" class="form-label">Número de Documento</label>
                                    <input type="text" class="form-control" id="credential_number" name="credential_number" 
                                           value="<?php echo htmlspecialchars($user['credential_number']); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_of_birth" class="form-label">Fecha de Nacimiento</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                                           value="<?php echo htmlspecialchars($user['date_of_birth']); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Dirección</label>
                                    <input type="text" class="form-control" id="address" name="address" 
                                           value="<?php echo htmlspecialchars($user['address']); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Estado Actual</label>
                                    <p class="form-control-plaintext">
                                        <?php if ($user['is_active']): ?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactivo</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Rol Actual</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge bg-primary">
                                            <?php echo htmlspecialchars(ucfirst($user['role_type'])); ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" onclick="loadView('user/view?id=<?php echo $user['user_id']; ?>')">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-warning" onclick="loadView('user/changePassword?id=<?php echo $user['user_id']; ?>')">
                                    <i class="fas fa-key"></i> Cambiar Contraseña
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validación del formulario
document.getElementById('editUserForm').addEventListener('submit', function(e) {
    const firstName = document.getElementById('first_name').value.trim();
    const lastName = document.getElementById('last_name').value.trim();
    const email = document.getElementById('email').value.trim();
    
    if (!firstName || !lastName || !email) {
        e.preventDefault();
        alert('Por favor, completa todos los campos obligatorios.');
        return false;
    }
    
    if (!email.includes('@')) {
        e.preventDefault();
        alert('Por favor, ingresa un email válido.');
        return false;
    }
    
    return true;
});
</script> 