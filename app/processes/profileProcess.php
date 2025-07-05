<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)));
}

require_once ROOT . '/app/scripts/connection.php';
require_once ROOT . '/app/controllers/registerController.php';
require_once ROOT . '/app/controllers/UserController.php';

$dbConn = getConnection();
$controller = new RegisterController($dbConn);
$userController = new UserController($dbConn);

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['subject']) &&
    $_POST['subject'] === 'changePassword'
) {
    $userController->changePasswordAjax();
    exit;
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subject'])) {
    if ($_POST['subject'] === 'completeProfile') {
        $controller->completeProfile();
    } else if ($_POST['subject'] === 'updateProfile') {
        // Actualizar perfil del usuario autenticado
        session_start();
        require_once ROOT . '/app/library/SessionManager.php';
        $sessionManager = new SessionManager();
        if (!$sessionManager->isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'No has iniciado sesión.']);
            exit;
        }
        $user = $sessionManager->getCurrentUser();
        $userId = $user['user_id'] ?? null;
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Usuario no válido.']);
            exit;
        }
        $firstName = $_POST['first_name'] ?? '';
        $lastName = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        $credentialType = $_POST['credential_type'] ?? '';
        $credentialNumber = $_POST['credential_number'] ?? '';
        
        if (!$firstName || !$lastName || !$email || !$credentialType || !$credentialNumber) {
            echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos.']);
            exit;
        }
        
        require_once ROOT . '/app/models/userModel.php';
        $userModel = new UserModel($dbConn);
        
        // Verificar si el nuevo número de documento ya existe para otro usuario
        if ($credentialNumber !== $user['credential_number']) {
            $existingUser = $userModel->searchUsersByDocument($credentialType, $credentialNumber);
            if (!empty($existingUser) && $existingUser[0]['user_id'] != $userId) {
                echo json_encode(['success' => false, 'message' => 'Ya existe un usuario con ese número de documento.']);
                exit;
            }
        }
        
        // Verificar si el nuevo email ya existe para otro usuario
        if ($email !== $user['email']) {
            $checkEmailQuery = "SELECT COUNT(*) FROM users WHERE email = :email AND user_id != :user_id";
            $checkEmailStmt = $dbConn->prepare($checkEmailQuery);
            $checkEmailStmt->execute([':email' => $email, ':user_id' => $userId]);
            if ($checkEmailStmt->fetchColumn() > 0) {
                echo json_encode(['success' => false, 'message' => 'Ya existe un usuario con ese email.']);
                exit;
            }
        }
        
        // Actualizar usuario incluyendo los campos de documento
        $ok = $userModel->updateUserWithDocument($userId, [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'date_of_birth' => null,
            'address' => $address,
            'credential_type' => $credentialType,
            'credential_number' => $credentialNumber
        ]);
        
        if ($ok) {
            // Actualizar la sesión con los nuevos datos
            $updatedUser = $userModel->getUser($userId);
            if ($updatedUser) {
                $_SESSION['user'] = $updatedUser;
            }
            echo json_encode(['success' => true, 'message' => 'Perfil actualizado correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar perfil.']);
        }
        exit;
    } else {
        echo json_encode([
            'status' => 'error',
            'msg' => 'Acción no válida'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Método no permitido o datos incompletos'
    ]);
}
