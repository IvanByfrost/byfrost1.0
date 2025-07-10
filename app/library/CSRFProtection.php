<?php
/**
 * CSRF Protection - ByFrost
 */

function addCSRFToken($form) {
    $token = Validator::generateCSRFToken();
    return str_replace("</form>", "<input type=\"hidden\" name=\"csrf_token\" value=\"$token\"></form>", $form);
}

function validateCSRFToken() {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $token = $_POST["csrf_token"] ?? "";
        if (!Validator::validateCSRFToken($token)) {
            $_SESSION["error"] = "Token CSRF inválido";
            header("Location: " . url . "error");
            exit();
        }
    }
}
?>