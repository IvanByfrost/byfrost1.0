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
        <h1>Nuestro equipo</h1>
        <div class="photo">
            <img src="<?php echo url . rq ?>img\team1.webp" alt="Team Member 1">
            <h3>Iván Ruiz</h3>
            <p>CEO - Scrum Master</p>
        </div>
        <div class="photo">
            <img src="<?php echo url . rq ?>img\team2.webp" alt="Team Member 1">
            <h3>Johan Carrillo</h3>
            <p>Backend Developer</p>
        </div>
        <div class="photo">
            <img src="<?php echo url . rq ?>img\team3.webp" alt="Team Member 1">
            <h3>Lucía Nova</h3>
            <p>Backend Developer</p>
        </div>
        <div class="photo">
            <img src="<?php echo url . rq ?>img\team4.webp" alt="Team Member 1">
            <h3>Laura Ariza</h3>
            <p>Frontend Developer</p>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>