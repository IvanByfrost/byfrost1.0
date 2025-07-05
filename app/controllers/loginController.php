<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname((__DIR__))));
}
require_once ROOT . '/config.php';
require_once 'MainController.php';

class LoginController extends MainController
{
    protected $dbConn;

    // Constructor de la clase 
    public function __construct($dbConn)
    {
        parent::__construct($dbConn);
        $this->dbConn = $dbConn;
    }

    // Muestra el formulario de login
    public function index()
    {
        // Si ya está logueado, redirigir al dashboard correspondiente
        if ($this->sessionManager->isLoggedIn()) {
            $userRole = $this->sessionManager->getUserRole();
            // Usar el formato correcto con ?view= para el sistema de rutas
            $this->redirect(url . "?view=$userRole/dashboard");
        }
        
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

            // Primero verificar si el usuario existe
            $queryCheck = "SELECT COUNT(*) FROM users WHERE credential_type = :credType AND credential_number = :userDocument";
            $stmtCheck = $this->dbConn->prepare($queryCheck);
            $stmtCheck->execute([
                ':credType' => $credType,
                ':userDocument' => $userDocument
            ]);
            $userExists = $stmtCheck->fetchColumn() > 0;

            if (!$userExists) {
                // Usuario no registrado
                echo json_encode([
                    "status" => "not_registered",
                    "msg" => "No tienes una cuenta registrada. ¿Te gustaría crear una?",
                    "redirect" => url . "app/views/index/register.php"
                ]);
                exit;
            }

            $query = "SELECT u.*, r.role_type AS role
          FROM users u
          JOIN user_roles r ON u.user_id = r.user_id
          WHERE u.credential_type = :credType 
          AND u.credential_number = :userDocument
          AND r.is_active = 1
          AND u.is_active = 1
          LIMIT 1";
            $stmt = $this->dbConn->prepare($query);
            $stmt->execute([
                ':credType'     => $credType,
                ':userDocument'  => $userDocument,
            ]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user !== false) {
                if (is_null($user['role'])) {
                    echo json_encode([
                        "status" => "no_role",
                        "msg" => "Tu cuenta aún no tiene un rol asignado. Por favor, contacta al administrador del sistema."
                    ]);
                    exit;
                }

                if (password_verify($userPassword, $user['password_hash'])) {
                    // Contraseña válida, usar SessionManager para iniciar sesión
                    $userData = [
                        'id' => $user['user_id'],
                        'email' => $user['email'] ?? $user['credential_number'] . '@byfrost.com',
                        'role' => $user['role'],
                        'first_name' => $user['first_name'],
                        'last_name' => $user['last_name']
                    ];
                    
                    $loginSuccess = $this->sessionManager->login($userData);
                    
                    if ($loginSuccess) {
                        unset($user['password_hash']);

                        // Guardar el rol en la sesión para que charger.php sepa a dónde redirigir
                        $this->sessionManager->setSessionData('ByFrost_redirect', $user['role'] . '&action=dashboard');

                        echo json_encode([
                            "status" => "ok",
                            "msg" => "Has iniciado sesión",
                            "redirect" => url . "app/views/index/charger.php"
                        ]);
                        exit;
                    } else {
                        echo json_encode([
                            "status" => "error",
                            "msg" => "Error al iniciar sesión. Inténtalo de nuevo."
                        ]);
                        exit;
                    }
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
    public function logout()
    {
        // Usar SessionManager para cerrar sesión de forma segura
        $this->sessionManager->logout();
        
        // URL de redirección
        $loginUrl = url . "app/views/index/login.php";
        
        // Redirigir al login
        header("Location: " . $loginUrl);
        exit;
    }
} 