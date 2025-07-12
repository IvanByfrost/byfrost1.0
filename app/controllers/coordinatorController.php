<?php

require_once 'app/models/coordinatorModel.php';
require_once 'app/controllers/MainController.php';

class CoordinatorController extends MainController {
    protected $model;

    public function __construct($dbConn) {
        // Asegurar que session_start() solo se llame una vez y antes de cualquier salida
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct($dbConn);
        $this->model = new CoordinatorModel();
    }

    public function showDashCoord() {
        // Verificar si el usuario está logueado
        if (!$this->sessionManager->isLoggedIn()) {
            $this->redirect(url . '?view=login');
            return;
        }
        
        // Verificar si el usuario tiene rol de coordinador
        if (!$this->sessionManager->hasRole('coordinator')) {
            $this->redirect(url . '?view=unauthorized');
            return;
        }
        
        // Obtener datos del usuario para la vista
        $user = $this->sessionManager->getCurrentUser();
        
        $data = $this->model->getData();
        $this->render('coordinator', 'dashboard', [
            'data' => $data,
            'user' => $user
        ]);
    }

    // =============================================
    // FUNCIONES CRUD PARA COORDINADORES
    // =============================================

    // Función para mostrar lista de coordinadores
    public function listCoordinators() {
        // Verificar permisos de administrador
        if (!$this->sessionManager->hasRole('root') && !$this->sessionManager->hasRole('director')) {
            $this->redirect(url . '?view=unauthorized');
            return;
        }

        try {
            $coordinators = $this->model->getAllCoordinators();
            $this->render('coordinator', 'list', [
                'coordinators' => $coordinators,
                'user' => $this->sessionManager->getCurrentUser()
            ]);
        } catch (Exception $e) {
            $this->render('Error', 'error', [
                'error' => 'Error al cargar la lista de coordinadores: ' . $e->getMessage()
            ]);
        }
    }

    // Función para mostrar formulario de asignar rol de coordinador
    public function createCoordinatorForm() {
        // Verificar permisos de administrador
        if (!$this->sessionManager->hasRole('root') && !$this->sessionManager->hasRole('director')) {
            $this->redirect(url . '?view=unauthorized');
            return;
        }

        $this->render('coordinator', 'create', [
            'user' => $this->sessionManager->getCurrentUser()
        ]);
    }

    // Función para procesar asignación de rol de coordinador
    public function createCoordinator() {
        // Verificar permisos de administrador
        if (!$this->sessionManager->hasRole('root') && !$this->sessionManager->hasRole('director')) {
            $this->redirect(url . '?view=unauthorized');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(url . '?view=coordinator&action=createCoordinatorForm');
            return;
        }

        try {
            // Validar que se proporcione un ID de usuario
            if (empty(htmlspecialchars($_POST['user_id']))) {
                throw new Exception("Debe seleccionar un usuario");
            }

            $userId = (int)htmlspecialchars($_POST['user_id']);

            // Asignar rol de coordinador al usuario existente
            $this->model->createCoordinator($userId);

            // Redirigir con mensaje de éxito
            $this->redirect(url . '?view=coordinator&action=listCoordinators&success=created');

        } catch (Exception $e) {
            $this->render('coordinator', 'create', [
                'error' => $e->getMessage(),
                'formData' => $_POST,
                'user' => $this->sessionManager->getCurrentUser()
            ]);
        }
    }

    // Función para mostrar formulario de editar coordinador
    public function editCoordinatorForm($coordinatorId = null) {
        // Verificar permisos de administrador
        if (!$this->sessionManager->hasRole('root') && !$this->sessionManager->hasRole('director')) {
            $this->redirect(url . '?view=unauthorized');
            return;
        }

        $coordinatorId = $coordinatorId ?? (htmlspecialchars($_GET['id']) ?? null);
        if (!$coordinatorId) {
            $this->redirect(url . '?view=coordinator&action=listCoordinators&error=no_id');
            return;
        }

        try {
            $coordinator = $this->model->getCoordinator($coordinatorId);
            if (!$coordinator) {
                throw new Exception("Coordinador no encontrado");
            }

            $this->render('coordinator', 'edit', [
                'coordinator' => $coordinator,
                'user' => $this->sessionManager->getCurrentUser()
            ]);

        } catch (Exception $e) {
            $this->redirect(url . '?view=coordinator&action=listCoordinators&error=' . urlencode($e->getMessage()));
        }
    }

    // Función para procesar actualización de coordinador
    public function updateCoordinator() {
        // Verificar permisos de administrador
        if (!$this->sessionManager->hasRole('root') && !$this->sessionManager->hasRole('director')) {
            $this->redirect(url . '?view=unauthorized');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(url . '?view=coordinator&action=listCoordinators');
            return;
        }

        $coordinatorId = htmlspecialchars($_POST['coordinator_id']) ?? null;
        if (!$coordinatorId) {
            $this->redirect(url . '?view=coordinator&action=listCoordinators&error=no_id');
            return;
        }

        try {
            // Validar datos requeridos
            $requiredFields = ['credential_number', 'first_name', 'last_name', 'credential_type', 
                              'date_of_birth', 'email'];
            
            foreach ($requiredFields as $field) {
                if (empty(htmlspecialchars($_POST[$field]))) {
                    throw new Exception("El campo $field es requerido");
                }
            }

            // Validar formato de email
            if (!filter_var(htmlspecialchars($_POST['email']), FILTER_VALIDATE_EMAIL)) {
                throw new Exception("El formato del email no es válido");
            }

            // Preparar datos del usuario
            $userData = [
                'credential_number' => htmlspecialchars($_POST['credential_number']),
                'first_name' => htmlspecialchars($_POST['first_name']),
                'last_name' => htmlspecialchars($_POST['last_name']),
                'credential_type' => htmlspecialchars($_POST['credential_type']),
                'date_of_birth' => htmlspecialchars($_POST['date_of_birth']),
                'address' => htmlspecialchars($_POST['address']) ?? '',
                'phone' => htmlspecialchars($_POST['phone']) ?? '',
                'email' => htmlspecialchars($_POST['email'])
            ];

            // Actualizar coordinador
            $this->model->updateCoordinator($coordinatorId, $userData);

            // Redirigir con mensaje de éxito
            $this->redirect(url . '?view=coordinator&action=listCoordinators&success=updated');

        } catch (Exception $e) {
            $this->redirect(url . '?view=coordinator&action=editCoordinatorForm&id=' . $coordinatorId . '&error=' . urlencode($e->getMessage()));
        }
    }

    // Función para cambiar contraseña de coordinador
    public function changeCoordinatorPassword() {
        // Verificar permisos de administrador
        if (!$this->sessionManager->hasRole('root') && !$this->sessionManager->hasRole('director')) {
            $this->redirect(url . '?view=unauthorized');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(url . '?view=coordinator&action=listCoordinators');
            return;
        }

        $coordinatorId = htmlspecialchars($_POST['coordinator_id']) ?? null;
        $newPassword = htmlspecialchars($_POST['new_password']) ?? '';
        $confirmPassword = htmlspecialchars($_POST['confirm_password']) ?? '';

        if (!$coordinatorId || !$newPassword || !$confirmPassword) {
            $this->redirect(url . '?view=coordinator&action=listCoordinators&error=missing_data');
            return;
        }

        if ($newPassword !== $confirmPassword) {
            $this->redirect(url . '?view=coordinator&action=listCoordinators&error=passwords_dont_match');
            return;
        }

        try {
            $this->model->updateCoordinatorPassword($coordinatorId, $newPassword);
            $this->redirect(url . '?view=coordinator&action=listCoordinators&success=password_changed');

        } catch (Exception $e) {
            $this->redirect(url . '?view=coordinator&action=listCoordinators&error=' . urlencode($e->getMessage()));
        }
    }

    // Función para desactivar rol de coordinador (Director y Root)
    public function deactivateCoordinator() {
        // Verificar permisos de administrador
        if (!$this->sessionManager->hasRole('root') && !$this->sessionManager->hasRole('director')) {
            $this->redirect(url . '?view=unauthorized');
            return;
        }

        $coordinatorId = htmlspecialchars($_GET['id']) ?? htmlspecialchars($_POST['coordinator_id']) ?? null;
        if (!$coordinatorId) {
            $this->redirect(url . '?view=coordinator&action=listCoordinators&error=no_id');
            return;
        }

        try {
            // Solo desactivar el rol, no el usuario
            $this->model->deactivateCoordinatorRole($coordinatorId);
            $this->redirect(url . '?view=coordinator&action=listCoordinators&success=role_deactivated');

        } catch (Exception $e) {
            $this->redirect(url . '?view=coordinator&action=listCoordinators&error=' . urlencode($e->getMessage()));
        }
    }

    // Función para eliminar usuario permanentemente (Solo Root)
    public function deleteCoordinator() {
        // Solo root puede eliminar usuarios permanentemente
        if (!$this->sessionManager->hasRole('root')) {
            $this->redirect(url . '?view=unauthorized');
            return;
        }

        $coordinatorId = htmlspecialchars($_GET['id']) ?? htmlspecialchars($_POST['coordinator_id']) ?? null;
        if (!$coordinatorId) {
            $this->redirect(url . '?view=coordinator&action=listCoordinators&error=no_id');
            return;
        }

        try {
            $this->model->deleteCoordinator($coordinatorId);
            $this->redirect(url . '?view=coordinator&action=listCoordinators&success=user_deleted');

        } catch (Exception $e) {
            $this->redirect(url . '?view=coordinator&action=listCoordinators&error=' . urlencode($e->getMessage()));
        }
    }

    // Función para buscar coordinadores
    public function searchCoordinators() {
        // Verificar permisos de administrador
        if (!$this->sessionManager->hasRole('root') && !$this->sessionManager->hasRole('director')) {
            $this->redirect(url . '?view=unauthorized');
            return;
        }

        $searchTerm = htmlspecialchars($_GET['search']) ?? htmlspecialchars($_POST['search']) ?? '';
        if (empty($searchTerm)) {
            $this->redirect(url . '?view=coordinator&action=listCoordinators');
            return;
        }

        try {
            $coordinators = $this->model->searchCoordinators($searchTerm);
            $this->render('coordinator', 'list', [
                'coordinators' => $coordinators,
                'searchTerm' => $searchTerm,
                'user' => $this->sessionManager->getCurrentUser()
            ]);

        } catch (Exception $e) {
            $this->render('Error', 'error', [
                'error' => 'Error en la búsqueda: ' . $e->getMessage()
            ]);
        }
    }

    // Función para ver perfil de coordinador
    public function viewCoordinator($coordinatorId = null) {
        // Verificar permisos
        if (!$this->sessionManager->hasRole('root') && !$this->sessionManager->hasRole('director')) {
            $this->redirect(url . '?view=unauthorized');
            return;
        }

        $coordinatorId = $coordinatorId ?? (htmlspecialchars($_GET['id']) ?? null);
        if (!$coordinatorId) {
            $this->redirect(url . '?view=coordinator&action=listCoordinators&error=no_id');
            return;
        }

        try {
            $coordinator = $this->model->getCoordinator($coordinatorId);
            if (!$coordinator) {
                throw new Exception("Coordinador no encontrado");
            }

            $this->render('coordinator', 'view', [
                'coordinator' => $coordinator,
                'user' => $this->sessionManager->getCurrentUser()
            ]);

        } catch (Exception $e) {
            $this->redirect(url . '?view=coordinator&action=listCoordinators&error=' . urlencode($e->getMessage()));
        }
    }

    /**
     * Carga una vista parcial vía AJAX para el módulo coordinator
     */
    public function loadPartial()
    {
        $view = htmlspecialchars($_POST['view']) ?? htmlspecialchars($_GET['view']) ?? '';
        $action = htmlspecialchars($_POST['action']) ?? htmlspecialchars($_GET['action']) ?? 'index';
        $partialView = htmlspecialchars($_POST['partialView']) ?? htmlspecialchars($_GET['partialView']) ?? '';
        $force = isset($_POST['force']) && htmlspecialchars($_POST['force']) || isset($_GET['force']) && htmlspecialchars($_GET['force']);

        if (!$this->isAjaxRequest() && !$force) {
            if (empty($partialView)) {
                echo '<div class="alert alert-warning">Vista no especificada. Use: ?view=coordinator&action=loadPartial&partialView=vista</div>';
                return;
            }
            $viewPath = 'coordinator/' . $partialView;
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
        $viewPath = 'coordinator/' . $partialView;
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
