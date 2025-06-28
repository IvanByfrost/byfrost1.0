<?php
require_once __DIR__ . '/../models/SchoolModel.php';

require_once __DIR__ . '/MainController.php';
class SchoolController extends MainController
{
    protected $schoolModel;

    public function __construct($dbConn)
    {
        parent::__construct($dbConn);
        $this->schoolModel = new SchoolModel();
    }

    public function index()
    {
        $this->protectSchool();
        // Renderizar la vista de creación de colegio
        $this->render('school', 'dashboard');
    }

    public function createSchool()
    {
        $this->protectSchool();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validar campos obligatorios
                $requiredFields = ['school_name', 'school_dane', 'school_document'];
                $missingFields = [];
                
                foreach ($requiredFields as $field) {
                    if (empty($_POST[$field])) {
                        $missingFields[] = $field;
                    }
                }
                
                if (!empty($missingFields)) {
                    $errorMessage = 'Los siguientes campos son obligatorios: ' . implode(', ', $missingFields);
                    
                    // Verificar si es una petición AJAX
                    if ($this->isAjaxRequest()) {
                        $this->sendJsonResponse(false, $errorMessage);
                        return;
                    } else {
                        $this->loadView('school/createSchool', [
                            'error' => $errorMessage,
                            'formData' => $_POST,
                            'directors' => $this->schoolModel->getDirectors(),
                            'coordinators' => $this->schoolModel->getCoordinators()
                        ]);
                        return;
                    }
                }
                
                // Preparar los datos
                $data = [
                    'school_name' => trim($_POST['school_name']),
                    'school_dane' => trim($_POST['school_dane']),
                    'school_document' => trim($_POST['school_document']),
                    'total_quota' => intval($_POST['total_quota'] ?? 0),
                    'director_user_id' => !empty($_POST['director_user_id']) ? intval($_POST['director_user_id']) : null,
                    'coordinator_user_id' => !empty($_POST['coordinator_user_id']) ? intval($_POST['coordinator_user_id']) : null,
                    'address' => trim($_POST['address'] ?? ''),
                    'phone' => trim($_POST['phone'] ?? ''),
                    'email' => trim($_POST['email'] ?? '')
                ];
                
                // Validar formato de email si se proporciona
                if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $errorMessage = 'El formato del email no es válido';
                    
                    if ($this->isAjaxRequest()) {
                        $this->sendJsonResponse(false, $errorMessage);
                        return;
                    } else {
                        $this->loadView('school/createSchool', [
                            'error' => $errorMessage,
                            'formData' => $_POST,
                            'directors' => $this->schoolModel->getDirectors(),
                            'coordinators' => $this->schoolModel->getCoordinators()
                        ]);
                        return;
                    }
                }
                
                // Llamar al método del modelo
                $schoolId = $this->schoolModel->createSchool($data);
                
                if ($schoolId) {
                    // Éxito
                    if ($this->isAjaxRequest()) {
                        $this->sendJsonResponse(true, 'Escuela creada exitosamente', ['school_id' => $schoolId]);
                    } else {
                        // Redirigir a la lista de escuelas con mensaje de éxito
                        $this->redirect('?view=school&action=consultSchool&success=1&msg=' . urlencode('Escuela creada exitosamente'));
                    }
                } else {
                    $errorMessage = 'Error al crear la escuela. Verifique que los datos sean únicos.';
                    
                    if ($this->isAjaxRequest()) {
                        $this->sendJsonResponse(false, $errorMessage);
                    } else {
                        $this->loadView('school/createSchool', [
                            'error' => $errorMessage,
                            'formData' => $_POST,
                            'directors' => $this->schoolModel->getDirectors(),
                            'coordinators' => $this->schoolModel->getCoordinators()
                        ]);
                    }
                }
                
            } catch (Exception $e) {
                error_log("Error en SchoolController::createSchool: " . $e->getMessage());
                
                $errorMessage = 'Error interno del servidor: ' . $e->getMessage();
                
                if ($this->isAjaxRequest()) {
                    $this->sendJsonResponse(false, $errorMessage);
                } else {
                    $this->loadView('school/createSchool', [
                        'error' => $errorMessage,
                        'formData' => $_POST,
                        'directors' => $this->schoolModel->getDirectors(),
                        'coordinators' => $this->schoolModel->getCoordinators()
                    ]);
                }
            }
        } else {
            // Si es GET, mostrar el formulario
            $this->loadView('school/createSchool', [
                'directors' => $this->schoolModel->getDirectors(),
                'coordinators' => $this->schoolModel->getCoordinators()
            ]);
        }
    }

    public function consultSchool()
    {
        $this->protectSchool();
        
        $search = $_GET['search'] ?? '';
        $schools = $this->schoolModel->getAllSchools();
        
        // Si hay término de búsqueda, filtrar los resultados
        if (!empty($search)) {
            $schools = array_filter($schools, function($school) use ($search) {
                return stripos($school['school_name'], $search) !== false ||
                       stripos($school['school_dane'], $search) !== false ||
                       stripos($school['school_document'], $search) !== false;
            });
        }
        
        // Verificar si hay mensaje de éxito
        $success = $_GET['success'] ?? false;
        $message = $_GET['msg'] ?? '';
        
        $this->loadView('school/consultSchool', [
            'schools' => $schools,
            'search' => $search,
            'success' => $success,
            'message' => $message
        ]);
    }

    // Protección de acceso solo para escuela o tesorero
    private function protectSchool() {
        if (!isset($this->sessionManager) || !$this->sessionManager->isLoggedIn() || !$this->sessionManager->hasAnyRole(['director', 'coordinator', 'treasurer', 'root'])) {
            header('Location: /?view=unauthorized');
            exit;
        }
    }
}
