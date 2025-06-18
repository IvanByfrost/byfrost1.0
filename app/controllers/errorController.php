<?php
class ErrorController extends MainController
{
    public function Error($message = null)
{
    $errorCode = $_GET['error'] ?? null;

    switch ($errorCode) {
        case '400':
            $this->render('Error', '400');
            break;
        case '404':
            $this->render('Error', '404');
            break;
        case '500':
            $this->render('Error', '500');
            break;
        default:
            // Si $message es un array, lo pasamos como data, si es string lo convertimos
            if (is_array($message)) {
                $this->render('Error', 'error', $message);
            } else {
                $this->render('Error', 'error', ['message' => $message]);
            }
            break;
    }
}
}
