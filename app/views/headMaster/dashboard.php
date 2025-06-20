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
        require_once 'hmasterSidebar.php';
        ?>
    </nav>

  
</body>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    lucide.createIcons();
  });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.has-submenu > a').forEach(function (menuLink) {
        menuLink.addEventListener('click', function (e) {
            e.preventDefault(); // evita navegación si href="#"
            const parentLi = this.parentElement;
            parentLi.classList.toggle('active');
        });
    });

    lucide.createIcons(); // importante para que cargue los iconos
});
</script>