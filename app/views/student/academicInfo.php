<h2>Información Académica</h2>
<ul>
<?php foreach ($academic as $item): ?>
    <li><strong>Año:</strong> <?= htmlspecialchars($item['year']) ?> | <strong>Resumen:</strong> <?= htmlspecialchars($item['summary']) ?> | <strong>GPA:</strong> <?= htmlspecialchars($item['gpa']) ?></li>
<?php endforeach; ?>
</ul> 