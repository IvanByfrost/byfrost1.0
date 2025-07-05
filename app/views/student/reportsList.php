<h2>Reportes</h2>
<a href="?controller=student&action=createReport&studentId=<?= urlencode($_GET['studentId']) ?>">Nuevo Reporte</a>
<ul>
<?php foreach ($reports as $report): ?>
    <li>
        <strong><?= htmlspecialchars($report['title']) ?></strong> (<?= htmlspecialchars($report['date']) ?>)
        <?php if ($report['file_path']): ?>
            <a href="<?= htmlspecialchars($report['file_path']) ?>" target="_blank">Archivo</a>
        <?php endif; ?>
        <?php if ($report['grade']): ?>
            <span>Calificación: <?= htmlspecialchars($report['grade']) ?></span>
        <?php endif; ?>
        <a href="?controller=student&action=editReport&reportId=<?= $report['id'] ?>">Editar</a>
        <a href="?controller=student&action=deleteReport&reportId=<?= $report['id'] ?>" onclick="return confirm('¿Eliminar reporte?')">Eliminar</a>
    </li>
<?php endforeach; ?>
</ul> 