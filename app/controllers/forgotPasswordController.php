<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname((__DIR__))));
}
require_once ROOT . '/config.php';
require_once 'mainController.php';

class ForgotPasswordController extends MainController
{
    protected $dbConn;

    public function __construct($dbConn)
    {
        // Asegurar que session_start() solo se llame una vez y antes de cualquier salida
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct($dbConn);
        $this->dbConn = $dbConn;
    }

    public function index()
    {
        require_once url . app . views . 'index/forgotPassword.php';
    }

    public function requestReset()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $credType = htmlspecialchars($_POST['credType']) ?? '';
            $userDocument = htmlspecialchars($_POST['userDocument']) ?? '';
            $userEmail = htmlspecialchars($_POST['userEmail']) ?? '';

            // Validar campos obligatorios
            if (empty($credType) || empty($userDocument) || empty($userEmail)) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Todos los campos son obligatorios'
                ]);
                return;
            }

            try {
                // Verificar si el usuario existe y el email coincide
                $query = "SELECT user_id, first_name, last_name, email FROM users 
                          WHERE credential_type = :credType 
                          AND credential_number = :userDocument 
                          AND email = :email 
                          AND is_active = 1";
                
                $stmt = $this->dbConn->prepare($query);
                $stmt->execute([
                    ':credType' => $credType,
                    ':userDocument' => $userDocument,
                    ':email' => $userEmail
                ]);

                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$user) {
                    echo json_encode([
                        'status' => 'error',
                        'msg' => 'No se encontró una cuenta con esos datos. Verifica tu documento y correo electrónico.'
                    ]);
                    return;
                }

                // Generar token único para restablecimiento
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token válido por 1 hora

                // Guardar token en la base de datos
                $queryToken = "INSERT INTO password_resets (user_id, token, expires_at, created_at) 
                              VALUES (:user_id, :token, :expires_at, NOW())";
                
                $stmtToken = $this->dbConn->prepare($queryToken);
                $result = $stmtToken->execute([
                    ':user_id' => $user['user_id'],
                    ':token' => $token,
                    ':expires_at' => $expires
                ]);

                if ($result) {
                    // Enviar email (simulado por ahora)
                    $resetLink = url . "app/views/index/resetPassword.php?token=" . $token;
                    
                    // Por ahora, solo mostrar el enlace en la respuesta
                    // En producción, aquí se enviaría el email
                    echo json_encode([
                        'status' => 'ok',
                        'msg' => 'Se ha enviado un enlace de restablecimiento a tu correo electrónico.',
                        'resetLink' => $resetLink // Solo para desarrollo
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'msg' => 'Error al procesar la solicitud. Inténtalo de nuevo.'
                    ]);
                }

            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Error interno del servidor. Inténtalo más tarde.'
                ]);
            }
        }
    }

    public function resetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = htmlspecialchars($_POST['token']) ?? '';
            $newPassword = htmlspecialchars($_POST['newPassword']) ?? '';
            $confirmPassword = htmlspecialchars($_POST['confirmPassword']) ?? '';

            // Validar campos
            if (empty($token) || empty($newPassword) || empty($confirmPassword)) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Todos los campos son obligatorios'
                ]);
                return;
            }

            if ($newPassword !== $confirmPassword) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Las contraseñas no coinciden'
                ]);
                return;
            }

            if (strlen($newPassword) < 6) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'La contraseña debe tener al menos 6 caracteres'
                ]);
                return;
            }

            try {
                // Verificar token válido y no expirado
                $query = "SELECT pr.user_id, pr.token, pr.expires_at, u.email 
                          FROM password_resets pr 
                          JOIN users u ON pr.user_id = u.user_id 
                          WHERE pr.token = :token 
                          AND pr.expires_at > NOW() 
                          AND pr.used = 0";
                
                $stmt = $this->dbConn->prepare($query);
                $stmt->execute([':token' => $token]);
                $reset = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$reset) {
                    echo json_encode([
                        'status' => 'error',
                        'msg' => 'El enlace de restablecimiento no es válido o ha expirado.'
                    ]);
                    return;
                }

                // Actualizar contraseña
                $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                
                $queryUpdate = "UPDATE users SET password_hash = :password_hash WHERE user_id = :user_id";
                $stmtUpdate = $this->dbConn->prepare($queryUpdate);
                $resultUpdate = $stmtUpdate->execute([
                    ':password_hash' => $newPasswordHash,
                    ':user_id' => $reset['user_id']
                ]);

                if ($resultUpdate) {
                    // Marcar token como usado
                    $queryMarkUsed = "UPDATE password_resets SET used = 1 WHERE token = :token";
                    $stmtMarkUsed = $this->dbConn->prepare($queryMarkUsed);
                    $stmtMarkUsed->execute([':token' => $token]);

                    echo json_encode([
                        'status' => 'ok',
                        'msg' => 'Contraseña actualizada correctamente. Ya puedes iniciar sesión.'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'msg' => 'Error al actualizar la contraseña. Inténtalo de nuevo.'
                    ]);
                }

            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Error interno del servidor. Inténtalo más tarde.'
                ]);
            }
        }
    }
} 