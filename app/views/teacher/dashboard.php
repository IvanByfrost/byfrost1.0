<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}
require_once ROOT . '/config.php';
require_once ROOT . '/app/views/layouts/dashHeader.php';
?>

<body>
    <nav>
        <?php
        require_once 'teacherSidebar.php';
        ?>
    </nav>
</body>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    lucide.createIcons();
  });
</script>