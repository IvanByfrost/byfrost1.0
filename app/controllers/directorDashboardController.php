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
<<<<<<< HEAD

    /**
     * API endpoint para obtener datos de comunicación
     */
    public function getCommunicationData() {
        header('Content-Type: application/json');
        
        if (!$this->sessionManager->hasRole('director')) {
            http_response_code(403);
            echo json_encode(['error' => 'Acceso denegado']);
            return;
        }

        $communicationData = $this->getCommunicationData();
        echo json_encode($communicationData);
    }

    /**
     * API endpoint para obtener datos de gráficos
     */
    public function getChartData() {
        header('Content-Type: application/json');
        
        if (!$this->sessionManager->hasRole('director')) {
            http_response_code(403);
            echo json_encode(['error' => 'Acceso denegado']);
            return;
        }

        $chartData = $this->getChartData();
        echo json_encode($chartData);
    }

    /**
     * API endpoint para crear un nuevo evento
     */
    public function createEvent() {
        header('Content-Type: application/json');
        
        // Solo permitir POST y directores
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        if (!$this->sessionManager->hasRole('director')) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Acceso denegado']);
            return;
        }
        
        // Leer datos JSON
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
            return;
        }
        // Validar campos obligatorios
        $required = ['title','type','date'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => "El campo $field es obligatorio"]);
                return;
            }
        }
        // Preparar datos
        $title = trim($input['title']);
        $type = trim($input['type']);
        $date = trim($input['date']);
        $time = !empty($input['time']) ? trim($input['time']) : null;
        $location = !empty($input['location']) ? trim($input['location']) : null;
        $priority = !empty($input['priority']) ? trim($input['priority']) : 'normal';
        $description = !empty($input['description']) ? trim($input['description']) : null;
        $participants = !empty($input['participants']) ? implode(',', (array)$input['participants']) : null;
        $created_by = $_SESSION['user_id'] ?? 1;
        
        try {
            $query = "INSERT INTO events (title, type, event_date, event_time, location, priority, description, participants, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$title, $type, $date, $time, $location, $priority, $description, $participants, $created_by]);
            echo json_encode(['success' => true, 'message' => 'Evento creado correctamente']);
        } catch (Exception $e) {
            error_log('Error al crear evento: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al crear el evento']);
        }
    }
=======
>>>>>>> a5de83c07509871e42286c8545c7c10438ecaf78
}
?> 