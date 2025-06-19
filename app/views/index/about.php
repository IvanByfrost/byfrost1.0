<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}
require_once ROOT . '/config.php';
require_once __DIR__ . '/../layouts/head.php';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="about-container">
    <div class="our-team">
        <h2>Nuestro equipo</h2>
        <div class="photo"></div>
        <div class="photo"></div>
        <div class="photo"></div>
    </div>
</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>