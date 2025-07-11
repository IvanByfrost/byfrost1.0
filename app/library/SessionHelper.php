<?php
require_once ROOT . '/app/library/SessionManager.php';

function getSession() {
    static $session = null;
    if ($session === null) {
        $session = new SessionManager();
    }
    return $session;
}
