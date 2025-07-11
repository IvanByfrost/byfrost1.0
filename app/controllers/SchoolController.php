<?php
require_once __DIR__ . '/../models/SchoolModel.php';
require_once __DIR__ . '/MainController.php';

class SchoolController extends MainController
{
    protected $schoolModel;

    public function __construct($dbConn)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct($dbConn);
        $this->schoolModel = new SchoolModel();
    }

    public function index()
    {
        $this->protectSchool();
        $this->render('school', 'dashboard');
    }

    public function createSchool()
    {
        $this->protectSchool();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar CSRF
            if (!$this->validateCsrfToken($_POST['csrf_token'] ?? null)) {
                $this->sendJsonResponse(false, 'Token CSRF inválido.');
                return;
            }

            try {
                $missingFields = $this->checkRequiredFields([
                    'school_name',
                    'school_dane',
                    'school_document',
                    'departamento',
                    'municipio',
                    'direccion',
                    'telefono',
                    'correo',
                    'director_user_id'
                ]);

                if (!empty($missingFields)) {
                    $errorMessage = 'Los siguientes campos son obligatorios: ' . implode(', ', $missingFields);
                    $this->handleErrorResponse($errorMessage, 'school/createSchool');
                    return;
                }

                // Validar formato de correo
                if (!filter_var($this->clean($_POST['correo']), FILTER_VALIDATE_EMAIL)) {
                    $errorMessage = 'El formato del correo electrónico no es válido.';
                    $this->handleErrorResponse($errorMessage, 'school/createSchool');
                    return;
                }

                $data = [
                    'school_name'          => $this->clean($_POST['school_name']),
                    'school_dane'          => $this->clean($_POST['school_dane']),
                    'school_document'      => $this->clean($_POST['school_document']),
                    'total_quota'          => intval($_POST['total_quota'] ?? 0),
                    'director_user_id'     => intval($_POST['director_user_id']),
                    'coordinator_user_id'  => !empty($_POST['coordinator_user_id']) ? intval($_POST['coordinator_user_id']) : null,
                    'email'                => $this->clean($_POST['correo']),
                    'phone'                => $this->clean($_POST['telefono']),
                ];

                // Construir dirección completa
                $data['address'] =
                    $this->clean($_POST['direccion']) . ', ' .
                    $this->clean($_POST['municipio']) . ', ' .
                    $this->clean($_POST['departamento']);

                $schoolId = $this->schoolModel->createSchool($data);

                if ($schoolId) {
                    $this->sendJsonResponse(true, 'Escuela creada exitosamente', ['school_id' => $schoolId]);
                } else {
                    $errorMessage = 'Error al crear la escuela. Verifique que los datos sean únicos.';
                    $this->handleErrorResponse($errorMessage, 'school/createSchool');
                }

            } catch (Exception $e) {
                error_log("Error en SchoolController::createSchool: " . $e->getMessage());
                $this->handleErrorResponse('Error interno del servidor: ' . $e->getMessage(), 'school/createSchool');
            }
        } else {
            $this->loadPartialView('school/createSchool', [
                'directors'    => $this->schoolModel->getDirectors(),
                'coordinators' => $this->schoolModel->getCoordinators(),
            ]);
        }
    }

    public function consultSchool()
    {
        $this->protectSchool();

        $search = $this->clean($_GET['search'] ?? '');
        $schools = $this->schoolModel->getAllSchools();

        if (!empty($search)) {
            $schools = array_filter($schools, function ($school) use ($search) {
                return stripos($school['school_name'], $search) !== false ||
                       stripos($school['school_dane'], $search) !== false ||
                       stripos($school['school_document'], $search) !== false;
            });
        }

        $success = $this->clean($_GET['success'] ?? false);
        $message = $this->clean($_GET['msg'] ?? '');

        $this->loadPartialView('school/consultSchool', [
            'schools'  => $schools,
            'search'   => $search,
            'success'  => $success,
            'message'  => $message
        ]);
    }

    public function view()
    {
        $this->protectSchool();

        $schoolId = $this->clean($_GET['id'] ?? null);

        if (!$schoolId) {
            $this->loadPartialView('school/consultSchool', [
                'error'   => 'ID de escuela no proporcionado',
                'schools' => $this->schoolModel->getAllSchools()
            ]);
            return;
        }

        $school = $this->schoolModel->getSchoolById($schoolId);

        if (!$school) {
            $this->loadPartialView('school/consultSchool', [
                'error'   => 'Escuela no encontrada',
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

        $schoolId = $this->clean($_GET['id'] ?? null);

        if (!$schoolId) {
            $this->loadPartialView('school/consultSchool', [
                'error'   => 'ID de escuela no proporcionado',
                'schools' => $this->schoolModel->getAllSchools()
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar CSRF
            if (!$this->validateCsrfToken($_POST['csrf_token'] ?? null)) {
                $this->sendJsonResponse(false, 'Token CSRF inválido.');
                return;
            }

            try {
                $missingFields = $this->checkRequiredFields([
                    'school_name',
                    'school_dane',
                    'school_document'
                ]);

                if (!empty($missingFields)) {
                    $errorMessage = 'Los siguientes campos son obligatorios: ' . implode(', ', $missingFields);
                    $this->handleErrorResponse($errorMessage, 'school/editSchool', $schoolId);
                    return;
                }

                if (!empty($_POST['email']) &&
                    !filter_var($this->clean($_POST['email']), FILTER_VALIDATE_EMAIL)) {
                    $errorMessage = 'El formato del email no es válido.';
                    $this->handleErrorResponse($errorMessage, 'school/editSchool', $schoolId);
                    return;
                }

                $data = [
                    'school_id'           => $schoolId,
                    'school_name'         => $this->clean($_POST['school_name']),
                    'school_dane'         => $this->clean($_POST['school_dane']),
                    'school_document'     => $this->clean($_POST['school_document']),
                    'total_quota'         => intval($_POST['total_quota'] ?? 0),
                    'director_user_id'    => intval($_POST['director_user_id']),
                    'coordinator_user_id' => !empty($_POST['coordinator_user_id']) ? intval($_POST['coordinator_user_id']) : null,
                    'address'             => $this->clean($_POST['address']),
                    'phone'               => $this->clean($_POST['phone']),
                    'email'               => $this->clean($_POST['email']),
                ];

                $result = $this->schoolModel->updateSchool($data);

                if ($result) {
                    $this->sendJsonResponse(true, 'Escuela actualizada exitosamente');
                } else {
                    $errorMessage = 'Error al actualizar la escuela. Verifique que los datos sean únicos.';
                    $this->handleErrorResponse($errorMessage, 'school/editSchool', $schoolId);
                }

            } catch (Exception $e) {
                error_log("Error en SchoolController::edit: " . $e->getMessage());
                $this->handleErrorResponse('Error interno del servidor: ' . $e->getMessage(), 'school/editSchool', $schoolId);
            }
        } else {
            $school = $this->schoolModel->getSchoolById($schoolId);
            if (!$school) {
                $this->loadPartialView('school/consultSchool', [
                    'error'   => 'Escuela no encontrada',
                    'schools' => $this->schoolModel->getAllSchools()
                ]);
                return;
            }

            $this->loadPartialView('school/editSchool', [
                'school'       => $school,
                'directors'    => $this->schoolModel->getDirectors(),
                'coordinators' => $this->schoolModel->getCoordinators()
            ]);
        }
    }

    public function delete()
    {
        $this->protectSchool();

        $schoolId = $this->clean($_GET['id'] ?? null);

        if (!$schoolId) {
            $this->handleErrorResponse('ID de escuela no proporcionado', 'school/consultSchool');
            return;
        }

        try {
            $result = $this->schoolModel->deleteSchool($schoolId);

            if ($result) {
                $this->sendJsonResponse(true, 'Escuela eliminada exitosamente');
            } else {
                $this->handleErrorResponse('Error al eliminar la escuela.', 'school/consultSchool');
            }

        } catch (Exception $e) {
            error_log("Error en SchoolController::delete: " . $e->getMessage());
            $this->handleErrorResponse('Error interno del servidor: ' . $e->getMessage(), 'school/consultSchool');
        }
    }

    public function searchDirectorAjax()
    {
        $this->protectSchool();

        header('Content-Type: application/json');

        $document = $this->clean($_POST['document'] ?? '');

        if (empty($document)) {
            echo json_encode([
                'status' => 'error',
                'msg'    => 'No se proporcionó el documento de búsqueda'
            ]);
            return;
        }

        try {
            $results = $this->schoolModel->searchDirectorsByDocument($document);

            echo json_encode([
                'status' => 'ok',
                'data'   => $results
            ]);
        } catch (Exception $e) {
            error_log("Error en searchDirectorAjax: " . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'msg'    => 'Error en el servidor: ' . $e->getMessage()
            ]);
        }
    }

    private function protectSchool()
    {
        if (!isset($this->sessionManager) ||
            !$this->sessionManager->isLoggedIn() ||
            !$this->sessionManager->hasAnyRole(['director', 'coordinator', 'treasurer', 'root'])) {
            header('Location: /?view=unauthorized');
            exit;
        }
    }

    private function clean($value)
    {
        return trim(htmlspecialchars($value ?? ''));
    }

    private function checkRequiredFields(array $fields)
    {
        $missing = [];
        foreach ($fields as $field) {
            if (empty($_POST[$field])) {
                $missing[] = $field;
            }
        }
        return $missing;
    }

    private function validateCsrfToken($token)
    {
        return !empty($token) && $token === ($_SESSION['csrf_token'] ?? null);
    }

    private function handleErrorResponse($message, $view, $schoolId = null)
    {
        if ($this->isAjaxRequest()) {
            $this->sendJsonResponse(false, $message);
        } else {
            $data = [
                'error'        => $message,
                'formData'     => $_POST,
                'directors'    => $this->schoolModel->getDirectors(),
                'coordinators' => $this->schoolModel->getCoordinators()
            ];

            if ($schoolId) {
                $data['school'] = $this->schoolModel->getSchoolById($schoolId);
            }

            $this->loadPartialView($view, $data);
        }
    }
}
