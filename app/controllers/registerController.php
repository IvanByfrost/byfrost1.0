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
                'credType' => $_POST['credType'] ?? '',
                'userDocument' => $_POST['userDocument'] ?? '',
                'userEmail' => $_POST['userEmail'] ?? '',
                'userPassword' => $_POST['userPassword'] ?? ''
            ];

            if (empty($data['userDocument']) || empty($data['userEmail']) || empty($data['userPassword'])) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Todos los campos son obligatorios'
                ]);
                return;
            }

            // 2. Llamar al modelo dentro de un try-catch
            try {
                require_once ROOT . '/app/models/userModel.php';
                $userModel = new userModel($this->dbConn);
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
        $userModel = new userModel($this->$dbConn);
        $success = $userModel->getUser($data);
    }
}
