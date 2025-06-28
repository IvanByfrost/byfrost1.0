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

// Verificar si el usuario tiene el rol de escuela (asumiendo que es 'school' o 'treasurer')
if (!$sessionManager->hasAnyRole(['school', 'treasurer'])) {
    header("Location: " . url . "?view=unauthorized");
    exit;
}

require_once ROOT . '/app/views/layouts/dashHeader.php';
?>
<script>
    const BASE_URL = "<?php echo url ?>";
    console.log("Valor de BASE_URL: ", BASE_URL);
</script>

<script type="text/javascript" src="<?php echo url . app . rq ?>js/loadView.js"></script>

<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <?php
            require_once 'schoolSidebar.php';
            ?>
        </aside>
        <div id="mainContent">

        </div>
    </div>
</body>

<?php
require_once __DIR__ . '/../layouts/dashFooter.php';
?>