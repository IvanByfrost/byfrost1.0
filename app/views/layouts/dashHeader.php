<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}
require_once ROOT . '/config.php';
require_once ROOT . '/app/views/layouts/dashHead.php';
?>

<header>
    <div class="main-header">
        <a href= "#">
            <img src="<?php echo url . rq ?>img\horizontal-logo.svg" alt="Byfrost Logo" width="200">
        </a>
    </div>
</header>