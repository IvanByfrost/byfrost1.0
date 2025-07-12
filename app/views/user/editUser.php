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

$success = $_GET['success'] ?? false;
$message = $_GET['message'] ?? '';
$error = $_GET['error'] ?? '';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>Editar Usuario</h2>
                    <p>Modifica la información del usuario seleccionado.</p>
                </div>
                <div class="btn-group" role="group">
                    <button
                        type="button"
                        class="btn btn-primary"
                        onclick="loadView('user', 'view', '#mainContent', true, { id: <?= $user['user_id'] ?> })">
                        <i class="fas fa-eye"></i> Ver usuario
                    </button>

                    <button type="button" class="btn btn-secondary" onclick="loadView('user/consultUser', null, '#mainContent', true)">
                        <i class="fas fa-arrow-left"></i> Volver
                    </button>
                </div>
            </div>

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

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit"></i> Información del Usuario
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" id="editUserForm">
                        <input type="hidden" name="csrf_token" value="<?= Validator::generateCSRFToken() ?>">
                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['user_id']) ?>">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">Nombre *</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                        value="<?= htmlspecialchars($user['first_name']) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Apellido *</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                        value="<?= htmlspecialchars($user['last_name']) ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="<?= htmlspecialchars($user['email']) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                        value="<?= htmlspecialchars($user['phone']) ?>">
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
                                        value="<?= htmlspecialchars($user['credential_number']) ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_of_birth" class="form-label">Fecha de Nacimiento</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                                        value="<?= htmlspecialchars($user['date_of_birth']) ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Dirección</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        value="<?= htmlspecialchars($user['address']) ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Estado Actual</label>
                                    <p class="form-control-plaintext">
                                        <?php if ($user['is_active']) : ?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php else : ?>
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
                                            <?= htmlspecialchars(ucfirst($user['role_type'])) ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                onclick="loadView('user', 'view', '#mainContent', true, { id: <?= $user['user_id'] ?> })">
                                <i class="fas fa-times"></i> Cancelar
                            </button>

                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-warning" onclick="loadView('user/changePassword', 'id=<?= $user['user_id'] ?>', '#mainContent', true)">
                                    <i class="fas fa-key"></i> Cambiar Contraseña
                                </button>
                                <button type="button" class="btn btn-primary" onclick="submitEditUserForm()">
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