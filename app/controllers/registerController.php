<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname((__DIR__))));
}
require_once ROOT . '/config.php';
require_once 'mainController.php';

class RegisterController extends MainController
{
    protected $dbConn;

    public function __construct($dbConn)
    {
        parent::__construct($dbConn);
        $this->dbConn = $dbConn;
    }

    public function index()
    {
        // Si ya está logueado, redirigir a su dashboard
        if ($this->sessionManager->isLoggedIn()) {
            $userRole = $this->sessionManager->getUserRole();
            header('Location: /' . $userRole . '/dashboard');
            exit;
        }
        require_once app . views . 'index/register.php';
    }

    public function registerUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Recolectar solo los datos que se envían desde el formulario
            $data = [
                'credential_type' => $_POST['credType'] ?? '',
                'credential_number' => $_POST['userDocument'] ?? '',
                'email' => $_POST['userEmail'] ?? '',
                'password' => $_POST['userPassword'] ?? '',
                // Campos obligatorios que se completarán después
                'first_name' => '',  // Se completará en completeProf.php
                'last_name' => '',   // Se completará en completeProf.php
                'date_of_birth' => date('Y-m-d'), // Fecha por defecto, se actualizará después
                'phone' => null,
                'address' => null
            ];

            // Validar campos obligatorios del formulario
            if (empty($data['credential_type']) || empty($data['credential_number']) || 
                empty($data['email']) || empty($data['password'])) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Todos los campos del formulario son obligatorios'
                ]);
                return;
            }

            // 2. Llamar al modelo dentro de un try-catch
            try {
                require_once ROOT . '/app/models/userModel.php';
                $userModel = new UserModel();
                $success = $userModel->createUser($data);

                if ($success) {
                    echo json_encode([
                        'status' => 'ok',
                        'msg' => 'Usuario registrado exitosamente. Complete su perfil.'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'msg' => 'No se pudo registrar el usuario'
                    ]);
                }
            } catch (Exception $e) {
                // Aquí capturamos el error del modelo
                echo json_encode([
                    'status' => 'error',
                    'msg' => $e->getMessage()
                ]);
            }
        }
    }

    public function completeProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recolectar datos del formulario de completar perfil
            $data = [
                'credential_number' => $_POST['userDocument'] ?? '',
                'first_name' => $_POST['userName'] ?? '',
                'last_name' => $_POST['lastnameUser'] ?? '',
                'date_of_birth' => $_POST['dob'] ?? '',
                'phone' => $_POST['userPhone'] ?? '',
                'address' => $_POST['addressUser'] ?? ''
            ];

            // Validar campos obligatorios
            if (empty($data['credential_number']) || empty($data['first_name']) || 
                empty($data['last_name']) || empty($data['date_of_birth']) || 
                empty($data['phone']) || empty($data['address'])) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Todos los campos son obligatorios para completar el perfil'
                ]);
                return;
            }

            try {
                require_once ROOT . '/app/models/userModel.php';
                $userModel = new UserModel();
                $success = $userModel->completeProfile($data);

                if ($success) {
                    echo json_encode([
                        'status' => 'ok',
                        'msg' => 'Perfil completado exitosamente'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'msg' => 'No se pudo completar el perfil'
                    ]);
                }
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => $e->getMessage()
                ]);
            }
        }
    }

    public function getUser($userId)
    {
        require_once ROOT . '/app/models/userModel.php';
        $userModel = new UserModel();
        return $userModel->getUser($userId);
    }
}
