<?php
require_once 'app/controllers/MainController.php';
class ErrorController extends MainController
{
    public function __construct($dbConn, $view = null)
    {
        parent::__construct($dbConn, $view);
    }

    public function Error($message = null)
    {
        // Prevenir cache del navegador
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        $errorCode = $message ?? htmlspecialchars($_GET['error']) ?? null;
        
        // Obtener datos del usuario si está logueado
        $user = null;
        if ($this->sessionManager->isLoggedIn()) {
            $user = $this->sessionManager->getCurrentUser();
        }

        switch ($errorCode) {
            case '400':
                $this->render('Error', '400', ['user' => $user]);
                break;
            case '404':
                $this->render('Error', '404', ['user' => $user]);
                break;
            case '500':
                $this->render('Error', '500', ['user' => $user]);
                break;
            case 'unauthorized':
                $this->render('Error', 'unauthorized', ['user' => $user]);
                break;
            default:
                // Si $message es un array, lo pasamos como data, si es string lo convertimos
                if (is_array($message)) {
                    $message['user'] = $user;
                    $this->render('Error', 'error', $message);
                } else {
                    $this->render('Error', 'error', [
                        'message' => $message,
                        'user' => $user
                    ]);
                }
                break;
        }
    }
}
