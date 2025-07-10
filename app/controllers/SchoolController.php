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
                // Validar campos obligatorios del formulario principal
                $requiredFields = ['school_name', 'school_dane', 'school_document'];
                $missingFields = [];
                
                foreach ($requiredFields as $field) {
                    if (empty($_POST[$field])) {
                        $missingFields[] = $field;
                    }
                }
                
                // Validar campos obligatorios del modal
                $modalRequiredFields = ['departamento', 'municipio', 'direccion', 'telefono', 'correo'];
                foreach ($modalRequiredFields as $field) {
                    if (empty($_POST[$field])) {
                        $missingFields[] = $field;
                    }
                }
                
                if (!empty($missingFields)) {
                    $errorMessage = 'Los siguientes campos son obligatorios: ' . implode(', ', $missingFields);
                    
                    if ($this->isAjaxRequest()) {
                        $this->sendJsonResponse(false, $errorMessage);
                        return;
                    } else {
                        $this->loadPartialView('school/createSchool', [
                            'error' => $errorMessage,
                            'formData' => $_POST,
                            'directors' => $this->schoolModel->getDirectors(),
                            'coordinators' => $this->schoolModel->getCoordinators()
                        ]);
                        return;
                    }
                }
                
                // Validar formato de email
                if (!empty($_POST['correo']) && !filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL)) {
                    $errorMessage = 'El formato del email no es válido';
                    
                    if ($this->isAjaxRequest()) {
                        $this->sendJsonResponse(false, $errorMessage);
                        return;
                    } else {
                        $this->loadPartialView('school/createSchool', [
                            'error' => $errorMessage,
                            'formData' => $_POST,
                            'directors' => $this->schoolModel->getDirectors(),
                            'coordinators' => $this->schoolModel->getCoordinators()
                        ]);
                        return;
                    }
                }
                
                // Validar que se haya seleccionado un director
                if (empty($_POST['director_user_id'])) {
                    $errorMessage = 'Debe seleccionar un director para la escuela';
                    
                    if ($this->isAjaxRequest()) {
                        $this->sendJsonResponse(false, $errorMessage);
                        return;
                    } else {
                        $this->loadPartialView('school/createSchool', [
                            'error' => $errorMessage,
                            'formData' => $_POST,
                            'directors' => $this->schoolModel->getDirectors(),
                            'coordinators' => $this->schoolModel->getCoordinators()
                        ]);
                        return;
                    }
                }
                
                // Preparar los datos mapeando correctamente los campos del modal
                $data = [
                    'school_name' => trim($_POST['school_name']),
                    'school_dane' => trim($_POST['school_dane']),
                    'school_document' => trim($_POST['school_document']),
                    'total_quota' => intval($_POST['total_quota'] ?? 0),
                    'director_user_id' => intval($_POST['director_user_id']),
                    'coordinator_user_id' => !empty($_POST['coordinator_user_id']) ? intval($_POST['coordinator_user_id']) : null,
                    'address' => trim($_POST['direccion'] ?? ''), // Mapear desde el modal
                    'phone' => trim($_POST['telefono'] ?? ''), // Mapear desde el modal
                    'email' => trim($_POST['correo'] ?? '') // Mapear desde el modal
                ];
                
                // Construir dirección completa con departamento y municipio
                if (!empty($_POST['departamento']) && !empty($_POST['municipio'])) {
                    $data['address'] = trim($_POST['direccion'] ?? '') . ', ' . 
                                     trim($_POST['municipio']) . ', ' . 
                                     trim($_POST['departamento']);
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
                        $this->loadPartialView('school/createSchool', [
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
                    $this->loadPartialView('school/createSchool', [
                        'error' => $errorMessage,
                        'formData' => $_POST,
                        'directors' => $this->schoolModel->getDirectors(),
                        'coordinators' => $this->schoolModel->getCoordinators()
                    ]);
                }
            }
        } else {
            // Si es GET, mostrar el formulario
            $this->loadPartialView('school/createSchool', [
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
        
        $this->loadPartialView('school/consultSchool', [
            'schools' => $schools,
            'search' => $search,
            'success' => $success,
            'message' => $message
        ]);
    }

    public function view()
    {
        $this->protectSchool();
        
        $schoolId = $_GET['id'] ?? null;
        
        if (!$schoolId) {
            $this->loadPartialView('school/consultSchool', [
                'error' => 'ID de escuela no proporcionado',
                'schools' => $this->schoolModel->getAllSchools()
            ]);
            return;
        }
        
        $school = $this->schoolModel->getSchoolById($schoolId);
        
        if (!$school) {
            $this->loadPartialView('school/consultSchool', [
                'error' => 'Escuela no encontrada',
                'schools' => $this->schoolModel->getAllSchools()
            ]);
            return;
        }
        
        $this->loadPartialView('school/viewSchool', [
            'school' => $school
        ]);
    }

    public function edit()
    {
        $this->protectSchool();
        
        $schoolId = $_GET['id'] ?? null;
        
        if (!$schoolId) {
            $this->loadPartialView('school/consultSchool', [
                'error' => 'ID de escuela no proporcionado',
                'schools' => $this->schoolModel->getAllSchools()
            ]);
            return;
        }
        
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
                    
                    if ($this->isAjaxRequest()) {
                        $this->sendJsonResponse(false, $errorMessage);
                        return;
                    } else {
                        $school = $this->schoolModel->getSchoolById($schoolId);
                        $this->loadPartialView('school/editSchool', [
                            'error' => $errorMessage,
                            'school' => $school,
                            'formData' => $_POST,
                            'directors' => $this->schoolModel->getDirectors(),
                            'coordinators' => $this->schoolModel->getCoordinators()
                        ]);
                        return;
                    }
                }
                
                // Validar formato de email
                if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $errorMessage = 'El formato del email no es válido';
                    
                    if ($this->isAjaxRequest()) {
                        $this->sendJsonResponse(false, $errorMessage);
                        return;
                    } else {
                        $school = $this->schoolModel->getSchoolById($schoolId);
                        $this->loadPartialView('school/editSchool', [
                            'error' => $errorMessage,
                            'school' => $school,
                            'formData' => $_POST,
                            'directors' => $this->schoolModel->getDirectors(),
                            'coordinators' => $this->schoolModel->getCoordinators()
                        ]);
                        return;
                    }
                }
                
                // Preparar los datos
                $data = [
                    'school_id' => $schoolId,
                    'school_name' => trim($_POST['school_name']),
                    'school_dane' => trim($_POST['school_dane']),
                    'school_document' => trim($_POST['school_document']),
                    'total_quota' => intval($_POST['total_quota'] ?? 0),
                    'director_user_id' => intval($_POST['director_user_id']),
                    'coordinator_user_id' => !empty($_POST['coordinator_user_id']) ? intval($_POST['coordinator_user_id']) : null,
                    'address' => trim($_POST['address'] ?? ''),
                    'phone' => trim($_POST['phone'] ?? ''),
                    'email' => trim($_POST['email'] ?? '')
                ];
                
                // Llamar al método del modelo para actualizar
                $result = $this->schoolModel->updateSchool($data);
                
                if ($result) {
                    // Éxito
                    if ($this->isAjaxRequest()) {
                        $this->sendJsonResponse(true, 'Escuela actualizada exitosamente');
                    } else {
                        // Redirigir a la lista de escuelas con mensaje de éxito
                        $this->redirect('?view=school&action=consultSchool&success=1&msg=' . urlencode('Escuela actualizada exitosamente'));
                    }
                } else {
                    $errorMessage = 'Error al actualizar la escuela. Verifique que los datos sean únicos.';
                    
                    if ($this->isAjaxRequest()) {
                        $this->sendJsonResponse(false, $errorMessage);
                    } else {
                        $school = $this->schoolModel->getSchoolById($schoolId);
                        $this->loadPartialView('school/editSchool', [
                            'error' => $errorMessage,
                            'school' => $school,
                            'formData' => $_POST,
                            'directors' => $this->schoolModel->getDirectors(),
                            'coordinators' => $this->schoolModel->getCoordinators()
                        ]);
                    }
                }
                
            } catch (Exception $e) {
                error_log("Error en SchoolController::edit: " . $e->getMessage());
                
                $errorMessage = 'Error interno del servidor: ' . $e->getMessage();
                
                if ($this->isAjaxRequest()) {
                    $this->sendJsonResponse(false, $errorMessage);
                } else {
                    $school = $this->schoolModel->getSchoolById($schoolId);
                    $this->loadPartialView('school/editSchool', [
                        'error' => $errorMessage,
                        'school' => $school,
                        'formData' => $_POST,
                        'directors' => $this->schoolModel->getDirectors(),
                        'coordinators' => $this->schoolModel->getCoordinators()
                    ]);
                }
            }
        } else {
            // Si es GET, mostrar el formulario de edición
            $school = $this->schoolModel->getSchoolById($schoolId);
            
            if (!$school) {
                $this->loadPartialView('school/consultSchool', [
                    'error' => 'Escuela no encontrada',
                    'schools' => $this->schoolModel->getAllSchools()
                ]);
                return;
            }
            
            $this->loadPartialView('school/editSchool', [
                'school' => $school,
                'directors' => $this->schoolModel->getDirectors(),
                'coordinators' => $this->schoolModel->getCoordinators()
            ]);
        }
    }

    public function delete()
    {
        $this->protectSchool();
        
        $schoolId = $_GET['id'] ?? null;
        
        if (!$schoolId) {
            if ($this->isAjaxRequest()) {
                $this->sendJsonResponse(false, 'ID de escuela no proporcionado');
            } else {
                $this->loadPartialView('school/consultSchool', [
                    'error' => 'ID de escuela no proporcionado',
                    'schools' => $this->schoolModel->getAllSchools()
                ]);
            }
            return;
        }
        
        try {
            $result = $this->schoolModel->deleteSchool($schoolId);
            
            if ($result) {
                if ($this->isAjaxRequest()) {
                    $this->sendJsonResponse(true, 'Escuela eliminada exitosamente');
                } else {
                    $this->redirect('?view=school&action=consultSchool&success=1&msg=' . urlencode('Escuela eliminada exitosamente'));
                }
            } else {
                if ($this->isAjaxRequest()) {
                    $this->sendJsonResponse(false, 'Error al eliminar la escuela');
                } else {
                    $this->loadPartialView('school/consultSchool', [
                        'error' => 'Error al eliminar la escuela',
                        'schools' => $this->schoolModel->getAllSchools()
                    ]);
                }
            }
        } catch (Exception $e) {
            error_log("Error en SchoolController::delete: " . $e->getMessage());
            
            if ($this->isAjaxRequest()) {
                $this->sendJsonResponse(false, 'Error interno del servidor: ' . $e->getMessage());
            } else {
                $this->loadPartialView('school/consultSchool', [
                    'error' => 'Error interno del servidor: ' . $e->getMessage(),
                    'schools' => $this->schoolModel->getAllSchools()
                ]);
            }
        }
    }

    /**
     * Carga una vista parcial vía AJAX para el módulo school
     */
    public function loadPartial()
    {
        $view = $_POST['view'] ?? $_GET['view'] ?? '';
        $action = $_POST['action'] ?? $_GET['action'] ?? 'index';
        $partialView = $_POST['partialView'] ?? $_GET['partialView'] ?? '';
        $force = isset($_POST['force']) || isset($_GET['force']);

        if (!$this->isAjaxRequest() && !$force) {
            if (empty($partialView)) {
                echo '<div class="alert alert-warning">Vista no especificada. Use: ?view=school&action=loadPartial&partialView=vista</div>';
                return;
            }
            $viewPath = 'school/' . $partialView;
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
        $viewPath = 'school/' . $partialView;
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

    // Protección de acceso solo para escuela o tesorero
    private function protectSchool() {
        if (!isset($this->sessionManager) || !$this->sessionManager->isLoggedIn() || !$this->sessionManager->hasAnyRole(['director', 'coordinator', 'treasurer', 'root'])) {
            header('Location: /?view=unauthorized');
            exit;
        }
    }
}
