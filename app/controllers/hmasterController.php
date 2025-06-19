<?php
// software_academico/app/controllers/RectorController.php

require_once 'hmasterModel.php';

class hmasterController {
    private $hmasterController;

    public function __construct() {
        $this->hmasterController = new hmasterController();
    }

    // Acción por defecto: Lista de rectores
    public function headMasterList() {
        $this->listAction();
    }

    // Mostrar la lista de rectores
    public function listAction() {
        $HeadMaster = $this->hmasterController->getAllRectores();
        // Cargar la vista
        require_once app.views . 'headMaster/hmasterLists.php';
    }

    // Mostrar el formulario para agregar un nuevo rector
    public function newHeadMaster() {
        // Cargar la vista del formulario
        require_once app.views . 'headMaster/createHmaster.php';
    }

    // Procesar la adición de un nuevo rector
    public function addHeadMaster() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userName = $_POST['userName'] ?? '';
            $userLastName = $_POST['userLastName'] ?? '';
            $userEmail = $_POST['email'] ?? '';
            $userPassword = $_POST['password'] ?? ''; // La contraseña en texto plano
            $phoneUser = $_POST['phoneUser'] ?? null;

            // Validar y hashear la contraseña antes de guardarla
            if (!empty($userPassword)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            } else {
                // Manejar error o asignar un valor por defecto si la contraseña es obligatoria
                echo "Error: La contraseña es obligatoria.";
                return;
            }

            if ($this->rectorModel->createRector($userName, $userLastName, $userEmail, $hashedPassword, $phoneUser)) {
                header('Location: /software_academico/rector/listar'); // Redirigir a la lista
                exit();
            } else {
                echo "Error al guardar el rector.";
            }
        } else {
            header('Location: /software_academico/rector/crear'); // Si no es POST, redirigir al formulario
            exit();
        }
    }

    // Mostrar el formulario para editar un rector existente
    public function editarAction() {
        $id = $_GET['id'] ?? null; // Obtener ID de la URL
        if ($id) {
            $rector = $this->rectorModel->getRectorById($id);
            if ($rector) {
                require_once  . 'rector/editar.php';
            } else {
                echo "Rector no encontrado.";
            }
        } else {
            echo "ID de rector no proporcionado.";
        }
    }

    // Procesar la actualización de un rector
    public function actualizarAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_rector'] ?? null;
            $nombre = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $email = $_POST['email'] ?? '';
            $telefono = $_POST['telefono'] ?? null;

            if ($id && $this->rectorModel->updateRector($id, $nombre, $apellido, $email, $telefono)) {
                header('Location: /software_academico/rector/listar');
                exit();
            } else {
                echo "Error al actualizar el rector.";
            }
        } else {
            header('Location: /software_academico/rector/listar'); // Redirigir si no es POST
            exit();
        }
    }

    // Eliminar un rector
    public function eliminarAction() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            if ($this->rectorModel->deleteRector($id)) {
                header('Location: /software_academico/rector/listar');
                exit();
            } else {
                echo "Error al eliminar el rector.";
            }
        } else {
            echo "ID de rector no proporcionado para eliminar.";
        }
    }
}
?>