<?php
if (!defined('ROOT')) {
  define('ROOT', dirname(dirname(dirname(__DIR__))));
}
require_once ROOT . '/config.php';
require_once ROOT . '/app/views/layouts/dashHeader.php';
?>
<script>
    const ROOT = "<?php echo url ?>";
</script>

<body>
  <nav>
    <?php
    require_once 'rootSidebar.php';
    ?>
  </nav>
  <div id="main-content">

</div>
</body>
<?php
require_once __DIR__ . '/../layouts/dashFooter.php';
?>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="<?php echo url . rq ?>js/loadView.js"></script>
<script>
  // Inicializar Lucide
  lucide.createIcons();