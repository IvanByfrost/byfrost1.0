<?php
if (!defined('ROOT')) {
    define('ROOT', realpath(__DIR__ . '/../../'));
}

require_once ROOT . '/app/scripts/connection.php';
require_once ROOT . '/app/models/UserModel.php';
require_once ROOT . '/app/library/SessionManager.php';

// Debug: mostrar datos recibidos
error_log("DEBUG assignProcess - POST data: " . print_r($_POST, true));
error_log("DEBUG assignProcess - REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);

$dbConn = getConnection();
$model = new UserModel($dbConn);

// Inicializar SessionManager para obtener el rol del usuario actual
$sessionManager = new SessionManager();
$currentUserRole = null;

if ($sessionManager->isLoggedIn()) {
    $currentUserRole = $sessionManager->getUserRole();
    error_log("DEBUG assignProcess - Usuario actual logueado con rol: " . $currentUserRole);
} else {
    error_log("DEBUG assignProcess - Usuario no logueado");
}

// Verificar método y subject
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = htmlspecialchars($_POST['subject']) ?? '';

    switch ($subject) {
        case 'assign_role':
            // Asignar rol a usuario
            $userId = htmlspecialchars($_POST['user_id']) ?? null;
            $roleType = htmlspecialchars($_POST['role_type']) ?? null;

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
            $credentialType = htmlspecialchars($_POST['credential_type']) ?? null;
            $credentialNumber = htmlspecialchars($_POST['credential_number']) ?? null;

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
            $roleType = htmlspecialchars($_POST['role_type'] ?? '');
            $searchType = htmlspecialchars($_POST['search_type'] ?? '');
            $query = htmlspecialchars($_POST['query'] ?? '');

            if (!$roleType) {
                error_log("DEBUG assignProcess - Falta role_type para búsqueda por rol");
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Falta dato requerido: role_type'
                ]);
                exit;
            }

            try {
                error_log("DEBUG assignProcess - Iniciando búsqueda por rol: role_type=$roleType, search_type=$searchType, query=$query");
                
                if ($searchType === 'document' && $query) {
                    error_log("DEBUG assignProcess - Buscando usuarios por rol y documento");
                    $users = $model->searchUsersByRoleAndDocument($roleType, $query, $currentUserRole);
                } elseif ($roleType === 'no_role') {
                    error_log("DEBUG assignProcess - Buscando usuarios sin rol");
                    $users = $model->getUsersWithoutRole();
                } else {
                    error_log("DEBUG assignProcess - Buscando usuarios por rol general");
                    $users = $model->getUsersByRole($roleType, $currentUserRole);
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
                error_log("DEBUG assignProcess - Excepción en búsqueda por rol: " . $e->getMessage());
                error_log("DEBUG assignProcess - Stack trace: " . $e->getTraceAsString());
                
                // Determinar el tipo de error para dar una respuesta más específica
                $errorMessage = $e->getMessage();
                if (strpos($errorMessage, 'No tienes permisos') !== false) {
                    $errorMessage = 'No tienes permisos para buscar usuarios con ese rol.';
                } elseif (strpos($errorMessage, 'Error de base de datos') !== false) {
                    $errorMessage = 'Error de conexión con la base de datos.';
                } elseif (strpos($errorMessage, 'Error al preparar') !== false || strpos($errorMessage, 'Error al ejecutar') !== false) {
                    $errorMessage = 'Error interno del servidor.';
                }
                
                echo json_encode([
                    'status' => 'error',
                    'msg' => $errorMessage
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
            $credentialType = htmlspecialchars($_POST['credential_type'] ?? '');
            $credentialNumber = htmlspecialchars($_POST['credential_number'] ?? '');

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
            $roleType = htmlspecialchars($_POST['role_type'] ?? '');
            $credentialNumber = htmlspecialchars($_POST['query'] ?? '');
            if (!$roleType || !$credentialNumber) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Faltan datos requeridos: role_type y query (número de documento)'
                ]);
                exit;
            }
            try {
                $users = $model->searchUsersByRoleAndDocument($roleType, $credentialNumber, $currentUserRole);
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
            error_log("DEBUG assignProcess - Subject no reconocido: " . $subject);
            echo json_encode([
                'status' => 'error',
                'msg' => 'Operación no reconocida'
            ]);
            break;
    }
} else {
    error_log("DEBUG assignProcess - Método no permitido: " . $_SERVER['REQUEST_METHOD']);
    echo json_encode([
        'status' => 'error',
        'msg' => 'Método no permitido'
    ]);
}
