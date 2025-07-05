<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}

require_once ROOT . '/config.php';
require_once ROOT . '/app/library/SessionManager.php';

// Inicializar SessionManager
$sessionManager = new SessionManager();

// Verificar si el usuario está logueado
if (!$sessionManager->isLoggedIn()) {
    header("Location: " . url . "?view=login");
    exit;
}

// Verificar si el usuario tiene el rol de estudiante
if (!$sessionManager->hasRole('student')) {
    header("Location: " . url . "?view=unauthorized");
    exit;
}

require_once ROOT . '/app/views/layouts/scHeader.php';
?>
<script>
    console.log("BASE_URL será configurada en dashFooter.php");
</script>

<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <?php
            require_once 'studentSidebar.php';
            ?>
        </aside>
        <div id="mainContent">

        </div>
    </div>
</body>

<?php
require_once __DIR__ . '/../layouts/dashFooter.php';
?>