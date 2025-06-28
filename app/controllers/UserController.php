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
        
        $this->loadView('user/consultUser', [
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
        $this->loadView('user/assignRole', [
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
     * Protección de acceso solo para root
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
        
        // Verificar si el usuario tiene rol de root
        if (!$this->sessionManager->hasRole('root')) {
            $user = $this->sessionManager->getCurrentUser();
            $userRole = $user['role'] ?? 'sin rol';
            error_log("UserController::protectRoot - Usuario sin permisos: " . $user['email'] . " (rol: " . $userRole . ")");
            
            if ($this->isAjaxRequest()) {
                $this->sendJsonResponse(false, 'No tienes permisos para realizar esta acción. Necesitas rol de root.');
            } else {
                HeaderManager::redirect('/?view=unauthorized');
            }
            exit;
        }
        
        // Si llegamos aquí, el usuario tiene permisos
        error_log("UserController::protectRoot - Acceso autorizado para usuario: " . $this->sessionManager->getCurrentUser()['email']);
    }
} 