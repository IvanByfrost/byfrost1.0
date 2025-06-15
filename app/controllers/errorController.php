<?php
class ErrorController extends MainController
{
    public function Error($message = null)
    {
        $errorCode = $_GET['error'] ?? null;

        switch ($errorCode) {
            case '400':
                $this->view->Render('Error', '400');
                break;
            case '500':
                $this->view->Render('Error', '500');
                break;
            default:
                $this->view->Render('Error', 'error', $message);
        }
    }
}
