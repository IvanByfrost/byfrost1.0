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
        
        $users = $this->userModel->getUsers();
        
        $this->loadPartialView('user/consultUser', [
            'users' => $users
        ]);
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
        $credentialType = $_GET['credential_type'] ?? $_POST['credential_type'] ?? null;
        $credentialNumber = $_GET['credential_number'] ?? $_POST['credential_number'] ?? null;
        
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
        
        $userId = $_POST['user_id'] ?? null;
        $roleType = $_POST['role_type'] ?? null;
        
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
        $credentialType = $_GET['credential_type'] ?? null;
        $credentialNumber = $_GET['credential_number'] ?? null;
        
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
        
        $role = $_GET['role'] ?? '';
        $query = $_GET['query'] ?? '';
        
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
        $this->showRoleHistory();
    }

    /**
     * Muestra la vista de administración de usuarios (settingsRoles)
     */
    public function settingsRoles()
    {
        $this->protectRoot();
        $this->loadView('user/settingsRoles');
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
            'first_name' => $data['nombre'] ?? '',
            'last_name' => '',
            'email' => $data['usuario'] ?? '',
            'password' => $data['clave'] ?? '',
            'credential_type' => $data['tipoDoc'] ?? '',
            'credential_number' => $data['numeroDoc'] ?? '',
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
     * Edita un usuario vía AJAX (solo nombre por ahora)
     */
    public function editUserAjax()
    {
        $this->protectRoot();
        $data = json_decode(file_get_contents('php://input'), true);
        $userId = $data['user_id'] ?? null;
        $nuevoNombre = $data['nombre'] ?? null;
        if (!$userId || !$nuevoNombre) {
            $this->sendJsonResponse(false, 'Faltan datos requeridos');
            return;
        }
        // Separar nombre y apellido si es posible
        $partes = explode(' ', $nuevoNombre, 2);
        $firstName = $partes[0];
        $lastName = $partes[1] ?? '';
        try {
            $this->userModel->updateUser($userId, [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => null,
                'date_of_birth' => null,
                'address' => null
            ]);
            $this->sendJsonResponse(true, 'Usuario editado correctamente');
        } catch (Exception $e) {
            $this->sendJsonResponse(false, 'Error al editar usuario: ' . $e->getMessage());
        }
    }

    /**
     * Elimina (desactiva) un usuario vía AJAX
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
            $this->sendJsonResponse(true, 'Usuario eliminado correctamente');
        } catch (Exception $e) {
            $this->sendJsonResponse(false, 'Error al eliminar usuario: ' . $e->getMessage());
        }
    }
}