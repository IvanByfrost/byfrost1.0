<?php
class ErrorController extends MainController
{
    public function error($message = null)
    {
        $errorCode = $_GET['error'] ?? null;

        switch ($errorCode) {
            case '400':
                $this->render('Error/400');
                break;
            case '404':
                $this->render('Error/404');
                break;
            case '500':
                $this->render('Error/500');
                break;
            default:
                $this->render('Error/error', ['message' => $message]);
        }
    }
}
