<h2>Trabajos</h2>
<a href="?controller=student&action=createWork&studentId=<?= urlencode($_GET['studentId']) ?>">Nuevo Trabajo</a>
<ul>
<?php foreach ($works as $work): ?>
    <li>
        <strong><?= htmlspecialchars($work['title']) ?></strong> (<?= htmlspecialchars($work['status']) ?>, <?= htmlspecialchars($work['due_date']) ?>)
        <?php if ($work['file_path']): ?>
            <a href="<?= htmlspecialchars($work['file_path']) ?>" target="_blank">Archivo</a>
        <?php endif; ?>
        <?php if ($work['grade']): ?>
            <span>Calificación: <?= htmlspecialchars($work['grade']) ?></span>
        <?php endif; ?>
        <a href="?controller=student&action=editWork&workId=<?= $work['id'] ?>">Editar</a>
        <a href="?controller=student&action=deleteWork&workId=<?= $work['id'] ?>" onclick="return confirm('¿Eliminar trabajo?')">Eliminar</a>
    </li>
<?php endforeach; ?>
</ul> 