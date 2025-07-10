<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)));
}

require_once ROOT . '/app/scripts/connection.php';
require_once ROOT . '/app/controllers/RegisterController.php';
require_once ROOT . '/app/controllers/UserController.php';

$dbConn = getConnection();
$controller = new RegisterController($dbConn);
$userController = new UserController($dbConn);

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset(htmlspecialchars($_POST['subject'])) &&
    htmlspecialchars($_POST['subject']) === 'changePassword'
) {
    $userController->changePasswordAjax();
    exit;
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset(htmlspecialchars($_POST['subject']))) {
    if (htmlspecialchars($_POST['subject']) === 'completeProfile') {
        $controller->completeProfile();
    } else if (htmlspecialchars($_POST['subject']) === 'updateProfile') {
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
        $firstName = htmlspecialchars($_POST['first_name']) ?? '';
        $lastName = htmlspecialchars($_POST['last_name']) ?? '';
        $email = htmlspecialchars($_POST['email']) ?? '';
        $phone = htmlspecialchars($_POST['phone']) ?? '';
        $address = htmlspecialchars($_POST['address']) ?? '';
        $credentialType = htmlspecialchars($_POST['credential_type']) ?? '';
        $credentialNumber = htmlspecialchars($_POST['credential_number']) ?? '';
        
        if (!$firstName || !$lastName || !$email || !$credentialType || !$credentialNumber) {
            echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos.']);
            exit;
        }
        
        require_once ROOT . '/app/models/UserModel.php';
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
        
        // Manejo de foto de perfil
        $profilePhotoName = $user['profile_photo'] ?? null;
        if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
            $fileType = $_FILES['profile_photo']['type'];
            $fileSize = $_FILES['profile_photo']['size'];
            if (!array_key_exists($fileType, $allowedTypes)) {
                echo json_encode(['success' => false, 'message' => 'Tipo de imagen no permitido.']);
                exit;
            }
            if ($fileSize > 2 * 1024 * 1024) { // 2MB
                echo json_encode(['success' => false, 'message' => 'La imagen no debe superar los 2MB.']);
                exit;
            }
            $ext = $allowedTypes[$fileType];
            $newFileName = 'user_' . $userId . '.' . $ext;
            $uploadDir = ROOT . '/app/resources/img/profiles/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $uploadPath = $uploadDir . $newFileName;
            // Eliminar imagen anterior si es diferente
            if ($profilePhotoName && $profilePhotoName !== $newFileName && file_exists($uploadDir . $profilePhotoName)) {
                unlink($uploadDir . $profilePhotoName);
            }
            if (!move_uploaded_file($_FILES['profile_photo']['tmp_name'], $uploadPath)) {
                echo json_encode(['success' => false, 'message' => 'Error al guardar la imagen.']);
                exit;
            }
            $profilePhotoName = $newFileName;
        }
        
        // Actualizar usuario incluyendo los campos de documento y foto
        $ok = $userModel->updateUserWithDocument($userId, [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'date_of_birth' => null,
            'address' => $address,
            'credential_type' => $credentialType,
            'credential_number' => $credentialNumber,
            'profile_photo' => $profilePhotoName
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
