<?php
session_start();
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname((__DIR__))));
}
require_once ROOT . '/config.php';
//require ROOT . '/app/scripts/connection.php';
require_once 'mainController.php';
class LoginController extends MainController
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

            $query = "SELECT u.*, r.role_type AS rol
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
                if (is_null($user['rol'])) {
                    echo json_encode([
                        "status" => "error",
                        "msg" => "El usuario no se encuentra activado. Por favor, contacta con Byfrost."
                    ]);
                    exit;
                }

                if (password_verify($userPassword, $user['password_hash'])) {
                    // Contraseña válida, el usuario ha sido autenticado
                    $_SESSION["ByFrost_id"] = $user['user_id'];
                    $_SESSION["ByFrost_role"] = $user['rol'];
                    $_SESSION["ByFrost_userName"] = $user['first_name'] . ' ' . $user['last_name'];
                    unset($user['password_hash']);

                    $validRoles = ['professor', 'student', 'headmaster', 'coordinator', 'treasurer', 'parent', 'root'];
                    $redirectPage = in_array($user['rol'], $validRoles)
                        ? "{$user['rol']}/dashboard"
                        : 'login.php';

                    $_SESSION['ByFrost_redirect'] = $redirectPage;

                    echo json_encode([
                        "status" => "ok",
                        "msg" => "Has iniciado sesión",
                        "redirect" => url . "app/views/index/charger.php"
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
    public function logout()
    {
        // Debug: mostrar información antes del logout
        error_log("=== INICIO DEL LOGOUT ===");
        error_log("Estado de sesión: " . session_status());
        error_log("ID de sesión: " . session_id());
        error_log("Variables de sesión: " . print_r($_SESSION, true));
        
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
            error_log("Sesión iniciada");
        }
        
        // Limpiar todas las variables de sesión
        $sessionCount = count($_SESSION);
        $_SESSION = array();
        error_log("Variables de sesión limpiadas ($sessionCount eliminadas)");
        
        // Destruir la cookie de sesión si existe
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            $cookieDestroyed = setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
            error_log("Cookie destruida: " . ($cookieDestroyed ? 'SÍ' : 'NO'));
        }
        
        // Destruir la sesión
        $sessionDestroyed = session_destroy();
        error_log("Sesión destruida: " . ($sessionDestroyed ? 'SÍ' : 'NO'));
        
        // Limpiar cualquier variable de sesión que pueda quedar
        unset($_SESSION);
        error_log("Variable \$_SESSION eliminada");
        
        // URL de redirección
        $loginUrl = url . "app/views/index/login.php";
        error_log("URL de redirección: $loginUrl");
        error_log("Constantes usadas:");
        error_log("  - url = " . url);
        error_log("  - app = " . app);
        error_log("  - views = " . views);
        error_log("URL completa construida: " . url . "app/views/index/login.php");
        error_log("=== FIN DEL LOGOUT ===");
        
        // Redirigir al login
        header("Location: " . $loginUrl);
        exit;
    }
}

