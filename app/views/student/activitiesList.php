<h2>Actividades</h2>
<a href="?controller=student&action=createActivity&studentId=<?= urlencode($_GET['studentId']) ?>">Nueva Actividad</a>
<ul>
<?php foreach ($activities as $activity): ?>
    <li>
        <strong><?= htmlspecialchars($activity['title']) ?></strong> (<?= htmlspecialchars($activity['status']) ?>, <?= htmlspecialchars($activity['date']) ?>)
        <a href="?controller=student&action=editActivity&activityId=<?= $activity['id'] ?>">Editar</a>
        <a href="?controller=student&action=deleteActivity&activityId=<?= $activity['id'] ?>" onclick="return confirm('¿Eliminar actividad?')">Eliminar</a>
    </li>
<?php endforeach; ?>
</ul> 