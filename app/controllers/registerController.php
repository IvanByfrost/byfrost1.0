<?php
class registerController
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
            'userName' => $_POST['userName'] ?? '',
            'userEmail' => $_POST['userEmail'] ?? '',
            'userPassword' => $_POST['userPassword'] ?? ''
        ];

        if (empty($data['userName']) || empty($data['userEmail']) || empty($data['userPassword'])) {
            echo json_encode([
                'estatus' => 'error',
                'msg' => 'Todos los campos son obligatorios'
            ]);
            return;
        }

        // 2. Llamar al modelo
        require_once 'app/models/userModel.php';
        $userModel = new userModel($this->$dbConn);
        $success = $userModel->createUser($data);

        // 3. Devolver respuesta
        if ($success) {
            echo json_encode([
                'estatus' => 'ok',
                'msg' => 'Usuario registrado exitosamente'
            ]);
        } else {
            echo json_encode([
                'estatus' => 'error',
                'msg' => 'No se pudo registrar el usuario'
            ]);
        }
    }
}

}
