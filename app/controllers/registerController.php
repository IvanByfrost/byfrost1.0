<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname((__DIR__))));
}
include_once ROOT . '/app/scripts/connection.php';
require_once 'mainController.php';
class registerController extends mainController
{
    protected $dbConn;
    public function index()
    {
        require_once 'app/views/index/register.php';
    }

    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
    }

    public function registerUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Recolectar y validar los datos
            $data = [
                'credential_type' => $_POST['credType'] ?? '',
                'credential_number' => $_POST['userDocument'] ?? '',
                'first_name' => $_POST['firstName'] ?? '',
                'last_name' => $_POST['lastName'] ?? '',
                'date_of_birth' => $_POST['dateOfBirth'] ?? '',
                'email' => $_POST['userEmail'] ?? '',
                'password' => $_POST['userPassword'] ?? '',
                'phone' => $_POST['userPhone'] ?? '',
                'address' => $_POST['userAddress'] ?? ''
            ];

            // Validar campos obligatorios
            $requiredFields = ['credential_number', 'first_name', 'last_name', 'date_of_birth', 'email', 'password'];
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    echo json_encode([
                        'status' => 'error',
                        'msg' => 'Todos los campos obligatorios deben estar completos'
                    ]);
                    return;
                }
            }

            // 2. Llamar al modelo dentro de un try-catch
            try {
                require_once ROOT . '/app/models/userModel.php';
                $userModel = new UserModel();
                $success = $userModel->createUser($data);

                if ($success) {
                    echo json_encode([
                        'status' => 'ok',
                        'msg' => 'Usuario registrado exitosamente'
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

    public function getUser()
    {
        require_once 'app/models/userModel.php';
        $userModel = new userModel();
        $success = $userModel->getUser($data);
    }
}
