<?php
require_once 'app/models/hmasterModel.php';
require_once 'app/controllers/mainController.php';

class hmasterController extends MainController {
    protected $dbConn;
    protected $view;
    public function __construct($dbConn, $view)
    {
        $this->dbConn = $dbConn;
        $this->view = $view;
    }

    // Acción por defecto: Lista de rectores
    public function headMasterList() {
        $this->listAction();
    }

    // Mostrar la lista de rectores
    public function listAction() {
        $headMaster = $this->dbConn->getAllRectores();
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
                $hashedPassword = password_hash($userPassword, PASSWORD_DEFAULT);
            } else {
                // Manejar error o asignar un valor por defecto si la contraseña es obligatoria
                echo "Error: La contraseña es obligatoria.";
                return;
            }

            if ($this->dbConn->createHmaster($userName, $userLastName, $userEmail, $hashedPassword, $phoneUser)) {
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
    public function editHeadMaster() {
        $id = $_GET['id'] ?? null; // Obtener ID de la URL
        if ($id) {
            $headMaster = $this->dbConn->getRectorById($id);
            if ($headMaster) {
                require_once app.views. 'rector/editar.php';
            } else {
                echo "Rector no encontrado.";
            }
        } else {
            echo "ID de rector no proporcionado.";
        }
    }

    // Procesar la actualización de un rector
    public function headMasterUpdate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_rector'] ?? null;
            $userName = $_POST['userName'] ?? '';
            $userLastName = $_POST['userLastName'] ?? '';
            $userEmail = $_POST['email'] ?? '';
            $phoneUser = $_POST['phoneUser'] ?? null;

            $userEmail = $_POST['email'] ?? '';
            if ($id && $this->dbConn->updateHeadMaster($id, $userName, $userLastName, $userEmail, $phoneUser)) {
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
    public function deleteHeadMaster() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            if ($this->dbConn->deleteHeadMaster($id)) {
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