<?php
session_start();

// Verificar sesión
if (!isset($_SESSION["ByFrost_id"]) || !isset($_SESSION["ByFrost_role"])) {
    header("Location: " . url . "app/views/index/login.php");
    exit;
}

// Verificar que sea un usuario root
if ($_SESSION["ByFrost_role"] !== 'root') {
    header("Location: " . url . "app/views/index/login.php");
    exit;
}

if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}

require_once ROOT . '/config.php';
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
        <?php require_once __DIR__ . '/rootSidebar.php'; ?>
    </aside>
    
    <div id="mainContent" class="mainContent">
        <div class="welcome-message">
            <h1>Bienvenido al Dashboard de Administrador</h1>
            <p>Selecciona una opción del menú lateral para comenzar.</p>
        </div>
    </div>
</div>
</body>

<?php require_once __DIR__ . '/../layouts/dashFooter.php'; ?>