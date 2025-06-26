<?php
session_start();
if (!isset($_SESSION["ByFrost_id"])) {
    header("Location: " . url . "views/index/login.php");
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