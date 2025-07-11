<?php
require_once 'app/controllers/MainController.php';

class RootDashboardController extends MainController
{
    public function __construct($dbConn)
    {
        // Asegurar que session_start() solo se llame una vez y antes de cualquier salida
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct($dbConn);
        // Verificar permisos de root
        if (!$this->sessionManager->isLoggedIn() || $this->sessionManager->getUserRole() !== 'root') {
            header('Location: ' . url . '?view=index&action=login');
            exit;
        }
    }

    /**
     * Dashboard principal de root
     */
    public function dashboard()
    {
        try {
            // Obtener estadísticas para el dashboard
            $stats = $this->getDashboardStats();
            
            // Cargar la vista del dashboard
            $this->loadDashboardView('root/dashboard', [
                'stats' => $stats,
                'currentUser' => $this->sessionManager->getCurrentUser()
            ]);
        } catch (Exception $e) {
            ErrorHandler::logError("Error en dashboard de root: " . $e->getMessage());
            $this->loadView('Error/500', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Obtener estadísticas para el dashboard
     */
    private function getDashboardStats()
    {
        try {
            $stats = [];
            
            // Total de usuarios activos
            $stmt = $this->dbConn->prepare("SELECT COUNT(*) as total FROM users WHERE is_active = 1");
            $stmt->execute();
            $stats['totalUsers'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Total de escuelas activas
            $stmt = $this->dbConn->prepare("SELECT COUNT(*) as total FROM schools WHERE is_active = 1");
            $stmt->execute();
            $stats['totalSchools'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Usuarios por rol
            $stmt = $this->dbConn->prepare("
                SELECT ur.role_type as role, COUNT(*) as count 
                FROM users u
                JOIN user_roles ur ON u.user_id = ur.user_id
                WHERE u.is_active = 1 AND ur.is_active = 1
                GROUP BY ur.role_type
            ");
            $stmt->execute();
            $stats['usersByRole'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $stats;
            
        } catch (Exception $e) {
            ErrorHandler::logError("Error obteniendo estadísticas de root: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Gestión de usuarios
     */
    public function userManagement()
    {
        try {
            $this->loadDashboardView('root/userManagement', [
                'currentUser' => $this->sessionManager->getCurrentUser()
            ]);
        } catch (Exception $e) {
            ErrorHandler::logError("Error en gestión de usuarios: " . $e->getMessage());
            $this->loadView('Error/500', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Gestión de roles
     */
    public function roleManagement()
    {
        try {
            $this->loadDashboardView('root/roleManagement', [
                'currentUser' => $this->sessionManager->getCurrentUser()
            ]);
        } catch (Exception $e) {
            ErrorHandler::logError("Error en gestión de roles: " . $e->getMessage());
            $this->loadView('Error/500', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Configuración del sistema
     */
    public function systemSettings()
    {
        try {
            $this->loadDashboardView('root/systemSettings', [
                'currentUser' => $this->sessionManager->getCurrentUser()
            ]);
        } catch (Exception $e) {
            ErrorHandler::logError("Error en configuración del sistema: " . $e->getMessage());
            $this->loadView('Error/500', ['error' => $e->getMessage()]);
        }
    }

    // El método loadPartial() se hereda del MainController
} 