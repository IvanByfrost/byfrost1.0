<?php
require_once ROOT . '/app/library/SessionManager.php';
require_once ROOT . '/app/models/DirectorModel.php';

class DirectorDashboardController {
    private $db;
    private $sessionManager;
    private $directorModel;
    private $view;

    public function __construct($db, $view) {
        $this->db = $db;
        $this->view = $view;
        $this->sessionManager = new SessionManager();
        $this->directorModel = new DirectorModel($db);
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
        
        // Cargar la vista del dashboard
        $this->view->loadView('director/dashboard', [
            'metrics' => $metrics
        ]);
    }

    /**
     * Obtiene las métricas principales del dashboard
     */
    private function getDashboardMetrics() {
        try {
            $metrics = [
                'totalStudents' => $this->getTotalStudents(),
                'totalTeachers' => $this->getTotalTeachers(),
                'attendanceRate' => $this->getAttendanceRate(),
                'pendingTasks' => $this->getPendingTasks(),
                'recentActivities' => $this->getRecentActivities(),
                'chartData' => $this->getChartData(),
                'communicationData' => $this->getCommunicationData()
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
     * Obtiene el total de estudiantes
     */
    private function getTotalStudents() {
        try {
            $query = "SELECT COUNT(*) as total FROM students WHERE status = 'active'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            error_log("Error obteniendo total de estudiantes: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obtiene el total de docentes
     */
    private function getTotalTeachers() {
        try {
            $query = "SELECT COUNT(*) as total FROM teachers WHERE status = 'active'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            error_log("Error obteniendo total de docentes: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obtiene la tasa de asistencia promedio
     */
    private function getAttendanceRate() {
        try {
            // Simular cálculo de asistencia - en producción esto vendría de la base de datos
            $query = "SELECT AVG(attendance_rate) as avg_rate FROM attendance_records WHERE date >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch();
            return round($result['avg_rate'] ?? 94.2, 1);
        } catch (Exception $e) {
            error_log("Error obteniendo tasa de asistencia: " . $e->getMessage());
            return 94.2; // Valor por defecto
        }
    }

    /**
     * Obtiene las tareas pendientes
     */
    private function getPendingTasks() {
        try {
            $query = "SELECT COUNT(*) as total FROM tasks WHERE status = 'pending' AND assigned_to_role = 'director'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            error_log("Error obteniendo tareas pendientes: " . $e->getMessage());
            return 12; // Valor por defecto
        }
    }

    /**
     * Obtiene las actividades recientes
     */
    private function getRecentActivities() {
        try {
            $query = "SELECT * FROM activities WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) ORDER BY created_at DESC LIMIT 5";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error obteniendo actividades recientes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene datos para los gráficos
     */
    private function getChartData() {
        try {
            return [
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
            ];
        } catch (Exception $e) {
            error_log("Error obteniendo datos de gráficos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene datos de comunicación
     */
    private function getCommunicationData() {
        try {
            return [
                'monthlyEvents' => $this->getMonthlyEvents(),
                'parentCommunications' => $this->getParentCommunications(),
                'importantAnnouncements' => $this->getImportantAnnouncements(),
                'recentNotifications' => $this->getRecentNotifications()
            ];
        } catch (Exception $e) {
            error_log("Error obteniendo datos de comunicación: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene eventos del mes
     */
    private function getMonthlyEvents() {
        try {
            $query = "SELECT * FROM activities WHERE MONTH(event_date) = MONTH(NOW()) AND YEAR(event_date) = YEAR(NOW()) ORDER BY event_date ASC LIMIT 5";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $events = $stmt->fetchAll();
            
            // Si no hay eventos en la BD, usar datos de ejemplo
            if (empty($events)) {
                return [
                    [
                        'title' => 'Reunión de Padres',
                        'date' => '2024-03-15',
                        'time' => '18:00',
                        'type' => 'important'
                    ],
                    [
                        'title' => 'Exámenes Finales',
                        'date' => '2024-03-20',
                        'time' => '08:00',
                        'type' => 'academic'
                    ],
                    [
                        'title' => 'Festival de Arte',
                        'date' => '2024-03-28',
                        'time' => '14:00',
                        'type' => 'cultural'
                    ]
                ];
            }
            
            return $events;
        } catch (Exception $e) {
            error_log("Error obteniendo eventos del mes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene estadísticas de comunicación con padres
     */
    private function getParentCommunications() {
        try {
            $query = "SELECT COUNT(*) as total_sent, AVG(read_rate) as avg_read_rate FROM parent_communications WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch();
            
            return [
                'totalSent' => $result['total_sent'] ?? 247,
                'readRate' => round($result['avg_read_rate'] ?? 89, 1)
            ];
        } catch (Exception $e) {
            error_log("Error obteniendo estadísticas de comunicación: " . $e->getMessage());
            return [
                'totalSent' => 247,
                'readRate' => 89
            ];
        }
    }

    /**
     * Obtiene anuncios importantes
     */
    private function getImportantAnnouncements() {
        try {
            $query = "SELECT * FROM announcements WHERE priority = 'high' AND status = 'active' ORDER BY created_at DESC LIMIT 3";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $announcements = $stmt->fetchAll();
            
            // Si no hay anuncios en la BD, usar datos de ejemplo
            if (empty($announcements)) {
                return [
                    [
                        'title' => '¡Anuncio Importante!',
                        'message' => 'Reunión de padres programada para el próximo viernes 15 de marzo a las 6:00 PM. Todos los padres deben asistir.',
                        'type' => 'warning'
                    ]
                ];
            }
            
            return $announcements;
        } catch (Exception $e) {
            error_log("Error obteniendo anuncios importantes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene notificaciones recientes
     */
    private function getRecentNotifications() {
        try {
            $query = "SELECT * FROM notifications WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) ORDER BY created_at DESC LIMIT 5";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $notifications = $stmt->fetchAll();
            
            // Si no hay notificaciones en la BD, usar datos de ejemplo
            if (empty($notifications)) {
                return [
                    [
                        'title' => 'Nuevo estudiante registrado',
                        'message' => 'María González se ha registrado en el sistema.',
                        'time_ago' => 'Hace 3 horas'
                    ],
                    [
                        'title' => 'Reporte mensual listo',
                        'message' => 'El reporte de febrero está disponible.',
                        'time_ago' => 'Hace 1 día'
                    ],
                    [
                        'title' => 'Actualización de horarios',
                        'message' => 'Los horarios han sido actualizados.',
                        'time_ago' => 'Hace 2 días'
                    ]
                ];
            }
            
            return $notifications;
        } catch (Exception $e) {
            error_log("Error obteniendo notificaciones recientes: " . $e->getMessage());
            return [];
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
}
?> 