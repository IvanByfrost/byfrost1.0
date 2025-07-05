<h2>Historial Académico</h2>
<ul>
<?php foreach ($history as $item): ?>
    <li><strong>Año:</strong> <?= htmlspecialchars($item['year']) ?> | <strong>Resumen:</strong> <?= htmlspecialchars($item['summary']) ?> | <strong>GPA:</strong> <?= htmlspecialchars($item['gpa']) ?></li>
<?php endforeach; ?>
</ul> 