<?php
require_once __DIR__ . '/../models/SchoolModel.php';

require_once __DIR__ . '/MainController.php';
class SchoolController extends MainController
{
    protected $schoolModel;

    public function __construct($dbConn)
    {
        // Asegurar que session_start() solo se llame una vez y antes de cualquier salida
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
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
                    if (empty(htmlspecialchars($_POST[$field]))) {
                        $missingFields[] = $field;
                    }
                }
                
                // Validar campos obligatorios del modal
                $modalRequiredFields = ['departamento', 'municipio', 'direccion', 'telefono', 'correo'];
                foreach ($modalRequiredFields as $field) {
                    if (empty(htmlspecialchars($_POST[$field]))) {
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
                if (!empty(htmlspecialchars($_POST['correo'])) && !filter_var(htmlspecialchars($_POST['correo']), FILTER_VALIDATE_EMAIL)) {
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
                if (empty(htmlspecialchars($_POST['director_user_id']))) {
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
                    'school_name' => trim(htmlspecialchars($_POST['school_name'])),
                    'school_dane' => trim(htmlspecialchars($_POST['school_dane'])),
                    'school_document' => trim(htmlspecialchars($_POST['school_document'])),
                    'total_quota' => intval(htmlspecialchars($_POST['total_quota']) ?? 0),
                    'director_user_id' => intval(htmlspecialchars($_POST['director_user_id'])),
                    'coordinator_user_id' => !empty(htmlspecialchars($_POST['coordinator_user_id'])) ? intval(htmlspecialchars($_POST['coordinator_user_id'])) : null,
                    'address' => trim(htmlspecialchars($_POST['direccion']) ?? ''), // Mapear desde el modal
                    'phone' => trim(htmlspecialchars($_POST['telefono']) ?? ''), // Mapear desde el modal
                    'email' => trim(htmlspecialchars($_POST['correo']) ?? '') // Mapear desde el modal
                ];
                
                // Construir dirección completa con departamento y municipio
                if (!empty(htmlspecialchars($_POST['departamento'])) && !empty(htmlspecialchars($_POST['municipio']))) {
                    $data['address'] = trim(htmlspecialchars($_POST['direccion']) ?? '') . ', ' . 
                                     trim(htmlspecialchars($_POST['municipio'])) . ', ' . 
                                     trim(htmlspecialchars($_POST['departamento']));
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
        
        $search = htmlspecialchars($_GET['search'] ?? '');
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
        $success = htmlspecialchars($_GET['success'] ?? false);
        $message = htmlspecialchars($_GET['msg'] ?? '');
        
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
        
        $schoolId = htmlspecialchars($_GET['id']) ?? null;
        
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
        
        $schoolId = htmlspecialchars($_GET['id']) ?? null;
        
        // Debug: Log de la petición
        error_log("SchoolController::edit - Método: " . $_SERVER['REQUEST_METHOD']);
        error_log("SchoolController::edit - isAjaxRequest: " . ($this->isAjaxRequest() ? 'true' : 'false'));
        error_log("SchoolController::edit - HTTP_X_REQUESTED_WITH: " . ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? 'no definido'));
        
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
                    if (empty(htmlspecialchars($_POST[$field]))) {
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
                if (!empty(htmlspecialchars($_POST['email'])) && !filter_var(htmlspecialchars($_POST['email']), FILTER_VALIDATE_EMAIL)) {
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
                    'school_name' => trim(htmlspecialchars($_POST['school_name'])),
                    'school_dane' => trim(htmlspecialchars($_POST['school_dane'])),
                    'school_document' => trim(htmlspecialchars($_POST['school_document'])),
                    'total_quota' => intval(htmlspecialchars($_POST['total_quota']) ?? 0),
                    'director_user_id' => intval(htmlspecialchars($_POST['director_user_id'])),
                    'coordinator_user_id' => !empty(htmlspecialchars($_POST['coordinator_user_id'])) ? intval(htmlspecialchars($_POST['coordinator_user_id'])) : null,
                    'address' => trim(htmlspecialchars($_POST['address']) ?? ''),
                    'phone' => trim(htmlspecialchars($_POST['phone']) ?? ''),
                    'email' => trim(htmlspecialchars($_POST['email']) ?? '')
                ];
                
                // Debug: Log antes de actualizar
                error_log("SchoolController::edit - Antes de actualizar, isAjaxRequest: " . ($this->isAjaxRequest() ? 'true' : 'false'));
                
                // Llamar al método del modelo para actualizar
                $result = $this->schoolModel->updateSchool($data);
                
                if ($result) {
                    // Éxito
                    error_log("SchoolController::edit - Actualización exitosa, isAjaxRequest: " . ($this->isAjaxRequest() ? 'true' : 'false'));
                    
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
        
        $schoolId = htmlspecialchars($_GET['id']) ?? null;
        
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

    // Protección de acceso solo para escuela o tesorero
    private function protectSchool() {
        if (!isset($this->sessionManager) || !$this->sessionManager->isLoggedIn() || !$this->sessionManager->hasAnyRole(['director', 'coordinator', 'treasurer', 'root'])) {
            header('Location: /?view=unauthorized');
            exit;
        }
    }

    public function searchDirectorAjax()
{
    $this->protectSchool();

    header('Content-Type: application/json');

    $document = trim(htmlspecialchars($_POST['document'] ?? ''));

    if (empty($document)) {
        echo json_encode([
            'status' => 'error',
            'msg' => 'No se proporcionó el documento de búsqueda'
        ]);
        return;
    }

    try {
        $results = $this->schoolModel->searchDirectorsByDocument($document);

        echo json_encode([
            'status' => 'ok',
            'data' => $results
        ]);
    } catch (Exception $e) {
        error_log("Error en searchDirectorAjax: " . $e->getMessage());

        echo json_encode([
            'status' => 'error',
            'msg' => 'Error en el servidor: ' . $e->getMessage()
        ]);
    }
}

}
