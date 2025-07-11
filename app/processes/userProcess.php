<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}

require_once ROOT . '/config.php';
require_once ROOT . '/app/library/SessionManager.php';
require_once ROOT . '/app/library/Validator.php';

header('Content-Type: application/json');

$sessionManager = new SessionManager();
if (!$sessionManager->isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['subject'] ?? '') === 'updateUser') {
    require_once ROOT . '/app/scripts/connection.php';
    $dbConn = getConnection();
    require_once ROOT . '/app/models/UserModel.php';

    $userModel = new UserModel($dbConn);

    try {
        $userId = htmlspecialchars($_POST['user_id'] ?? '');
        if (!$userId) {
            throw new Exception('ID de usuario inválido.');
        }

        $firstName = htmlspecialchars($_POST['first_name'] ?? '');
        $lastName = htmlspecialchars($_POST['last_name'] ?? '');
        $email = htmlspecialchars($_POST['email'] ?? '');
        $phone = htmlspecialchars($_POST['phone'] ?? '');
        $address = htmlspecialchars($_POST['address'] ?? '');
        $dateOfBirth = htmlspecialchars($_POST['date_of_birth'] ?? '');
        $credentialType = htmlspecialchars($_POST['credential_type'] ?? '');
        $credentialNumber = htmlspecialchars($_POST['credential_number'] ?? '');

        if (empty($firstName) || empty($lastName) || empty($email)) {
            throw new Exception('Los campos nombre, apellido y email son obligatorios.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('El formato del email no es válido.');
        }

        $updateData = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'date_of_birth' => $dateOfBirth,
            'credential_type' => $credentialType,
            'credential_number' => $credentialNumber
        ];

        $updated = $userModel->updateUser($userId, $updateData);

        if ($updated) {
            echo json_encode([
                'success' => true,
                'message' => 'Usuario actualizado correctamente.'
            ]);
        } else {
            throw new Exception('No se pudo actualizar el usuario.');
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Petición inválida.']);
