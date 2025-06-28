<?php
require_once __DIR__ . '/../models/UserModel.php';
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
        
        // Si se envió una búsqueda
        if (isset($_GET['credential_type']) && isset($_GET['credential_number']) && !empty($_GET['credential_number'])) {
            $credentialType = $_GET['credential_type'];
            $credentialNumber = $_GET['credential_number'];
            
            try {
                $users = $this->userModel->searchUsersByDocument($credentialType, $credentialNumber);
                
                if (empty($users)) {
                    $message = "No se encontraron usuarios con el documento especificado.";
                }
            } catch (Exception $e) {
                $error = "Error al buscar usuarios: " . $e->getMessage();
            }
        }
        
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
        if (!isset($this->sessionManager) || !$this->sessionManager->isLoggedIn() || !$this->sessionManager->hasRole('root')) {
            if ($this->isAjaxRequest()) {
                $this->sendJsonResponse(false, 'No tienes permisos para realizar esta acción');
            } else {
                header('Location: /?view=unauthorized');
            }
            exit;
        }
    }
} 