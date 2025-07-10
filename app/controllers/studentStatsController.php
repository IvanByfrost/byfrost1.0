<?php
require_once 'app/models/studentStatsModel.php';

class StudentStatsController extends MainController
{
    private $studentStatsModel;

    public function __construct()
    {
        parent::__construct();
        $this->studentStatsModel = new StudentStatsModel($this->dbConn);
    }

    /**
     * Muestra el dashboard de estadísticas de estudiantes
     */
    public function dashboard()
    {
        // Verificar permisos
        if (!$this->sessionManager->hasRole(['director', 'coordinator'])) {
            $this->loadView('Error/403');
            return;
        }

        try {
            $data = [
                'stats' => $this->studentStatsModel->getStudentStats(),
                'genderStats' => $this->studentStatsModel->getStudentStatsByGender(),
                'ageStats' => $this->studentStatsModel->getStudentStatsByAge(),
                'recentStudents' => $this->studentStatsModel->getRecentStudents(10),
                'topStudents' => $this->studentStatsModel->getTopPerformingStudents(10),
                'attentionStudents' => $this->studentStatsModel->getStudentsNeedingAttention(10),
                'monthlyGrowth' => $this->studentStatsModel->getMonthlyGrowth()
            ];

            $this->loadView('studentStats/dashboard', $data);
        } catch (Exception $e) {
            error_log("Error en StudentStatsController::dashboard: " . $e->getMessage());
            $this->loadView('Error/500');
        }
    }

    /**
     * Obtiene estadísticas en formato JSON para AJAX
     */
    public function getStats()
    {
        // Verificar permisos
        if (!$this->sessionManager->hasRole(['director', 'coordinator'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Acceso denegado']);
            return;
        }

        try {
            $stats = $this->studentStatsModel->getStudentStats();
            echo json_encode(['success' => true, 'data' => $stats]);
        } catch (Exception $e) {
            error_log("Error en StudentStatsController::getStats: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Error interno del servidor']);
        }
    }

    /**
     * Obtiene estadísticas por género
     */
    public function getGenderStats()
    {
        if (!$this->sessionManager->hasRole(['director', 'coordinator'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Acceso denegado']);
            return;
        }

        try {
            $stats = $this->studentStatsModel->getStudentStatsByGender();
            echo json_encode(['success' => true, 'data' => $stats]);
        } catch (Exception $e) {
            error_log("Error en StudentStatsController::getGenderStats: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Error interno del servidor']);
        }
    }

    /**
     * Obtiene estadísticas por edad
     */
    public function getAgeStats()
    {
        if (!$this->sessionManager->hasRole(['director', 'coordinator'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Acceso denegado']);
            return;
        }

        try {
            $stats = $this->studentStatsModel->getStudentStatsByAge();
            echo json_encode(['success' => true, 'data' => $stats]);
        } catch (Exception $e) {
            error_log("Error en StudentStatsController::getAgeStats: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Error interno del servidor']);
        }
    }

    /**
     * Obtiene estudiantes con mejor rendimiento
     */
    public function getTopStudents()
    {
        if (!$this->sessionManager->hasRole(['director', 'coordinator'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Acceso denegado']);
            return;
        }

        try {
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $students = $this->studentStatsModel->getTopPerformingStudents($limit);
            echo json_encode(['success' => true, 'data' => $students]);
        } catch (Exception $e) {
            error_log("Error en StudentStatsController::getTopStudents: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Error interno del servidor']);
        }
    }

    /**
     * Obtiene estudiantes que necesitan atención
     */
    public function getAttentionStudents()
    {
        if (!$this->sessionManager->hasRole(['director', 'coordinator'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Acceso denegado']);
            return;
        }

        try {
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $students = $this->studentStatsModel->getStudentsNeedingAttention($limit);
            echo json_encode(['success' => true, 'data' => $students]);
        } catch (Exception $e) {
            error_log("Error en StudentStatsController::getAttentionStudents: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Error interno del servidor']);
        }
    }

    /**
     * Obtiene crecimiento mensual
     */
    public function getMonthlyGrowth()
    {
        if (!$this->sessionManager->hasRole(['director', 'coordinator'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Acceso denegado']);
            return;
        }

        try {
            $growth = $this->studentStatsModel->getMonthlyGrowth();
            echo json_encode(['success' => true, 'data' => $growth]);
        } catch (Exception $e) {
            error_log("Error en StudentStatsController::getMonthlyGrowth: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Error interno del servidor']);
        }
    }

    /**
     * Genera reporte PDF de estadísticas
     */
    public function generateReport()
    {
        if (!$this->sessionManager->hasRole(['director', 'coordinator'])) {
            $this->loadView('Error/403');
            return;
        }

        try {
            $data = [
                'stats' => $this->studentStatsModel->getStudentStats(),
                'genderStats' => $this->studentStatsModel->getStudentStatsByGender(),
                'ageStats' => $this->studentStatsModel->getStudentStatsByAge(),
                'topStudents' => $this->studentStatsModel->getTopPerformingStudents(20),
                'attentionStudents' => $this->studentStatsModel->getStudentsNeedingAttention(20),
                'monthlyGrowth' => $this->studentStatsModel->getMonthlyGrowth(),
                'reportDate' => date('d/m/Y H:i:s')
            ];

            // Aquí podrías integrar una librería de PDF como TCPDF o FPDF
            $this->loadView('studentStats/report', $data);
        } catch (Exception $e) {
            error_log("Error en StudentStatsController::generateReport: " . $e->getMessage());
            $this->loadView('Error/500');
        }
    }

    /**
     * Exporta datos a Excel
     */
    public function exportExcel()
    {
        if (!$this->sessionManager->hasRole(['director', 'coordinator'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Acceso denegado']);
            return;
        }

        try {
            $data = [
                'stats' => $this->studentStatsModel->getStudentStats(),
                'genderStats' => $this->studentStatsModel->getStudentStatsByGender(),
                'ageStats' => $this->studentStatsModel->getStudentStatsByAge(),
                'recentStudents' => $this->studentStatsModel->getRecentStudents(50),
                'topStudents' => $this->studentStatsModel->getTopPerformingStudents(50),
                'attentionStudents' => $this->studentStatsModel->getStudentsNeedingAttention(50)
            ];

            // Configurar headers para descarga
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="estadisticas_estudiantes_' . date('Y-m-d') . '.xls"');
            header('Cache-Control: max-age=0');

            $this->loadView('studentStats/excel', $data);
        } catch (Exception $e) {
            error_log("Error en StudentStatsController::exportExcel: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Error interno del servidor']);
        }
    }
}
?> 