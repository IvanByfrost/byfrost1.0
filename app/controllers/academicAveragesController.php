<?php
require_once 'app/models/academicAveragesModel.php';
require_once 'app/library/Views.php';
require_once 'app/library/SessionManager.php';

class AcademicAveragesController {
    private $model;
    private $views;
    private $sessionManager;
    
    public function __construct() {
        // Asegurar que session_start() solo se llame una vez y antes de cualquier salida
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->model = new AcademicAveragesModel();
        $this->views = new Views();
        $this->sessionManager = new SessionManager();
    }
    
    /**
     * Vista principal de promedios académicos
     */
    public function index() {
        try {
            // Verificar permisos
            if (!$this->sessionManager->isLoggedIn()) {
                header('Location: ' . $GLOBALS['url'] . 'login');
                exit;
            }
            
            $userRole = $this->sessionManager->getUserRole();
            $allowedRoles = ['director', 'coordinator', 'professor', 'root'];
            
            if (!in_array($userRole, $allowedRoles)) {
                header('Location: ' . $GLOBALS['url'] . 'error/403');
                exit;
            }
            
            // Obtener datos
            $termAverages = $this->model->getTermAverages();
            $generalStats = $this->model->getGeneralStats();
            $topStudents = $this->model->getTopStudents();
            $monthlyTrends = $this->model->getMonthlyTrends();
            
            // Preparar datos para la vista
            $data = [
                'termAverages' => $termAverages,
                'generalStats' => $generalStats,
                'topStudents' => $topStudents,
                'monthlyTrends' => $monthlyTrends,
                'userRole' => $userRole,
                'pageTitle' => 'Promedios Académicos'
            ];
            
            // Cargar vista
            $this->views->Render('academicAverages', 'index', $data);
            
        } catch (Exception $e) {
            error_log("Error en AcademicAveragesController::index: " . $e->getMessage());
            header('Location: ' . $GLOBALS['url'] . 'error/500');
            exit;
        }
    }
    
    /**
     * Vista de promedios por asignatura
     */
    public function subjects() {
        try {
            // Verificar permisos
            if (!$this->sessionManager->isLoggedIn()) {
                header('Location: ' . $GLOBALS['url'] . 'login');
                exit;
            }
            
            $userRole = $this->sessionManager->getUserRole();
            $allowedRoles = ['director', 'coordinator', 'professor', 'root'];
            
            if (!in_array($userRole, $allowedRoles)) {
                header('Location: ' . $GLOBALS['url'] . 'error/403');
                exit;
            }
            
            // Obtener datos
            $subjectAverages = $this->model->getSubjectAverages();
            $generalStats = $this->model->getGeneralStats();
            
            // Preparar datos para la vista
            $data = [
                'subjectAverages' => $subjectAverages,
                'generalStats' => $generalStats,
                'userRole' => $userRole,
                'pageTitle' => 'Promedios por Asignatura'
            ];
            
            // Cargar vista
            $this->views->Render('academicAverages', 'subjects', $data);
            
        } catch (Exception $e) {
            error_log("Error en AcademicAveragesController::subjects: " . $e->getMessage());
            header('Location: ' . $GLOBALS['url'] . 'error/500');
            exit;
        }
    }
    
    /**
     * Vista de promedios por profesor
     */
    public function professors() {
        try {
            // Verificar permisos
            if (!$this->sessionManager->isLoggedIn()) {
                header('Location: ' . $GLOBALS['url'] . 'login');
                exit;
            }
            
            $userRole = $this->sessionManager->getUserRole();
            $allowedRoles = ['director', 'coordinator', 'root'];
            
            if (!in_array($userRole, $allowedRoles)) {
                header('Location: ' . $GLOBALS['url'] . 'error/403');
                exit;
            }
            
            // Obtener datos
            $professorAverages = $this->model->getProfessorAverages();
            $generalStats = $this->model->getGeneralStats();
            
            // Preparar datos para la vista
            $data = [
                'professorAverages' => $professorAverages,
                'generalStats' => $generalStats,
                'userRole' => $userRole,
                'pageTitle' => 'Promedios por Profesor'
            ];
            
            // Cargar vista
            $this->views->Render('academicAverages', 'professors', $data);
            
        } catch (Exception $e) {
            error_log("Error en AcademicAveragesController::professors: " . $e->getMessage());
            header('Location: ' . $GLOBALS['url'] . 'error/500');
            exit;
        }
    }
    
    /**
     * Vista de mejores estudiantes
     */
    public function topStudents() {
        try {
            // Verificar permisos
            if (!$this->sessionManager->isLoggedIn()) {
                header('Location: ' . $GLOBALS['url'] . 'login');
                exit;
            }
            
            $userRole = $this->sessionManager->getUserRole();
            $allowedRoles = ['director', 'coordinator', 'professor', 'root'];
            
            if (!in_array($userRole, $allowedRoles)) {
                header('Location: ' . $GLOBALS['url'] . 'error/403');
                exit;
            }
            
            // Obtener período específico si se proporciona
            $termId = isset(_GET['term_id']) && htmlspecialchars(_GET['term_id']) ? (int)htmlspecialchars($_GET['term_id']) : null;
            
            // Obtener datos
            $topStudents = $this->model->getTopStudents($termId);
            $generalStats = $this->model->getGeneralStats();
            
            // Preparar datos para la vista
            $data = [
                'topStudents' => $topStudents,
                'generalStats' => $generalStats,
                'selectedTermId' => $termId,
                'userRole' => $userRole,
                'pageTitle' => 'Mejores Estudiantes'
            ];
            
            // Cargar vista
            $this->views->Render('academicAverages', 'topStudents', $data);
            
        } catch (Exception $e) {
            error_log("Error en AcademicAveragesController::topStudents: " . $e->getMessage());
            header('Location: ' . $GLOBALS['url'] . 'error/500');
            exit;
        }
    }
    
    /**
     * API para obtener datos en formato JSON
     */
    public function api() {
        try {
            // Verificar permisos
            if (!$this->sessionManager->isLoggedIn()) {
                http_response_code(401);
                echo json_encode(['error' => 'No autorizado']);
                exit;
            }
            
            $userRole = $this->sessionManager->getUserRole();
            $allowedRoles = ['director', 'coordinator', 'professor', 'root'];
            
            if (!in_array($userRole, $allowedRoles)) {
                http_response_code(403);
                echo json_encode(['error' => 'Acceso denegado']);
                exit;
            }
            
            // Obtener tipo de datos solicitado
            $type = isset(_GET['type']) && htmlspecialchars(_GET['type']) ? htmlspecialchars($_GET['type']) : 'terms';
            
            $data = [];
            
            switch ($type) {
                case 'terms':
                    $data = $this->model->getTermAverages();
                    break;
                case 'subjects':
                    $data = $this->model->getSubjectAverages();
                    break;
                case 'professors':
                    $data = $this->model->getProfessorAverages();
                    break;
                case 'top_students':
                    $termId = isset(_GET['term_id']) && htmlspecialchars(_GET['term_id']) ? (int)htmlspecialchars($_GET['term_id']) : null;
                    $data = $this->model->getTopStudents($termId);
                    break;
                case 'trends':
                    $data = $this->model->getMonthlyTrends();
                    break;
                case 'general':
                    $data = $this->model->getGeneralStats();
                    break;
                default:
                    http_response_code(400);
                    echo json_encode(['error' => 'Tipo de datos no válido']);
                    exit;
            }
            
            // Devolver respuesta JSON
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => $data,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            
        } catch (Exception $e) {
            error_log("Error en AcademicAveragesController::api: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Error interno del servidor']);
        }
    }
}
?> 