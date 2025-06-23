<?php
session_start();
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname((__DIR__))));
}
require_once ROOT . '/config.php';
//require ROOT . '/app/scripts/connection.php';
require_once 'mainController.php';
class LoginController extends mainController
{
    protected $dbConn;

    // Constructor de la clase 

    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
    }

    // Muestra el formulario de login
    public function index()
    {
        require_once app . views . 'index/login.php';
    }

    // Procesa el formulario y redirige al dashboard según el rol
    public function authUser()
    {
        //Verifica que se hayan enviado los datos necesarios
        if (empty($_POST['subject']) || empty($_POST['credType']) || empty($_POST['userDocument']) || empty($_POST['userPassword'])) {
            echo json_encode([
                "status" => "error",
                "msg" => "Faltan uno o más datos obligatorios. Revisa el tipo de documento, el número o la contraseña."
            ]);
            exit;
        }
        $subject = $_POST['subject'];
        if ($subject == "login") {
            $credType = $_POST['credType'];
            $userDocument = $_POST['userDocument'];
            $userPassword = $_POST['userPassword'];

            $query = "SELECT u.*, r.roleName AS rol
          FROM mainUser u
          JOIN roles r ON u.roleId = r.roleId
          WHERE u.credType = :credType 
          AND u.userDocument = :userDocument
          LIMIT 1";
            $stmt = $this->dbConn->prepare($query);
            $stmt->execute([
                ':credType'     => $credType,
                ':userDocument'  => $userDocument,
                //':userPassword' => $userPassword  // ¡No olvides este!
            ]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user !== false) {
                if (is_null($user['roleId'])) {
                    echo json_encode([
                        "status" => "error",
                        "msg" => "El usuario no se encuentra activado. Por favor, contacta con Byfrost."
                    ]);
                    exit;
                }

                if (password_verify($userPassword, $user['userPassword'])) {
                    // Contraseña válida, el usuario ha sido autenticado
                    $_SESSION["ByFrost_id"] = $user['userId'];
                    $_SESSION["ByFrost_role"] = $user['rol'];
                    $_SESSION["ByFrost_userName"] = $user['userName'];
                    unset($user['userPassword']);

                    $validRoles = ['root', 'teacher', 'student', 'headmaster', 'coordinator', 'treasurer', 'parent'];
                    $redirectPage = in_array($user['rol'], $validRoles)
                        ? "{$user['rol']}/dashboard.php"
                        : 'login.php';

                    $_SESSION['ByFrost_redirect'] = $redirectPage;

                    echo json_encode([
                        "status" => "ok",
                        "msg" => "Has iniciado sesión",
                        "redirect" => url . "views/index/charger.php"
                    ]);
                    exit;
                }
            }

            // Si no se encontró el usuario o la contraseña es incorrecta
            echo json_encode([
                "status" => "error",
                "msg" => "Credenciales inválidas."
            ]);
            exit;
        }
    }

    // Cierra la sesión y vuelve al login
    public function logout() {}
}
