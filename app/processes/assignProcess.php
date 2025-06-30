<?php
if (!defined('ROOT')) {
    define('ROOT', realpath(__DIR__ . '/../../'));
}

require_once ROOT . '/app/scripts/connection.php';
require_once ROOT . '/app/models/UserModel.php';

// Debug: mostrar datos recibidos
error_log("DEBUG assignProcess - POST data: " . print_r($_POST, true));
error_log("DEBUG assignProcess - REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);

$dbConn = getConnection();
$model = new UserModel($dbConn);

// Verificar método y subject
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'] ?? '';

    switch ($subject) {
        case 'assign_role':
            // Asignar rol a usuario
            $userId = $_POST['user_id'] ?? null;
            $roleType = $_POST['role_type'] ?? null;

            if (!$userId || !$roleType) {
                error_log("DEBUG assignProcess - Faltan datos para asignar rol");
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Faltan datos requeridos: user_id y role_type'
                ]);
                exit;
            }

            try {
                $ok = $model->assignRole($userId, $roleType);

                if ($ok) {
                    error_log("DEBUG assignProcess - Rol asignado exitosamente");
                    echo json_encode([
                        'status' => 'ok',
                        'msg' => 'Rol asignado exitosamente'
                    ]);
                } else {
                    error_log("DEBUG assignProcess - Error al asignar rol");
                    echo json_encode([
                        'status' => 'error',
                        'msg' => 'Error al asignar el rol'
                    ]);
                }
            } catch (Exception $e) {
                error_log("DEBUG assignProcess - Excepción: " . $e->getMessage());
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Error: ' . $e->getMessage()
                ]);
            }
            break;

        case 'search_users':
            // Buscar usuarios por documento
            $credentialType = $_POST['credential_type'] ?? null;
            $credentialNumber = $_POST['credential_number'] ?? null;

            if (!$credentialType || !$credentialNumber) {
                error_log("DEBUG assignProcess - Faltan datos para búsqueda");
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Faltan datos requeridos: credential_type y credential_number'
                ]);
                exit;
            }

            try {
                $users = $model->searchUsersByDocument($credentialType, $credentialNumber);

                if (!empty($users)) {
                    error_log("DEBUG assignProcess - Usuarios encontrados: " . count($users));
                    echo json_encode([
                        'status' => 'ok',
                        'msg' => 'Usuarios encontrados',
                        'data' => $users
                    ]);
                } else {
                    error_log("DEBUG assignProcess - No se encontraron usuarios");
                    echo json_encode([
                        'status' => 'ok',
                        'msg' => 'No se encontraron usuarios con el documento especificado',
                        'data' => []
                    ]);
                }
            } catch (Exception $e) {
                error_log("DEBUG assignProcess - Excepción en búsqueda: " . $e->getMessage());
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Error al buscar usuarios: ' . $e->getMessage()
                ]);
            }
            break;

        case 'search_users_by_role':
            $roleType = $_POST['role_type'] ?? null;
            $searchType = $_POST['search_type'] ?? null;
            $query = $_POST['query'] ?? null;

            if (!$roleType) {
                error_log("DEBUG assignProcess - Falta role_type para búsqueda por rol");
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Falta dato requerido: role_type'
                ]);
                exit;
            }

            try {
                if ($searchType === 'document' && $query) {
                    $users = $model->searchUsersByRoleAndDocument($roleType, $query);
                } elseif ($roleType === 'no_role') {
                    $users = $model->getUsersWithoutRole();
                } else {
                    $users = $model->getUsersByRole($roleType);
                }

                if (!empty($users)) {
                    error_log("DEBUG assignProcess - Usuarios encontrados por rol: " . count($users));
                    echo json_encode([
                        'status' => 'ok',
                        'msg' => 'Usuarios encontrados con el rol especificado',
                        'data' => $users
                    ]);
                } else {
                    error_log("DEBUG assignProcess - No se encontraron usuarios con ese rol");
                    echo json_encode([
                        'status' => 'ok',
                        'msg' => 'No se encontraron usuarios con el rol especificado',
                        'data' => []
                    ]);
                }
            } catch (Exception $e) {
                error_log("DEBUG assignProcess - Excepción en búsqueda: " . $e->getMessage());
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Error al buscar usuarios: ' . $e->getMessage()
                ]);
            }
            break;

        case 'get_users_without_role':
            // Obtener usuarios sin rol asignado
            try {
                $users = $model->getUsersWithoutRole();

                error_log("DEBUG assignProcess - Usuarios sin rol encontrados: " . count($users));
                echo json_encode([
                    'status' => 'ok',
                    'msg' => 'Usuarios obtenidos',
                    'data' => $users
                ]);
            } catch (Exception $e) {
                error_log("DEBUG assignProcess - Excepción al obtener usuarios sin rol: " . $e->getMessage());
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Error al obtener usuarios: ' . $e->getMessage()
                ]);
            }
            break;

        case 'search_role_history':
            // Buscar historial de roles por documento
            $credentialType = $_POST['credential_type'] ?? null;
            $credentialNumber = $_POST['credential_number'] ?? null;

            if (!$credentialType || !$credentialNumber) {
                error_log("DEBUG assignProcess - Faltan datos para búsqueda de historial");
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Faltan datos requeridos: credential_type y credential_number'
                ]);
                exit;
            }

            try {
                // Primero buscar el usuario por documento
                $users = $model->searchUsersByDocument($credentialType, $credentialNumber);

                if (empty($users)) {
                    error_log("DEBUG assignProcess - No se encontró usuario para historial");
                    echo json_encode([
                        'status' => 'ok',
                        'msg' => 'No se encontró ningún usuario con ese documento',
                        'data' => null,
                        'userInfo' => null
                    ]);
                    exit;
                }

                $userInfo = $users[0];
                $userId = $userInfo['user_id'];

                // Obtener el historial de roles
                $roleHistory = $model->getRoleHistory($userId);

                error_log("DEBUG assignProcess - Historial de roles obtenido para usuario ID: " . $userId);
                echo json_encode([
                    'status' => 'ok',
                    'msg' => 'Historial de roles obtenido',
                    'data' => $roleHistory,
                    'userInfo' => $userInfo
                ]);
            } catch (Exception $e) {
                error_log("DEBUG assignProcess - Excepción en búsqueda de historial: " . $e->getMessage());
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Error al buscar historial de roles: ' . $e->getMessage()
                ]);
            }
            break;

        case 'search_users_by_document':
            $roleType = $_POST['role_type'] ?? null;
            $credentialNumber = $_POST['query'] ?? null;
            if (!$roleType || !$credentialNumber) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Faltan datos requeridos: role_type y query (número de documento)'
                ]);
                exit;
            }
            try {
                $users = $model->searchUsersByRoleAndDocument($roleType, $credentialNumber);
                if (!empty($users)) {
                    echo json_encode([
                        'status' => 'ok',
                        'msg' => 'Usuario encontrado',
                        'data' => $users
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'ok',
                        'msg' => 'No se encontró usuario con ese documento y rol',
                        'data' => []
                    ]);
                }
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Error al buscar usuario: ' . $e->getMessage()
                ]);
            }
            break;

        default:
            error_log("DEBUG assignProcess - Subject inválido: " . $subject);
            echo json_encode([
                'status' => 'error',
                'msg' => 'Subject inválido. Valores permitidos: assign_role, search_users, search_users_by_role, get_users_without_role, search_role_history',
                'debug' => [
                    'received_subject' => $subject,
                    'expected_subjects' => ['assign_role', 'search_users', 'search_users_by_role', 'get_users_without_role', 'search_role_history']
                ]
            ]);
            break;
    }
} else {
    error_log("DEBUG assignProcess - Método no permitido: " . $_SERVER['REQUEST_METHOD']);
    echo json_encode([
        'status' => 'error',
        'msg' => 'Método no permitido. Se requiere POST',
        'debug' => [
            'method' => $_SERVER['REQUEST_METHOD'],
            'expected_method' => 'POST'
        ]
    ]);
}
