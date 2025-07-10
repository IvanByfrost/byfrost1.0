<?php
require_once 'MainController.php';
require_once ROOT . '/app/library/SessionManager.php';
require_once ROOT . '/app/models/DirectorModel.php';

class DirectorDashboardController extends MainController {
    private $directorModel;

    public function __construct($dbConn, $view = null) {
        parent::__construct($dbConn, $view);
        $this->directorModel = new DirectorModel($this->dbConn);
    }

    /**
     * Muestra el dashboard principal del director
     */
    public function showDashboard() {
        // Verificar permisos
        if (!$this->sessionManager->isLoggedIn()) {
            header("Location: " . url . "?view=index&action=login");
            exit;
        }

        if (!$this->sessionManager->hasRole('director')) {
            header("Location: " . url . "?view=unauthorized");
            exit;
        }

        // Obtener métricas del dashboard
        $metrics = $this->getDashboardMetrics();
        
        // Cargar la vista del dashboard usando loadDashboardView
        $this->loadDashboardView('director/dashboard', [
            'metrics' => $metrics
        ]);
    }

    /**
     * Obtiene las métricas principales del dashboard
     */
    private function getDashboardMetrics() {
        try {
            $metrics = [
                'totalStudents' => 1247,
                'totalTeachers' => 89,
                'attendanceRate' => 94.2,
                'pendingTasks' => 12,
                'recentActivities' => [
                    [
                        'title' => 'Reunión de Padres',
                        'date' => '2024-03-15',
                        'type' => 'important'
                    ],
                    [
                        'title' => 'Exámenes Finales',
                        'date' => '2024-03-20',
                        'type' => 'academic'
                    ]
                ],
                'chartData' => [
                    'attendance' => [
                        'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                        'data' => [92, 94, 91, 95, 93, 94]
                    ],
                    'students' => [
                        'labels' => ['Primaria', 'Secundaria', 'Bachillerato'],
                        'data' => [450, 380, 417]
                    ],
                    'performance' => [
                        'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                        'data' => [85, 87, 86, 89, 88, 90]
                    ]
                ],
                'communicationData' => [
                    'monthlyEvents' => [
                        [
                            'title' => 'Reunión de Padres',
                            'date' => '2024-03-15',
                            'time' => '18:00',
                            'type' => 'important'
                        ]
                    ],
                    'parentCommunications' => [
                        'totalSent' => 247,
                        'readRate' => 89
                    ],
                    'importantAnnouncements' => [
                        [
                            'title' => '¡Anuncio Importante!',
                            'message' => 'Reunión de padres programada para el próximo viernes.',
                            'type' => 'warning'
                        ]
                    ],
                    'recentNotifications' => [
                        [
                            'title' => 'Nuevo estudiante registrado',
                            'message' => 'María González se ha registrado en el sistema.',
                            'time_ago' => 'Hace 3 horas'
                        ]
                    ]
                ]
            ];

            return $metrics;
        } catch (Exception $e) {
            error_log("Error obteniendo métricas del dashboard: " . $e->getMessage());
            return [
                'totalStudents' => 0,
                'totalTeachers' => 0,
                'attendanceRate' => 0,
                'pendingTasks' => 0,
                'recentActivities' => [],
                'chartData' => [],
                'communicationData' => []
            ];
        }
    }

    /**
     * API endpoint para obtener métricas en tiempo real
     */
    public function getMetrics() {
        header('Content-Type: application/json');
        
        if (!$this->sessionManager->hasRole('director')) {
            http_response_code(403);
            echo json_encode(['error' => 'Acceso denegado']);
            return;
        }

        $metrics = $this->getDashboardMetrics();
        echo json_encode($metrics);
    }

    /**
     * Carga una vista parcial para AJAX (sidebar dinámico)
     */
    public function loadPartial() {
        $view = $_POST['view'] ?? $_GET['view'] ?? '';
        $action = $_POST['action'] ?? $_GET['action'] ?? 'index';
        // Seguridad básica: solo permitir vistas del director
        $allowedViews = [
            'dashboard', 'createEvent', 'editDirector', 'createDirector',
            'studentStats/dashboard', 'activity/dashboard', 'schedule/schedule',
            'student/academicHistory', 'academicAverages',
            'user/consultUser', 'user/assignRole', 'user/roleHistory',
            'payroll/dashboard', 'payroll/employees', 'payroll/periods',
            'user/settingsRoles',
            // agrega aquí más vistas si lo necesitas
        ];
        // Normalizar para rutas tipo 'activity/dashboard'
        $viewKey = $view;
        if ($action && $action !== 'index') {
            $viewKey .= '/' . $action;
        }
        // LOG DEBUG
        error_log("[loadPartial] view: $view | action: $action | viewKey: $viewKey");
        if (!in_array($viewKey, $allowedViews)) {
            error_log("[loadPartial] Vista NO permitida: $viewKey");
            echo "<div class='alert alert-danger'>Vista no permitida: $viewKey</div>";
            return;
        }
        error_log("[loadPartial] Vista permitida: $viewKey. Cargando vista parcial...");
        // Cargar la vista parcial
        $this->renderPartial($view, $action);
    }
}
?> 