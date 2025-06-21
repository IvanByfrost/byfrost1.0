<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname((__DIR__))));
}
include ROOT . '/app/scripts/connection.php';
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
    public function auth()
    {
        //Verifica que se hayan enviado los datos necesarios
        if (!isset($_POST['subject'], $_POST['credType'], $_POST['userDocument'], $_POST['userPassword'])) {
            return [
                "status" => "error",
                "msg" => "Faltan datos en la solicitud."
            ];
        }
        $subject = $_POST['subject'];
        if ($subject == "login") {
            $credType = $_POST['credType'];
            $userDocument = $_POST['userDocument'];
            $userPassword = $_POST['userPassword'];

            $query = "SELECT * FROM mainUser 
          WHERE credType = :credType 
          AND userDocument = :documentNum 
          AND userPassword = :passwordUser 
          LIMIT 1";
            $stmt = $this->dbConn->prepare($query);
            $stmt->execute([
                ':credType'     => $credType,
                ':documentNum'  => $userDocument,
                //':passwordUser' => $passwordUser  // ¡No olvides este!
            ]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $counter = count($user);

            if ($user && password_verify($userPassword, $user['userPassword'])) {
                // Contraseña válida, el usuario ha sido autenticado
                session_start();
                $_SESSION["ByFrost_id"] = $user['id'];   // Guarda el ID
                $_SESSION["ByFrost_role"] = $user['rol']; // Guarda el rol en una clave diferente

                // No reveles el hash de la contraseña al cliente
                unset($user['userPassword']);

                return [
                    "status"      => "ok",
                    "msg"         => "¡Bienvenido, " . $user['userName'] . "!",
                    "redirection" => "admin.php",
                    "userData"    => $user // Puedes enviar datos del usuario si es necesario
                ];
            }
        }
    }

    // Cierra la sesión y vuelve al login
    public function logout() {}
}
