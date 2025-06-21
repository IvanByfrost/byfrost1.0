<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel del Coordinador</title>
  <link rel="stylesheet" href="vista/coordinador.css">
</head>
<body>
  <div class="sidebar">
    <div class="perfil">
      <img src="https://i.pravatar.cc/100" alt="Foto">
      <h2><?= $datos['nombre'] ?></h2>
      <p><?= $datos['rol'] ?></p>
    </div>
    <ul>
      <li class="activo">Estudiante</li>
      <li>Horario</li>
      <li>Citaciones</li>
    </ul>
  </div>

  <div class="main">
    <section>
      <h1>Estudiante: <?= $datos['estudiante']['nombre'] ?></h1>
      <p><strong>Estado de cuenta:</strong> <?= $datos['estudiante']['estado'] ?></p>
      <p><strong>Fecha de pago:</strong> <?= $datos['estudiante']['fecha_pago'] ?></p>
      <p><strong>Matrícula:</strong> $<?= number_format($datos['estudiante']['matricula'], 0, ',', '.') ?></p>
      <p><strong>Pensión:</strong> $<?= number_format($datos['estudiante']['pension'], 0, ',', '.') ?></p>
    </section>

    <section>
      <h2>Horario</h2>
      <ul>
        <?php foreach ($datos['horario'] as $clase): ?>
          <li><?= $clase['materia'] ?> - <?= $clase['docente'] ?> (<?= $clase['aula'] ?>)</li>
        <?php endforeach; ?>
      </ul>
    </section>

    <section>
      <h2>Citaciones</h2>
      <ul>
        <?php foreach ($datos['citaciones'] as $cita): ?>
          <li><?= $cita['fecha'] ?>: <?= $cita['motivo'] ?></li>
        <?php endforeach; ?>
      </ul>
    </section>
  </div>
</body>
</html>


<?php
require_once 'controlador/CoordinadorController.php';

$controlador = new CoordinatorController($dbConn);
// $controlador->mostrarPanel(); // Removed because the method does not exist
