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
     * Muestra la vista de consulta de usuarios
     */
    public function consultUser()
    {
        $this->protectRoot();
        
        // Obtener parámetros de búsqueda
        $searchType = htmlspecialchars($_GET['search_type'] ?? '');
        $search = htmlspecialchars($_GET['search'] ?? '');
        $roleType = htmlspecialchars($_GET['role_type'] ?? '');
        $credentialType = htmlspecialchars($_GET['credential_type'] ?? '');
        $credentialNumber = htmlspecialchars($_GET['credential_number'] ?? '');
        $nameSearch = htmlspecialchars($_GET['name_search'] ?? '');
        
        $users = [];
        $message = '';
        $error = '';
        
        try {
            // Si hay criterios de búsqueda específicos
            if (!empty($searchType)) {
                switch ($searchType) {
                    case 'document':
                        if (!empty($credentialType) && !empty($credentialNumber)) {
                            $users = $this->userModel->searchUsersByDocument($credentialType, $credentialNumber);
                        }
                        break;
                        
                    case 'role':
                        if (!empty($roleType)) {
                            $users = $this->userModel->getUsersByRole($roleType, $this->sessionManager->getUserRole());
                        }
                        break;
                        
                    case 'name':
                        if (!empty($nameSearch)) {
                            // Buscar por nombre usando el método existente o crear uno nuevo
                            $users = $this->userModel->searchUsersByName($nameSearch);
                        }
                        break;
                        
                    case 'general':
                        if (!empty($search)) {
                            // Búsqueda general en todos los campos
                            $users = $this->userModel->searchUsersGeneral($search);
                        }
                        break;
                }
            } else {
                // Si no hay criterios específicos, obtener todos los usuarios
                $users = $this->userModel->getUsers();
            }
            
            // Verificar si hay mensaje de éxito
            $success = htmlspecialchars($_GET['success'] ?? false);
            $message = htmlspecialchars($_GET['msg'] ?? '');
            
        } catch (Exception $e) {
            $error = 'Error al buscar usuarios: ' . $e->getMessage();
            error_log("UserController::consultUser - Error: " . $e->getMessage());
        }
        
        // Verificar si estamos en un contexto de dashboard
        $isDashboardContext = $this->isDashboardContext();
        
        // Si es una petición AJAX, usar loadPartialView
        if ($this->isAjaxRequest()) {
            $this->loadPartialView('user/consultUser', [
                'users' => $users,
                'search' => $search,
                'searchType' => $searchType,
                'roleType' => $roleType,
                'credentialType' => $credentialType,
                'credentialNumber' => $credentialNumber,
                'nameSearch' => $nameSearch,
                'success' => $success,
                'message' => $message,
                'error' => $error
            ]);
        } elseif ($isDashboardContext) {
            // Si estamos en dashboard, usar loadPartialView
            $this->loadPartialView('user/consultUser', [
                'users' => $users,
                'search' => $search,
                'searchType' => $searchType,
                'roleType' => $roleType,
                'credentialType' => $credentialType,
                'credentialNumber' => $credentialNumber,
                'nameSearch' => $nameSearch,
                'success' => $success,
                'message' => $message,
                'error' => $error
            ]);
        } else {
            // Si no estamos en dashboard, redirigir al dashboard con los parámetros
            $params = [];
            if ($search) $params[] = "search=" . urlencode($search);
            if ($searchType) $params[] = "search_type=$searchType";
            if ($roleType) $params[] = "role_type=$roleType";
            if ($credentialType) $params[] = "credential_type=$credentialType";
            if ($credentialNumber) $params[] = "credential_number=$credentialNumber";
            if ($nameSearch) $params[] = "name_search=" . urlencode($nameSearch);
            
            $paramString = !empty($params) ? '&' . implode('&', $params) : '';
            $redirectUrl = url . "?view=root&action=dashboard" . $paramString;
            
            header("Location: $redirectUrl");
            exit;
        }
    }

    /**
     * Muestra la vista de asignación de roles
     */
    public function assignRole()
    {
        $this->protectRoot();
        
        $users = [];
        $message = '';
        $error = '';
        
        // Si se envió una búsqueda (GET o POST)
        $credentialType = htmlspecialchars($_GET['credential_type'] ?? '') ?: htmlspecialchars($_POST['credential_type'] ?? '');
        $credentialNumber = htmlspecialchars($_GET['credential_number'] ?? '') ?: htmlspecialchars($_POST['credential_number'] ?? '');
        
        if ($credentialType && $credentialNumber && !empty($credentialNumber)) {
            try {
                $users = $this->userModel->searchUsersByDocument($credentialType, $credentialNumber);
                
                if (empty($users)) {
                    $message = "No se encontraron usuarios con el documento especificado.";
                }
            } catch (Exception $e) {
                $error = "Error al buscar usuarios: " . $e->getMessage();
            }
        }
        
        // Si es una petición AJAX POST, devolver solo la sección de resultados
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->isAjaxRequest()) {
            // Renderizar solo la sección de resultados
            $this->renderPartial('user', 'assignRoleResults', [
                'users' => $users,
                'message' => $message,
                'error' => $error
            ]);
            return;
        }
        
        // Para peticiones normales, cargar la vista completa
        $this->loadPartialView('user/assignRole', [
            'users' => $users,
            'message' => $message,
            'error' => $error
        ]);
    }

    /**
     * Procesa la asignación de rol via AJAX
     */
    public function processAssignRole()
    {
        $this->protectRoot();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendJsonResponse(false, 'Método no permitido');
            return;
        }
        
        $userId = htmlspecialchars($_POST['user_id']) ?? null;
        $roleType = htmlspecialchars($_POST['role_type']) ?? null;
        
        if (!$userId || !$roleType) {
            $this->sendJsonResponse(false, 'Faltan datos requeridos');
            return;
        }
        
        try {
            $result = $this->userModel->assignRole($userId, $roleType);
            
            if ($result) {
                $this->sendJsonResponse(true, 'Rol asignado exitosamente');
            } else {
                $this->sendJsonResponse(false, 'Error al asignar el rol');
            }
        } catch (Exception $e) {
            $this->sendJsonResponse(false, 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene usuarios sin rol asignado via AJAX
     */
    public function getUsersWithoutRole()
    {
        $this->protectRoot();
        
        try {
            $users = $this->userModel->getUsersWithoutRole();
            $this->sendJsonResponse(true, 'Usuarios obtenidos', $users);
        } catch (Exception $e) {
            $this->sendJsonResponse(false, 'Error al obtener usuarios: ' . $e->getMessage());
        }
    }

    /**
     * Protección de acceso para root y director
     */
    private function protectRoot() {
        // Verificar si SessionManager está disponible
        if (!isset($this->sessionManager)) {
            error_log("UserController::protectRoot - SessionManager no disponible");
            if ($this->isAjaxRequest()) {
                $this->sendJsonResponse(false, 'Error de sesión: SessionManager no disponible');
            } else {
                HeaderManager::redirect('/?view=index&action=login');
            }
            exit;
        }
        
        // Verificar si el usuario está logueado
        if (!$this->sessionManager->isLoggedIn()) {
            error_log("UserController::protectRoot - Usuario no logueado");
            if ($this->isAjaxRequest()) {
                $this->sendJsonResponse(false, 'No estás logueado. Por favor, inicia sesión.');
            } else {
                HeaderManager::redirect('/?view=index&action=login');
            }
            exit;
        }
        
        // Verificar si el usuario tiene rol de root o director
        if (!$this->sessionManager->hasRole('root') && !$this->sessionManager->hasRole('director')) {
            $user = $this->sessionManager->getCurrentUser();
            $userRole = $user['role'] ?? 'sin rol';
            error_log("UserController::protectRoot - Usuario sin permisos: " . $user['email'] . " (rol: " . $userRole . ")");
            
            if ($this->isAjaxRequest()) {
                $this->sendJsonResponse(false, 'No tienes permisos para realizar esta acción. Necesitas rol de root o director.');
            } else {
                HeaderManager::redirect('/?view=unauthorized');
            }
            exit;
        }
        
        // Si llegamos aquí, el usuario tiene permisos
        error_log("UserController::protectRoot - Acceso autorizado para usuario: " . $this->sessionManager->getCurrentUser()['email']);
    }

    /**
     * Muestra el historial de roles de un usuario o permite buscarlo por documento
     */
    public function showRoleHistory($userId = null)
    {
        $this->protectRoot();
        $roleHistory = [];
        $searchError = '';
        $userInfo = null;

        // Si se envía el formulario de búsqueda
        $credentialType = htmlspecialchars($_GET['credential_type'] ?? '');
        $credentialNumber = htmlspecialchars($_GET['credential_number'] ?? '');
        
        if ($credentialType && $credentialNumber) {
            // Buscar usuario por documento
            try {
                $users = $this->userModel->searchUsersByDocument($credentialType, $credentialNumber);
                if (!empty($users)) {
                    $userInfo = $users[0];
                    $userId = $users[0]['user_id'];
                    $roleHistory = $this->userModel->getRoleHistory($userId);
                } else {
                    $searchError = 'No se encontró ningún usuario con ese documento.';
                }
            } catch (Exception $e) {
                $searchError = 'Error al buscar usuario: ' . $e->getMessage();
            }
        } elseif ($userId) {
            // Si se pasa el userId directamente
            $roleHistory = $this->userModel->getRoleHistory($userId);
        }

        // Si es una petición AJAX, cargar solo la vista parcial
        if ($this->isAjaxRequest()) {
            $this->loadPartialView('user/roleHistory', [
                'roleHistory' => $roleHistory,
                'userId' => $userId,
                'searchError' => $searchError,
                'userInfo' => $userInfo
            ]);
        } else {
            $this->loadView('user/roleHistory', [
                'roleHistory' => $roleHistory,
                'userId' => $userId,
                'searchError' => $searchError,
                'userInfo' => $userInfo
            ]);
        }
    }

    /**
     * Busca usuarios por rol y criterio de búsqueda
     */
    public function searchUsers()
    {
        $this->protectSchool();
        
        $role = htmlspecialchars($_GET['role'] ?? '');
        $query = htmlspecialchars($_GET['query'] ?? '');
        
        if (empty($role) || empty($query)) {
            $this->sendJsonResponse(false, 'Faltan parámetros requeridos');
            return;
        }
        
        try {
            $users = $this->userModel->searchUsersByRole($role, $query);
            $this->sendJsonResponse(true, 'Usuarios encontrados', ['users' => $users]);
        } catch (Exception $e) {
            $this->sendJsonResponse(false, 'Error al buscar usuarios: ' . $e->getMessage());
        }
    }

    /**
     * Protección de acceso para escuela (director, coordinador, tesorero, root)
     */
    private function protectSchool() {
        if (!isset($this->sessionManager) || !$this->sessionManager->isLoggedIn() || !$this->sessionManager->hasAnyRole(['director', 'coordinator', 'treasurer', 'root'])) {
            if ($this->isAjaxRequest()) {
                $this->sendJsonResponse(false, 'No tienes permisos para realizar esta acción');
            } else {
                header('Location: /?view=unauthorized');
            }
            exit;
        }
    }

    public function roleHistory() {
        $this->protectRoot();
        
        $roleHistory = [];
        $searchError = '';
        $userInfo = null;
        $credentialType = htmlspecialchars($_GET['credential_type'] ?? '');
        $credentialNumber = htmlspecialchars($_GET['credential_number'] ?? '');

        // Si se envía el formulario de búsqueda
        if ($credentialType && $credentialNumber) {
            try {
                // Buscar usuario por documento
                $users = $this->userModel->searchUsersByDocument($credentialType, $credentialNumber);
                if (!empty($users)) {
                    $userInfo = $users[0];
                    $userId = $users[0]['user_id'];
                    $roleHistory = $this->userModel->getRoleHistory($userId);
                } else {
                    $searchError = 'No se encontró ningún usuario con ese documento.';
                }
            } catch (Exception $e) {
                $searchError = 'Error al buscar usuario: ' . $e->getMessage();
                error_log("UserController::roleHistory - Error: " . $e->getMessage());
            }
        }

        // Verificar si hay mensaje de éxito
        $success = htmlspecialchars($_GET['success'] ?? false);
        $message = htmlspecialchars($_GET['msg'] ?? '');

        // Verificar si estamos en un contexto de dashboard
        $isDashboardContext = $this->isDashboardContext();
        
        // Si es una petición AJAX, usar loadPartialView
        if ($this->isAjaxRequest()) {
            $this->loadPartialView('user/roleHistory', [
                'roleHistory' => $roleHistory,
                'userInfo' => $userInfo,
                'searchError' => $searchError,
                'credentialType' => $credentialType,
                'credentialNumber' => $credentialNumber,
                'success' => $success,
                'message' => $message
            ]);
        } elseif ($isDashboardContext) {
            // Si estamos en dashboard, usar loadPartialView
            $this->loadPartialView('user/roleHistory', [
                'roleHistory' => $roleHistory,
                'userInfo' => $userInfo,
                'searchError' => $searchError,
                'credentialType' => $credentialType,
                'credentialNumber' => $credentialNumber,
                'success' => $success,
                'message' => $message
            ]);
        } else {
            // Si no estamos en dashboard, redirigir al dashboard con los parámetros
            $params = [];
            if ($credentialType) $params[] = "credential_type=$credentialType";
            if ($credentialNumber) $params[] = "credential_number=$credentialNumber";
            
            $paramString = !empty($params) ? '&' . implode('&', $params) : '';
            $redirectUrl = url . "?view=root&action=dashboard" . $paramString;
            
            header("Location: $redirectUrl");
            exit;
        }
    }

    /**
     * Muestra la vista de administración de usuarios (settingsRoles)
     */
    public function settingsRoles()
    {
        $this->protectRoot();
        $this->loadPartialView('user/settingsRoles');
    }

    /**
     * Devuelve la lista de usuarios en formato JSON (AJAX)
     */
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

    /**
     * Crea un usuario vía AJAX
     */
    public function createUserAjax()
    {
        $this->protectRoot();
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $this->sendJsonResponse(false, 'Datos inválidos');
            return;
        }
        // Mapear datos del formulario a los campos del modelo
            $userData = [
                'first_name' => $data['first_name'] ?? '',
                'last_name' => $data['last_name'] ?? '',
                'email' => $data['username'] ?? '',
                'password' => $data['password'] ?? '',
                'credential_type' => $data['document_type'] ?? '',
                'credential_number' => $data['document_number'] ?? '',
                'date_of_birth' => null,
                'phone' => null,
                'address' => null
            ];
        try {
            $this->userModel->createUser($userData);
            // Asignar rol si es diferente de student
            if (!empty($data['rol']) && $data['rol'] !== 'student') {
                $user = $this->userModel->searchUsersByDocument($userData['credential_type'], $userData['credential_number']);
                if (!empty($user[0]['user_id'])) {
                    $this->userModel->assignRole($user[0]['user_id'], $data['rol']);
                }
            }
            $this->sendJsonResponse(true, 'Usuario creado correctamente');
        } catch (Exception $e) {
            $this->sendJsonResponse(false, 'Error al crear usuario: ' . $e->getMessage());
        }
    }

    /**
     * Edita un usuario vía AJAX (nombre, apellido, correo, teléfono, dirección)
     */
    public function editUserAjax()
    {
        $this->protectRoot();
        $data = json_decode(file_get_contents('php://input'), true);
        $userId = $data['user_id'] ?? null;
        $firstName = $data['first_name'] ?? null;
        $lastName = $data['last_name'] ?? null;
        $email = $data['email'] ?? null;
        $phone = $data['phone'] ?? null;
        $address = $data['address'] ?? null;
        if (!$userId || !$firstName || !$lastName || !$email) {
            $this->sendJsonResponse(false, 'Faltan datos requeridos');
            return;
        }
        try {
            $this->userModel->updateUser($userId, [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => $phone,
                'date_of_birth' => null,
                'address' => $address
            ]);
            $this->sendJsonResponse(true, 'Usuario editado correctamente');
        } catch (Exception $e) {
            $this->sendJsonResponse(false, 'Error al editar usuario: ' . $e->getMessage());
        }
    }

    /**
     * Desactiva un usuario vía AJAX (soft delete)
     */
    public function deleteUserAjax()
    {
        $this->protectRoot();
        $data = json_decode(file_get_contents('php://input'), true);
        $userId = $data['user_id'] ?? null;
        if (!$userId) {
            $this->sendJsonResponse(false, 'Falta el ID de usuario');
            return;
        }
        try {
            $this->userModel->deleteUser($userId);
            $this->sendJsonResponse(true, 'Usuario desactivado correctamente');
        } catch (Exception $e) {
            $this->sendJsonResponse(false, 'Error al desactivar usuario: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un usuario permanentemente vía AJAX (hard delete)
     */
    public function deleteUserPermanentlyAjax()
    {
        $this->protectRoot();
        $data = json_decode(file_get_contents('php://input'), true);
        $userId = $data['user_id'] ?? null;
        if (!$userId) {
            $this->sendJsonResponse(false, 'Falta el ID de usuario');
            return;
        }

        try {
            // Verificar si el usuario puede ser eliminado
            $canDelete = $this->userModel->canDeleteUserPermanently($userId);
            if (!$canDelete['can_delete']) {
                $this->sendJsonResponse(false, $canDelete['reason']);
                return;
            }

            $this->userModel->deleteUserPermanently($userId);
            $this->sendJsonResponse(true, 'Usuario eliminado permanentemente');
        } catch (Exception $e) {
            $this->sendJsonResponse(false, 'Error al eliminar usuario: ' . $e->getMessage());
        }
    }

    /**
     * Verifica si un usuario puede ser eliminado permanentemente vía AJAX
     */
    public function checkCanDeleteUserAjax()
    {
        $this->protectRoot();
        $data = json_decode(file_get_contents('php://input'), true);
        $userId = $data['user_id'] ?? null;
        if (!$userId) {
            $this->sendJsonResponse(false, 'Falta el ID de usuario');
            return;
        }

        try {
            $canDelete = $this->userModel->canDeleteUserPermanently($userId);
            $this->sendJsonResponse($canDelete['can_delete'], $canDelete['reason'], $canDelete);
        } catch (Exception $e) {
            $this->sendJsonResponse(false, 'Error al verificar: ' . $e->getMessage());
        }
    }

    /**
     * Cambia la contraseña del usuario autenticado (AJAX)
     */
    public function changePasswordAjax()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->isAjaxRequest()) {
            $this->sendJsonResponse(false, 'Método no permitido');
            return;
        }
        // Obtener usuario autenticado
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
        $currentPassword = htmlspecialchars($_POST['currentPassword']) ?? '';
        $newPassword = htmlspecialchars($_POST['newPassword']) ?? '';
        $confirmPassword = htmlspecialchars($_POST['confirmPassword']) ?? '';
        if (!$currentPassword || !$newPassword || !$confirmPassword) {
            $this->sendJsonResponse(false, 'Todos los campos son obligatorios.');
            return;
        }
        if ($newPassword !== $confirmPassword) {
            $this->sendJsonResponse(false, 'La nueva contraseña y la confirmación no coinciden.');
            return;
        }
        // Puedes agregar aquí validaciones adicionales de seguridad para la nueva contraseña
        $result = $this->userModel->changePassword($userId, $currentPassword, $newPassword);
        if ($result === true) {
            $this->sendJsonResponse(true, 'Contraseña actualizada correctamente.');
        } else {
            $this->sendJsonResponse(false, $result);
        }
    }

    /**
     * Devuelve los datos del usuario autenticado (AJAX)
     */
    public function getProfileAjax()
    {
        if (!isset($this->sessionManager) || !$this->sessionManager->isLoggedIn()) {
            $this->sendJsonResponse(false, 'No has iniciado sesión.');
            return;
        }
        $user = $this->sessionManager->getCurrentUser();
        if (!$user || !isset($user['user_id'])) {
            $this->sendJsonResponse(false, 'Usuario no válido.');
            return;
        }
        $userData = $this->userModel->getUser($user['user_id']);
        if ($userData) {
            $this->sendJsonResponse(true, 'Usuario encontrado', ['user' => $userData]);
        } else {
            $this->sendJsonResponse(false, 'No se encontraron datos de usuario.');
        }
    }

    /**
     * Muestra la vista de configuración de perfil personal
     */
    public function profileSettings()
    {
        if (!isset($this->sessionManager) || !$this->sessionManager->isLoggedIn()) {
            HeaderManager::redirect('/?view=index&action=login');
            exit;
        }
        $this->loadPartialView('user/profileSettings');
    }

    /**
     * Muestra la vista de detalles del usuario
     */
    public function view()
    {
        $this->protectRoot();
        
        $userId = htmlspecialchars($_GET['id'] ?? '');
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
            error_log("UserController::view - Error: " . $e->getMessage());
            $this->loadPartialView('Error/500');
        }
    }

    /**
     * Muestra la vista de edición del usuario
     */
    public function edit()
    {
        $this->protectRoot();
        
        $userId = htmlspecialchars($_GET['id'] ?? '');
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
            error_log("UserController::edit - Error: " . $e->getMessage());
            $this->loadPartialView('Error/500');
        }
    }

    /**
     * Muestra la vista de historial de roles para un usuario específico
     */
    public function viewRoleHistory()
    {
        $this->protectRoot();
        
        $userId = htmlspecialchars($_GET['id'] ?? '');
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
            
            $roleHistory = $this->userModel->getRoleHistory($userId);
            
            $this->loadPartialView('user/roleHistory', [
                'user' => $user,
                'roleHistory' => $roleHistory
            ]);
        } catch (Exception $e) {
            error_log("UserController::viewRoleHistory - Error: " . $e->getMessage());
            $this->loadPartialView('Error/500');
        }
    }

    /**
     * Muestra la vista de desactivación de usuario
     */
    public function deactivate()
    {
        $this->protectRoot();
        
        $userId = htmlspecialchars($_GET['id'] ?? '');
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
            
            $this->loadPartialView('user/deactivate', ['user' => $user]);
        } catch (Exception $e) {
            error_log("UserController::deactivate - Error: " . $e->getMessage());
            $this->loadPartialView('Error/500');
        }
    }

    /**
     * Muestra la vista de activación de usuario
     */
    public function activate()
    {
        $this->protectRoot();
        
        $userId = htmlspecialchars($_GET['id'] ?? '');
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
            
            $this->loadPartialView('user/activate', ['user' => $user]);
        } catch (Exception $e) {
            error_log("UserController::activate - Error: " . $e->getMessage());
            $this->loadPartialView('Error/500');
        }
    }

    /**
     * Muestra la vista de cambio de contraseña
     */
    public function changePassword()
    {
        $this->protectRoot();
        
        $userId = htmlspecialchars($_GET['id'] ?? '');
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
            
            $this->loadPartialView('user/changePassword', ['user' => $user]);
        } catch (Exception $e) {
            error_log("UserController::changePassword - Error: " . $e->getMessage());
            $this->loadPartialView('Error/500');
        }
    }

    /**
     * Carga una vista parcial vía AJAX para el módulo user
     */
    public function loadPartial()
    {
        $view = htmlspecialchars($_POST['view'] ?? '') ?: htmlspecialchars($_GET['view'] ?? '');
        $action = htmlspecialchars($_POST['action'] ?? '') ?: htmlspecialchars($_GET['action'] ?? 'index');
        $partialView = htmlspecialchars($_POST['partialView'] ?? '') ?: htmlspecialchars($_GET['partialView'] ?? '');
        $force = !empty($_POST['force']) && htmlspecialchars($_POST['force'] ?? '') || !empty($_GET['force']) && htmlspecialchars($_GET['force'] ?? '');

        if (!$this->isAjaxRequest() && !$force) {
            if (empty($partialView)) {
                echo '<div class="alert alert-warning">Vista no especificada. Use: ?view=user&action=loadPartial&partialView=vista</div>';
                return;
            }
            $viewPath = 'user/' . $partialView;
            $fullPath = ROOT . "/app/views/{$viewPath}.php";
            if (!file_exists($fullPath)) {
                echo '<div class="alert alert-danger">Vista no encontrada: ' . htmlspecialchars($viewPath) . '</div>';
                return;
            }
            try {
                $this->loadPartialView($viewPath);
            } catch (Exception $e) {
                echo '<div class="alert alert-danger">Error al cargar la vista: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
            return;
        }
        if (empty($partialView)) {
            echo json_encode(['success' => false, 'message' => 'Vista no especificada']);
            return;
        }
        $viewPath = 'user/' . $partialView;
        $fullPath = ROOT . "/app/views/{$viewPath}.php";
        if (!file_exists($fullPath)) {
            echo json_encode(['success' => false, 'message' => "Vista no encontrada: {$viewPath}"]);
            return;
        }
        try {
            $this->loadPartialView($viewPath);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al cargar la vista: ' . $e->getMessage()]);
        }
    }
}