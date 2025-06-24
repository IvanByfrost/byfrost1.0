<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}
require_once ROOT . '/config.php';
require_once __DIR__ . '/../layouts/head.php';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="about-container">
    <div class="mision">
        <div class="mvcard">
            <h1>Nuestra Misión</h1>
            <p>Somos una plataforma educativa enfocada en la inclusión de las distintas comunidades para mejorar la calidad de la educación.</p>
        </div>
        <div class="mvcard">
            <h1> Nuestra Visión </h1>
            <p>Ser la plataforma líder en gestión de proyectos, ofreciendo herramientas innovadoras y un entorno colaborativo que potencie la productividad y el éxito de nuestros usuarios.</p>
        </div>
    </div>
    <h1>Nuestro equipo</h1>
    <div class="our-team">
        <div class="miembro-equipo">
            <div class="photo">
                <img src="<?php echo url . app . rq ?>img/team1.jpg" alt="Iván Ruiz">
            </div>
            <div class="desc-member">
                <h3>Iván Ruiz</h3>
                <p>CEO - Scrum Master</p>
                <p>"Saber que no se sabe, ya es saber"</p>
            </div>
        </div>
        <div class="miembro-equipo">
            <div class="photo">
                <img src="<?php echo url . app . rq ?>img/team2.jpg" alt="Johan Carrilo">
            </div>
            <div class="desc-member">
                <h3>Johan Carrillo</h3>
                <p>Backend Developer</p>
                <p>"El hombre está condenado a ser libre"</p>
            </div>
        </div>
        <div class="miembro-equipo">
            <div class="photo">
                <img src="<?php echo url . app . rq ?>img/team3.jpg" alt="Lucía Nova">
            </div>
            <div class="desc-member">
                <h3>Lucía Nova</h3>
                <p>Frontend Developer</p>
                <p>"La ilusión de saber ciega más que la ignorancia"</p>
            </div>
        </div>
        <div class="miembro-equipo">
            <div class="photo">
                <img src="<?php echo url . app . rq ?>img/team4.jpg" alt="Johan Carrilo">
            </div>
            <div class="desc-member">
                <h3>Laura Ariza</h3>
                <p>Frontend Developer</p>
                <p>"El alma se tiñe del color de sus pensamientos"</p>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>