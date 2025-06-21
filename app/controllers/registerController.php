<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname((__DIR__))));
}
include_once ROOT.'/app/scripts/connection.php';
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

            // 2. Llamar al modelo
            require_once ROOT.'/app/models/userModel.php';
            $userModel = new userModel($this->dbConn);
            $success = $userModel->createUser($data);

            // 3. Devolver respuesta
            if ($success) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'ok',
                    'msg' => 'Usuario registrado exitosamente'
                ]);
                exit;
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'No se pudo registrar el usuario'
                ]);
                exit;
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
