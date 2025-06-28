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

    // Protección de acceso solo para escuela o tesorero
    private function protectSchool() {
        if (!isset($this->sessionManager) || !$this->sessionManager->isLoggedIn() || !$this->sessionManager->hasAnyRole(['school', 'treasurer'])) {
            header('Location: /?view=unauthorized');
            exit;
        }
    }
}
