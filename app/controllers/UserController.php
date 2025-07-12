<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../library/HeaderManager.php';
require_once 'MainController.php';

class UserController extends MainController
{
    protected $userModel;

    public function __construct($dbConn)
    {
        parent::__construct($dbConn);
        $this->userModel = new UserModel($dbConn);
    }

    /**
     * Exponer loadPartial de MainController como público,
     * para permitir su uso desde el Router.
     */
    public function loadPartial()
    {
        parent::loadPartial();
    }

    // ------------------------------------------------------------------------
    // Consultas y vistas generales
    // ------------------------------------------------------------------------

    public function consultUser()
    {
        $this->protectRoot();
    
        // Debug: Log de la petición
        error_log("DEBUG UserController::consultUser - isAjaxRequest: " . ($this->isAjaxRequest() ? 'true' : 'false'));
        error_log("DEBUG UserController::consultUser - isDashboardContext: " . ($this->isDashboardContext() ? 'true' : 'false'));
        error_log("DEBUG UserController::consultUser - HTTP_X_REQUESTED_WITH: " . ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? 'not set'));
    
        $searchType = $_GET['search_type'] ?? '';
        $search = $_GET['search'] ?? '';
        $roleType = $_GET['role_type'] ?? '';
        $credentialType = $_GET['credential_type'] ?? '';
        $credentialNumber = $_GET['credential_number'] ?? '';
        $nameSearch = $_GET['name_search'] ?? '';
    
        $users = [];
        $message = '';
        $error = '';
        $success = $_GET['success'] ?? false;  // ✅ Declarado siempre
    
        try {
            if (!empty($searchType)) {
                switch ($searchType) {
                    case 'document':
                        if ($credentialType && $credentialNumber) {
                            $users = $this->userModel->searchUsersByDocument($credentialType, $credentialNumber);
                        }
                        break;
    
                    case 'role':
                        if (!empty($roleType)) {
                            $users = $this->userModel->getUsersByRole($roleType, $this->sessionManager->getUserRole());
                        } else {
                            error_log("[UserController::consultUser] Rol vacío en búsqueda por rol");
                        }
                        break;
    
                    case 'name':
                        if (!empty($nameSearch)) {
                            $users = $this->userModel->searchUsersByName($nameSearch);
                        }
                        break;
    
                    case 'general':
                        if (!empty($search)) {
                            $users = $this->userModel->searchUsersGeneral($search);
                        }
                        break;
                }
            } else {
                $users = $this->userModel->getUsers();
            }
    
            $message = $_GET['msg'] ?? '';
    
        } catch (Exception $e) {
            $error = 'Error al buscar usuarios: ' . $e->getMessage();
            error_log("[UserController::consultUser] " . $e->getMessage());
        }
    
        $viewData = compact(
            'users',
            'search',
            'searchType',
            'roleType',
            'credentialType',
            'credentialNumber',
            'nameSearch',
            'success',
            'message',
            'error'
        );
    
        if ($this->isDashboardContext() || $this->isAjaxRequest()) {
            $this->loadPartialView('user/consultUser', $viewData);
        } else {
            $redirectUrl = url . "?view=root&action=dashboard";
            header("Location: $redirectUrl");
            exit;
        }
    }
    

    public function view()
    {
        $this->protectRoot();

        $userId = $_GET['id'] ?? '';
        if (!$userId) {
            $this->loadPartialView('Error/404');
            return;
        }

        try {
            $user = $this->userModel->getUser($userId);
            if (!$user) {
                $this->loadPartialView('Error/404');
                return;
            }
            $this->loadPartialView('user/viewUser', ['user' => $user]);
        } catch (Exception $e) {
            error_log("[UserController::view] " . $e->getMessage());
            $this->loadPartialView('Error/500');
        }
    }

    public function edit()
    {
        $this->protectRoot();

        $userId = $_GET['id'] ?? '';
        if (!$userId) {
            $this->loadPartialView('Error/404');
            return;
        }

        try {
            $user = $this->userModel->getUser($userId);
            if (!$user) {
                $this->loadPartialView('Error/404');
                return;
            }
            $this->loadPartialView('user/editUser', ['user' => $user]);
        } catch (Exception $e) {
            error_log("[UserController::edit] " . $e->getMessage());
            $this->loadPartialView('Error/500');
        }
    }

    public function deactivate()
    {
        $this->protectRoot();
        $this->loadUserActionView('deactivate');
    }

    public function activate()
    {
        $this->protectRoot();
        $this->loadUserActionView('activate');
    }

    public function changePassword()
    {
        $this->protectRoot();
        $this->loadUserActionView('changePassword');
    }

    private function loadUserActionView($viewName)
    {
        $userId = $_GET['id'] ?? '';
        if (!$userId) {
            $this->loadPartialView('Error/404');
            return;
        }

        try {
            $user = $this->userModel->getUser($userId);
            if (!$user) {
                $this->loadPartialView('Error/404');
                return;
            }
            $this->loadPartialView("user/{$viewName}", ['user' => $user]);
        } catch (Exception $e) {
            error_log("[UserController::{$viewName}] " . $e->getMessage());
            $this->loadPartialView('Error/500');
        }
    }

    // ------------------------------------------------------------------------
    // Role History
    // ------------------------------------------------------------------------

    public function roleHistory()
    {
        $this->protectRoot();

        $roleHistory = [];
        $userInfo = null;
        $searchError = '';

        $credentialType = $_GET['credential_type'] ?? '';
        $credentialNumber = $_GET['credential_number'] ?? '';

        if ($credentialType && $credentialNumber) {
            try {
                $users = $this->userModel->searchUsersByDocument($credentialType, $credentialNumber);
                if (!empty($users)) {
                    $userInfo = $users[0];
                    $userId = $userInfo['user_id'];
                    $roleHistory = $this->userModel->getRoleHistory($userId);
                } else {
                    $searchError = 'No se encontró ningún usuario con ese documento.';
                }
            } catch (Exception $e) {
                $searchError = 'Error al buscar usuario: ' . $e->getMessage();
                error_log("[UserController::roleHistory] " . $e->getMessage());
            }
        }

        $viewData = compact(
            'roleHistory',
            'userInfo',
            'searchError',
            'credentialType',
            'credentialNumber'
        );

        if ($this->isAjaxRequest() || $this->isDashboardContext()) {
            $this->loadPartialView('user/roleHistory', $viewData);
        } else {
            $this->loadView('user/roleHistory', $viewData);
        }
    }

    // ------------------------------------------------------------------------
    // AJAX Endpoints
    // ------------------------------------------------------------------------

    public function getUsersAjax()
    {
        $this->protectRoot();

        try {
            $users = $this->userModel->getUsers();
            $this->sendJsonResponse(true, 'Usuarios obtenidos', ['users' => $users]);
        } catch (Exception $e) {
            $this->sendJsonResponse(false, 'Error al obtener usuarios: ' . $e->getMessage());
        }
    }

    public function createUserAjax()
    {
        $this->protectRoot();

        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $this->sendJsonResponse(false, 'Datos inválidos.');
            return;
        }

        $userData = [
            'first_name' => $data['first_name'] ?? '',
            'last_name' => $data['last_name'] ?? '',
            'email' => $data['username'] ?? '',
            'password' => $data['password'] ?? '',
            'credential_type' => $data['document_type'] ?? '',
            'credential_number' => $data['document_number'] ?? '',
            'date_of_birth' => null,
            'phone' => null,
            'address' => null,
        ];

        try {
            $userId = $this->userModel->createUser($userData);
            if (!empty($data['rol']) && $data['rol'] !== 'student') {
                $this->userModel->assignRole($userId, $data['rol']);
            }
            $this->sendJsonResponse(true, 'Usuario creado correctamente.');
        } catch (Exception $e) {
            $this->sendJsonResponse(false, 'Error al crear usuario: ' . $e->getMessage());
        }
    }

    public function editUserAjax()
    {
        $this->protectRoot();
    
        $data = json_decode(file_get_contents('php://input'), true);
        $userId = $data['user_id'] ?? null;
    
        if (!$userId 
            || empty($data['first_name']) 
            || empty($data['last_name']) 
            || empty($data['email'])
        ) {
            $this->sendJsonResponse(false, 'Faltan datos requeridos.');
            return;
        }
    
        // Construir array de datos
        $updateData = [
            'first_name'         => $data['first_name'],
            'last_name'          => $data['last_name'],
            'email'              => $data['email'],
            'phone'              => $data['phone'] ?? null,
            'date_of_birth'      => $data['date_of_birth'] ?? null,
            'address'            => $data['address'] ?? null,
            'credential_type'    => $data['credential_type'] ?? null,
            'credential_number'  => $data['credential_number'] ?? null,
        ];
    
        try {
            $this->userModel->updateUser($userId, $updateData);
    
            $this->sendJsonResponse(true, 'Usuario editado correctamente.', [
                'redirect' => "?view=user&action=view&id={$userId}&partialView=true"
            ]);
    
        } catch (Exception $e) {
            error_log("[UserController::editUserAjax] " . $e->getMessage());
            $this->sendJsonResponse(false, 'Error al editar usuario: ' . $e->getMessage());
        }
    }
    

    public function deleteUserAjax()
    {
        $this->protectRoot();

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = $data['user_id'] ?? null;

        if (!$userId) {
            $this->sendJsonResponse(false, 'Falta el ID de usuario.');
            return;
        }

        try {
            $this->userModel->deleteUser($userId);
            $this->sendJsonResponse(true, 'Usuario desactivado correctamente.');
        } catch (Exception $e) {
            $this->sendJsonResponse(false, 'Error al desactivar usuario: ' . $e->getMessage());
        }
    }

    public function deleteUserPermanentlyAjax()
    {
        $this->protectRoot();

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = $data['user_id'] ?? null;

        if (!$userId) {
            $this->sendJsonResponse(false, 'Falta el ID de usuario.');
            return;
        }

        try {
            $canDelete = $this->userModel->canDeleteUserPermanently($userId);
            if (!$canDelete['can_delete']) {
                $this->sendJsonResponse(false, $canDelete['reason']);
                return;
            }

            $this->userModel->deleteUserPermanently($userId);
            $this->sendJsonResponse(true, 'Usuario eliminado permanentemente.');
        } catch (Exception $e) {
            $this->sendJsonResponse(false, 'Error al eliminar usuario: ' . $e->getMessage());
        }
    }

    public function checkCanDeleteUserAjax()
    {
        $this->protectRoot();

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = $data['user_id'] ?? null;

        if (!$userId) {
            $this->sendJsonResponse(false, 'Falta el ID de usuario.');
            return;
        }

        try {
            $canDelete = $this->userModel->canDeleteUserPermanently($userId);
            $this->sendJsonResponse($canDelete['can_delete'], $canDelete['reason'], $canDelete);
        } catch (Exception $e) {
            $this->sendJsonResponse(false, 'Error al verificar: ' . $e->getMessage());
        }
    }

    public function changePasswordAjax()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->isAjaxRequest()) {
            $this->sendJsonResponse(false, 'Método no permitido.');
            return;
        }

        if (!isset($this->sessionManager) || !$this->sessionManager->isLoggedIn()) {
            $this->sendJsonResponse(false, 'No has iniciado sesión.');
            return;
        }

        $user = $this->sessionManager->getCurrentUser();
        $userId = $user['user_id'] ?? null;

        if (!$userId) {
            $this->sendJsonResponse(false, 'Usuario no válido.');
            return;
        }

        $currentPassword = $_POST['currentPassword'] ?? '';
        $newPassword = $_POST['newPassword'] ?? '';
        $confirmPassword = $_POST['confirmPassword'] ?? '';

        if (!$currentPassword || !$newPassword || !$confirmPassword) {
            $this->sendJsonResponse(false, 'Todos los campos son obligatorios.');
            return;
        }

        if ($newPassword !== $confirmPassword) {
            $this->sendJsonResponse(false, 'La nueva contraseña y la confirmación no coinciden.');
            return;
        }

        $result = $this->userModel->changePassword($userId, $currentPassword, $newPassword);
        if ($result === true) {
            $this->sendJsonResponse(true, 'Contraseña actualizada correctamente.');
        } else {
            $this->sendJsonResponse(false, $result);
        }
    }

    public function getProfileAjax()
    {
        if (!isset($this->sessionManager) || !$this->sessionManager->isLoggedIn()) {
            $this->sendJsonResponse(false, 'No has iniciado sesión.');
            return;
        }

        $user = $this->sessionManager->getCurrentUser();
        $userId = $user['user_id'] ?? null;

        if (!$userId) {
            $this->sendJsonResponse(false, 'Usuario no válido.');
            return;
        }

        $userData = $this->userModel->getUser($userId);
        if ($userData) {
            $this->sendJsonResponse(true, 'Usuario encontrado.', ['user' => $userData]);
        } else {
            $this->sendJsonResponse(false, 'No se encontraron datos de usuario.');
        }
    }

    // ------------------------------------------------------------------------
    // Protección de acceso
    // ------------------------------------------------------------------------

    private function protectRoot()
    {
        if (!isset($this->sessionManager) || !$this->sessionManager->isLoggedIn()) {
            HeaderManager::redirect('/?view=index&action=login');
            exit;
        }

        if (!$this->sessionManager->hasAnyRole(['root', 'director'])) {
            error_log("[UserController::protectRoot] Acceso denegado.");
            HeaderManager::redirect('/?view=unauthorized');
            exit;
        }
    }

    private function protectSchool()
    {
        if (!isset($this->sessionManager) || !$this->sessionManager->isLoggedIn() ||
            !$this->sessionManager->hasAnyRole(['director', 'coordinator', 'treasurer', 'root'])) {
            if ($this->isAjaxRequest()) {
                $this->sendJsonResponse(false, 'No tienes permisos para realizar esta acción.');
            } else {
                header('Location: /?view=unauthorized');
            }
            exit;
        }
    }

    public function updateUser()
    {
        $this->protectRoot();
    
        // Solo permitir método POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect("?view=user&action=consultUser");
            return;
        }
    
        // Validar token CSRF
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!Validator::validateCSRFToken($csrfToken)) {
            $this->redirect("?view=user&action=consultUser&error=" . urlencode("Token CSRF inválido."));
            return;
        }
    
        $userId = $_POST['user_id'] ?? null;
    
        if (!$userId) {
            $this->redirect("?view=user&action=consultUser&error=" . urlencode("Usuario no especificado."));
            return;
        }
    
        // Validar campos obligatorios
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
    
        if (empty($firstName) || empty($lastName) || empty($email)) {
            $this->redirect("?view=user&action=edit&id={$userId}&error=" . urlencode("Nombre, apellido y correo son obligatorios."));
            return;
        }
    
        // Construir datos
        $data = [
            'first_name'         => $firstName,
            'last_name'          => $lastName,
            'email'              => $email,
            'phone'              => trim($_POST['phone'] ?? ''),
            'date_of_birth'      => trim($_POST['date_of_birth'] ?? ''),
            'address'            => trim($_POST['address'] ?? ''),
            'credential_type'    => trim($_POST['credential_type'] ?? ''),
            'credential_number'  => trim($_POST['credential_number'] ?? ''),
        ];
    
        try {
            // Actualizar en la base de datos
            $this->userModel->updateUser($userId, $data);
    
            $successMsg = urlencode("Usuario actualizado correctamente.");
            $this->redirect("?view=user&action=view&id={$userId}&success=1&message={$successMsg}");
    
        } catch (Exception $e) {
            error_log("[UserController::updateUser] " . $e->getMessage());
            $errorMsg = urlencode("Error al actualizar el usuario.");
            $this->redirect("?view=user&action=edit&id={$userId}&error={$errorMsg}");
        }
    }
    
    public function viewRoleHistory()
{
    $this->protectRoot();

    $userId = $_GET['id'] ?? null;

    if (!$userId) {
        $this->loadPartialView('Error/404');
        return;
    }

    try {
        // Obtener historial de roles desde el modelo
        $roleHistory = $this->userModel->getRoleHistory($userId);

        // Obtener información del usuario
        $user = $this->userModel->getUser($userId);
        
        $this->loadPartialView('user/viewRoleHistory', [
            'userId' => $userId,
            'user' => $user,
            'roleHistory' => $roleHistory
        ]);
    } catch (Exception $e) {
        error_log("[UserController::viewRoleHistory] " . $e->getMessage());
        $this->loadPartialView('Error/500');
    }
}

public function assignRole()
{
    $this->protectRoot();

    $userId = $_POST['user_id'] ?? null;
    $roleType = $_POST['role'] ?? null;

    if (!$userId || !$roleType) {
        $this->loadPartialView('Error/400', [
            'message' => 'Faltan datos para asignar el rol.'
        ]);
        return;
    }

    try {
        $this->userModel->assignRole($userId, $roleType);

        $successMsg = urlencode("Rol asignado correctamente.");
        $this->redirect("?view=user&action=view&id={$userId}&success=1&message={$successMsg}");
    } catch (Exception $e) {
        error_log("[UserController::assignRole] " . $e->getMessage());
        $errorMsg = urlencode("Error al asignar el rol.");
        $this->redirect("?view=user&action=view&id={$userId}&error={$errorMsg}");
    }
}

public function assignRoleAjax()
{
    $this->protectRoot();

    $data = json_decode(file_get_contents('php://input'), true);

    $userId = $data['user_id'] ?? null;
    $roleType = $data['role_type'] ?? null;

    if (!$userId || !$roleType) {
        $this->sendJsonResponse(false, 'Faltan datos obligatorios.');
        return;
    }

    try {
        $this->userModel->assignRole($userId, $roleType);
        $this->sendJsonResponse(true, 'Rol asignado correctamente.');
    } catch (Exception $e) {
        error_log("[UserController::assignRoleAjax] " . $e->getMessage());
        $this->sendJsonResponse(false, 'Error al asignar el rol.');
    }
}

public function assignRoleView()
{
    $this->protectRoot();

    $userId = $_GET['id'] ?? null;

    if (!$userId) {
        $this->loadPartialView('Error/404');
        return;
    }

    try {
        $user = $this->userModel->getUser($userId);

        if (!$user) {
            $this->loadPartialView('Error/404');
            return;
        }

        $this->loadPartialView('user/assignRole', [
            'user' => $user
        ]);

    } catch (Exception $e) {
        error_log("[UserController::assignRoleView] " . $e->getMessage());
        $this->loadPartialView('Error/500');
    }
}

public function updateUserAjax()
{
    $this->protectRoot();

    $data = $_POST;

    $userId = $data['user_id'] ?? null;

    if (!$userId) {
        $this->sendJsonResponse(false, 'Usuario no especificado.');
        return;
    }

    $firstName = trim($data['first_name'] ?? '');
    $lastName = trim($data['last_name'] ?? '');
    $email = trim($data['email'] ?? '');

    if (empty($firstName) || empty($lastName) || empty($email)) {
        $this->sendJsonResponse(false, 'Nombre, apellido y correo son obligatorios.');
        return;
    }

    $updateData = [
        'first_name'        => $firstName,
        'last_name'         => $lastName,
        'email'             => $email,
        'phone'             => trim($data['phone'] ?? ''),
        'date_of_birth'     => trim($data['date_of_birth'] ?? ''),
        'address'           => trim($data['address'] ?? ''),
        'credential_type'   => trim($data['credential_type'] ?? ''),
        'credential_number' => trim($data['credential_number'] ?? ''),
    ];

    try {
        $this->userModel->updateUser($userId, $updateData);
        $this->sendJsonResponse(true, 'Usuario actualizado correctamente.');
    } catch (Exception $e) {
        error_log("[UserController::updateUserAjax] " . $e->getMessage());
        $this->sendJsonResponse(false, 'Error al actualizar el usuario.');
    }
}


}
