<?php
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
$isPartial = isset($_GET['partialView']) && $_GET['partialView'] === 'true';

if (!$isAjax || !$isPartial) {
    require_once ROOT . '/app/views/layouts/head.php';
    require_once ROOT . '/app/views/layouts/headers/dashHeader.php';

    require_once ROOT . '/app/helpers/SessionHelper.php';
    $session = getSession();

    if ($session->isSessionExpired()) {
        $session->logout();
        header("Location: " . url . "?view=index&action=login");
        exit;
    }

    $role = $session->getUserRole() ?? 'guest';
    $sidebarPath = ROOT . '/app/views/layouts/sidebars/' . $role . 'Sidebar.php';

    echo '<div class="dashboard-container">';
    echo '<aside class="sidebar">';
    if (file_exists($sidebarPath)) {
        require $sidebarPath;
    } else {
        echo '<p class="text-danger p-3">Sidebar no disponible para el rol: ' . htmlspecialchars($role) . '</p>';
    }
    echo '</aside>';
    echo '<div id="mainContent" class="mainContent">';
}
?>
