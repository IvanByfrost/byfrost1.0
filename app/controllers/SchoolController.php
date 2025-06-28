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
                    $response = [
                        'status' => 'error',
                        'msg' => 'Los siguientes campos son obligatorios: ' . implode(', ', $missingFields)
                    ];
                    
                    if ($this->isAjaxRequest()) {
                        header('Content-Type: application/json');
                        echo json_encode($response);
                        return;
                    }
                    
                    // Si no es AJAX, redirigir con error
                    $this->loadView('school/createSchool', [
                        'error' => $response['msg'],
                        'formData' => $_POST
                    ]);
                    return;
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
                    $response = [
                        'status' => 'error',
                        'msg' => 'El formato del email no es válido'
                    ];
                    
                    if ($this->isAjaxRequest()) {
                        header('Content-Type: application/json');
                        echo json_encode($response);
                        return;
                    }
                    
                    $this->loadView('school/createSchool', [
                        'error' => $response['msg'],
                        'formData' => $_POST
                    ]);
                    return;
                }
                
                // Llamar al método del modelo
                $success = $this->schoolModel->createSchool($data);
                
                if ($success) {
                    $response = [
                        'status' => 'success',
                        'msg' => 'Escuela creada exitosamente'
                    ];
                    
                    if ($this->isAjaxRequest()) {
                        header('Content-Type: application/json');
                        echo json_encode($response);
                        return;
                    }
                    
                    // Si no es AJAX, redirigir a la lista de escuelas
                    $this->redirect('?view=school&action=consultSchool&message=' . urlencode($response['msg']));
                } else {
                    $response = [
                        'status' => 'error',
                        'msg' => 'Error al crear la escuela. Verifique que los datos sean únicos.'
                    ];
                    
                    if ($this->isAjaxRequest()) {
                        header('Content-Type: application/json');
                        echo json_encode($response);
                        return;
                    }
                    
                    $this->loadView('school/createSchool', [
                        'error' => $response['msg'],
                        'formData' => $_POST
                    ]);
                }
                
            } catch (Exception $e) {
                error_log("Error en SchoolController::createSchool: " . $e->getMessage());
                
                $response = [
                    'status' => 'error',
                    'msg' => 'Error interno del servidor: ' . $e->getMessage()
                ];
                
                if ($this->isAjaxRequest()) {
                    header('Content-Type: application/json');
                    echo json_encode($response);
                    return;
                }
                
                $this->loadView('school/createSchool', [
                    'error' => $response['msg'],
                    'formData' => $_POST
                ]);
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
        try {
            // Obtener datos de búsqueda
            $searchData = [];
            
            if (isset($_POST['nit']) && !empty($_POST['nit'])) {
                $searchData['nit'] = trim($_POST['nit']);
            }
            
            if (isset($_POST['school_name']) && !empty($_POST['school_name'])) {
                $searchData['school_name'] = trim($_POST['school_name']);
            }
            
            if (isset($_POST['codigoDANE']) && !empty($_POST['codigoDANE'])) {
                $searchData['codigoDANE'] = trim($_POST['codigoDANE']);
            }
            
            // Si no hay parámetros específicos, usar búsqueda general
            if (empty($searchData)) {
                $searchTerm = $_POST['search'] ?? $_GET['search'] ?? '';
                if (!empty($searchTerm)) {
                    $searchData['search'] = trim($searchTerm);
                }
            }
            
            // Si no hay ningún término de búsqueda, obtener todas las escuelas
            if (empty($searchData)) {
                $schools = $this->schoolModel->getAllSchools();
            } else {
                $schools = $this->schoolModel->consultSchool($searchData);
            }
            
            // Preparar respuesta
            $response = [
                'success' => true,
                'message' => count($schools) > 0 ? 'Escuelas encontradas' : 'No se encontraron escuelas',
                'count' => count($schools),
                'data' => $schools
            ];
            
            // Si es una petición AJAX, devolver JSON
            if ($this->isAjaxRequest()) {
                header('Content-Type: application/json');
                echo json_encode($response);
                return;
            }
            
            // Si no es AJAX, cargar la vista con los datos
            $this->loadView('school/consultSchool', [
                'schools' => $schools,
                'searchData' => $searchData,
                'message' => $response['message']
            ]);
            
        } catch (Exception $e) {
            error_log("Error en SchoolController::consultSchool: " . $e->getMessage());
            
            $errorResponse = [
                'success' => false,
                'message' => 'Error al consultar las escuelas',
                'error' => $e->getMessage()
            ];
            
            if ($this->isAjaxRequest()) {
                header('Content-Type: application/json');
                echo json_encode($errorResponse);
                return;
            }
            
            $this->loadView('school/consultSchool', [
                'schools' => [],
                'searchData' => [],
                'error' => 'Error al consultar las escuelas'
            ]);
        }
    }

    // Protección de acceso solo para escuela o tesorero
    private function protectSchool() {
        if (!isset($this->sessionManager) || !$this->sessionManager->isLoggedIn() || !$this->sessionManager->hasAnyRole(['director', 'coordinator', 'treasurer'])) {
            header('Location: /?view=unauthorized');
            exit;
        }
    }
}
