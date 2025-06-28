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
        //1. Captar los datos del formulario.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $requiredFields = ['school_name', 'school_dane', 'school_document'];

            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    echo json_encode([
                        'status' => 'error',
                        'msg' => "El campo $field es obligatorio"
                    ]);
                    return;
                }
            }

            // Prerarar los datos
            $data = [
                'school_name' => $_POST['school_name'],
                'school_dane' => $_POST['school_dane'],
                'school_document' => $_POST['school_document'],
                //'school_name' => $_POST['school_name'],
            ];
            //2. Llamar al método del modelo. 
            try {
                $success = $this->schoolModel->createSchool($data);
                if ($success) {
                    echo json_encode([
                        'status' => 'success',
                        'msg' => 'Colegio creado exitosamente'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'msg' => 'Error al crear el colegio'
                    ]);
                }
            } catch (Exception $e) {
            }

            //3. Redirigir a la vista de éxito o error.
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
