<h2>Historial Académico</h2>
<a href="?controller=student&action=createAcademicHistory&studentId=<?= urlencode($_GET['studentId']) ?>">Nuevo Registro</a>
<ul>
<?php foreach ($history as $item): ?>
    <li>
        <strong><?= htmlspecialchars($item['year']) ?></strong> (GPA: <?= htmlspecialchars($item['gpa']) ?>)
        <a href="?controller=student&action=editAcademicHistory&historyId=<?= $item['id'] ?>">Editar</a>
        <a href="?controller=student&action=deleteAcademicHistory&historyId=<?= $item['id'] ?>" onclick="return confirm('¿Eliminar registro?')">Eliminar</a>
    </li>
<?php endforeach; ?>
</ul> 